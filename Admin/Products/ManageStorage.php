<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
?>

<? 
if (!UserMay("Admin_ViewStorage")) { AccessDenied(); }
$DB = new conn();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Storage</h1></div>
	<div id="bullets" style="height:auto;" class="navContent">
		<div id="bulletManageInventory" style="height:auto;" class="navContent">
			<div class="divFilters">
				<div>
					Side Filters
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
		<? if (UserMay("Admin_EditStorage")) { ?>
		<a href="AddLocation.php">Add Location</a>
		<? } ?>
	</div>
	<div class="contentDiv">





	<div class="divTable">
		 <TABLE id="theTable" class="data" >
			<thead>
				<TR id="headerRow">
					<? if (UserMay("Admin_EditStorage")) { ?>
					<th style="width:40px"></th>
					<? } ?>
					<th>Name</th>
					<th>Description</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<? if (UserMay("Admin_EditStorage")) { ?>
					<td></td>
					<? } ?>

					<td>
						<input id="tbNameH" Type="TEXT">
					</td>
					<td><Input id="tbDescriptionH" TYPE="TEXT"></TD>
				</TR>
			</thead>
				
		</TABLE> 

	</div>
	<div class="spacer"></div>

	</div>
</div>


<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {
		createFilters();
	});
	$('input[id^=tbProductName]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbProductName]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('select[id^=ddlProductType]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlTeam]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbModel]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbModel]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbTeamId]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbTeamId]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbDescription]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbDescription]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});


	$('#btnSubmit').click(function() {
		createFilters();
	});

	$('#btnReset').click(function() {

		$('input:text').val('');
		$('select').each( function() {
			if ($(this).find("option[value='%']").length > 0)
			{
				$(this).val('%');
			}
			else
			{
				$(this).val('');
			}
		});
		createFilters();
	});

	function createFilters()
	{

		var filterArray = new Object();


		filterArray["productName"] = $('#tbProductNameH').val();
		if (filterArray["productName"] == "") filterArray["productName"] = "%";

		filterArray["productType"] = $('#ddlProductTypeH').val();
		if (filterArray["productType"] == "") filterArray["productType"] = "%";

		filterArray["productModel"] = $('#tbModelH').val();
		if (filterArray["productModel"] == "") filterArray["productModel"] = "%";

		filterArray["productDescription"] = $('#tbDescriptionH').val();
		if (filterArray["productDescription"] == "") filterArray["productDescription"] = "%";

		
		rePost(filterArray);

	}

	function displayJSON(jsonString)
	{
		var output = '';
		var theObject = JSON.parse(jsonString);
		var i = theObject.elements.length;
		for (var i = 0; i < theObject.elements.length ; i++ )
		{
			if (typeof(theObject.elements[i].flatAmount) == 'undefined')
			{
				var amount = 0;
			}
			else
			{
				var amount = theObject.elements[i].flatAmount;
			}

			var currencyStr = '';
			if (amount > 0)
			{
				currencyStr = formatCurrency(amount);
			}

			output = output + theObject.elements[i].payeeType + ": " + currencyStr + " " + theObject.elements[i].paymentType + "<br>";
		}

		return output;
	}


	function rePost(filters)
	{
		filters["id"] = "getNewStorageTable";

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

						var editCellContent = perms.Admin_EditStorage ? '<td><div class="selected" style="display:none" id="commandDivSelected_' + _r.storagelocation_id + '"><a href="#" id="btnSave_' + _r.storagelocation_id + '" class="btnSave">' + saveLinkContent() + '</a><a href="#" class="cancelLink">' + cancelLinkContent() + '</a></div><div class="unselected" id="commandDiv_' + _r.storagelocation_id + '"><a href="#" id="editLink_' + _r.storagelocation_id + '" class="editLink" >' + editLinkContent() + '</a></div></td>' : "";

						$('#theTable').append('<tr class="row'+row+'" id="row_' + _r.storagelocation_id + '">' + editCellContent + '<td><div class="view"><span id="spnName_' + _r.storagelocation_id + '">'+_r.storagelocation_name+'</span></div><div class="editCell"><input id="tbName_' + _r.storagelocation_id+'"  value="'+ _r.storagelocation_name + '"></input></div></td><td><div class="view"><span id="spnDescription_'+_r.storagelocation_id+'">'+ _r.description + '</span></div><div class="editCell"><input type="text" id="tbDescription_'+_r.storagelocation_id+'" value="'+_r.description+'" /></div></td></tr>');
						row = 1 - row;
					}

					$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="7" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" class="button" type="submit" value="Clear"/></p><p class="centered"><img src="/<?= $ROOTPATH ?>/images/first.png" class="first"/><img src="/<?= $ROOTPATH ?>/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay" autocomplete="off"/><img src="/<?= $ROOTPATH ?>/images/next.png" class="next"/><img src="/<?= $ROOTPATH ?>/images/last.png" class="last"/></p></td></tr></tfoot>');


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
						  filterColumns: [ 1, 2, 3, 4, 5, 6 ],
						  filterCaseSensitive: false});
					}


					$('.deleteLink').click( function() {
						if (confirm("Are you sure you wish to permanently delete this Product?"))
						{
							var Location_ID = this.id.replace("deleteLink_", "");
							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "deleteProduct",
									Location_ID: Location_ID}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									createFilters();

								}
								else alert("Ajax failed.");
							});

						}
						else
						{
							
						}
					});


					$('.editLink').click( function() {

						var Location_ID = this.id.replace("editLink_", "");



						// Add Edit Style
						$('#theTable tr').removeClass("edit");
						$('#row_' + Location_ID).addClass("edit");

						// Reset Status
						$('.editCell').css("display", "none");
						$('.view').css("display", "block");

						// Reset all fields
						$('#row_' + Location_ID + ' .view').css("display", "none");
						$('#row_' + Location_ID + ' .editCell').css("display", "block");



						// Reset Commands
						$('.selected').css("display", "none");
						$('.unselected').css("display", "block");

						// Command Column
						$('#commandDivSelected_' + Location_ID).css("display", "block");
						$('#commandDiv_' + Location_ID).css("display", "none");

						//Product Type Column
						$('#ddlProductType_' + Location_ID).val($('#spnProductType_' + Location_ID).html());

						// Status Column
						$('#ddlStatus_' + Location_ID).val($('#spanUserStatus_' + Location_ID).html());



						$('.btnSave').unbind();
						$('.btnSave').click( function() {
							var Location_ID = this.id.replace("btnSave_", "");
							var name = $('#tbName_' + Location_ID).val();
							var description = $('#tbDescription_' + Location_ID).val();
							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "updateStorage",
									Location_ID: Location_ID,
									name: name,
									description: description,
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
					var Location_ID = id.replace("lbDelete", "");
					$("#hv_Location_ID").val(Location_ID);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+Location_ID+"?"))
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
	<INPUT TYPE="HIDDEN" id="hv_Location_ID" NAME="Location_ID" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>