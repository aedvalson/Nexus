<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
	if (!UserMay("Admin_EditUsers")) { AccessDenied(); }

$DB = new conn();
$DB->connect();
$UsernameDisabled = 0;
$user_id = "";
$dummytext = "";


// Form Vars
	$Action = "addNew";
	if ($_REQUEST)
	{
		$firephp->log($_REQUEST);
		if ( isset($_REQUEST["id"]) )
		{
			$user_id = $DB->sanitize($_REQUEST["id"]);
			$sql = "SELECT * FROM users WHERE user_id = '".$user_id."'";

			$result = $DB->query($sql);
			if ($result) {
				$user = mysql_fetch_assoc($result);
				$firephp->log($user);
				$UsernameDisabled = 1;
				$dummytext = "dummytext";
			}

			$Action = "update";
		}

		if ( isset($_REQUEST["user_id"]) )
		{
			$Action = "update";
			$user_id = $DB->sanitize($_REQUEST["user_id"]);
		}


		if (isset($_REQUEST["Action"] ))
		{
		$firephp->log("request action");
			$action = $_REQUEST["Action"];

			// ALL FORM INPUTS MUST BE SANITIZED
			$Username = $DB->sanitize($_REQUEST["Username"]);
			$FirstName = $DB->sanitize($_REQUEST["FirstName"]);
			$LastName = $DB->sanitize($_REQUEST["LastName"]);
			$Role = $DB->sanitize($_REQUEST["PermissionRole"]);
			$Password = $DB->sanitize($_REQUEST["tbPassword"]);
			$Team = $DB->sanitize($_REQUEST["Teams"]);
			$License = $DB->sanitize($_REQUEST["License"]);
			$Social = $DB->sanitize($_REQUEST["Social"]);
			$BirthDate = $DB->sanitize($_REQUEST["BirthDate"]);
			$Address1 = $DB->sanitize($_REQUEST["Address1"]);
			$Address2 = $DB->sanitize($_REQUEST["Address2"]);
			$HomeType = $DB->sanitize($_REQUEST["HomeType"]);
			$City = $DB->sanitize($_REQUEST["City"]);
			$State = $DB->sanitize($_REQUEST["State"]);
			$ZipCode = $DB->sanitize($_REQUEST["ZipCode"]);
			$Phone = $DB->sanitize($_REQUEST["Phone"]);
			$Cell = $DB->sanitize($_REQUEST["Cell"]);
			$ContactFirstName = $DB->sanitize($_REQUEST["ContactFirstName"]);
			$ContactLastName = $DB->sanitize($_REQUEST["ContactLastName"]);
			$ContactAddress1 = $DB->sanitize($_REQUEST["ContactAddress1"]);
			$ContactAddress2 = $DB->sanitize($_REQUEST["ContactAddress2"]);
			$ContactCity = $DB->sanitize($_REQUEST["ContactCity"]);
			$ContactState = $DB->sanitize($_REQUEST["ContactState"]);
			$ContactZipCode = $DB->sanitize($_REQUEST["ContactZipCode"]);
			$ContactPhone = $DB->sanitize($_REQUEST["ContactPhone"]);
			$ContactCell = $DB->sanitize($_REQUEST["ContactCell"]);
			$dtoffice = "";
			$firephp->log($_REQUEST["dtoffice"]);
			if (isset($_REQUEST["dtoffice"])) {
				$firephp->log("DT Set");
				$dtoffice = $DB->sanitize($_REQUEST["dtoffice"]);
			}

			if ($action == "addNew" && !$user_id)
			{
				$sql = "INSERT INTO users (Username, user_password, FirstName, LastName, permission_role, team_id, License, Social, BirthDate, Address, Address2, HomeType, City, State, ZipCode, Phone, Cell, ContactFirstName, ContactLastName, ContactAddress, ContactAddress2, ContactCity, ContactState, ContactZipCode, ContactPhone, ContactCell, dtoffice) VALUES ('".$Username."', MD5('".$Password."'), '".$FirstName."', '".$LastName."', '".$Role."', ".$Team.", '".$License."', '".$Social."', '".$BirthDate."', '".$Address1."', '".$Address2."', '".$HomeType."', '".$City."', '".$State."', '".$ZipCode."', '".$Phone."', '".$Cell."', '".$ContactFirstName."', '".$ContactLastName."', '".$ContactAddress1."', '".$ContactAddress2."', '".$ContactCity."', '".$ContactState."', '".$ContactZipCode."', '".$ContactPhone."', '".$ContactCell."', '".$dtoffice."')";
				
				$firephp->log($sql);
				$DB->execute_nonquery($sql);
				$DB->addHistory( 'users', $_SESSION["user_id"],  "insert", "" );
				header("Location: ManageUsers.php");

			}

			if ($action == "update")
			{
				$Action = "update";

				$sql = "UPDATE users SET FirstName = '" . $FirstName . "', LastName = '" . $LastName . "', permission_role = '" . $Role . "', team_id = '" . $Team . "', License = '" . $License . "', Social = '" . $Social . "', BirthDate = '" . $BirthDate . "', Address = '" . $Address1 . "', Address2 = '" . $Address2 . "', HomeType = '" . $HomeType . "', City = '" . $City . "', State = '" . $State . "', ZipCode = '" . $ZipCode . "', Phone = '" . $Phone . "', Cell = '" . $Cell . "', ContactFirstName = '" . $ContactFirstName . "', ContactLastName = '" . $ContactLastName . "', ContactAddress = '" . $ContactAddress1 . "', ContactAddress2 = '" . $ContactAddress2 . "', ContactCity = '" . $ContactCity . "', ContactState = '" . $ContactState . "', ContactZipCode = '" . $ContactZipCode . "', ContactPhone = '" . $ContactPhone . "', ContactCell = '" . $ContactCell . "', dtoffice = '" . $dtoffice . "' WHERE user_id = " . $user_id;

				$DB->execute_nonquery($sql);
				$DB->addHistory( 'users', $_SESSION["user_id"],  "update", "" );


				if ($Password && $Password != "dummytext")
				{
					$sql = "UPDATE users SET user_password = MD5('".$Password."') WHERE user_id = " . $user_id;

					$DB->execute_nonquery($sql);
					$DB->addHistory( 'users', $_SESSION["user_id"],  "update", "" );						
				}

				header("Location: ManageUsers.php");
			}
		}
	}


