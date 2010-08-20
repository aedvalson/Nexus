<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";


// Get Parameters

	$today = date("Y/m/01", strtotime("-1 month", strtotime(date("m/d/Y"))));
 
	//$startDate	= date("Y-m-01", strtotime("today", strtotime(date("Y-m-d"))));
	$startDate	= date("m/01/Y", strtotime("-1 months", strtotime(date("m/d/Y"))));
	$endDate	= date("m/01/Y", strtotime("+1 month", strtotime("today")));
	$DB = new conn();
	$DB->connect();
	if (isset($_REQUEST["startDate"]))
	{
		$startDate = $DB->sanitize($_REQUEST["startDate"]);
	}

	if (isset($_REQUEST["endDate"]))
	{
		$endDate = $DB->sanitize($_REQUEST["endDate"]);
	}

	if (isset($_REQUEST["productType"]))
	{
		$prodType = $DB->sanitize($_REQUEST["productType"]);
		if ($prodType == "Kirbys") 
		{
			$ProductHeader = "(Kirbys)";
			$sqlProductType = "Product";
		}
		else if ($prodType == "Accessories") 
		{
			$sqlProductType = "Accessory"; 
			$ProductHeader = "(Accessories)";
		}
		else
		{
			$sqlProductType == "%";
			$ProductHeader = "(All)";
		}

	}

// End Parameters


// Construct Query
	$sql = "SELECT inventory.status as invStatus, inventory.status_data as invStatusData, inventory.*, inventory_status.*, products.* from inventory ";
	$sql .= "JOIN products on inventory.product_id = products.product_id ";
	$sql .= "JOIN inventory_status on inventory.status = inventory_status.status_id ";
	$sql .= "WHERE DateAdded >= STR_TO_DATE('" . $startDate . "', '%m/%d/%Y') ";
	$sql .= "AND DateAdded < STR_TO_DATE('" . $endDate . "', '%m/%d/%Y') ";
	$sql .= "AND products.product_type LIKE '%" . $sqlProductType . "%' ";
	$sql .= "ORDER BY DateAdded, inventory_id ";

	$result = mysql_query($sql);
	if ($result)
	{
		while ($row = mysql_fetch_assoc($result))
		{
			$InventoryArray[] = $row;
		}
	}
	$DB->close();


function getLocation ($status, $data)
{
	$DB = new conn();
	$DB->connect();
	$location = "";
	
	if ($status == 1)
	{
		// Get Office Location
		$sql = "select storagelocation_name from storagelocations where storagelocation_id = " . $data;
		$location = $DB->query_scalar($sql) . "<br /><br />";
	}

	if ($status == 2)
	{
		// Get Employee Address
		$sql = "select Username from users where user_id = " . $data;
		$location = $DB->query_scalar($sql) . "<br /><br />";
	}

	if ($status == 3)
	{
		$location = "Transferred<br/>";
	}

	if ($status == 4 || $status == 5)
	{
		// Get customer address
		$sql = "select contacts.* from orders join contacts on orders.contact_id = contacts.contact_id where orders.order_id = " . $data;
		$result = $DB->query($sql);
		if ($result)
		{
			$row = mysql_fetch_assoc($result);
			$location = $row["contact_address"] . "<br />" . $row["contact_city"] . " " . $row["contact_state"] . ", " . $row["contact_zipcode"];
		}
	}
	return $location;
}




?>
<a href="#" onclick="pdfReport($('.reportContainer')); return false;">View PDF</a> | <a href="#" onclick="pdfReport($('.reportContainer'), 1); return false;">Save PDF</a>
<div class="reportContainer">
	<div class="reportHeader">
		<h1>Inventory Report <?= $ProductHeader ?></h1>
		<h2><?= date("m/d/Y", strtotime($startDate)) ?> - <?= date("m/d/Y", strtotime($endDate)) ?></h2>
		<h2><?= $AgencyParams["AgencyName"] ?></h2>
	</div>

	<TABLE style="page-break-inside: avoid" class="report" BORDER="1" CELLSPACING="1">
		<THEAD>
			<TR>
				<TD style="width: 12%">Serial #</TD>
				<TD style="width: 22%">Type</TD>
				<TD style="width: 22%">Date Received</TD>
				<TD style="width: 16%">Status</TD>
				<TD style="width: 28%">Location</TD>

			</TR>
		</THEAD>
		<TBODY>
			<? 
				$row = "even";
			if ($InventoryArray)
			{
				$i = 2;
				foreach ($InventoryArray as $InventoryRow)
				{
					$i++;
					if ($rowClass == "even") $rowClass = "odd";
					else $rowClass = "even";

					if ($i > 23)
					{
						echo "</TABLE><TABLE style=\"page-break-inside: avoid\" class=\"report\" BORDER=\"1\" CELLSPACING=\"1\">";
						$i = 0;
					}

			?> 
			<TR style="page-break-before: auto;" class="<?= $rowClass ?>">
				<TD style="width: 12%" class="shaded"><?= $InventoryRow["serial"] ?></TD>
				<td style="width: 22%"><?= $InventoryRow["product_name"] ?></td>
				<td style="width: 22%"><?= date("m/d/Y", strtotime($InventoryRow["DateAdded"])) ?></td>
				<td style="width: 16%"><?= $InventoryRow["status_name"] ?></td>
				<td style="width: 28%; white-space:nowrap;"><?= getLocation($InventoryRow["invStatus"], $InventoryRow["invStatusData"]) ?></td>
			</TR> 
			<? }
			}
			else
			{ ?>
				<TR class="even">
					<td colspan="5">No Matching Inventory Found</td>
				</TR>
			<? }?>
		</TBODY>

	</TABLE>
</div>



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>
