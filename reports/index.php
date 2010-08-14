<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
$F = new FormElements();
$db = new conn();

$firephp->log($_REQUEST);
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
		if ($_REQUEST["generalSalesStartDate"] && $_REQUEST["generalSalesEndDate"])
		{
			$db->connect();
			$startDate = $db->Sanitize($_REQUEST["generalSalesStartDate"]);
			$endDate = $db->Sanitize($_REQUEST["generalSalesEndDate"]);
			$db->close();
			header("Location: NexusReport_GeneralSales.php?startDate=".$startDate."&endDate=".$endDate);
		}
	}

	// CommissionSummary Report
	if ($_REQUEST["report"] == "payroll")
	{
			$firephp->log("comm");
		if ($_REQUEST["payrollStartDate"] && $_REQUEST["payrollEndDate"] && $_REQUEST["Staff"])
		{
			$firephp->log("redirecting");
			$db->connect();
			$startDate = $db->Sanitize($_REQUEST["payrollStartDate"]);
			$endDate = $db->Sanitize($_REQUEST["payrollEndDate"]);
			$user_id = $db->Sanitize($_REQUEST["Staff"]);
			$db->close();
			header("Location: NexusReport_Payroll.php?startDate=".$startDate."&endDate=".$endDate."&user_id=".$user_id);
		}
	}

	// CommissionSummary Report
	if ($_REQUEST["report"] == "commissionSummary")
	{
			$firephp->log("comm");
		if ($_REQUEST["commissionSummaryStartDate"] && $_REQUEST["commissionSummaryEndDate"])
		{
			$firephp->log("redirecting");
			$db->connect();
			$startDate = $db->Sanitize($_REQUEST["commissionSummaryStartDate"]);
			$endDate = $db->Sanitize($_REQUEST["commissionSummaryEndDate"]);
			$db->close();
			header("Location: NexusReport_CommissionSummary.php?startDate=".$startDate."&endDate=".$endDate);
		}
	}

	// Direct Sales Report
	if ($_REQUEST["report"] == "DirectSales")
	{
		if ($_REQUEST["directSalesStartDate"] && $_REQUEST["directSalesEndDate"])
		{
			$db->connect();
			$startDate = $db->Sanitize($_REQUEST["directSalesStartDate"]);
			$endDate = $db->Sanitize($_REQUEST["directSalesEndDate"]);
			$db->close();
			header("Location: NexusReport_DirectSales.php?startDate=".$startDate."&endDate=".$endDate);
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
		<div class="navBullet" id="directSalesBullet"><a href="#" class="pagetab" id="directSalesLink">Direct Sales</a></div>
		<div class="navBullet" id="commissionSummaryBullet"><a href="#" class="pagetab" id="commissionSummaryLink">Comm. Summary</a></div>
		<div class="navBullet" id="payrollBullet"><a href="#" class="pagetab" id="payrollLink">Payroll</a></div>
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

		<div class="formDiv" id="directSalesDiv" style="display: none; background-color: #EDECDC">
			<h1>Consumer Installation Report - Direct Sales Report</h1>
			<form autocomplete="off" name="directSalesForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
			<ul class="form">
				<? $F->tbNotVal("directSalesStartDate", "Start Date", "datepicker"); ?>
				<? $F->tbNotVal("directSalesEndDate", "End Date", "datepicker"); ?>
				<? $F->SubmitButton("Open Report"); ?>
			</ul>
			<input type="hidden" name="report" value="DirectSales">
			</form>
		</div>

		<div class="formDiv" id="commissionSummaryDiv" style="display: none; background-color: #EDECDC">
			<h1>General Commission Summary</h1>
			<form autocomplete="off" name="commissionSummaryForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
			<ul class="form">
				<? $F->tbNotVal("commissionSummaryStartDate", "Start Date", "datepicker"); ?>
				<? $F->tbNotVal("commissionSummaryEndDate", "End Date", "datepicker"); ?>
				<? $F->SubmitButton("Open Report"); ?>
			</ul>
			<input type="hidden" name="report" value="commissionSummary">
			</form>
		</div>

		<div class="formDiv" id="payrollDiv" style="display: none; background-color: #EDECDC">
			<h1>Payroll Report</h1>
			<form autocomplete="off" name="payrollForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
			<ul class="form">
				<? $F->tbNotVal("payrollStartDate", "Start Date", "datepicker"); ?>
				<? $F->tbNotVal("payrollEndDate", "End Date", "datepicker"); ?>
				<? $F->ddlStaff($db, "payrollStaff") ?>
				<? $F->SubmitButton("Open Report"); ?>
			</ul>
			<input type="hidden" name="report" value="payroll">
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

$('#reportInventoryLink, #individualSaleLink, #generalSalesLink, #directSalesLink, #commissionSummaryLink, #payrollLink').click( function() {
	$('.navBullet').removeClass("navBulletSelected");
	$(this).parent('.navBullet').addClass("navBulletSelected");
	var formDivName = $(this).attr("id").replace("Link", "Div");

	$('.formDiv').hide();
	$('#' + formDivName).show();
	return false;
});


</SCRIPT>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>