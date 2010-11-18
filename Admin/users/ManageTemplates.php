<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>
<? 

$DB = new conn();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Commission Templates</h1></div>

	<div id="bulletManageInventory" style="height:auto;" class="navContent">
		<div class="divFilters"></div>

		<div class="spacer"></div>
	</div>
	

	<div class="navSpacer"></div>
	<div class="spacer"></div>

</div>

<div class="pageContent">

	<div class="contentHeaderDiv">
		<a href="AddTeam.php">Add Team</a>
	</div>
	<div class="contentDiv">





	<div class="divTable">
		 <TABLE id="theTable" class="data" >
			<thead>
				<TR id="headerRow">
					<th style="width:40px"></th>
					<th>Team_id</th>
					<th>Leader</th>
					<th style="width:100px">Team Name</th>
					<th>Users</th>
					<th>Date Added</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<td></td>
					<td>
						<input id="tbTeamIdH" Type="TEXT">
					</td>
					<td><Input id="tbNameH" TYPE="TEXT"></TD>
					<td>
						<SELECT id="ddlTeamH" style="width:100%">
							<OPTION value="%">Any</OPTION>
							<?
							$teams = $DB->getTeams();
							foreach ($teams as $team)
							{
								?><OPTION value="<?= $team["team_id"] ?>"><? echo $team["team_name"]; ?></option><?
							}
							?>
						</SELECT>
					</td>
					<td>
						<input id="tbUsersH" type="text">
					</td>
					<td>
						<input id="tbDateH" class="datepicker" Type="TEXT">
					</td>
					
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


	function rePost(filters)
	{
		filters["id"] = "getNewTeamTable";

		$('#theTable tr').each( function () {
			id = $(this).attr('id');
			if (id != 'headerRow' && id != 'filterRow')
			{
				$(this).remove(); // Remove all existing rows
			}
		});
		$('#theTable').append('<tr id="loadingRow"><td style="padding:25px"><center><img src="/php/images/loading.gif"><br>Filtering Results</center></td></tr>');

       	$.post('/php/Includes/ajax.php', filters, function(json) {
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
						$('#theTable').append('<tr class="row'+row+'" id="row_' + _r.team_id + '"><td><div class="selected" style="display:none" id="commandDivSelected_' + _r.team_id + '"><a href="#" id="btnSave_' + _r.team_id + '" class="btnSave">Save</a><br><a href="#" class="cancelLink">Cancel</a></div><div class="unselected" id="commandDiv_' + _r.team_id + '"><a href="#" id="editLink_' + _r.team_id + '" class="editLink" >Edit</a><br></div> </td><td><div class="view"><span id="spnTeamId_' + _r.team_id + '">'+_r.team_id+'</span></div><div class="editCell">'+_r.team_id+'</div></td><td><div class="view"><span id="spnTeamLeader">'+_r.firstname+' '+_r.lastname+'</span><input id="spnLeaderID_' + _r.team_id+'" style="display:none" value="'+ _r.team_leader + '"></input></div><div class="editCell"><SELECT id="ddlTeam_'+_r.team_id+'"><?
							$users = $DB->getUsers();
							foreach ($users as $user)
							{
								?><OPTION value="<?= $user["user_id"] ?>"><?= $user["FirstName"] ?> <?= $user["LastName"] ?></option><?
							}
							?></SELECT></div></td><td><div class="view"><span style="display:none;" id="spanTeamId_' + _r.team_id +'">'+_r.team_id+'</span><span id="spnTeam_' + _r.team_id + '">'+_r.team_name+'</span></div><div class="editCell"><input type="text" id="tbTeamName_'+ _r.team_id + '" value="'+ _r.team_name + '"></input></div></td><td><div class="view"><span id="spnTeamUsers_' + _r.team_id + '">'+_r.team_users+'</span></div><div class="editCell">'+_r.team_users+'</div></td><td><div class="view">'+ prettyDate( _r.date_added) + '</div></td></tr>');
						row = 1 - row;
					}

					$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="7" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" class="button" type="submit" value="Clear"/></p><p class="centered"><img src="/php/images/first.png" class="first"/><img src="/php/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay" autocomplete="off"/><img src="/php/images/next.png" class="next"/><img src="/php/images/last.png" class="last"/></p></td></tr></tfoot>');


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


					$('.editLink').click( function() {

						var team_id = this.id.replace("editLink_", "");


						// Add Edit Style
						$('#theTable tr').removeClass("edit");
						$('#row_' + team_id).addClass("edit");

						// Reset Status
						$('.editCell').css("display", "none");
						$('.view').css("display", "block");

						// Reset all fields
						$('#row_' + team_id + ' .view').css("display", "none");
						$('#row_' + team_id + ' .editCell').css("display", "block");



						// Reset Commands
						$('.selected').css("display", "none");
						$('.unselected').css("display", "block");

						// Command Column
						$('#commandDivSelected_' + team_id).css("display", "block");
						$('#commandDiv_' + team_id).css("display", "none");

						// Team Column
						$('#ddlTeam_' + team_id).val($('#spnLeaderID_' + team_id).val());

						// Status Column
						$('#ddlStatus_' + team_id).val($('#spanUserStatus_' + team_id).html());



						$('.btnSave').unbind();
						$('.btnSave').click( function() {
							var team_id = this.id.replace("btnSave_", "");
							var team_leader = $('#ddlTeam_' + team_id).val();
							var team_name = $('#tbTeamName_' + team_id).val();

							$.post('/php/Includes/ajax.php', 
								{ id: "updateTeam",
									team_id: team_id,
									team_leader: team_leader,
									team_name: team_name}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									// reset Rows
									$('#theTable tr').removeClass("edit");

									// Reset Status
									$('.editCell').css("display", "none");
									$('.view').css("display", "block");

									// Update Spans
									$('#spnTeamLeader_' + team_id).html(args.output[0].FirstName + ' ' + args.output[0].LastName);
									$('#spnTeamName_' + team_id).html(args.output[0].team_name);

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
					var team_id = id.replace("lbDelete", "");
					$("#hv_team_id").val(team_id);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+team_id+"?"))
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
	<INPUT TYPE="HIDDEN" id="hv_team_id" NAME="team_id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>