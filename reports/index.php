<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
$F = new FormElements();
$db = new conn();


if ($_REQUEST["report"] )
{
	if ($_REQUEST["report"] == "inventory")
	{
		if ($_REQUEST["StartDate"] && $_REQUEST["EndDate"])
		{
			$db->connect();
			
			$startDate = $db->Sanitize($_REQUEST["StartDate"]);
			$endDate = $db->Sanitize($_REQUEST["EndDate"]);
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
				<? $F->tbNotVal("StartDate", "Start Date", "datepicker"); ?>
				<? $F->tbNotVal("EndDate", "End Date", "datepicker"); ?>
				 
				 <li class="validated" id="ddlProductType_li">
							  <label for="r_ddlProductType">Product Type:</label>
							  <div id="ddlProductType_img"></div>
								<select  name="ProductType" id="ddlProductType" >
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