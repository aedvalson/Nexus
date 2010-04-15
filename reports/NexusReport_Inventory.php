<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";


// Get Parameters

	$today = date("Y-m-01", strtotime("-1 month", strtotime(date("Y-m-d"))));
 
	//$startDate	= date("Y-m-01", strtotime("today", strtotime(date("Y-m-d"))));
	$startDate	= date("Y-m-01", strtotime("-1 months", strtotime(date("Y-m-d"))));
	$endDate	= date("Y-m-01", strtotime("+1 month", strtotime("today")));

	if (isset($_REQUEST["startDate"]))
	{
		$price = $DB->sanitize($_REQUEST["startDate"]);
	}

	if (isset($_REQUEST["endDate"]))
	{
		$price = $DB->sanitize($_REQUEST["endDate"]);
	}
// End Parameters


// Construct Query
	$sql = "SELECT * from inventory ";
	$sql .= "JOIN products on inventory.product_id = products.product_id ";
	$sql .= "JOIN inventory_status on inventory.status = inventory_status.status_id ";
	$sql .= "WHERE DateAdded >= STR_TO_DATE('" . $startDate . "', '%Y-%m-%d') ";
	$sql .= "AND DateAdded < STR_TO_DATE('" . $endDate . "', '%Y-%m-%d') ";
	$sql .= "ORDER BY DateAdded, inventory_id ";

	$DB = new conn();
	$DB->connect();

	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$InventoryArray[] = $row;
	}
	$DB->close();

?>

<div class="reportContainer">
	<h1>Inventory Report</h1>

	<TABLE class="report">
		<THEAD>
			<TR>
				<TD>Serial #</TD>
				<TD>Type</TD>
				<TD>Date Received</TD>
				<TD>Status</TD>
				<TD>Location</TD>
			</TR>
		</THEAD>
		<TBODY>
			<? 
				$row = "even";
			foreach ($InventoryArray as $InventoryRow)
			{
				if ($rowClass == "even") $rowClass = "odd";
				else $rowClass = "even";

			?> 
			<TR class="<?= $rowClass ?>">
				<TD class="shaded"><?= $InventoryRow["inventory_id"] ?></TD>
				<td><?= $InventoryRow["product_name"] ?></td>
				<td><?= date("m/d/Y", strtotime($InventoryRow["DateAdded"])) ?></td>
				<td><?= $InventoryRow["status_name"] ?></td>
				<td><?= $InventoryRow["status_data_text"] ?></td>
			</TR> 
			<? } ?>
		</TBODY>

	</TABLE>
</div>



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>
