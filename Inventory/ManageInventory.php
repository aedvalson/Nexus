<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>


<? 

$DB = new conn();
$DB->connect();
$locations = getStorageLocationsHash($DB);
?>
<SCRIPT TYPE="text/javascript">	
	var locations = <?= json_encode($locations) ?>;
	<?
		if ($AgencyParams["EnableDTOffices"])
		{
		?> var DTOffices = <?= json_encode($AgencyParams["DTOffices"]); ?>; <?
		}
	?>

</SCRIPT>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Inventory</h1></div>

	<div id="bullets" style="height:auto;" class="navContent">
		<div class="divFilters">
			<div>
				<label>Product Type:</label>
				<?
				$product_types = $DB->getProductTypes();
				foreach ($product_types as $type)
				{
					?><input type="checkbox" id="cb<?= $type["product_type"] ?>" class="cbProdType" onchange="handleCheckChange(this);" checked="checked"> <?= plural($type["product_type"]) ?></input><br>
					
					<div id="div<?= $type["product_type"] ?>" class="divProdType">

						<?
						$prods = $DB->getProducts($type["product_type"]);
						foreach ($prods as $prod)
						{
							?><div class="divProd_<?= $type["product_type"] ?>" style="display: block"><label style="margin-left: 8px; font-size: 80%"><input type="checkbox" id="cb<?= $prod["product_id"] ?>" name="cb<?= $prod["product_id"] ?>" class="cbProd_<?= $type["product_type"] ?>" onchange="createFilters()" checked="checked"> </input><?= $prod["product_name"] ?> - <?= $prod["product_model"] ?></label></div><?
						}
							?>
					</div>
					
					<?
				}
				?>


			</div>

			<div>
				<label>Status:</label>
				<SELECT id="ddlStatusV" style="width:90%;">
					<OPTION value="%">Any Status</OPTION>
					<?
					$products = $DB->getInventoryStatuses();
					foreach ($products as $prod)
					{
						$selected = "";
						if ($prod["status_name"] == "Checked In")
						{
							$selected = "selected";
						}
						?><OPTION value="<?= $prod["status_id"] ?>" <?= $selected ?>><? echo $prod["status_name"]; ?></option><?
					}
					?>
				</SELECT>
			</div>

			<div>
				<label>Invoice:</label>
				<INPUT style="width:90%" id="tbInvoiceV">
			</div>

			<div>
				<label>Serial:</label>
				<INPUT style="width:90%" id="tbSerialV">
			</div>

			<div>
				<label>Location:</label>
				<SELECT id="ddlLocationV" style="width:90%;">
					<OPTION value="%">Any Location</OPTION>
					<?
					$locations = $DB->getStorageLocations();
					foreach ($locations as $prod)
					{
						?><OPTION value="<?= $prod["storagelocation_id"] ?>"><? echo $prod["storagelocation_name"]; ?></option><?
					}
					?>
				</SELECT>
			</div>

			<input id="btnSubmit" type="submit" value="Submit">
			<input id="btnReset" type="submit" value="Reset">


		</div>
		<div class="spacer"></div>
	</div>
	

	<div class="navSpacer"></div>
	<div class="spacer"></div>

</div>

<div class="pageContent">

	<div class="contentHeaderDiv">
		<a href="AddInventory.php">Add Inventory</a>
	</div>
	<div class="contentDiv">

	<?
	$DB->connect();
	$sql = <<<SQLEND
			select inventory.inventory_id, inventory.product_id, inventory.invoice, products.product_model, products.product_name, inventory.serial, inventory.status, inventory.storagelocation_id, sl.storagelocation_name as slname, inventory_status.status_name
			from inventory
			join products on inventory.product_id = products.product_id
			join storagelocations sl on inventory.storagelocation_id = sl.storagelocation_id
			join inventory_status on inventory.status = inventory_status.status_id
