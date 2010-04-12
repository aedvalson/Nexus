<? include "findconfig.php" ?>
<? include $DOCROOT."/".$ROOTPATH."/Includes/Top.php" ?>


<?

$DB = new conn();
$DB->connect();


$sql = "select count(*) from orders";
$total_sales = $DB->query_scalar($sql);


$DB->close();


?>


<?php   
 /*
     Example1 : A simple line chart
 */

 // Standard inclusions      
 include($DOCROOT.$ROOTPATH."/pChart/pChart/pData.class");   
 include($DOCROOT."/".$ROOTPATH."/pChart/pChart/pChart.class");   
  
 // Dataset definition    
 $DataSet = new pData;   
 $DataSet->AddPoint(array(9, 8, 3), "Serie1");
 //$DataSet->AddPoint(array(9, 8, 3), "Serie2");
 $DataSet->AddPoint(array("Kirby Sentria", "Shampooer", "Zipp Brush"), "Serie2");
 $DataSet->AddAllSeries();   
 $DataSet->SetAbsciseLabelSerie("Serie2");   
  
 // Initialise the graph   
 $Test = new pChart(700,500);  
 $Test->setFontProperties($DOCROOT."/".$ROOTPATH."/pChart/Fonts/tahoma.ttf",13);   
 
  
  
 // Finish the graph   
 $Test->setFontProperties($DOCROOT."/".$ROOTPATH."/pChart/Fonts/tahoma.ttf",13);   
 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),250,210,240,PIE_PERCENTAGE_LABEL,TRUE,50,20,5);  
 $Test->drawPieLegend(510,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);  
   
 $Test->Render($TempDir . "/example1.png");
?>




<div id="container">
	<a href="#" onclick="pdfReport( $('#reportDiv') ); return false;">PDF</a>
	<div style="background-color: white; width:1020px;" id="reportDiv">

		<center>
		<h1>General Sales Report</h1>
		<h2>Monday November 30, 2009</h2>
		<h3>American Eagle Corp.</h3>

		<br><br><br>

		<table class="report" CELLSPACING="0" style="width: 1000px;"
			<tr>
				<td class="labelCell">TOTAL SALES:</td>
				<td><?= $total_sales ?></td>
				<td rowspan="2" class="labelCell">DEALERS:</td>
				<td rowspan="2">2 Shane Watkins<br>
					2 Billy Mayes<br>
					1 Mary Tyler<br>
					1 Joe Dirt<br>
					1 Mark Wauberg<br>
					1 David Polakoff<br>
					1 Sarah Lee<br>
				</td>
			</tr>
			<tr>
				<td class="labelCell">INVENTORY SOLD:</td>
				<td>9 Kirby Sentria<br>
					8 Shampooer<br>
					3 Zipp Brush<br>
				</td>
			</tr>
			<tr>
				<td class="labelCell">SALE FINANCE TYPES:</td>
				<td>4 Cash<br>
					2 Check<br>
					3 Financed<br>
				</td>
				<td class="labelCell" rowspan="2">REVENUE FIGURES:</td>
				<td rowspan="2">Total Revenue: $10.350<br>
					Total Commissions: $2,075<br>
					Total Neg. Adjustments: $175<br>
					Net Revenues: $8,275<br>
				</td>
			</tr>
			<tr>
				<td class="labelCell">TEAM PERFORMANCE:</td>
				<td>Top Team:<br>
					Mark Hendricks (6)<br>
					<br>
					Bottom Team:<br>
					Martha Jones (3)<br>
				</td>
			</tr>
			<tr>
				<td colspan="4" id="imgCell">
					<img src="<?= $FQDN . "/" . $ROOTPATH . $TempDirVirtual ?>/example1.png" />
				</td>
			</tr>
		</table>

	</div>
</div>

</div>
<br><br><br><br>
<div style="clear:both; height:1px">&nbsp;</div>



<? include $DOCROOT."/".$ROOTPATH."/Includes/Bottom.php" ?>
