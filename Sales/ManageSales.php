<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>


<? 

$DB = new conn();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Orders</h1></div>

	<div id="bullets" style="height:auto;" class="navContent">
		<div class="divFilters">
			<div>
				<b>Date Range:</b><br>
				<label>Start:</label>
				<INPUT style="width:90%" id="tbStartDateV" class="datepicker" value="<?= thirtydaysago() ?>"></input>

				<label>End:</label>
				<INPUT style="width:90%" id="tbEndDateV" class="datepicker" value="<?= today() ?>">
			</div>

			<div>
				<b>Price Range</b><br>
				<label>Min:</label>
				<INPUT style="width:90%" id="tbMinPriceV">

				<label>Max:</label>
				<input style="width:90%" id="tbMaxPriceV">
			</div>

			<input id="btnSubmit" type="submit" value="Submit">
			<input id="btnReset" type="submit" value="Reset">


		</div>
		<div class="spacer"></div>
	</div>
	

	<div class="navPageSpacing"></div>
	<div class="spacer"></div>

</div>

<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
		<a href="NewSale.php">Create New Order</a>
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
			?> <TABLE id="theTable" class="data" >
				<thead>

				<TR id="headerRow">
<!--				<th>Exp</th> -->
				<th style="width:40px">ID</th>
				<th style="width:120px;">Customer</th>
				<th style="width:80px;">Amount</th>
				<th style="width:130px;">Products</th>
				<th style="width:100px;">Date Added</th>
				<th>Dealer(s)</th>
				<th style="width:40px;">Status</th>
				</TR>

				

				<TR id="filterRow" class="filterRow">
					<td><Input id="tbOrder_IdH" TYPE="TEXT"></TD>
					<td><Input id="tbDisplayNameH" TYPE="TEXT"></TD>
					<td><Input id="tbAmountH" TYPE="TEXT"></TD>
					<td>
						<input type="text" id="tbProdH">
					</td>
					<td>
					<input id="tbDateH" class="datepicker" Type="TEXT"></td>
					<td>
						<SELECT id="ddlSellersH">
							<OPTION value="">Any User</OPTION>
							<?
							$users = $DB->getusers();
							foreach ($users as $user)
							{
								?><OPTION value="<?= $user["user_id"] ?>"><?= $user["Username"] ?></option>
							<? } ?>
						</select>
					</td>
					<td>
						<SELECT id="ddlStatusH" style="width:120px">
							<OPTION value="%">Any Status</OPTION>
							<?
							$statuses = $DB->getOrderStatuses();
							foreach ($statuses as $status)
							{
								?><OPTION value="<?= $status["order_status_id"] ?>"><?= $status["order_status_name"] ?></option>
							<? } ?>
						</SELECT>
					</td>
				</TR>
				</thead>
				
		</TABLE> <?
		}

		$DB->close();  ?>

	</div>
	<div class="spacer"></div>

	</div>
</div>


