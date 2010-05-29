<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
$F = new FormElements();
$db = new conn();


if ($_REQUEST["report"] )
{
	// Inventory Report
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

			$firephp->log($_REQUEST["report"]);
			$firephp->log($_REQUEST["generalSalesStartDate"]);
			$firephp->log($_REQUEST["generalSalesEndDate"]);

	// Individual Sale
	if ($_REQUEST["report"] == "IndividualOrder")
	{
		if ($_REQUEST["OrderID"])
		{
			$db->connect();
			$order_id = $DB->sanitize($_REQUEST["OrderID"]);
			$db->close();
			header("Location: NexusReport_IndividualOrder.php?OrderID=".$order_id);
		}
	}




	// General Sales Report
	if ($_REQUEST["report"] == "GeneralSales")
	{
			$firephp->log($_REQUEST["report"]);
			$firephp->log($_REQUEST["generalSalesStartDate"]);
			$firephp->log($_REQUEST["generalSalesEndDate"]);
		if ($_REQUEST["generalSalesStartDate"] && $_REQUEST["generalSalesEndDate"])
		{
			$firephp->log($_REQUEST["report"]);
			$firephp->log($_REQUEST["generalSalesStartDate"]);
			$firephp->log($_REQUEST["generalSalesEndDate"]);
			$firephp->log($_REQUEST["report"]);
			$db->connect();
			$startDate = $db->Sanitize($_REQUEST["generalSalesStartDate"]);
			$endDate = $db->Sanitize($_REQUEST["generalSalesEndDate"]);
			$db->close();
			header("Location: NexusReport_GeneralSales.php?startDate=".$startDate."&endDate=".$endDate."&productType=".$productType);
		}
	}
}


?>



<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Reports</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" class="pagetab" id="reportInventoryLink">Inventory Report</a></div>
		<div class="navBullet" id="generalSalesBullet"><a href="#" class="pagetab" id="generalSalesLink">General Sales</a></div>
		<div class="navBullet" id="individualSaleBullet"><a href="#" class="pagetab" id="individualSaleLink">Individual Sale</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">
	<div class="contentDiv">

		<div class="formDiv" id="reportInventoryDiv" style="display: block; background-color: #EDECDC">
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


		<div class="formDiv" id="generalSalesDiv" style="display: none; background-color: #EDECDC">
			<h1>General Sales Report</h1>
			<form autocomplete="off" name="generalSalesForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
			<ul class="form">
				<? $F->tbNotVal("generalSalesStartDate", "Start Date", "datepicker"); ?>
				<? $F->tbNotVal("generalSalesEndDate", "End Date", "datepicker"); ?>
				<? $F->SubmitButton("Open Report"); ?>
			</ul>
			<input type="hidden" name="report" value="GeneralSales">
			</form>
		</div>


		<div class="formDiv" id="individualSaleDiv" style="display: none; background-color: #EDECDC">
			<h1>Individual Sale Report</h1>
			<form autocomplete="off" name="individualSaleForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
			<ul class="form">
				<? $F->tbNotVal("OrderID", "Order ID"); ?>
				<? $F->SubmitButton("Open Report"); ?>
			</ul>
			<input type="hidden" name="report" value="IndividualOrder">
			</form>
		</div>

	</div>
</div>

<SCRIPT>

$('#reportInventoryLink, #individualSaleLink, #generalSalesLink').click( function() {
	$('.navBullet').removeClass("navBulletSelected");
	$(this).parent('.navBullet').addClass("navBulletSelected");
	var formDivName = $(this).attr("id").replace("Link", "Div");

	$('.formDiv').hide();
	$('#' + formDivName).show();
	return false;
});


</SCRIPT>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>