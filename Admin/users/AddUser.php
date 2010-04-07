<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";


$DB = new conn();
$DB->connect();


// Form Vars
	if ($_REQUEST)
	{
		if (isset($_REQUEST["Action"] ))
		{
			$action = $_REQUEST["Action"];
			//echo $action;
			if ($action == "addNew")
			{
				// ALL FORM INPUTS MUST BE SANITIZED

				$Username = $DB->sanitize($_REQUEST["tbUsername"]);
				$FirstName = $DB->sanitize($_REQUEST["tbFirstName"]);
				$LastName = $DB->sanitize($_REQUEST["tbLastName"]);
				$Password = $DB->sanitize($_REQUEST["tbPassword"]);
				$Team = $DB->sanitize($_REQUEST["Teams"]);
				
				$sql = "INSERT INTO users (Username, user_password, FirstName, LastName, team_id) VALUES ('".$Username."', MD5('".$Password."'), '".$FirstName."', '".$LastName."', ".$Team.")";
				$DB->execute_nonquery($sql);

				$DB->addHistory( 'users', $_SESSION["user_id"],  "insert", "" );

				header("Location: ManageUsers.php");
			}
		}
	}


$DB->close();

?>



<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Users</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" id="custBulletLink">Add New User</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
		<!--<a href="#" id="lbSave">Save</a>-->
	</div>

	<div class="contentDiv">

		<div class="formDiv" style="display: block">
			<h1>Add New User</h1>


		<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

			   <ul id="theUL" class="form">
				 <li class="validated" id="tbUsername_li">
							  <label for="r_tbUsername">Username:</label>
							  <div id="tbUsername_img"></div>
							  <input class="validated" name="tbUsername" id="tbUsername" type="text" maxlength="20" value=""  />
							  <input type="hidden" id="tbUsername_val" value="waiting">
							  <div id="tbUsername_msg"></div>
					  </li>
				 <li class="validated" id="tbPassword_li">
							  <label for="r_tbPassword">Password:</label>
							  <div id="tbPassword_img"></div>
							  <input class="validated" name="tbPassword" id="tbPassword" type="password" maxlength="20" value=""  />
							  <input type="hidden" id="tbPassword_val" value="waiting">
							  <div id="tbPassword_msg"></div>
					  </li> 
				 <li class="validated" id="tbConfirmPassword_li">
							  <label for="r_tbConfirmPassword">Confirm Password:</label>
							  <div id="tbConfirmPassword_img"></div>
							  <input class="validated" name="tbConfirmPassword" id="tbConfirmPassword" type="password" maxlength="20" value=""  />
							  <input type="hidden" id="tbConfirmPassword_val" value="waiting">
							  <div id="tbConfirmPassword_msg"></div>
					  </li>      
				 <li class="validated" id="tbFirstName_li">
							  <label for="r_tbFirstName">FirstName:</label>
							  <div id="tbFirstName_img"></div>
							  <input class="validated" name="tbFirstName" id="tbFirstName" type="text" maxlength="20"  />
							  <div id="tbFirstName_msg"></div>
					  </li>
				 <li class="validated" id="tbLastName_li">
							  <label for="r_tbLastName">LastName:</label>
							  <div id="tbLastName_img"></div>
							  <input class="validated" name="tbLastName" id="tbLastName" type="text" maxlength="20"   />
							  <div id="tbLastName_msg"></div>
					  </li>
				<?
					$F = new FormElements();
					$F->ddlTeams();
					$F->ddlPermissionRoles();
					?>
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

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>