<? include $_SERVER['DOCUMENT_ROOT']."/php/Includes/Top.php" ?>


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
 include($_SERVER['DOCUMENT_ROOT']."/php/pChart/pChart/pData.class");   
 include($_SERVER['DOCUMENT_ROOT']."/php/pChart/pChart/pChart.class");   
  
 // Dataset definition    
 $DataSet = new pData;   
 $DataSet->AddPoint(array(9, 8, 3), "Serie1");
 //$DataSet->AddPoint(array(9, 8, 3), "Serie2");
 $DataSet->AddPoint(array("Kirby Sentria", "Shampooer", "Zipp Brush"), "Serie2");
 $DataSet->AddAllSeries();   
 $DataSet->SetAbsciseLabelSerie("Serie2");   
  
 // Initialise the graph   
 $Test = new pChart(700,500);  
 $Test->setFontProperties($_SERVER['DOCUMENT_ROOT']."/php/pChart/Fonts/tahoma.ttf",13);   
 
  
  
 // Finish the graph   
 $Test->setFontProperties($_SERVER['DOCUMENT_ROOT']."/php/pChart/Fonts/tahoma.ttf",13);   
 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),250,210,240,PIE_PERCENTAGE_LABEL,TRUE,50,20,5);  
 $Test->drawPieLegend(510,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);  
   
 $Test->Render("example1.png");
?>




<div id="container">
	<div style="width:1020px;" id="reportDiv">

		<center>
		<h1>General Sales Report</h1>
		<h2>Monday November 30, 2009</h2>
		<h3>American Eagle Corp.</h3>

		<br><br><br>

		<table class="report" CELLSPACING="0" >
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
				<td colspan="4">
					<img src="/php/reports/example1.png" style="width:700px">
				</td>
			</tr>
		</table>

	</div>
</div>

</div>
<br><br><br><br>
<div style="clear:both; height:1px">&nbsp;</div>

		<SCRIPT type="text/javascript">
			$().ready( function() {
				var _element = $('#container');
				var $element = _element.clone(true);
				var method = 'pdfLink';
				
				$($element).find('#filterRow').remove();
				$($element).find('tfoot').remove();
				
				$.post('/php/Includes/ajax.php', { id: "generateReport",  value: $($element).html() }, function(json) {
					eval("var args = " + json);		
					if (args.success == "success")
					{
						if (args.output)
						{
							report = args.output;

							if (method == 'pdfLink')
							{
								url = '/php/reports/pdf/generate/html2ps.php?process_mode=single&URL=URLGOESHERE&proxy=&pixels=1024&scalepoints=1&renderimages=1&renderlinks=1&renderfields=1&media=Letter&cssmedia=Screen&leftmargin=30&rightmargin=15&topmargin=15&bottommargin=15&encoding=&headerhtml=&footerhtml=General%20Sales%20Report&watermarkhtml=&toc-location=before&smartpagebreak=1&pslevel=3&method=fpdf&pdfversion=1.3&output=0&convert=Convert+File457'
								.replace('URLGOESHERE', escape('republictech.net/php/reports/automated/_report.php?report_id=' + report + '&output=pdf'));
								window.open(url);
							}

							if (method == 'excelLink')
							{
								window.open('http://aedvalson.info/php/reports/automated/_report.php?report_id=' + report + '&output=excel');
							}
						}
						else
						{

						}
					}
					else
					{
						 alert("Ajax failed.");
					}
				});

			});
		</SCRIPT>


<? include $_SERVER['DOCUMENT_ROOT']."/php/Includes/Bottom.php" ?>