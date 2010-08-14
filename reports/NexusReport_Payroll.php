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
$sql = "select * from users join teams on users.team_id = teams.team_id";
$result = $DB->query($sql);
$users = array();
while ($userRow = mysql_fetch_assoc($result))
{
	$users[$userRow["user_id"]] = $userRow;
}
$firephp->log($users);

$user_id = $params["user_id"];
// Build Order Hash by User
$orderHash = array();
foreach ($users as $user)
{
	$user_id = $user["user_id"];
	foreach ($orders as $order)
	{
		// Check for Reserves and watch for largest finance method for finance types hash
		$order["reserveAmount"] = 0;
		$financeArray = json_decode($order["PaymentArray"], true);
		if ($financeArray)
		{
			foreach ($financeArray["paymentMethods"] as $financeMethod)
			{
				$order["resereAmount"] = $order["resereAmount"] + ($financeMethod["amount"] & $financeMethod["reserveRate"] / 100);
			}	
		}

		$order["adjAmount"] = $order["amount"] - $order["reserveAmount"];


		$remaining = $order["adjAmount"];
		$commissions	= json_decode($order["CommStructure"], true);
		$commissions	= $commissions["elements"];
		foreach ($commissions as $comm)
		{
			
			// Recalc comm amt
			if ($comm["paymentType"] == "flat")
			{
				$commAmount = $comm["flatAmount"];
			}
			else if ($comm["paymentType"] == "remaining")
			{
				$commAmount = $remaining;
			}
			else if ($comm["paymentType"] == "percentage")
			{
				$commAmount = $order["adjAmount"] * $comm["percentage"] / 100;
			}
			$remaining -= $commAmount;

			if ($comm["payeeType"] == "employee")
			{
				foreach($comm["dealers"] as $dealer)
				{
					if ($dealer["user"] == $user_id)
					{
						$length = count($comm["dealers"]);
						if ($length > 1)
						{
							$adjCommAmount = $commAmount / $length;
						}
						else
						{
							$adjCommAmount = $commAmount;
						}

						$orderHash[$user_id]["commissions"][$order["order_id"]]["order_id"] = $order["order_id"];
						$orderHash[$user_id]["commissions"][$order["order_id"]]["date"]		= $order["DateCompleted"];
						$orderHash[$user_id]["commissions"][$order["order_id"]]["customer"] = $order["contact_DisplayName"];
						$orderHash[$user_id]["commissions"][$order["order_id"]]["commission"] += $adjCommAmount;
						$orderHash[$user_id]["user_id"] = $user_id;

					}
				}
			}

			if ($comm["payeeType"] == "adjustment")
			{
				$commAmount = $comm["amount"];
				foreach($comm["dealers"] as $dealer)
				{
					if ($dealer["user"] == $user_id)
					{
						$length = count($comm["dealers"]);
						if ($length > 1)
						{
							$adjCommAmount = $commAmount / $length;
						}
						else
						{
							$adjCommAmount = $commAmount;
						}
						$firephp->log("adj: " . $commAmount . " " . $adjCommAmount);
						$orderHash[$user_id]["commissions"][$order["order_id"]]["order_id"] = $order["order_id"];
						$orderHash[$user_id]["commissions"][$order["order_id"]]["date"]		= $order["DateCompleted"];
						$orderHash[$user_id]["commissions"][$order["order_id"]]["customer"] = $order["contact_DisplayName"];
						$orderHash[$user_id]["commissions"][$order["order_id"]]["adjustment"] += $adjCommAmount;
						$orderHash[$user_id]["user_id"] = $user_id;
						if ($order["order_id"] == 57)
						{
							$firephp->log("adj total: " . $orderHash[$user_id]["commissions"][$order["order_id"]]["adjustment"]);
							$firephp->log("orderHash");
							$firephp->log($orderHash);
						}
					}
				}
			}
		}
	}
}

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