SQLEND;
	$result = $DB->query($sql);

	?>

	<div class="divTable"><?
		if ($result)
		{
			?> <TABLE id="theTable" class="data" ><thead>
				<TR id="headerRow">
				<th></th>
				<th>Product Name</th>
				<th style="width:60px;">Invoice</th>
				<th style="width:90px;">Serial</th>
				<th style="width:80px;">Added</th>
				<th style="width:70px;">Received</th>
				<th>Status</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<td></td>
					<TD>
						<SELECT id="ddlProductNameHFilter" style="width:90%;">
							<OPTION value="">All Products</OPTION>
							<?
							$products = $DB->getProducts();
							foreach ($products as $prod)
							{
								?><OPTION value="<?= $prod["product_id"] ?>"><? echo $prod["product_name"]; ?> - <? echo $prod["product_model"]; ?></option><?
							}
							?>
						</SELECT>
					</TD>
					<td><Input id="tbInvoiceH" TYPE="TEXT" /></TD>
					<TD><INPUT id="tbSerialH" TYPE="TEXT" /></TD>
					<TD><input id="tbDateH" Type="text" class="datepicker" /></td>
					<TD><input id="tbDateReceivedH" Type="text" class="datepicker" /></td>
					<TD>
						<SELECT id="ddlStatusH" style="width:90%;">
							<OPTION value="%">Any Status</OPTION>
							<?
							$products = $DB->getInventoryStatuses();
							foreach ($products as $prod)
							{
								$selected = "";
								if ($prod["status_name"] == "Checked In")
								{
									$selected = "selected";
								}
								if ($prod["status_name"] == "Transferred")
								{
									$firephp->log("TRANSFERRED");
									$firephp->log($AgencyParams["EnableDTOffices"]);
									if ($AgencyParams["EnableDTOffices"] != 1) 
										{
										$firephp->log("CONTINUE");
										continue; }
								}
								?><OPTION value="<?= $prod["status_id"] ?>" <?= $selected ?>><? echo $prod["status_name"]; ?></option><?
							}
							?>
						</SELECT>
						<br>
						<SELECT id="ddlLocationH" style="width:90%;">
							<OPTION value="%">Any Location</OPTION>
							<?
							$locations = $DB->getStorageLocations();
							foreach ($locations as $prod)
							{
								?><OPTION value="<?= $prod["storagelocation_id"] ?>"><? echo $prod["storagelocation_name"]; ?></option><?
							}
							?>
						</SELECT>
					</TD>
				</TR></thead>
				
		</TABLE>
		
		<FORM id="hreport" method="get" action="/<?= $ROOTPATH ?>/reports/Automated/_report.php">
			<input type="hidden" id="hdata" name="data" value="">
		</form>

		
		
		<a href="#" id="pdfLink">PDF</a> | <a href="#" id="excelLink">Excel</a>
		<SCRIPT type="text/javascript">
			$('#pdfLink, #excelLink').click( function() {
				var _table = $('#theTable:first');
				var $new_table = _table.clone(true);
				var method = this.id;
				
				$($new_table).find('#filterRow').remove();
				$($new_table).find('tfoot').remove();
				
				$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "generateReport",  value: "<table class=\"data\">" + $($new_table).html() + "</table>" }, function(json) {
					eval("var args = " + json);		
					if (args.success == "success")
					{
						if (args.output)
						{
							report = args.output;

							if (method == 'pdfLink')
							{
								url = '<?= $FQDN ?>/<?= $ROOTPATH ?>/reports/pdf/generate/html2ps.php?process_mode=single&URL=URLGOESHERE&proxy=&pixels=1024&scalepoints=1&renderimages=1&renderlinks=1&renderfields=1&media=Letter&cssmedia=Screen&leftmargin=30&rightmargin=15&topmargin=15&bottommargin=15&encoding=&headerhtml=&footerhtml=&watermarkhtml=&toc-location=before&smartpagebreak=1&pslevel=3&method=fpdf&pdfversion=1.3&output=0&convert=Convert+File457'
								.replace('URLGOESHERE', escape('<?= $FQDN ?>/php2/reports/automated/_report.php?report_id=' + report + '&output=pdf'));
								window.open(url);
							}

							if (method == 'excelLink')
							{
								window.open('<?= $FQDN ?>/<?= $ROOTPATH ?>/reports/automated/_report.php?report_id=' + report + '&output=excel');
							}
						}
						else
						{

						}
					}
					else
					{
						 alert("Ajax failed.");
					}
				});

			});
		</SCRIPT>
		<?
		}

		$DB->close();  ?>

	</div>
	<div class="spacer"></div>

	</div>
