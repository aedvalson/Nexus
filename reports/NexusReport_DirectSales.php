<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";

$DB = new conn();
$DB->connect();

// Get Parameters
if (!$_REQUEST["startDate"] || !$_REQUEST["endDate"]) { header("Location: /$ROOTPATH/reports"); };

$params["endDate"]		=  date("Y/m/d", strtotime ( $DB->sanitize($_REQUEST["endDate"])));
$params["startDate"]	=  date("Y/m/d", strtotime ( $DB->sanitize($_REQUEST["startDate"])));
// End Parameters


// Get all Reports in range
$sql = "SELECT * from orders join contacts on orders.contact_id = contacts.contact_id where order_status_id = 5 AND DateCompleted >= '" . $params["startDate"] . "' AND DateCompleted <=  '" . $params["endDate"] . "' ORDER BY DateCompleted";
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

// Build Product Sales Array from orders
$AllProducts = array();
foreach ($orders as $order)
{
	if ($order["order_status_id"] == 5)
	{
		$DealerName="unknown";
		$dealersArray = json_decode($order["dealerArray"], true);
		$roles = $dealersArray["roles"];
		foreach ($roles as $role)
		{
			if ($role["roleText"] == "Dealer")
			{
				$DealerName = $role["displayName"];
			}
		}

		$productsArray = json_decode($order["ProductsArray"], true);
		$products = $productsArray["products"];
		if ($products)
		{
			foreach ($products as $product)
			{
				$serial = $product["Serial"];
				$AllProducts[$serial]["Serial"] =			$serial;
				$AllProducts[$serial]["DateComplated"] =	$order["DateCompleted"];
				$AllProducts[$serial]["CustomerName"] =		$order["contact_DisplayName"];
				$AllProducts[$serial]["Address"] =			$order["contact_address"];
				$AllProducts[$serial]["City"] =				$order["contact_city"];
				$AllProducts[$serial]["State"] =			$order["contact_state"];
				$AllProducts[$serial]["Zip"] =				$order["contact_zipcode"];
				$AllProducts[$serial]["Phone"] =			$order["contact_phone"];
				$AllProducts[$serial]["DealerName"] =		$DealerName;
			}
		}
	}
}
?>







<a href="#" onclick="pdfReport($('.reportContainer'), 0, 1, 'Landscape'); return false;">View PDF</a> | <a href="#" onclick="pdfReport($('.reportContainer'), 1, 1, 'Landscape'); return false;">Save PDF</a>

<div class="reportContainer" style="width: 80em">
	<div class="reportHeader" style="padding: 0.1em 0 1em 0;">
		<center>
			<h1 style="text-transform: capitalize">Consumer Installation Report - Direct Sales</h1>
			<h2 style="font-size: 90%; margin-top: 1em; margin-bottom: 1em; font-weight: normal">Distributor Account <b>#<?= $AgencyParams["DistributorNumber"] ?></b>&nbsp;&nbsp; For:  <b><?= date("m/d/Y", strtotime ( $params["startDate"] )) ?> to <?= date("m/d/Y", strtotime ( $params["endDate"] )) ?></b>&nbsp;&nbsp; Total Firm Direct Sales: <b><?= count($AllProducts) ?></h2>
		</center>
	</div>

	<table class="DirectSales" style="width: 100%; page-break-after: always;" CELLPADDING="0" CELLSPACING="0">
		<thead>
			<tr class="DirectSalesHeader">
				<td colspan="2">Serial<br />Number</td>
				<td>Date<br />Delivered</td>
				<td>Name of<br />Purchaser</td>
				<td>Street Address,<br />City, State and Zip code</td>
				<td>Phone<br />Number</td>
				<td>Dealer<br />Name</td>
			</tr>
		</thead>
		<tbody>
			
			<? 
			$pageRowCount = 2;
			$i = 0;

			foreach ($AllProducts as $product)
			{
				$i++;
				$pageRowCount++;
				if ($pageRowCount == 17)
				{
					?>
		</tbody>
	</table>
	<table class="DirectSales" style="width: 100%" CELLPADDING="0" CELLSPACING="0">
		<thead>
			<tr class="DirectSalesHeader">
				<td colspan="2">Serial<br />Number</td>
				<td>Date<br />Delivered</td>
				<td>Name of<br />Purchaser</td>
				<td>Street Address,<br />City, State and Zip code</td>
				<td>Phone<br />Number</td>
				<td>Dealer<br />Name</td>
			</tr>
		</thead>
		<tbody>

					<?
				$pageRowCount = 0;
				}
				
				?>
			<tr class="DirectSalesBody">
				<td rowspan="2" style="width: 2em"><?= $i ?></td>
				<td rowspan="2" style="width: 9em"><?= $product["Serial"] ?></td>
				<td rowspan="2" style="width: 6em"><?= date("m/d/Y", strtotime ( $product["DateComplated"] )) ?></td>
				<td rowspan="2" style="width: 15em"><?= $product["CustomerName"] ?></td>
				<td style="width: 26em" ><?= $product["Address"] ?></td>
				<td rowspan="2" style="width: 12em"><?= format_phone($product["Phone"]) ?></td>
				<td rowspan="2" style="width: 15em"><?= $product["DealerName"] ?></td>
			</tr>
			<tr class="DirectSalesBody">
				<td><?= $product["City"] . " " . $product["State"] . ", " . $product["Zip"] ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>

	<table BORDER="0" CELLPADDING="0" CELLSPACING="0" style="page-break-inside: avoid">
	<tr>
	<td>
	<div class="reportHeader" style="padding:0;">
	<p style="margin-top: 1.5em; margin-bottom: 1.5em; font-size: 90%;">I, the undersigned, hereby certify: 1) that firm direct sales by my organization have been accurately reported; 2) that a warranty card, signed by each consumer end user will be forwarded to Kirby World Headquarters; 3) that each consumer end-user has received proper instruction and demonstration with respect to the operation and maintenance of the product; and 4) that all firm sales made during the time period have been reported on this report.</p>

	<center><h2 style="font-size: 90%; font-weight: normal">Factory Distributor's Signature ____________________________ Distributor Trainee's Signature ____________________________ DT Acct # __________</h2></center>
	</div>
	</td>
	</tr>
	</table>

</div>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>