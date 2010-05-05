<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";

$DB = new conn();
$DB->connect();

// Get Parameters
$order_id = $DB->sanitize($_REQUEST["OrderID"]);
// End Parameters


// Construct Query
$sql = "SELECT * from orders";
$sql .= " join contacts on orders.contact_id = contacts.contact_id";
$sql .= " join order_status on orders.order_status_id = order_status.order_status_id";
$sql .= " where order_id = ".$order_id;

$result = mysql_query($sql);
$row = "";
if ($result)
{
	$row = mysql_fetch_assoc($result); // Just 1
}
$firephp->log($row);
$roles			= json_decode($row["dealerArray"], true);
$products		= json_decode($row["ProductsArray"], true);
$products		= $products["products"];
$accessories	= json_decode($row["AccessoriesArray"], true);
$accessories	= $accessories["products"];
$commissions	= json_decode($row["CommStructure"], true);
$commissions	= $commissions["elements"];
$payments		= json_decode($row["PaymentArray"], true);
$payments		= $payments["paymentMethods"];



// Get Users
$sql = "SELECT * from users";
$result = $DB->query($sql);
$allUsers = array();
if ($result)
{
	while ($userRow = mysql_fetch_assoc($result))
	{
		$allUsers[$userRow["user_id"]] = $userRow;  // Creating dictionary of users
	}
}



$DB->close();




$saleText = "Sale to";
foreach ($roles["roles"] as $i)
{
	if ($i["roleText"] == "Dealer")
	{
		if ($i["displayName"])
		{
			$saleText = $i["displayName"];
		}
		else
		{
			$saleText = $i["userText"];
		}
		$saleText = $saleText . " sold to";
	}
}

$saleText .= " " . $row["contact_DisplayName"];


if ($row["DateCompleted"] && $row["order_status_id"] == 5)  // Order is Completed and has a completed date
{
	$saleText .= " on " . date("m/d/Y", strtotime ( $row["DateCompleted"] ));
}

//$firephp->log($saleText);
//$firephp->log($sql);
//$firephp->log($row);

?>
<a href="#" onclick="pdfReport($('.reportContainer')); return false;">PDF</a>

<div class="reportContainer">
	<div class="reportHeader">
		<h1>Individual Sale Report</h1>
		<h2><?= $saleText ?></h2>
		<h2>American Eagle Corp.</h2>
	</div>
	<center>
	<TABLE style="width: 90%" class="report" BORDER="1" CELLSPACING="1">
	<? foreach ($roles["roles"] as $i)
	{ // $firephp->log($i); ?>
		<tr>
			<td class="shaded" style="width: 25%"><?= $i["roleText"] ?></td>
			<td><?= $i["displayName"] ?></td>
		</tr>
		<? } ?>

		<tr>
			<td class="shaded">Customer</td>
			<td>
				<?= $row["contact_DisplayName"] ?><br />
				<?= $row["contact_address"]		?><br />
				<?= $row["contact_city"] . " " . $row["contact_state"] . ", " . $row["contact_zipcode"] ?><br/>
			</td>
		</tr>

		<tr>
			<td class="shaded">Equipment Sold</td>
			<td>
				<? 
				if ($products)
				{
					foreach ($products as $product) 
					{
						echo $product["Name"] . "  #" . $product["Serial"] . "<br />";
					} 
				}

				if ($accessories)
				{
					if ($products) { echo "<br />"; }
					foreach ($accessories as $accessory)
					{
						echo $accessory["quantity"] . " " . $accessory["Name"] . "<br />";
					}
				}

				?>
			</td>
		</tr>
		<tr>
			<td class="shaded">Payment Info</td>
			<td>
				Total Sale Price: $<?= $row["amount"] ?><br /><br />

				<? 
				$reserveTotal = 0;

				// Cash First
				foreach ($payments as $payment)
				{
					//$firephp->log($payment);
					if ($payment["paymentType"] == "cash")
					{
						?>Cash Downpayment: $<?= money_format("%i", $payment["amount"]) ?><br/><?
					}
				} 

				foreach ($payments as $payment)
				{
//					$firephp->log($payment); // Financing Second
					if ($payment["paymentType"] == "finance")
					{
						?>
						Financed: $<?= money_format("%i", $payment["amount"]) ?><br/><br />
						Financier: <?= $payment["financeCompany"] ?><br />
						Loan Type: <?= $payment["loanOption"] ?><br />
						Reserve: <?= $payment["reserveRate"] ?> ($<?= money_format("%i", $payment["amount"] / $payment["reserveRate"]) ?>) <br />
						<?
						$reserveTotal = $reserveTotal + ($payment["amount"] / $payment["reserveRate"]);
					}

				} 
				
				?> 
				<br />
				Sales Tax: $<?= money_format("%i", $row["tax"]) ?><br />
				Total Profit: $<?= money_format("%i", $row["amount"] - $reserveTotal) ?><br />
			</td>
		</tr>
	</TABLE>

	<? if ($commissions) { ?>
	<TABLE style="width: 90%" class="report" BORDER="1" CELLSPACING="1">
		<tr>
			<td class="shaded" style="width: 25%">Commissions</td>
			<td>
			<?
				$remaining = $row["amount"] - $reserveTotal;
				$commAmount = 0;
				foreach ($commissions as $comm)
				{
					// Recalc comm amt
					if ($comm["paymentType"] == "flat")
					{
						$commAmount = $comm["flatAmount"];
					}
					if ($comm["paymentType"] == "remaining")
					{
						$commAmount = $remaining;
					}

					// Display Comm
					if ($comm["payeeType"] == "employee")
					{
						$dealerCount = count($comm["dealers"]);
						foreach ($comm["dealers"] as $dealer)
						{
							$myComm = $commAmount / $dealerCount;
							$user_id = $dealer["user"];
							$role = $dealer["role"];
							$displayName = $allUsers[$user_id]["FirstName"] . " " . $allUsers[$user_id]["LastName"];
							echo $displayName . " (" . $role . "): $" . money_format("%i", $myComm) . "<br />";
						}
					}
					if ($comm["payeeType"] == "corporate")
					{	
						echo "Corporate: $" . money_format("%i", $commAmount) . "<br /><br />";
					}

					$remaining -= $commAmount;
				}
			?>
			</td>
		</tr>
	</TABLE>
	<? } ?>

	<TABLE style="width: 90%" class="report" BORDER="1" CELLSPACING="1">
		<tr>
			<td class="shaded" style="width: 25%">Status</td>
			<td><?= $row["order_status_name"] ?></td>
		</tr>
		<?
		if ($row["DateCompleted"] && $row["order_status_id"] == 5)
		{ ?>
		<tr>
			<td class="shaded" style="width: 25%">Date Completed</td>
			<td>
				<?= date("m/d/Y", strtotime ( $row["DateCompleted"] )) ?>
			</td>
		</tr>
		<? } ?>
	</TABLE>
	</center>

</div>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>