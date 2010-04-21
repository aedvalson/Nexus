<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
$F = new FormElements();
$db = new conn();


if ($_REQUEST["report"] )
{
	if ($_REQUEST["report"] == "inventory")
	{
		if ($_REQUEST["tbStartDate"] && $_REQUEST["tbEndDate"])
		{
			$db->connect();
			
			$startDate = $db->Sanitize($_REQUEST["tbStartDate"]);
			$endDate = $db->Sanitize($_REQUEST["tbEndDate"]);
			$productType = $db->Sanitize($_REQUEST["ProductType"]);
			$db->close();
			header("Location: NexusReport_Inventory.php?startDate=".$startDate."&endDate=".$endDate."&productType=".$productType);
		}
	}
}


?>



<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Reports</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" class="pagetab" id="reportInventoryLink">Inventory Report</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">
	<div class="contentDiv">

		<div class="formDiv" style="display: block; background-color: #EDECDC">
			<h1>Inventory Report</h1>
			<form autocomplete="off" name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
			<ul class="form">
				 <li class="validated" id="tbStartDate_li">
							  <label for="r_tbStartDate">Start Date:</label>
							  <div id="tbStartDate_img"></div>
							  <input autocomplete="off" class="datepicker validated" name="tbStartDate" id="tbStartDate" type="text" maxlength="20" value=""  />
							  <input type="hidden" id="tbStartDate_val" value="waiting">
							  <div id="tbStartDate_msg"></div>
					  </li>
				 <li class="validated" id="tbEndDate_li">
							  <label for="r_tbEndDate">End Date:</label>
							  <div id="tbEndDate_img"></div>
							  <input autocomplete="off" class="datepicker validated" name="tbEndDate" id="tbEndDate" type="text" maxlength="20" value=""  />
							  <input type="hidden" id="tbEndDate_val" value="waiting">
							  <div id="tbEndDate_msg"></div>
					  </li>

				 <li class="validated" id="ddlProductType_li">
							  <label for="r_ddlProductType">Product Type:</label>
							  <div id="ddlProductType_img"></div>
								<select class="validated" name="ProductType" id="ddlProductType" >
									<option value="All">Select Product Type</option>
									<option value="Kirbys">Kirbys</option>
									<option value="Accessories">Accessories</option>
								</select>	
								<input type="hidden" id="ddlProductType_val" value="waiting">
							  <div id="ddlProductType_msg"></div>
					  </li>

				<? $F->SubmitButton("Open Report"); ?>
			</ul>
			<input type="hidden" name="report" value="inventory">
			</form>
		</div>

	</div>
</div>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>