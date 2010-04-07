<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>

<?

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
				
				$sql = "INSERT INTO StorageLocations (storagelocation_name) VALUES ('".$StorageLocationName."')";
				$DB->execute_nonquery($sql);
				header("Location: ManageStorage.php");
			}
		}
	}


$DB->close();

?>



<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

   <ul class="form">
	 <li class="validated" id="tbLocationName_li">
                  <label for="r_tbLocationName">Location Name:</label>
                  <div id="tbLocationName_img"></div>
					<input class="validated" name="LocationName" id="tbLocationName" type="text" maxlength="20" value=""  />		
					<input type="hidden" id="tbLocationName_val" value="waiting">
                  <div id="tbLocationName_msg"></div>
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



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>