$DB->close();
$F = new FormElements();

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

		<div class="formDiv" style="display: block; background-color: #EDECDC">
			<h1>Add New User</h1>


		<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

				<ul id="theUL" class="form">
				<h1>User Information</h1>
				<?= 
				$F->tbVal("Username", "Username","", "float:left", $user["Username"], $UsernameDisabled);
				$firephp->log($AgencyParams);
				if (count($AgencyParams["DTOffices"]) > 0) {
					$F->ddlDTOffices( "dtoffice", "DT Office", $css="float: left", $AgencyParams["DTOffices"], $user);
				}
				$F->tbPassword("Password", "Password", "clear: both; float:left;", $dummytext);
				$F->tbPassword("ConfirmPassword", "Confirm Password", "float:left;", $dummytext);
				$F->ddlTeams($user["team_id"], "clear: both; float: left;");
				$F->ddlPermissionRoles($user["permission_role"], "float:left");
				?>

				<div style="clear: both"></div>
				<h1>Personal Information</h1>
				<?
				$F->tbVal("FirstName", "First Name", "", "float: left;", $user["FirstName"]);
				$F->tbVal("LastName", "Last Name", "", "float: left;", $user["LastName"]);
				$F->tbNotVal("License", "Driver's License", "", "clear: both; float: left;", $user["License"]);
				$F->tbNotVal("Social", "Social Security Number", "", "float: left", $user["Social"]);
				$F->tbNotVal("BirthDate", "Birth Date", "", "clear: both;", $user["BirthDate"]);
				?>

				<div style="clear: both"></div>
				<h1>Contact Info:</h1>
				<?
				$firephp->log($AgencyParams);
				$F->tbVal("Address1", "Address 1", "", "float: left;", $user["Address"]);
				$F->tbNotVal("Address2", "Address 2", "", "float: left;", $user["Address2"]);
				$F->ddlHomeType("float:left;", $user["HomeType"]);

				$F->tbVal("City", "City", "", "clear: both; float: left", $user["City"]);
				$F->ddlStates($user["State"], "State", "State", "float:left;");
				$F->tbVal("ZipCode", "Zip Code", "", "float: left", $user["ZipCode"]);

				$F->tbVal("Phone", "Phone", "", "clear: both; float: left", $user["Phone"]);
				$F->tbVal("Cell", "Cell", "", "float: left", $user["Cell"]);
				?>

				<div style="clear: both"></div>
				<h1>Emergency Contact:</h1>
				<?
				$F->tbNotVal("ContactFirstName", "Contact First Name", "", "float: left;", $user["ContactFirstName"]);
				$F->tbNotVal("ContactLastName", "Contact Last Name", "", "float: left;", $user["ContactLastName"]);

				$F->tbNotVal("ContactAddress1", "Contact Address 1", "", "clear: both; float: left;", $user["ContactAddress"]);
				$F->tbNotVal("ContactAddress2", "Contact Address 2", "", "float: left;", $user["ContactAddress2"]);

				$F->tbNotVal("ContactCity", "Contact City", "", "clear: both; float: left", $user["ContactCity"]);
				$F->ddlStates($user["ContactState"], "ContactState", "Contact State", "float:left;");
				$F->tbNotVal("ContactZipCode", "Contact Zip", "", "float: left", $user["ContactZipCode"]);

				$F->tbNotVal("ContactPhone", "Contact Phone", "", "clear: both; float: left", $user["ContactPhone"]);
				$F->tbNotVal("ContactCell", "Contact Cell", "", "float: left", $user["ContactCell"]);

				$firephp->log($Action);
				?>

				  <input type="hidden" name="Action" value="<?= $Action ?>"></input>
				  <input type="hidden" name="user_id" value="<?= $user_id ?>"></input>
				
					<div style="clear: both"></div>
				<? $F->submitButton("Submit"); ?>
			   </ul>


		</form>
		</div>
	</div>

	<SCRIPT type="text/javascript">
		$("#tbPhone, #tbCell, #tbContactPhone, #tbContactCell").mask("(999) 999-9999 ?~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~",{placeholder:""});
		$('#tbBirthDate').mask("99/99/9999",{placeholder:""});
		$('#tbSocial').mask("999-99-9999",{placeholder:""});

	</SCRIPT>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>