<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";

$DB = new conn();
$DB->connect();

// Get Parameters
if (!$_REQUEST["user_id"]) { header("Location: /$ROOTPATH/reports"); };

$params["user_id"]		= $DB->sanitize($_REQUEST["user_id"]);
// End Parameters

// Get All Checked Out Inventory
$sql = "SELECT * FROM `inventory` join products on inventory.product_id = products.product_id  WHERE inventory.status = 2 and status_data = " . $params["user_id"];
$result = $DB->query($sql);
if (!$result)
{
	$DB->close();	
	echo "No Inventory checked out to that user.";
	exit();
}

$inventory = array();
while ($invRow = mysql_fetch_assoc($result))
{
	$inventory[$invRow["inventory_id"]] = $invRow;
}

// Get All Users
$users			= getUserHash($DB);
$productHash	= getProductHash($DB);

$user_id = $params["user_id"];

echo $users[0]["user_id"];
$firephp->log($users);
$firephp->log($inventory);
?>

<? $firephp->log("asd"); ?>

<a href="#" onclick="pdfReport($('.reportContainer')); return false;">PDF</a>

<div class="reportContainer">
	<div class="reportHeader">
		<h1>Equipment Check In Report</h1>
		<h2><?= date("m/d/Y") ?></h2>
		<h2><?= $AgencyParams["AgencyName"] ?></h2>
	</div>
	<br />



	<div style="page-break-inside: avoid">
		
	<table class="report" CELLSPACING="0" style="width: 1000px; margin-bottom: 1em; page-break-inside: avoid">
		<tr>
			<td>Product Name</td>
			<td>Serial #</td>
			<td>Return Information</td>
		</tr>


	<? 
	foreach ($inventory as $inv)
		{
		?>
			<tr>
				<td><?= $inv["product_name"] ?></td>
				<td><?= $inv["serial"] ?></td>
				<td>Check-In / Sold</td>
			</tr>
		<?
		}
		?>
	</table>
	<div class="reportHeader" style="padding:0; width: 60em;">
		<p style="margin-top: 1.5em; margin-bottom: 1.5em; margin-left: 2em; font-size: 90%; width: 100%; line-height: 1.3em">I have received the above listed equipment from American Eagle Corporation. I understand and agree that the above equipment is the sole property of American Eagle Corporation and agree to return the above equipment within 24 hours of a request being made, either written or verbal. Failure to do so may be subject to criminal and civil actions and that I am responsible for all cost associated with American Eagle Corporations retrieval of the above equipment or its equivalent value, including but not limited to legal fees and court costs. I, the above listed representative, hereby agree to the above.</p>

		<p style="margin-left: 3em; margin-top: 1em;">Sign: ________________________________</p>
	</div>
</div>
	

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>