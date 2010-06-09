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
$sql = "SELECT * from orders join contacts on orders.contact_id = contacts.contact_id where order_status_id = 5 AND DateCompleted >= '" . $params["startDate"] . "' AND DateCompleted <=  '" . $params["endDate"] . "'";
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


// Build Date Hash
$days = array();
$currentDay = strtotime($params["startDate"]);
$endDay = strtotime($params["endDate"]);

// Count Days
$daycount = 1;
while ($currentDay < $endDay)
{
	$currentDay = strtotime("+1 day", $currentDay);
	$daycount++;
}

$currentDay = strtotime($params["startDate"]);
$days[Date("m/d/Y", $currentDay)]["date"] = Date("Y/m/d", $currentDay);
$days[Date("m/d/Y", $currentDay)]["quantity"] = 0;
$currentDisplay = Date("m/d", strtotime($params["startDate"]));
$days[Date("m/d/Y", $currentDay)]["display"] = $currentDisplay;
$i = 0;
while ($currentDay < $endDay)
{
	$currentDay = strtotime("+1 day", $currentDay);
	$days[Date("m/d/Y", $currentDay)]["date"] = Date("Y/m/d", $currentDay);
	$days[Date("m/d/Y", $currentDay)]["quantity"] = 0;

	if ($daycount > 14)  // Lots of days, so we only show weekly labels for image
	{
		if (strtotime("last sunday", $currentDay) < strtotime($params["startDate"]))
		{
			$days[Date("m/d/Y", $currentDay)]["display"] = Date("m/d", strtotime($params["startDate"]));
		}
		else
		{
			$days[Date("m/d/Y", $currentDay)]["display"] = Date("m/d", strtotime("last sunday", $currentDay));
		}

		if ($days[Date("m/d/Y", $currentDay)]["display"] == $currentDisplay)
		{
			$days[Date("m/d/Y", $currentDay)]["display"] = "";
		}
		else
		{
			$currentDisplay = $days[Date("m/d/Y", $currentDay)]["display"];
		}
	}
	else
	{
		$days[Date("m/d/Y", $currentDay)]["display"] = Date("m/d", $currentDay);
	}

}
foreach ($orders as $order)
{
	$days[Date("m/d/Y", strtotime($order["DateCompleted"]))]["quantity"]++;
}


// Calculate Gross Sales
$Gross = 0;
$InventoryArray = array();
foreach ($orders as $order)
{
	$myAmount = $order["amount"];
	$Gross += $myAmount;
}