</div>


<SCRIPT TYPE="TEXT/JAVASCRIPT">

		function prettyDate(rawDate)
		{
			if (rawDate != null)
			{
				var _date = rawDate.split(" ")[0];
				_date = replaceAll(_date, "-", "/");
				var myDate = new Date(_date);
				var month = myDate.getMonth() + 1;
				var prettyDate = month + '/' + myDate.getDate() + '/' + myDate.getFullYear();
				return prettyDate;
			}
		}

	function handleCheckChange( sender )
	{
		var type = sender.id.replace("cb", "");
		if ($(sender).attr("checked"))
		{
			$('.cbProd_' + type).attr("checked", "checked");
			$('.divProd_' + type).css("display", "block");
		}
		else
		{
			$('.cbProd_' + type).removeAttr("checked");
			$('.divProd_' + type).css("display", "none");
		}
		createFilters();
	}

	
	$(document).ready(function() {

		createFilters();
		$("a[id^=expand_]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var inventory_id = id.replace("expand_", "");
					$("#td_" + inventory_id).html("<img src='/<?= $ROOTPATH ?>/images/loading.gif'>");
					$("#tr_" +  inventory_id).toggle();
		        	$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getInventoryDetails",  value: inventory_id }, function(json) {
	                  	eval("var args = " + json);		
	                  	if (args.success == "success")
	                  	{
		                  	if (args.output)
		                  	{
								var text="Storage Location: <b>" + args.output[0]["storagelocation_name"] + '</b><br />';
								text += "Status: <b>" + args.output[0]["status_name"] + '</b><br />';

								$("#td_" + inventory_id).css("paddingLeft", "20px");
		                  	  	$("#td_" + inventory_id).html(text);
		                  	}
		                  	else
		                  	{
		                  		$("#td_" + inventory_id).html("No inventory at Any Location");
		                  	}
	                  	}
	                  	else
	                  	{
	                         alert("Ajax failed.");
	                  	}
	                  });
					$("#hv_id").val(inventory_id);
					$("#hv_Action").val("Delete");
					
					return false;
				});
			});
		});

	$('select[id^=ddlProductName]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlProductName]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});
	$('select[id^=ddlStatus]').change(function() {
		var newVal = $(this).val();

		populateDataOptions($(this).val(), "H", "ddlLocation");
		populateDataOptions($(this).val(), "V", "ddlLocation");
		$('select[id^=ddlStatus]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});
	$('select[id^=ddlLocation]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlLocation]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbInvoice]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbInvoice]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});
	$('input[id^=tbSerial]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbSerial]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('#btnSubmit').click(function() {
		createFilters();
	});

	$('#btnReset').click(function() {

		$('input:text').val('');
		$('select').val('%');
		createFilters();
	});

	function createFilters()
	{

		var filterArray = new Object();
		filterArray["status"] = $('#ddlStatusV').val();
		filterArray["location"] = $('#ddlLocationH').val();
		filterArray["invoice"] = $('#tbInvoiceH').val();
		if (filterArray["invoice"] == "") filterArray["invoice"] = "%";
		filterArray["serial"] = $('#tbSerialH').val();
		if (filterArray["serial"] == "") filterArray["serial"] = "%";
		filterArray["productID"] = $('#ddlProductNameHFilter').val();
		if (filterArray["productID"] == "") filterArray["productID"] = "%";

		var productIds = [];
		$('input[class^=cbProd_]:checked').each( function() {
			productIds[productIds.length] = this.id.replace("cb", "");
		});
		filterArray["products"] = productIds.join(",");
		
		rePost(filterArray);
	}


	function rePost(filters)
	{
		filters["id"] = "getNewInventoryTable";

		$('#theTable tbody tr').each( function () {
			id = $(this).attr('id');
			if (id != 'headerRow' && id != 'filterRow')
			{
				$(this).find('.datepicker').each( function() {
					$(this).unbind();
				});
				$(this).remove(); // Remove all existing rows
			}
		});
		$('#theTable').append('<tr id="loadingRow"><td style="padding:25px"><center><img src="/<?= $ROOTPATH ?>/images/loading.gif"><br>Filtering Results</center></td></tr>');

       	$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', filters, function(json) {
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#theTable tbody').remove();
				$('#theTable tfoot').remove();
				$('#theTable').append('<tbody></tbody>');

				var row = 0;
				if (args.output)
				{
					for (r in args.output)
					{
						_r = args.output[r];
						var rowdata = _r.status_data;
						if (_r.status_name == "Sale Pending" || _r.status_name == "Sold")
						{
							rowdata = "Order <a href=\"/<?= $ROOTPATH ?>/Sales/NewSale.php?order_id=" + _r.status_data + "\" style=\"color: #CC0000;\">#" + _r.status_data + "</a>";
						}
						else if (_r.status_name == "Transferred")
						{
							rowdata = DTOffices[_r.status_data];
						}
						else if (_r.status_name == "Checked In")
						{
							rowdata = locations[_r.status_data].storagelocation_name;
						}


						_r.DateReceived = (_r.DateReceived.match(/^0000/g)) ? "" : prettyDate(_r.DateReceived);

						var newTR = $('<tr class="row'+row+'" id="row_' + _r.inventory_id + '"><td><div class="selected" style="display:none" id="commandDivSelected_' + _r.inventory_id + '"><a href="#" id="btnSave_' + _r.inventory_id + '" class="btnSave">' + saveLinkContent() + '</a><br><a href="#" class="cancelLink">' + cancelLinkContent() + '</a></div><div class="unselected" id="commandDiv_' + _r.inventory_id + '"><a href="#" id="editLink_' + _r.inventory_id + '" class="editLink" >' + editLinkContent() + '</a><br></div> </td><td><div class="view"><span>'+_r.product_name+" - "+_r.product_model+'</span></div></td><td><div class="view"><span>'+_r.invoice+'</span></div></td><td><div class="view"><span>'+_r.serial+'</span></div></td><td><div class="view"><span>' + getPrettyDate(_r.DateAdded) + '<br>by: ' + _r.AddedByName + '</span></div></td><td><div id="view_dateReceived_' + _r.inventory_id + '" class="view"><span>' + _r.DateReceived + '</span></div><div class="maskEdit" id="edit_dateReceived_' + _r.inventory_id + '"><input class="datepicker" id="dateReceived_'+_r.inventory_id + '" value="' + _r.DateReceived + '"></input></td><td class="maskContainer"><div id="view_status_' + _r.inventory_id + '" class="view"><span id="currentStatusName_' + _r.inventory_id + '">' + _r.status_name + '</span><br> <span id="currentStatusPreposition_' + _r.inventory_id + '">' +  _r.preposition + ':</span>  <span id="currentDataText_' + _r.inventory_id + '"> '+ rowdata +'</span><span style="display:none" id="currentStatusData_' + _r.inventory_id + '">'+ _r.status_data + '</span><br> on: <span id="current_date_' + _r.inventory_id + '">'+ prettyDate(_r.status_date) +'</span></div><div id="edit_status_' + _r.inventory_id + '" class="maskEdit"><span style="display:none;" id="currentStatus_' + _r.inventory_id + '">' + _r.status + '</span><SELECT id="ddlStatus_' + _r.inventory_id + '" style="width:90%;"><?
						$products = $DB->getInventoryStatuses();
						foreach ($products as $prod)
						{
							if ($prod["status_name"] == "Transferred")
							{
								if ($AgencyParams["EnableDTOffices"] != 1 ) { continue; }
							}
							?><OPTION value="<?= $prod["status_id"] ?>"><? echo $prod["status_name"]; ?></option><?
						}
						?></SELECT><br> ' +  _r.preposition + ': <select style="width:70%" id="ddlStatusData_' + _r.inventory_id + '"></select><br>date:&nbsp;<input type="text" style="width:70%" class="datepicker" id="tbDate_' + _r.inventory_id + '"></input></div></td></tr>');
						$('#theTable tbody').append(newTR);
					}
					$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="7" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" type="submit" value="Clear"/></p><p class="centered"><img src="/<?= $ROOTPATH ?>/images/first.png" class="first"/><img src="/<?= $ROOTPATH ?>/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay" autocomplete="off"/><img src="/<?= $ROOTPATH ?>/images/next.png" class="next"/><img src="/<?= $ROOTPATH ?>/images/last.png" class="last"/></p></td></tr></tfoot>');

					//$(".datepicker").datepicker( {duration: 'fast'} );

					if ($('.sorted').size() == 0)
					{
						sortTable();
					}
					else
					{
						$("#theTable").trigger("update"); 
						$("#theTable").trigger("appendCache"); 
						$('#theTable').tablesorterPager({container: $("#pager"), positionFixed: false}).tablesorterFilter({filterContainer: $("#filter-box"),
						  filterClearContainer: $("#filter-clear-button"),
						  filterColumns: [1, 2, 3, 4, 5, 6],
						  filterCaseSensitive: false});
					}
					fixHeight();
					$('.editLink').live("click", function() {

						var inventory_id = this.id.replace("editLink_", "");
						$("#row_" + inventory_id).find(".datepicker").datepicker( {duration: 'fast'} );

						$('#ddlStatus_' + inventory_id + ' option').each( function() {
							var inv = $(this).val();
							if (inv == 4 || inv == 5)
							{
								if (inv != $('#currentStatus_' + inventory_id).html())
								{
									$(this).remove();
								}
							}
						});

						// Add Edit Style
						$('#theTable tr').removeClass("edit");
						$('#row_' + inventory_id).addClass("edit");

						// Reset all fields
						$('.maskEdit').css("display", "none");
						$('.view').css("display", "block");

						// Reset Commands
						$('.selected').css("display", "none");
						$('.unselected').css("display", "block");

						// Date Received Column
						$('#view_dateReceived_' + inventory_id).css("display", "none");
						$('#edit_dateReceived_' + inventory_id).css("display", "block");


						// Status Column
						$('#view_status_' + inventory_id).css("display", "none");
						$('#edit_status_' + inventory_id).css("display", "block");

						// Command Column
						$('#commandDivSelected_' + inventory_id).css("display", "block");
						$('#commandDiv_' + inventory_id).css("display", "none");

						var $ddlStatus = $('#ddlStatus_' + inventory_id);
						$ddlStatus.val($('#currentStatus_' + inventory_id).html());
						var $ddlStatusData = $('#ddlStatusData_' + inventory_id);

						$('#tbDate_' + inventory_id).val($('#current_date_' + inventory_id).html());
						populateDataOptions($ddlStatus.val(), inventory_id,"ddlStatusData_");

						if ($('#currentStatus_' + inventory_id).html() == '4' || $('#currentStatus_' + inventory_id).html() == '5')
						{
							$ddlStatus.attr("disabled", "disabled");
							$('#ddlStatusData_' + inventory_id).attr("disabled", "disabled");
							$('#tbDate_' + inventory_id).attr("disabled", "disabled");
						}
						else
						{
							$ddlStatus.removeAttr("disabled");
							$('#ddlStatusData_' + inventory_id).removeAttr("disabled");
							$('#tbDate_' + inventory_id).removeAttr("disabled");
						}

						$ddlStatus.unbind();
						$ddlStatus.change( function() {
							populateDataOptions($(this).val(), inventory_id, "ddlStatusData_");
						});

						$('.btnSave').unbind();
						$('.btnSave').click( function() {
							var inventory_id = this.id.replace("btnSave_", "");
							var status = $('#ddlStatus_' + inventory_id).val();
							var statusdata = $('#ddlStatusData_' + inventory_id).val();
							var date = $('#tbDate_' + inventory_id).val();
							var receivedDate = $('#dateReceived_' + inventory_id).val();

							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "updateInventoryStatus",
									inventory_id: inventory_id,
									status: status,
									statusdata: statusdata,
									receivedDate: receivedDate,
									date: date}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									createFilters();
									fixHeight();
								}
								else alert("Ajax failed.");
							});

							receivedDate = null;
							return false;
						});

						$('.cancelLink').unbind();
						$('.cancelLink').click( function() {

							// reset Rows
							$('#theTable tr').removeClass("edit");

							// Reset Status
							$('.maskEdit').css("display", "none");
							$('.view').css("display", "block");

							// Reset Commands
							$('.selected').css("display", "none");
							$('.unselected').css("display", "block");

							return false;


						});

						return false;
						
					});


				}
				else
				{
					$('#theTable').append('<tr id="errorRow" style="height:50px"><td><font color="red">No Records Found.</font></td></tr>');
				}
			}
			else
			{
				 alert("Ajax failed.");
			}
		  });
	}

