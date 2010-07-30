<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>

<? 

$DB = new conn();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Contacts</h1></div>

	<div id="bulletManageInventory" style="height:auto;" class="navContent">
		<div class="divFilters">
			<div>
				<label>Type:</label>
				<SELECT id="ddlContactTypeV" style="width:90%;">
					<?
					$ContactTypes = $DB->getContactTypes();
						?><OPTION VALUE="%">Any Type</OPTION><?
					foreach ($ContactTypes as $ct)
					{
						?><OPTION value="<?= $ct["contact_type_id"] ?>"><? echo $ct["contact_type_name"]; ?></option><?
					}
					?>
				</SELECT>
			</div>

			<div>
				<label>First Name:</label>
				<INPUT style="width:90%" id="tbFirstV">
			</div>

			<div>
				<label>Last Name:</label>
				<INPUT style="width:90%" id="tbLastV">
			</div>

			<div>
				<label>Email:</label>
				<INPUT style="width:90%" id="tbEmailV">
			</div>

			<div>
				<label>Phone:</label>
				<INPUT style="width:90%" id="tbPhoneV">
			</div>

			<div>
				<label>Notes:</label>
				<INPUT style="width:90%" id="tbNotesV">
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
		<a href="AddContact.php">Add Contact</a>
	</div>
	<div class="contentDiv">





	<div class="divTable">
		 <TABLE id="theTable" class="data" ><thead>
				<TR id="headerRow">
<!--				<th>Exp</th> -->
				<th style="width:8em;">First Name</th>
				<th style="width:8em;">Last Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Type</th>
				<th>Notes</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<td><Input id="tbFirstH" TYPE="TEXT"></TD>
					<TD><INPUT id="tbLastH" TYPE="TEXT"></TD>
					<TD><INPUT id="tbEmailH" TYPE="TEXT"></TD>
					<TD><INPUT id="tbPhoneH" TYPE="TEXT"></TD>
					<TD>
						<SELECT id="ddlContactTypeV" style="width:90%;">
							<?
							$ContactTypes = $DB->getContactTypes();
								?><OPTION VALUE="%">Any Type</OPTION><?
							foreach ($ContactTypes as $ct)
							{
								?><OPTION value="<?= $ct["contact_type_id"] ?>"><? echo $ct["contact_type_name"]; ?></option><?
							}
							?>
						</SELECT>
					</TD>
					<TD><INPUT id="tbNotesH" TYPE="TEXT"></TD>
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
		$("a[id^=expand_]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var inventory_id = id.replace("expand_", "");
					$("#td_" + inventory_id).html("<img src='/<?= ROOTPATH ?>/images/loading.gif'>");
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

	$('select[id^=ddlContactType]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlContactType]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});
	$('input[id^=tbFirst]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbFirst]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});
	$('input[id^=tbLast]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbLast]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbEmail]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbEmail]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbPhone]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbPhone]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbNotes]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbNotes]').each( function () {
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

		filterArray["first"] = $('#tbFirstV').val();
		if (filterArray["first"] == "") filterArray["first"] = "%";

		filterArray["last"] = $('#tbLastV').val();
		if (filterArray["last"] == "") filterArray["last"] = "%";

		filterArray["Email"] = $('#tbEmailV').val();
		if (filterArray["Email"] == "") filterArray["Email"] = "%";

		filterArray["Phone"] = $('#tbPhoneV').val();
		if (filterArray["Phone"] == "") filterArray["Phone"] = "%";

		filterArray["Notes"] = $('#tbNotesV').val();
		if (filterArray["Notes"] == "") filterArray["Notes"] = "%";

		filterArray["contacttype"] = $('#ddlContactTypeV').val();

		rePost(filterArray);

	}


	function rePost(filters)
	{
		filters["id"] = "getNewContactTable";

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
				$('#theTable #loadingRow').remove();
				var row = 0;
				if (args.output)
				{
					for (r in args.output)
					{
						_r = args.output[r];
						$('#theTable tbody').append('<tr class="row'+row+'"><td>'+_r.contact_firstname+'</td><td>'+_r.contact_lastname+'</td><td>'+_r.contact_email+'</td><td>'+_r.contact_phone+' '+_r.contact_phonedetails+'</td><td>'+_r.contacttype+'</td><td>'+_r.contact_notes+'</td></tr>');
						row = 1 - row;
					}
				}
				else
				{
					$('#theTable tbody').append('<tr id="errorRow" style="height:50px"><td><font color="red">No Records Found.</font></td></tr>');
				}	
				$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="6" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" type="submit" value="Clear"/></p><p class="centered"><img src="/<?= $ROOTPATH ?>/images/first.png" class="first"/><img src="/<?= $ROOTPATH ?>/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay"/><img src="/<?= $ROOTPATH ?>/images/next.png" class="next"/><img src="/<?= $ROOTPATH ?>/images/last.png" class="last"/></p></td></tr></tfoot>');

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
					  filterColumns: [1, 2, 3, 4, 5],
					  filterCaseSensitive: false});
				}

			}
			else
			{
				 alert("Ajax failed.");
			}
		  });
	}
	
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




<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {

		// HoverRow
		$("#theTable tr.row0, #theTable tr.row1").hover( function() {
			$(this).addClass("hoverRow");
		}, function () {
			$(this).removeClass("hoverRow");
		});

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
</SCRIPT>



<FORM id="theForm"  action="" method="post">
	<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
	<INPUT TYPE="HIDDEN" id="hv_user_id" NAME="user_id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>