<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>
<? 
if (!UserMay("Admin_ViewUsers")) { AccessDenied(); }
$DB = new conn();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Users</h1></div>

	<div id="bulletManageInventory" style="height:auto;" class="navContent">
	<div id="bullets">
		<div class="divFilters">
			<div>
				<label>Username:</label>
				<INPUT style="width:100%" id="tbUsernameV">
			</div>

			<div>
				<label>Status:</label>
				<SELECT id="ddlStatusV" style="width:100%;">
					<OPTION VALUE="%">Any</OPTION>
					<?
					$products = $DB->getUserStatuses();
					foreach ($products as $prod)
					{
						?><OPTION value="<?= $prod["Status"] ?>"><? echo $prod["Status"]; ?></option><?
					}
					?>
				</SELECT>
			</div>

			<div>
				<label>First Name:</label>
				<INPUT style="width:100%" id="tbFirstV">
			</div>

			<div>
				<label>Last Name:</label>
				<INPUT style="width:100%" id="tbLastV">
			</div>

			<div>
				<label>Team:</label>
						<SELECT id="ddlTeamV" style="width:100%">
							<OPTION value="%">Any</OPTION>
							<?
							$teams = $DB->getTeams();
							foreach ($teams as $team)
							{
								?><OPTION value="<?= $team["team_id"] ?>"><? echo $team["team_name"]; ?></option><?
							}
							?>
						</SELECT>
			</div>


			<div>
				<label>Permission Role</label>
				<SELECT id="ddlPermV" style="width:100%">
					<OPTION Value="%">Any</OPTION>
					<?
					$perms = $DB->getPermissionRoles();
					foreach($perms as $perm)
					{
						?><OPTION value="<?= $perm["id"] ?>"><?= $perm["name"] ?></OPTION><?
					}
					?>
				</SELECT>
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
		<? if (UserMay("Admin_EditUsers"))
		{ ?>
		<a href="AddUser.php">Add User</a>
		<? } ?>
	</div>
	<div class="contentDiv">





	<div class="divTable">
		 <TABLE id="theTable" class="data" >
			<thead>
				<TR id="headerRow">
				<? if (UserMay("Admin_EditUsers")) { ?>
				<th style="width:40px"></th>
				<? } ?>
				<th style="width:80px">UserName</th>
				<th style="width:80px">First</th>
				<th style="width:80px">Last</th>
				<th style="width:70px">Team</th>
				<th>Perms</th>
				<th>Status</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<? if (UserMay("Admin_EditUsers")) { ?>
					<td></td>
					<? } ?>
					<TD>
						<Input id="tbUsernameH" TYPE="TEXT">
					</TD>
					<td><Input id="tbFirstH" TYPE="TEXT"></TD>
					<TD><INPUT id="tbLastH" TYPE="TEXT"></TD>
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
						<SELECT id="ddlPermH" style="width:100%">
							<OPTION Value="%">Any</OPTION>
							<?
							$perms = $DB->getPermissionRoles();
							foreach($perms as $perm)
							{
								?><OPTION value="<?= $perm["id"] ?>"><?= $perm["name"] ?></OPTION><?
							}
							?>
						</SELECT>
					</td>
					<TD>
						<SELECT id="ddlStatusH" style="width:100%;">
							<OPTION VALUE="%">Any</OPTION>
							<?
							$products = $DB->getUserStatuses();
							foreach ($products as $prod)
							{
								?><OPTION value="<?= $prod["Status"] ?>"><? echo $prod["Status"]; ?></option><?
							}
							?>
						</SELECT>
					</TD>
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


	$('input[id^=tbUsername]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbUsername]').each( function () {
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
	$('select[id^=ddlPerm]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlPerm]').each( function () {
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

		filterArray["username"] = $('#tbUsernameV').val();
		if (filterArray["username"] == "") filterArray["username"] = "%";

		filterArray["status"] = $('#ddlStatusV').val();

		filterArray["perm"] = $('#ddlPermV').val();

		filterArray["team"] = $('#ddlTeamV').val();

		filterArray["first"] = $('#tbFirstV').val();
		if (filterArray["first"] == "") filterArray["first"] = "%";

		filterArray["last"] = $('#tbLastV').val();
		if (filterArray["last"] == "") filterArray["last"] = "%";


		rePost(filterArray);

	}


	function rePost(filters)
	{
		filters["id"] = "getNewUserTable";

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

						var editCellContent = perms.Admin_EditUsers ? '<td><div class="selected" style="display:none" id="commandDivSelected_' + _r.user_id + '"><a href="#" id="btnSave_' + _r.user_id + '" class="btnSave">' + saveLinkContent() +'</a><a href="#" class="cancelLink">' + cancelLinkContent() + '</a></div><div class="unselected" id="commandDiv_' + _r.user_id + '">		<? if ($_SESSION["perm_level"] >= 100) { ?><a href="#" id="editLink_' + _r.user_id + '" class="editLink" >' + editLinkContent() + '</a><? } ?><? if ($_SESSION["perm_level"] >= 1000) { ?><a href="#" id="deleteLink_' + _r.user_id + '" class="deleteLink" >' + deleteLinkContent() + '</a></div><? } ?></td>' : "";
						
						$('#theTable').append('<tr class="row'+row+'" id="row_' + _r.user_id + '">' + editCellContent + '<td><div class="view"><a href="AddUser.php?id='+_r.user_id+'"><span id="spnUsername_' + _r.user_id + '">'+_r.Username+'</span></a></div><div class="editCell"><input id="tbUsername_'+_r.user_id+'" value="'+_r.Username+'"></div></td><td><div class="view"><span id="spnFirstName_' + _r.user_id + '">'+_r.FirstName+'</span></div><div class="editCell"><input id="tbFirstName_'+_r.user_id+'" value="'+_r.FirstName+'"></div></td><td><div class="view"><span id="spnLastName_' + _r.user_id + '">'+_r.LastName+'</span></div><div class="editCell"><input id="tbLastName_'+_r.user_id+'" value="'+_r.LastName+'"></div></td><td><div class="view"><span style="display:none;" id="spanTeamId_' + _r.user_id +'">'+_r.team_id+'</span><span id="spnTeam_' + _r.user_id + '">'+_r.team_name+'</span></div><div class="editCell"><SELECT id="ddlTeam_'+_r.user_id+'"><?
							$teams = $DB->getTeams();
							foreach ($teams as $team)
							{
								?><OPTION value="<?= $team["team_id"] ?>"><? echo str_replace("'", "\'", $team["team_name"]); ?></option><?
							}
							?></SELECT></div></td><td><div class="view"><span id="spnRoleName_'+_r.user_id+'">'+ _r.permname + '</span></div><div class="editCell"><SELECT id="ddlPerm_' + _r.user_id + '" style="width:100%"><?
							$perms = $DB->getPermissionRoles();
							foreach($perms as $perm)
							{
								?><OPTION value="<?= $perm["id"] ?>"><?= $perm["name"] ?></OPTION><?
							}
							?></SELECT></div></td><td><div class="view"><span id="spanUserStatus_'+_r.user_id+'">'+_r.Status+'</span></div><div class="editCell"><SELECT id="ddlStatus_'+_r.user_id+'" style="width:100%;"><?
							$products = $DB->getUserStatuses();
							foreach ($products as $prod)
							{
								?><OPTION value="<?= $prod["Status"] ?>"><? echo $prod["Status"]; ?></option><?
							}
							?></SELECT></div></td></tr>');
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

					$('.deleteLink').unbind();
					$('.deleteLink').live("click", function() {
						if (confirm("Are you sure you wish to permanently delete this User?"))
						{
							var user_id = this.id.replace("deleteLink_", "");
							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "deleteUser",
									user_id: user_id}, function(json) {
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


					$('.editLink').unbind();
					$('.editLink').live("click", function() {

						var user_id = this.id.replace("editLink_", "");


						// Add Edit Style
						$('#theTable tr').removeClass("edit");
						$('#row_' + user_id).addClass("edit");

						// Reset Status
						$('.editCell').css("display", "none");
						$('.view').css("display", "block");

						// Reset all fields
						$('#row_' + user_id + ' .view').css("display", "none");
						$('#row_' + user_id + ' .editCell').css("display", "block");



						// Reset Commands
						$('.selected').css("display", "none");
						$('.unselected').css("display", "block");

						// Command Column
						$('#commandDivSelected_' + user_id).css("display", "block");
						$('#commandDiv_' + user_id).css("display", "none");

						// Team Column
						$('#ddlTeam_' + user_id).val($('#spanTeamId_' + user_id).html());

						// Status Column
						$('#ddlStatus_' + user_id).val($('#spanUserStatus_' + user_id).html());



						$('.btnSave').unbind();
						$('.btnSave').click( function() {
							var user_id = this.id.replace("btnSave_", "");
							var FirstName = $('#tbFirstName_' + user_id).val();
							var LastName = $('#tbLastName_' + user_id).val();
							var Username = $('#tbUsername_' + user_id).val();
							var team_id = $('#ddlTeam_' + user_id).val();
							var status = $('#ddlStatus_' + user_id).val();
							var perm = $('#ddlPerm_' + user_id).val();

							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "updateUser",
									user_id: user_id,
									FirstName: FirstName,
									LastName: LastName,
									Username: Username,
									Status: status,
									perm: perm,
									team_id: team_id,
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
									$('#spnUsername_' + user_id).html(args.output[0].Username);
									$('#spnFirstName_' + user_id).html(args.output[0].FirstName);
									$('#spnLastName_' + user_id).html(args.output[0].LastName);
									$('#spnTeam_' + user_id).html(args.output[0].team_name);
									$('#spnTeamId_' + user_id).html(args.output[0].team_id);
									$('#spanUserStatus_' + user_id).html(args.output[0].Status);
									

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
	<INPUT TYPE="HIDDEN" id="hv_user_id" NAME="user_id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>