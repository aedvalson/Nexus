<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
?>

<? 

$DB = new conn();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Templates</h1></div>
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
		<a href="AddCommissionTemplate.php">Add Template</a>
	</div>
	<div class="contentDiv">





	<div class="divTable">
		 <TABLE id="theTable" class="data" >
			<thead>
				<TR id="headerRow">
					<th style="width:40px"></th>
					<th>Template Name</th>
					<th>Amount</th>
					<th>Min Price</th>
					<th>Max Price</th>
					<th>Dealers</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<td></td>
					<td><input id="tbTemplateNameH" Type="TEXT"></td>
					<td><input id="tbAmountH" Type="TEXT"></td>
					<td><Input id="tbMinPriceH" TYPE="TEXT"></TD>
					<td><Input id="tbMaxPriceH" TYPE="TEXT"></TD>
					<td><Input id="tbDetailsH" TYPE="TEXT"></TD>
					
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
	$('input[id^=tbDate]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbDate], #tbStartDateV, #tbEndDateV').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('select[id^=ddlTeam]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlTeam]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbName]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbName]').each( function () {
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

	$('input[id^=tbUsers]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbUsers]').each( function () {
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


		filterArray["date"] = $('#tbDateH').val();
		if (filterArray["date"] == "") filterArray["date"] = "%";

		filterArray["startDate"] = $('#tbStartDateV').val();
		if (filterArray["startDate"] == "") filterArray["startDate"] = "%";

		filterArray["endDate"] = $('#tbEndDateV').val();
		if (filterArray["endDate"] == "") filterArray["endDate"] = "%";

		filterArray["teamLeaderName"] = $('#tbNameV').val();
		if (filterArray["teamLeaderName"] == "") filterArray["teamLeaderName"] = "%";

		filterArray["teamId"] = $('#tbTeamIdH').val();
		if (filterArray["teamId"] == "") filterArray["teamId"] = "%";

		filterArray["teamId2"] = $('#ddlTeamV').val();
		if (filterArray["teamId2"] == "") filterArray["teamId2"] = "%";

		filterArray["users"] = $('#tbUsersH').val();
		if (filterArray["users"] == "") filterArray["users"] = "%";


		
		rePost(filterArray);

	}

	function displayJSON(jsonString)
	{

		var output = '';
		var theObject = JSON.parse(jsonString);
		var i = theObject.dealers.length;
		for (var i = 0; i < theObject.dealers.length ; i++ )
		{
			if (typeof(theObject.dealers[i].flatAmount) == 'undefined')
			{
				var amount = 0;
			}
			else
			{
				var amount = theObject.dealers[i].flatAmount;
			}

			var currencyStr = '';
			if (amount > 0)
			{
				currencyStr = formatCurrency(amount);
			}

			output = output + " - " + theObject.dealers[i].role + "<br>";
		}

		return output;
	}


	function formatAmount(amount, paymentType)
	{

		if (paymentType == 'flat') return formatCurrency(amount);
		if (paymentType == 'percentage') return amount + "%";
		if (paymentType == 'remaining') return "All Remaining";
	}

	function rePost(filters)
	{
		filters["id"] = "getTemplates";

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
						$('#theTable').append('<tr class="row'+row+'" id="row_' + _r.id + '"><td><div class="selected" style="display:none" id="commandDivSelected_' + _r.id + '"><a href="#" id="btnSave_' + _r.id + '" class="btnSave">' + saveLinkContent() + '</a><a href="#" class="cancelLink">' + cancelLinkContent() + '</a></div><div class="unselected" id="commandDiv_' + _r.id + '"><a href="#" id="editLink_' + _r.id + '" class="editLink" >' + editLinkContent() + '</a><? if ($_SESSION["perm_level"] >= 100) { ?><a href="#" id="deleteLink_' + _r.id + '" class="deleteLink" >' + deleteLinkContent() + '</a> <? } ?></div></td><td><div class="view"><span id="spnTemplateName_' + _r.id + '">'+_r.template_name+'</span></div><div class="editCell"><input id="tbTemplateName_' + _r.id+'"  value="'+ _r.template_name + '"></input></div></td><td><div class="view">'+formatAmount(_r.amount, _r.payment_type)+'</div><div class="editCell">'+formatAmount(_r.amount, _r.payment_type)+'</div></td><td><div class="view"><span id="spnMinPrice_' + _r.id + '">'+formatCurrency(_r.min_price)+'</span></div><div class="editCell"><input type="text" id="tbMinPrice_'+ _r.id + '" value="'+ _r.min_price + '"></input></div></td><td><div class="view"><span id="spnMaxPrice_' + _r.id + '">'+formatCurrency(_r.max_price)+'</span></div><div class="editCell"><input type="text" id="tbMaxPrice_'+ _r.id + '" value="'+ _r.max_price + '"></input></div></td><td><div class="view"><span id="spnRawData_' + _r.id + '">'+displayJSON(_r.dealers)+'</span></div><div class="editCell">'+displayJSON(_r.dealers)+'</div></td></tr>');
						row = 1 - row;
					}

					$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="7" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" class="button" type="submit" value="Clear"/></p><p class="centered"><img src="/<?= $ROOTPATH ?>/images/first.png" class="first"/><img src="/<?= $ROOTPATH ?>/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay"/><img src="/<?= $ROOTPATH ?>/images/next.png" class="next"/><img src="/<?= $ROOTPATH ?>/images/last.png" class="last"/></p></td></tr></tfoot>');


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
						if (confirm("Are you sure you wish to permanently delete this Template?"))
						{
							var template_id = this.id.replace("deleteLink_", "");
							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "deleteTemplate",
									template_id: template_id}, function(json) {
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

						var template_id = this.id.replace("editLink_", "");


						// Add Edit Style
						$('#theTable tr').removeClass("edit");
						$('#row_' + template_id).addClass("edit");

						// Reset Status
						$('.editCell').css("display", "none");
						$('.view').css("display", "block");

						// Reset all fields
						$('#row_' + template_id + ' .view').css("display", "none");
						$('#row_' + template_id + ' .editCell').css("display", "block");



						// Reset Commands
						$('.selected').css("display", "none");
						$('.unselected').css("display", "block");

						// Command Column
						$('#commandDivSelected_' + template_id).css("display", "block");
						$('#commandDiv_' + template_id).css("display", "none");

						// Team Column
						$('#ddlTeam_' + template_id).val($('#spnLeaderID_' + template_id).val());

						// Status Column
						$('#ddlStatus_' + template_id).val($('#spanUserStatus_' + template_id).html());



						$('.btnSave').unbind();
						$('.btnSave').click( function() {
							var template_id = this.id.replace("btnSave_", "");
							var template_name = $('#tbTemplateName_' + template_id).val();
							var min_price = $('#tbMinPrice_' + template_id).val();
							var max_price = $('#tbMaxPrice_' + template_id).val();

							$.post('/php/Includes/ajax.php', 
								{ id: "updateTemplate",
									template_id: template_id,
									template_name: template_name,
									min_price: min_price,
									max_price: max_price,
									sender: <?= $_SESSION["user_id"] ?>}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									// reset Rows
									$('#theTable tr').removeClass("edit");

									// Reset Status
									$('.editCell').css("display", "none");
									$('.view').css("display", "block");

									// Update Spans
									$('#spnTeamLeader_' + template_id).html(args.output[0].FirstName + ' ' + args.output[0].LastName);
									$('#spnTeamName_' + template_id).html(args.output[0].team_name);

									// Reset all fields
									$('.editCell').css("display", "none");
									$('.view').css("display", "block");

									// Reset Commands
									$('.selected').css("display", "none");
									$('.unselected').css("display", "block");

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
					var template_id = id.replace("lbDelete", "");
					$("#hv_template_id").val(template_id);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+template_id+"?"))
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
	<INPUT TYPE="HIDDEN" id="hv_template_id" NAME="template_id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>