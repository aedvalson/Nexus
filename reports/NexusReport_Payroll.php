<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";

$DB = new conn();
$DB->connect();

// Get Parameters
if (!$_REQUEST["startDate"] || !$_REQUEST["endDate"] || !$_REQUEST["user_id"]) { header("Location: /$ROOTPATH/reports"); };

$params["endDate"]		= date("Y/m/d", strtotime ( $DB->sanitize($_REQUEST["endDate"])));
$params["startDate"]	= date("Y/m/d", strtotime ( $DB->sanitize($_REQUEST["startDate"])));
$params["user_id"]		= $DB->sanitize($_REQUEST["user_id"]);
// End Parameters

// Get all Reports in range
$sql = "SELECT * from orders join contacts on orders.contact_id = contacts.contact_id  where order_status_id = 5 AND DateCompleted >= '" . $params["startDate"] . "' AND DateCompleted <=  '" . $params["endDate"] . "' ORDER BY order_id";
$result = $DB->query($sql);
if (!$result)
{
	$DB->close();	
	echo "No Sales Found in that Date Range";
	exit();
}

$orders = array();
while ($orderRow = mysql_fetch_assoc($result))
{
	$orders[$orderRow["order_id"]] = $orderRow;
}



// Get All Users
$users = getUserHash($DB);
$orderHash = buildOrdersByUsersHash($DB, $users, $orders);

$user_id = $params["user_id"];
// Build Order Hash by User


	$highestEarned = 0;
	foreach ($orderHash as $userHash)
	{
		$commTotal = 0;
		$bonusTotal = 0;
		$adjustmentTotal = 0;


		foreach ($userHash["commissions"] as $comm)
		{
			$commTotal += $comm["commission"];
			$adjustmentTotal += $comm["adjustment"];
		}

		$total = $commTotal + $bonusTotal + $adjustmentTotal;	
		$firephp->log("Total: " . $total . " Highest: " . $highestEarned);
		if ($total > $highestEarned)
		{
			$highestEarned = $total;
			$highestEarner = $users[$userHash["user_id"]]["FirstName"] . " " . $users[$userHash["user_id"]]["LastName"];
		}

		$dealerCount = count($orderHash);

		$gComm += $commTotal;
		$gAdjustment += $adjustmentTotal;
		$gTotal += $total;
	}
?>



<a href="#" onclick="pdfReport($('.reportContainer')); return false;">PDF</a>

<div class="reportContainer">
	<div class="reportHeader">
		<h1>Payroll Report</h1>
		<h2><?= date("m/d/Y", strtotime ( $params["startDate"] )) ?> to <?= date("m/d/Y", strtotime ( $params["endDate"] )) ?></h2>
		<h2><?= $AgencyParams["AgencyName"] ?></h2>
	</div>
	<br />

	<? 
	$i = 0;
	foreach ($orderHash as $userHash)
	{
	if ($userHash["user_id"] != $params["user_id"])
		continue;
	?>
	<h3>Payroll Report for <?= $users[$userHash["user_id"]]["FirstName"] ?> <?= $users[$userHash["user_id"]]["LastName"] ?></h3><br/>
	
	<div style="page-break-inside: avoid">
		
	<table class="report" CELLSPACING="0" style="width: 1000px; margin-bottom: 1em; page-break-inside: avoid">
		<tr>
			<td>Date</td>
			<td>Sale Id</td>
			<td>Customer</td>
			<td>Commission</td>
			<td>Bonus</td>
			<td>Deduction</td>
			<td>Total</td>
		</tr>


	<? 
	foreach ($userHash["commissions"] as $comm)
		{
		$total =  $comm["commission"] + $comm["adjustment"];
		$grandTotal += $total;

		?>
			<tr>
				<td><?=date("m/d/Y", strtotime($comm["date"])) ?></td>
				<td><?= $comm["order_id"] ?></td>
				<td><?= $comm["customer"] ?></td>
				<td>$<?= money_format("%i", $comm["commission"]) ?></td>
				<td><?= 0.00 ?></td>
				<td>$<?= money_format("%i", $comm["adjustment"]) ?></td>
				<td>$<?= money_format("%i", $total ) ?></td>
			</tr>
		<?
		}
	?>
			<tr>
				<td colspan="6" style="text-align: right">Total Commissions:</td>
				<td>$<?= money_format("%i", $grandTotal); ?></td>
			</tr>
		</table>
	</div><?
	} ?>
	

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>