function populateDataOptions(datatype, inventory_id, prefix)
{
	if (datatype == 1)
	{
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getStorageLocations" }, function(json) {
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#' + prefix + inventory_id + ' option').remove();
				if (args.output)
				{

					for (i in args.output)
					{
						$('#' + prefix + inventory_id).append('<option value="' + args.output[i].storagelocation_id + '">' + args.output[i].storagelocation_name + '</option>');
					}
					$('#' + prefix + inventory_id).val($('#currentStatusData_' + inventory_id).html());
				}
			}
			else alert("Ajax failed.");
		});
	}
	if (datatype == 2)
	{
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getUsers" }, function(json) {
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#' + prefix + inventory_id + ' option').remove();
				if (args.output)
				{

					for (i in args.output)
					{
						$('#' + prefix + inventory_id).append('<option value="' + args.output[i].user_id + '">' + args.output[i].Username + '</option>');
					}
					$('#ddlStatusData_' + inventory_id).val($('#currentStatusData_' + inventory_id).html());
				}
			}
			else alert("Ajax failed.");
		});
	}
	if (datatype == 3)
	{
		$('#' + prefix + inventory_id + ' option').remove();
		for (i in DTOffices)
		{
			$('#' + prefix + inventory_id).append('<option value="' + i + '">' + DTOffices[i] + '</option>');
		}
	}
	else
	{
		$('#' + prefix + inventory_id + ' option').remove();
	}
}




</SCRIPT>




<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {


		$("a[id^=lbDelete]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var user_id = id.replace("lbDelete", "");
					$("#hv_user_id").val(user_id);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+user_id+"?"))
					{
						$("#theForm").submit();	
					}
					return false;
				});
			});

		});

	function sortTable()
	{
		$('#theTable').addClass('sorted');
		$('#theTable').tablesorter( { widgets: ['zebra'] } )
			.tablesorterPager({container: $("#pager"), positionFixed: false})
			.tablesorterFilter({filterContainer: $("#filter-box"),
			  filterClearContainer: $("#filter-clear-button"),
			  filterColumns: [ 1, 2, 3, 4, 5 ],
			  filterCaseSensitive: false});
	}
</SCRIPT>



<FORM id="theForm"  action="" method="post">
	<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
	<INPUT TYPE="HIDDEN" id="hv_user_id" NAME="user_id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/". $ROOTPATH . "/Includes/Bottom.php" ?>