<SCRIPT TYPE="TEXT/JAVASCRIPT">
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

	$('input[id^=tbOrder_Id]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbOrder_IdName]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbAmount]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbAmount], #tbMinPriceV, #tbMaxPriceV').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbDate]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbDate], #tbStartDateV, #tbEndDateV').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('#tbMinPriceV, #tbMaxPriceV').change(function() {
		if ($('#tbMinPriceV').val() != $('#tbMaxPriceV').val())
		{
			$('input[id^=tbAmount]').val('');
		}
		createFilters();
	});

	$('#tbStartDateV, #tbEndDateV').change(function() {
		if ($('#tbStartDateV').val() != $('#tbEndDateV').val())
		{
			$('#tbDateH').val('');
		}
		createFilters();
	});

	$('#tbProdH').change(function() {
		createFilters();
	});



	$('input[id^=tbDisplayName]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbDisplayName]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('select[id^=ddlStatus]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlStatus]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('select[id^=ddlSellers]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlSellers]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('select[id^=ddlAddedBy]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlAddedBy]').each( function () {
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
		filterArray["order_id"] = $('#tbOrder_IdH').val();
		if (filterArray["order_id"] == "") filterArray["order_id"] = "%";

		filterArray["amount"] = $('#tbAmountH').val();
		if (filterArray["amount"] == "") filterArray["amount"] = "%";

		filterArray["amountMax"] = $('#tbMaxPriceV').val();
		if (filterArray["amountMax"] == "") filterArray["amountMax"] = "%";

		filterArray["amountMin"] = $('#tbMinPriceV').val();
		if (filterArray["amountMin"] == "") filterArray["amountMin"] = "%";

		filterArray["products"] = $('#tbProdH').val();
		if (filterArray["products"] == "") filterArray["products"] = "%";

		filterArray["orderstatus"] = $('#ddlStatusH').val();

		filterArray["displayname"] = $('#tbDisplayNameH').val();
		if (filterArray["displayname"] == "") filterArray["displayname"] = "%";

		filterArray["date"] = $('#tbDateH').val();
		if (filterArray["date"] == "") filterArray["date"] = "%";

		filterArray["startDate"] = $('#tbStartDateV').val();
		if (filterArray["startDate"] == "") filterArray["startDate"] = "%";

		filterArray["endDate"] = $('#tbEndDateV').val();
		if (filterArray["endDate"] == "") filterArray["endDate"] = "%";

		filterArray["sellers"] = $('#ddlSellersH').val();

		rePost(filterArray);
	}


	function rePost(filters)
	{
		filters["id"] = "getSalesTable";

		$('#theTable tbody tr').each( function () {
			id = $(this).attr('id');
			if (id != 'headerRow' && id != 'filterRow')
			{
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
				$('#theTable').append('<tbody></tbody');
				var row = 0;
				if (args.output)
				{
					for (r in args.output)
					{
						_r = args.output[r];
						var sellersText = "";
						if (_r.CommStructure != '')
							{
							
							var CommStructure = JSON.parse(_r.CommStructure);
							var soldBy = new Array();
							for (i in CommStructure.elements)
							{
								if (CommStructure.elements[i].users)
								{
									for (j in CommStructure.elements[i].users)
									{
										soldBy[soldBy.length] = CommStructure.elements[i].users[j].username;
									}
								}
							}

							for (k=0; k<soldBy.length ;k++ )
							{
								sellersText = sellersText + '<span>' +soldBy[k] + "</span><br><span style=\"color:silver; margin-bottom: 7px; display:block;\">(role in sale)</span>";
							}
						}


						var productText = "";
						if (_r.ProductsArray != '')
						{
							var ProductArray = JSON.parse(_r.ProductsArray);
							var products = new Array();
							for (i in ProductArray.products)
							{
								products[products.length] = ProductArray.products[i].Name.replace(" ", "&nbsp;") + '<br/>&nbsp;&nbsp;Serial:&nbsp;<span style="text-decoration: underline; color: #CC0000">' + ProductArray.products[i].Serial + '</span>';
							}
							for (k=0; k<products.length ; k++ )
							{
								productText = productText + '<span>' +products[k] + '</span><br><span style=\"color:silver; margin-bottom: 7px; display:block;\"></span>';
							}
						}
						if (_r.AccessoriesArray != '')
						{
							var ProductArray = JSON.parse(_r.AccessoriesArray);
							var products = new Array();
							for (i in ProductArray.products)
							{
								products[products.length] = ProductArray.products[i].quantity + "&nbsp;" + ProductArray.products[i].Name.replace(" ", "&nbsp;");
							}
							for (k=0; k<products.length ; k++ )
							{
								productText = productText + '<span>' +products[k] + '</span><br><span style=\"color:silver; margin-bottom: 7px; display:block;\"></span>';
							}
						}

						var dealerText = "";
						var currentRole = "";
						if (_r.dealerArray != "" )
						{
							var dealerArray = JSON.parse(_r.dealerArray);
							var roles = {};
							roleLength = 0;
							if (dealerArray)
							{
								for (i in dealerArray.roles)
								{
									var thisRoleText = dealerArray.roles[i].roleText;
									if (!roles[thisRoleText])
									{
										roles[thisRoleText] = dealerArray.roles[i].roleText.replace(" ", "&nbsp;") + ":";
									}
									var name = dealerArray.roles[i].displayName ? dealerArray.roles[i].displayName.replace(" ", "&nbsp;") : dealerArray.roles[i].userText;
									roles[thisRoleText] = roles[thisRoleText] + "<br>&nbsp;&nbsp;<span style=\"text-decoration: underline; color: #CC0000\">" + name + "</span>";
								}
								for (var k in roles)
								{
									dealerText = dealerText + '<span>' + roles[k] + '</span><br><br>';
								}
							}
						}
						
						var prettyDate = "";
						if (_r.DateCompleted != null)
						{
							var _date = _r.DateCompleted.split(" ")[0];
							_date = replaceAll(_date, "-", "/");
							var myDate = new Date(_date);
							var month = myDate.getMonth() + 1;
							prettyDate = '<span style="display: block; margin-top:7px;">Sale Date: ' + month + '/' + myDate.getDate() + '/' + myDate.getFullYear() + '</span>';
						}

						$('#theTable tbody').append('<tr id = "row'+_r.order_id+'" ><td>'+_r.order_id+'</td><td>' + _r.contact_DisplayName + '</td><td>'+formatCurrency(_r.amount)+'</td><td><span>' + productText + '</span></td><td><span>' + getPrettyDate(_r.DateAdded) + '<br>by: ' + _r.FirstName + ' ' +  _r.LastName + '</span><span style="display: none">' + _r.username + '</span></td><td><span>' + dealerText +  '</span></td><td><span>' + _r.order_status + prettyDate + '</span></td></tr>');
						row = 1 - row;
					}

					$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="7" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" type="submit" value="Clear"/></p><p class="centered"><img src="/<?= $ROOTPATH ?>/images/first.png" class="first"/><img src="/<?= $ROOTPATH ?>/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay"/><img src="/<?= $ROOTPATH ?>/images/next.png" class="next"/><img src="/<?= $ROOTPATH ?>/images/last.png" class="last"/></p></td></tr></tfoot>');
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
						  filterColumns: [0, 1, 2, 3, 4, 5, 6],
						  filterCaseSensitive: false});
					}
					fixHeight();

				}
				else
				{
					$('#theTable tbody').append('<tr id="errorRow" style="height:50px"><td><font color="red">No Records Found.</font></td></tr>');
				}
			}
			else
			{
				 alert("Ajax failed.");
			}
		  });
	}
</SCRIPT>





<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {




		$('#theTable tbody tr').live("click",  function() {
			window.location = 'NewSale.php?order_id=' + this.id.replace('row', '');
		});


		$("a[id^=lbDelete]").each( function() {
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
			  filterColumns: [0, 1, 2, 3, 4, 5, 6],
			  filterCaseSensitive: false});
	}

</SCRIPT>



<FORM id="theForm"  action="" method="post">
	<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
	<INPUT TYPE="HIDDEN" id="hv_user_id" NAME="user_id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/". $ROOTPATH ."/Includes/Bottom.php" ?>