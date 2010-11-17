<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
?>
<?
if (!UserMay("Admin_EditStorage")) { AccessDenied(); }
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

				$StorageLocationName = $DB->sanitize($_REQUEST["LocationName"]);
				$description = $DB->sanitize($_REQUEST["Description"]);
				
				$sql = "INSERT INTO storagelocations (storagelocation_name, description ) VALUES ('".$StorageLocationName."', '".$description."')";
				$DB->execute_nonquery($sql);

				$DB->addHistory( 'storagelocations', $_SESSION["user_id"],  "insert", "" );

				header("Location: ManageStorage.php");
			}
		}
	}


$DB->close();

?>
<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Locations</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" id="custBulletLink">Add New Location</a></div>
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
			<h1>Location Details</h1>


				<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

				   <ul class="form">
					 <li class="validated" id="tbLocationName_li">
								  <label for="r_tbLocationName">Location Name:</label>
								  <div id="tbLocationName_img"></div>
									<input class="validated" name="LocationName" id="tbLocationName" type="text" maxlength="20" value=""  />		
									<input type="hidden" id="tbLocationName_val" value="waiting">
								  <div id="tbLocationName_msg"></div>
						  </li>   
					 <li class="validated" id="tbDescription_li">
								  <label for="r_tbDescription">Description:</label>
								  <div id="tbDescription_img"></div>
									<input class="validated" name="Description" id="tbDescription" type="text" maxlength="50" value=""  />		
									<input type="hidden" id="tbDescription_val" value="waiting">
								  <div id="tbDescription_msg"></div>
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


<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>