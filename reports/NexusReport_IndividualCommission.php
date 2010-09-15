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
$json_restrictor = '"user":"' . $params["user_id"] . '"';

$sql = "SELECT * from orders join contacts on orders.contact_id = contacts.contact_id  where order_status_id = 5 AND DateCompleted >= '" . $params["startDate"] . "' AND DateCompleted <=  '" . $params["endDate"] . "' AND CommStructure LIKE '%" . $json_restrictor. "%' ORDER BY order_id";

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
$users		= getUserHash($DB);
$user		= $users[ $params["user_id"] ];
$orderHash = buildOrdersByUsersHash($DB, $users, $orders);

#$firephp->log($orderHash);


?>

<a href="#" onclick="pdfReport($('.reportContainer')); return false;">PDF</a>

<div class="reportContainer">
	<div class="reportHeader">
		<h1>Individual Commission Report</h1>
		<h2><?= date("m/d/Y", strtotime ( $params["startDate"] )) ?> to <?= date("m/d/Y", strtotime ( $params["endDate"] )) ?></h2>
		<h2><?= $AgencyParams["AgencyName"] ?></h2>
	</div>
	<br />

	<h3>Individual Commission Report for <?= $user["FirstName"] ?> <?= $user["LastName"] ?></h3><br/>

	<? 
	foreach ($orderHash[$params["user_id"]]["commissions"] as $order)
	{
#		$firephp->log($order);
		$CashLabel = ($order["financeTotal"] > 0) ? "Down Payment" : "Cash";
		?>


		<table class="report noborders" border="0" style="page-break-inside: avoid; font-size: 150%">
			<tr style="visibility: collapse">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

			<tr style="border-bottom: 1px solid black; ">
				<td colspan="1" style="font-weight: bold; width: 8em;">Sale <a href="<?= $FQDN ?>/<?= $ROOTPATH ?>/Sales/NewSale.php?order_id=<?= $order["details"]["order_id"] ?>">#<?= $order["details"]["order_id"] ?></a></td>
				<td colspan="2" style="font-weight: bold"><?= $order["details"]["contact_DisplayName"] ?></td>
				<td colspan="2" style="font-weight: bold; text-align: right;">Date Sold: <?= preg_replace("/\s+\S+\s\S+$/", "", prettyDate($order["details"]["DateCompleted"])) ?></td>
				<td colspan="3" style="font-weight: bold; text-align: right; whitespace: normal">Payment Types: 
				<?
					$paymentDisplay = array();
					foreach ($order["PaymentMethods"] as $method)
					{
						$output = ucwords($method["paymentType"]);
						if ($method["paymentType"] == "finance")
						{
							$output = $output . " (";
							$companies = array();
#							$firephp->log($method["paymentType"]);
#							$firephp->log($method);
#							$firephp->log($method["companies"]);
							foreach ($method["companies"] as $company)
							{
								$companies[] = $company["company"] . " - " . $company["option"];
							}
							$output = $output . implode(", ", $companies) . ")";
						}
						$paymentDisplay[] = $output;
					}
					echo implode(", ", $paymentDisplay);
				?>
				</td>
			</tr>
			<tr style="border-bottom: 1px solid black">
				<td style="font-weight: bold" >Address:</td>
				<td style="font-weight: bold" colspan="2"><?= $order["details"]["contact_address"] ?> </td>
				<td style="font-weight: bold; text-align: right;" colspan="2"><?= $order["details"]["contact_city"] . ", " . $order["details"]["contact_state"] . " " . $order["details"]["contact_zipcode"]?></td>
				<td></td>
				<td style="font-weight: bold" colspan="2"></td>
			</tr>
			<tr>
				<td colspan="4"></td>
				<td colspan="2" style="text-align: center"><u>Payment Breakdown</u></td>
				<td colspan="2" style="text-align: center"><u>Commission Breakdown</u></td>
			</tr>
			<tr>
				<td colspan="2"><u>Net Sales Price:</u></td>
				<td class="money">$<?= money_format("%i", $order["details"]["amount"]) ?></td>
				<td></td>
				<td><?= $CashLabel ?></td>
				<td class="money">$<?= money_format("%i", $order["cashTotal"]) ?></td>
				<td>Corporate</td>
				<td class="money">$<?= money_format("%i", $order["corp_commission"]) ?></td>
			</tr>
			<tr>
				<td colspan="2"><u>Tax Total:</u></td>
				<td class="money">$<?= money_format("%i", $order["details"]["tax"]) ?></td>
				<td></td>
				<td>Check</td>
				<td class="money">$<?= money_format("%i", $order["checkTotal"]) ?></td>
				<td>Other Personnel</td>
				<td class="money">$<?= money_format("%i", $order["othercomms"]) ?></td>
			</tr>
			<? $running_total = $order["details"]["amount"] - $order["details"]["tax"] ?>
			<tr>
				<td colspan="2"><u>Less Tax:</u></td>
				<td class="money">$<?= money_format("%i", $running_total ) ?></td>
				<td></td>
				<td>Finance Amount</td>
				<td class="money">$<?= money_format("%i", $order["financeTotal"]) ?></td>
				<td>Representative Commission</td>
				<td class="money">$<?= money_format("%i", $order["commission"]) ?></td>
			</tr>
			<? $running_total -= $order["reserveTotal"] ?>
			<tr>
				<td colspan="2"><u>Less Reserve:</u></td>
				<td class="money">$<?= money_format("%i", $running_total ) ?></td>
				<td></td>
				<td style="text-align: right"> - Reserve</td>
				<td class="money">$<?= money_format("%i", $order["reserveTotal"] ) ?></td>
				<td style="text-align: right">Less Deductions</td>
				<td class="money">$<?= money_format("%i", $order["deductionTotal"]) ?></td>
			</tr>
			<? $running_total -= $order["corp_commission"] ?>
			<tr>
				<td colspan="2"><u>Less Corporate:</u></td>
				<td class="money">$<?= money_format("%i", $running_total ) ?></td>
				<td colspan="3"></td>

				<td>Bonus Paid</td>
				<td class="money">$<?= money_format("%i", $order["bonusTotal"] ) ?></td>
			</tr>
			<? $running_total -= $order["othercomms"] ?>
			<tr style="border-bottom: 1px solid black">
				<td colspan="2"><u>Less Other Personnel:</u></td>
				<td class="money">$<?= money_format("%i", $running_total ) ?></td>
				<td></td>
				<td colspan="4">
			</tr>

			<tr style="border-bottom: 1px solid black">
				<td colspan="8"><b>Total Representative Commission from Sale: $<?= money_format("%i", $order["commission"] + $order["adjustment"] ) ?></b></td>
			</tr>
			<tr>
				<td colspan="8" style="padding: 0; margin: 0;"><hr style="padding: 0; margin:0" /></td>
			</tr>

		</table>
		
		<br/><br/><br/>

		<?
	}
	?>

</div>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>