// Build Inventory Array
$AllProducts = array();
foreach ($orders as $order)
{
	$productsArray = json_decode($order["ProductsArray"], true);
	$products = $productsArray["products"];
	if ($products)
	{
		foreach ($products as $product)
		{
			if (!$AllProducts[$product["Product_ID"] ]) // Add product to hash, set quant to 0, name to product name
			{ 
				$AllProducts[$product["Product_ID"] ]["quantity"] = 0; 
				$AllProducts[$product["Product_ID"] ]["product_name"] = $product["Name"]; 
			} 
			$AllProducts[$product["Product_ID"]]["quantity"] += $product["quantity"];
		}
	}
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


// Build Team Hash
$teams = array();
foreach ($users as $user)
{
	if (!$teams[$user["team_id"]]) { $teams[$user["team_id"]]["quantity"] = 0; }
	$teams[$user["team_id"]]["teamname"] = $user["team_name"];
	$teams[$user["team_id"]]["teamleader_id"] = $user["team_leader"];
	$teams[$user["team_id"]]["teamid"] = $user["team_id"];
	$teams[$user["team_id"]]["teamleader_name"] = $users[$user["team_leader"]]["FirstName"] . " " .  $users[$user["team_leader"]]["LastName"];
}



// Build Dealers Array
$AllDealers = array();
$financeTypes = array();
foreach ($orders as $order)
{
	$dealersArray = json_decode($order["dealerArray"], true);
	$roles = $dealersArray["roles"];
	foreach ($roles as $role)
	{
		if ($role["roleText"] == "Dealer")
		{
			if (!$AllDealers[$role["user"]])
			{
				$AllDealers[$role["user"]]["displayName"] = $role["displayName"];
				$AllDealers[$role["user"]]["quantity"] = 0;
			}
			$AllDealers[$role["user"]]["quantity"] += 1;

			// Add sale to teams array
			$teams[$users[$role["user"]]["team_id"]]["quantity"]++;
		}
	}
}
usort($AllDealers, "CompareQuantities");
usort($teams, "CompareQuantities");
$firephp->log($teams);



// Calculate Revenue and Build finance types hash
$financeTypes = array();
$TotalRevenue = 0;
$TotalCommissions = 0;
$TotalAdjustments = 0;
$NetRevenue = 0;
foreach ($orders as $order)
{
	// Get Base amount
	$amount = $order["amount"];
	$adj_amount = $amount;
	$largestFinanceType = "";
	$largestFinanceAmount = 0;

	// Check for Reserves and watch for largest finance method for finance types hash
	$financeArray = json_decode($order["PaymentArray"], true);
	foreach ($financeArray["paymentMethods"] as $financeMethod)
	{
		if ($financeMethod["amount"] > $largestFinanceAmount)
		{
			$largestFinanceType = $financeMethod["paymentType"];
			$largestFinanceAmount = $financeMethod["amount"];
		}
		if ($financeMethod["paymentType"] == "finance")
		{
			$adj_amount = $adj_amount - ($financeMethod["amount"] * $financeMethod["reserveRate"] / 100);
		}
	}	

	if ($largestFinanceAmount > 0)
	{
		if (!$financeTypes[$largestFinanceType]) 
		{
			$financeTypes[$largestFinanceType]["quantity"] = 0;
		}
		$financeTypes[$largestFinanceType]["type"] = $largestFinanceType;
		$financeTypes[$largestFinanceType]["quantity"]++;
	}

	$TotalRevenue = $TotalRevenue + $adj_amount;

	// Calculate Commission
	$corptotal = 0;
	$emptotal = 0;

	$commArray = json_decode($order["CommStructure"], true);
	foreach ($commArray["elements"] as $comm)
	{
		$commission = 0;


		if ($comm["paymentType"] == "flat")
		{
			$commission = $commission + $comm["flatAmount"];
		}
		if ($comm["paymentType"] == "percentage")
		{
			$commission = $commission + ($adj_amount * $comm["percentage"] / 100);
		}
		if ($comm["paymentType"] == "remaining")
		{
			$commission = $adj_amount - $corptotal - $emptotal;
		}

		if ($comm["payeeType"] == "corporate")
		{
			$corptotal = $corptotal + $commission;
		}
		if ($comm["payeeType"] == "employee")
		{
			$emptotal = $emptotal + $commission;
		}
	}

	$TotalCommissions = $TotalCommissions + $emptotal;
}
$NetRevenue = $TotalRevenue - $TotalCommissions;
usort($financeTypes, "CompareQuantityAndType");
//$firephp->log("Rev: " . $TotalRevenue . " Net: " . $NetRevenue . " Comm: " . $TotalCommissions);
//$firephp->log($financeTypes);



// Build By-Zip Array
$zipcodes = array();
foreach ($orders as $order)
{
	if (!$zipcodes[$order["contact_zipcode"]]) 
	{ 
		$zipcodes[$order["contact_zipcode"]]["quantity"] = 0;
		$zipcodes[$order["contact_zipcode"]]["zipcode"] = $order["contact_zipcode"];
	}
	$zipcodes[$order["contact_zipcode"]]["quantity"] = $zipcodes[$order["contact_zipcode"]]["quantity"] + 1;
}



function CompareQuantities($x, $y)  // Sort by quantity then display name.
{
 if ( $x["quantity"] == $y["quantity"] )
	{
	 if ($x["displayName"] == $y["displayName"])
		 return 0;
	 else if ($x["displayName"] < $y["displayName"])
		 return -1;
	 else
		 return 1;
	}
 else if ( $x["quantity"] < $y["quantity"] )
  return 1;
 else
  return -1;
}


function CompareQuantityAndType($x, $y)  // Sort by quantity then display name.
{
 if ( $x["quantity"] == $y["quantity"] )
	{
	 if ($x["type"] == $y["type"])
		 return 0;
	 else if ($x["type"] < $y["type"])
		 return -1;
	 else
		 return 1;
	}
 else if ( $x["quantity"] < $y["quantity"] )
  return 1;
 else
  return -1;
}



$DB->close();
// Build Bar Chart for zipcodes
include("../pChart/pChart/pData.class");  
include("../pChart/pChart/pChart.class");

$DataSet = new pData;
foreach ($zipcodes as $zipcode)
{
	$DataSet->AddPoint($zipcode["quantity"], "Series1");
	$DataSet->AddPoint($zipcode["zipcode"], "Series2");
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie("Series2");
	$DataSet->SetSerieName("Sales By Zip Code","Series1");
	$DataSet->RemoveSerie("Series2");
}
//$firephp->log($zipcodes);
//$firephp->log($DataSet);

  // Initialise the graph
  $Test = new pChart(700,230);
  $Test->setColorPalette(0,38,33,204); 
  $Test->setFontProperties("../pChart/Fonts/tahoma.ttf",10);
  $Test->setGraphArea(50,50,680,200);
  $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
  $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
  $Test->drawGraphArea(255,255,255,TRUE);
  $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,TRUE,0,2,TRUE);   
  $Test->drawGrid(4,TRUE,230,230,230,50);

  // Draw the 0 line
  $Test->setFontProperties("../pChart/Fonts/tahoma.ttf",14);
  $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

  // Draw the bar graph
  $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);

  // Finish the graph
  $Test->setFontProperties("../pChart/Fonts/tahoma.ttf",14);
  $Test->drawTitle(100,32,"Sales By Zip Code",50,50,50,585);
  $Test->Render("../temp/bar.png");



