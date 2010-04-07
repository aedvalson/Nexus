<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>

<? 

$DB = new conn();
$F = new FormElements();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Tax Rates</h1></div>
	<div id="bullets"  style="height:auto;" class="navContent">
		<div id="bulletManageInventory" style="height:auto;" class="navContent">
			<div class="divFilters">
				<div>
					<label>Company:</label>
					<INPUT style="width:100%" id="tbFilterCompanyV">
				</div>
				<input id="btnSubmit" type="submit" value="Submit">
				<input id="btnReset" type="submit" value="Reset">


			</div>
			<div class="spacer"></div>
		</div>
	</div>
	

	<div class="navPageSpacing"></div>
	<div class="spacer"></div>

</div>

<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
<!--		<a href="AddFinancing.php">Add Financing</a>-->
	</div>
	<div class="contentDiv">





	<div class="divTable">
		 <TABLE id="theTable" class="data" >
			<thead>
				<TR id="headerRow">
					<th style="width:40px"></th>
					<th style="width: 150px">State</th>
					<th style="width: 150px">County</th>
					<th>Rate</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<td></td>
					<td>
						<input id="tbFilterCompanyH" Type="TEXT">
					</td>
					<td><Input id="tbFilterContactH" TYPE="TEXT"></TD>
					<td><input id="tbFilterEmailH" TYPE="TEXT"></TD>
					
				</TR>
			</thead>
				
		</TABLE> 

	</div>
	<div class="spacer"></div>


	<div>
		<table id="tblAddNew" style="border:2px silver solid">
			<tr>
				<td>State</td>
				<td><select id="ddlStates" name="State"><? echoUsStateOptions(); ?></select></td>
			</tr>
			<tr>
				<td>County</td>
				<td><input id="tbCounty" name="County" /></td>
			</tr>
			<tr>
				<td>Rate</td>
				<td><input id="tbRate" name="Rate" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<center><input type="button" id="btnAdd" Value="Add New Rate">
				</td>
			</tr>
			
		</table>
	</div>


	</div>
</div>


