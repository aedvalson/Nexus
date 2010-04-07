<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";


$DB = new conn();
$DB->connect();


// Form Vars
	if ($_REQUEST)
	{
		if ($_REQUEST["Action"] )
		{
			$action = $_REQUEST["Action"];
			//echo $action;
			if ($action == "addNew")
			{
				// ALL FORM INPUTS MUST BE SANITIZED

				$TeamName = $DB->sanitize($_REQUEST["tbTeamName"]);
				$TeamLeader = $DB->sanitize($_REQUEST["ddlTeamLeader"]);

				$sql = "INSERT INTO teams (team_name, team_leader) VALUES ('".$TeamName."', ".$TeamLeader.")";
				$DB->execute_nonquery($sql);

				$DB->addHistory( 'teams', $_SESSION["user_id"],  "insert", "" );

				header("Location: ManageTeams.php");
			}
		}
	}

	$users = $DB->getUsers();


$DB->close();

?>


<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Teams</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" id="custBulletLink">Add New Team</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
		<a href="#" id="lbSave">Save</a>
	</div>

	<div class="contentDiv">

		<div class="formDiv" style="display: block">
			<h1>Team Details</h1>


		<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

		   <ul class="form" id="theForm">
			 <li class="validated" id="tbTeamName_li">
						  <label for="r_tbTeamName">Team Name:</label>
						  <div id="tbTeamName_img"></div>
						  <input class="validated" name="tbTeamName" id="tbTeamName" type="text" maxlength="20" value=""  />
						  <input type="hidden" id="tbTeamName_val" value="waiting">
						  <div id="tbTeamName_msg"></div>
				  </li>
			 <li class="validated" id="ddlTeamLeader_li">
						  <label for="r_ddlTeamLeader">Team Leader:</label>
						  <div id="ddlTeamLeader_img"></div>
						  <select class="validated" id="ddlTeamLeader" name="ddlTeamLeader">
						  <? foreach ($users as $user) { ?>
							<option value="<?= $user["user_id"] ?>"><?= $user["FirstName"] ?> <?= $user["LastName"] ?></option>
							<? } ?>
						  </select>
						  <input type="hidden" id="ddlTeamLeader_val" value="waiting">
						  <div id="ddlTeamLeader_msg"></div>
				  </li>
			 <li class="validated" id="btnSubmit_li">
						  <label for="r_btnSubmit"></label>
						  <div id="btnSubmit_img"></div>
						  <input type="hidden" name="Action" value="addNew"></input>
						  <input id="btnSubmit" type="Submit" maxlength="20" value="Submit"  />
						  <div id="btnSubmit_msg"></div>
				  </li>          
					   
		   </ul>


		</form>
		</div>
	</div>
</div>
<SCRIPT type="text/javascript">
	$('#lbSave').click( function() {
		validateForm(document.getElementById('theForm'));
		$('#theForm').submit();
		
	});

</SCRIPT>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>