// Build Line Chart for dates
$DataSet = new pData;
foreach ($days as $_day)
{
	$DataSet->AddPoint($_day["quantity"], "Series1");
	$DataSet->AddPoint($_day["display"], "Series2");
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie("Series2");
	$DataSet->SetSerieName("Sales By Date","Series1");
	//$DataSet->RemoveSerie("Series2");
}

  // Initialise the graph
  $Test = new pChart(1200,430);
  $Test->setColorPalette(0,38,33,204); 
  $Test->setFontProperties("../pChart/Fonts/tahoma.ttf",14);
  $Test->setGraphArea(50,50,1180,400);
  $Test->drawFilledRoundedRectangle(7,7,1193,423,5,240,240,240);
  $Test->drawRoundedRectangle(5,5,1195,425,5,230,230,230);
  $Test->drawGraphArea(255,255,255,TRUE);
  $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,TRUE,0,2,TRUE);   
  $Test->drawGrid(4,TRUE,230,230,230,50);

  // Draw the 0 line
  $Test->setFontProperties("../pChart/Fonts/tahoma.ttf",10);
  $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

  // Draw the bar graph
  $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);

  // Finish the graph
  $Test->setFontProperties("../pChart/Fonts/tahoma.ttf",22);
  $Test->drawTitle(550,36,"Sales By Date",50,50,50,585);
  $Test->Render("../temp/bar2.png");


?>


<a href="#" onclick="pdfReport($('.reportContainer')); return false;">PDF</a>

<div class="reportContainer">
	<div class="reportHeader">
		<h1>General Sales Report Report</h1>
		<h2><?= date("m/d/Y", strtotime ( $params["startDate"] )) ?> to <?= date("m/d/Y", strtotime ( $params["endDate"] )) ?></h2>
		<h2>American Eagle Corp.</h2>
	</div>

	<table class="report" CELLSPACING="0" style="width: 1000px;"
		<tr>
			<td class="labelCell" style="width: 170px">TOTAL SALES:</td>
			<td><?= count($orders) ?></td>
			<td rowspan="2" class="labelCell" style="width: 170px">DEALERS:</td>
			<td rowspan="2">
			<? foreach ($AllDealers as $dealer)
			{
				echo $dealer["quantity"] . " " . $dealer["displayName"] . "<br />";
			} ?>
			</td>
		</tr>
		<tr>
			<td class="labelCell">INVENTORY SOLD:</td>
			<td>
			<? foreach ($AllProducts as $product)
				{ 
					echo $product["quantity"] . " " . $product["product_name"] . "<br />";
				} ?>
			</td>
		</tr>
		<tr>
			<td class="labelCell">SALE FINANCE TYPES:</td>
			<td style="text-transform:capitalize">
				<? foreach ($financeTypes as $ft)
				{
					echo $ft["quantity"] . " " . $ft["type"] . "<br />";
				}
				?>
			</td>
			<td class="labelCell" rowspan="2">REVENUE FIGURES:</td>
			<td rowspan="2">Total Revenue: $<?= money_format("%i", $TotalRevenue) ?><br>
				Commissions: $<?= money_format("%i", $TotalCommissions) ?><br>
				Total Neg. Adjustments: $0<br>
				Net Revenue: $<?= money_format("%i", $NetRevenue) ?><br>
			</td>
		</tr>
		<tr>
			<td class="labelCell">TEAM PERFORMANCE:</td>
			<td>
				Top Team:<br>
				<?= $teams[0]["teamleader_name"] ?> (<?= $teams[0]["quantity"] ?>)<br><br>
				Bottom Team:<br>
				<?= $teams[count($teams)-1]["teamleader_name"] ?> (<?= $teams[count($teams)-1]["quantity"] ?>)<br>
			</td>
		</tr>
		<tr>
			<td colspan="4" id="imgCell">
				<center>
					<img style="width: 90%; margin-top: 1em" src="<?= $FQDN . "/" . $ROOTPATH . $TempDirVirtual ?>/bar.png" />
					<img style="width: 90%; margin-top: 1em" src="<?= $FQDN . "/" . $ROOTPATH . $TempDirVirtual ?>/bar2.png" />
				</center>
			</td>
		</tr>
	</table>

</div>




<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>