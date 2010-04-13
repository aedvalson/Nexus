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
	$sql .= "WHERE DateAdded >= STR_TO_DATE('" . $startDate . "', '%Y-%m-%d') ";
	$sql .= "AND DateAdded < STR_TO_DATE('" . $endDate . "', '%Y-%m-%d') ";

	$DB = new conn();
	$DB->connect();

	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$InventoryArray[] = $row;
	}
	$DB->close();

echo "Start: $startDate <br/>";
echo "end: $endDate<br/>";
echo "sql: $sql";

?>

<TABLE>

<? 
foreach ($InventoryArray as $InventoryRow)
{
	?> <TR><TD>Row</TD></TR> <?
}
	?>


</TABLE>



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>