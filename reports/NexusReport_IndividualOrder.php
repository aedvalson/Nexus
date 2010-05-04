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
$sql .= " where order_id = ".$order_id;

$result = mysql_query($sql);
$row = "";
if ($result)
{
	$row = mysql_fetch_assoc($result); // Just 1
}
$DB->close();

$roles			= json_decode($row["dealerArray"], true);
$products		= json_decode($row["ProductsArray"], true);
$products		= $products["products"];
$accessories	= json_decode($row["AccessoriesArray"], true);
$firephp->log($accessories);
$accessories	= $accessories["products"];
$firephp->log($accessories);


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

$firephp->log($saleText);
$firephp->log($sql);
$firephp->log($row);

?>

<div class="reportContainer">
	<div class="reportHeader">
		<h1>Individual Sale Report</h1>
		<h2><?= $saleText ?></h2>
		<h2>American Eagle Corp.</h2>
	</div>
	<center>
	<TABLE style="width: 90%" class="report" BORDER="1" CELLSPACING="1">
	<? foreach ($roles["roles"] as $i)
	{  $firephp->log($i); ?>
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
	</TABLE>
	</center>

</div>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>