<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {
		createFilters();
		$('#btnAdd').click( function() {
			var county = $('#tbCounty').val();
			var state = $('#ddlStates').val();
			var rate = parseFloat($('#tbRate').val());

			if (county == "")
			{
				alert("County is Required");
				return false;
			}
			if (rate == "")
			{
				alert("Rate is Required");
				return false;
			}
			if (isNaN(rate))
			{
				alert("Invalid Rate");
				return false;
			}

			$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
				{ id: "addTaxRate",
				  country: county,
				  state: state,
				  rate: rate}, function(json) {
				eval("var args = " + json);		
				if (args.success == "success")
				{
					alert("Rate Added");
					$('#tblAddNew input:text').val("");
					createFilters();
				}
				else alert("Ajax failed.");
			});



		});
	});
	$('input[id^=tbFilterCompany]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbFilterCompany]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbFilterContact]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbFilterContact]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbFilterEmail]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbFilterEmail]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbFilterAddress]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbFilterAddress]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbFilterOptions]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbFilterOptions]').each( function () {
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

		filterArray["company"] = $('#tbFilterCompanyH').val();
		if (filterArray["company"] == "") filterArray["company"] = "%";

		filterArray["contact"] = $('#tbFilterContactH').val();
		if (filterArray["contact"] == "") filterArray["contact"] = "%";

		filterArray["email"] = $('#tbFilterEmailH').val();
		if (filterArray["email"] == "") filterArray["email"] = "%";

		filterArray["address"] = $('#tbFilterAddressH').val();
		if (filterArray["address"] == "") filterArray["address"] = "%";

		filterArray["options"] = $('#tbFilterOptionsH').val();
		if (filterArray["options"] == "") filterArray["options"] = "%";
		
		rePost(filterArray);

	}


	function rePost(filters)
	{
		filters["id"] = "getNewTaxRatesTable";

		$('#theTable tr').each( function () {
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
						$('#theTable').append('<tr class="row'+row+'" id="row_' + _r.id + '"><td><div class="selected" style="display:none" id="commandDivSelected_' + _r.id + '"><a href="#" id="btnSave_' + _r.id + '" class="btnSave">' + saveLinkContent() + '</a><a href="#" class="cancelLink">' + cancelLinkContent() + '</a></div><div class="unselected" id="commandDiv_' + _r.id + '"><a href="#" id="editLink_' + _r.id + '" class="editLink" >' + editLinkContent() + '</a><? if ($_SESSION["perm_level"] >= 1000) { ?><a href="#" id="deleteLink_' + _r.id + '" class="deleteLink" >' + deleteLinkContent() + '</a></div><? } ?></div> </td><td><div class="view"><span id="spnState_' + _r.id + '">'+_r.state+'</span></div><div class="editCell"><span id="spnState_' + _r.id + '">'+_r.state+'</span></div></td><td><div class="view"><span id="spnCounty">'+_r.county+'</span></div><div class="editCell"><span id="spnCounty">'+_r.county+'</span></div></td><td><div class="view"><span id="spnRate">'+_r.rate+'%</span></div><div class="editCell"><input id="tbRate_'+_r.id+'" type="text" value="'+_r.rate+'" /></div></tr>');
						row = 1 - row;
					}

					$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="6" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" class="button" type="submit" value="Clear"/></p><p class="centered"><img src="/<?= $ROOTPATH ?>/images/first.png" class="first"/><img src="/<?= $ROOTPATH ?>/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay"/><img src="/<?= $ROOTPATH ?>/images/next.png" class="next"/><img src="/<?= $ROOTPATH ?>/images/last.png" class="last"/></p></td></tr></tfoot>');


					fixHeight();
					$(".datepicker").datepicker( {duration: 'fast'} );

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
						  filterColumns: [ 1, 2, 3 ],
						  filterCaseSensitive: false});
					}




					$('.deleteLink').click( function() {
						if (confirm("Are you sure you wish to permanently delete this Tax Rate?"))
						{
							var id = this.id.replace("deleteLink_", "");
							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "deleteTaxRate",
									f_id: id}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									createFilters();
								}
								else alert("Ajax failed.");
							});

						}
						return false;
					});



					$('.editLink').click( function() {

						var id = this.id.replace("editLink_", "");


						// Add Edit Style
						$('#theTable tr').removeClass("edit");
						$('#row_' + id).addClass("edit");

						// Reset Status
						$('.editCell').css("display", "none");
						$('.view').css("display", "block");

						// Reset all fields
						$('#row_' + id + ' .view').css("display", "none");
						$('#row_' + id + ' .editCell').css("display", "block");



						// Reset Commands
						$('.selected').css("display", "none");
						$('.unselected').css("display", "block");

						// Command Column
						$('#commandDivSelected_' + id).css("display", "block");
						$('#commandDiv_' + id).css("display", "none");


						$('.btnSave').unbind();
						$('.btnSave').click( function() {
							var id = this.id.replace("btnSave_", "");
							var rate = $('#tbRate_' + id).val();

							if (isNaN(rate))
							{
								alert("Invalid Rate");
								return false;
							}

							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "updateTaxRate",
									f_id: id,
									rate: rate,
									sender: <?= $_SESSION["user_id"] ?>}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									createFilters();
								}
								else alert("Ajax failed.");
							});

							return false;
						});

						$('.cancelLink').unbind();
						$('.cancelLink').click( function() {

							// reset Rows
							$('#theTable tr').removeClass("edit");

							// Reset Status
							$('.editCell').css("display", "none");
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
</SCRIPT>




<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {

		$("a[id^=lbDelete]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var id = id.replace("lbDelete", "");
					$("#hv_id").val(id);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+id+"?"))
					{
						$("#theForm").submit();	
					}
					return false;
				});
			});

		});
	var myTextExtraction = function(node)
	{
		var text = "";
		$(node).children('.view:visible').children('span:visible').each( function() {
			text = text + $(this).html();
			//alert(text);
		});
		
		return text;
	}

	function sortTable()
	{
		$('#theTable').addClass('sorted');
		$('#theTable').tablesorter( { widgets: ['zebra'], textExtraction: myTextExtraction } )
			.tablesorterPager({container: $("#pager"), positionFixed: false})
			.tablesorterFilter({filterContainer: $("#filter-box"),
			  filterClearContainer: $("#filter-clear-button"),
			  filterColumns: [ 1, 2, 3, 4, 5, 6],
			  filterCaseSensitive: false});
	}

</SCRIPT>



<FORM id="theForm"  action="" method="post">
	<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
	<INPUT TYPE="HIDDEN" id="hv_id" NAME="id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>