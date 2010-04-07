<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/"$ROOTPATH."/php/Includes/Top.php" 
?>

<?
$obj = array();

if ($_REQUEST)
{
	// See if we have been passed an object
	if (isset($_REQUEST["JsonObject2"])) {
		echo "Hello, Json object - ".$_REQUEST["JsonObject2"]."<br /><br />";
		$decoded = json_decode($_REQUEST["JsonObject2"], true);
		//echo "<br>Decoding: ".$decoded;
		$obj = $decoded; 
		}
	else $obj = array();



	// Validate Template name, return default value if blank
	if (isset($_REQUEST["TemplateName"]))  {
		$name = $_REQUEST["TemplateName"];
		if ($name != "")
		{
			$obj["TemplateName"] = $_REQUEST["TemplateName"];
		}
		else $obj["TemplateName"] = "Not Yet Set";
	}
	else { $obj["TemplateName"] = "Not Yet Set"; }


	// Validate Min price, or set default

	if (isset($_REQUEST["MinPrice"]))  {
		$price = $_REQUEST["MinPrice"];
		if ($price != "")
		{
			$obj["MinPrice"] = $_REQUEST["MinPrice"];
		}
		else $obj["MinPrice"] = "0";
	}
	else $obj["MinPrice"] = "0";
	

		



	// Validate new element
	if (isset($obj["TemplateElements"]))
	{
		// Check for Commands
		if (isset($_REQUEST["Action"]))
		{
			$action = $_REQUEST["Action"];
			if ($action == "DelElement")
			{
				if ($_REQUEST["Argument"]);
				{
					$arg = $_REQUEST["Argument"];
					echo $action." ".$arg;
					unset($obj["TemplateElements"][$arg]);

					$array = array();
					foreach($obj["TemplateElements"] as $element)
					{
						$array[] = $element;
					}
					$obj["TemplateElements"] = $array;

				}
			}
		}
	}
	else {
		$obj["TemplateElements"] = Array();
		}

	// Validate New Object
	if (isset($_REQUEST["Payee"]))
	{
		echo("adding new element<br><br>");
		$payee = $_REQUEST["Payee"];
		$paytype = $_REQUEST["PaymentType"];
		if ($paytype == "flat")
		{
			$Amount = $_REQUEST["FlatAmount"];
		}
		else if ($paytype == "percentage")
		{
			$Amount = $_REQUEST["Percentage"];
		}

		$element = array();
		$element["Payee"] = $payee;
		$element["PaymentType"] = $paytype;

		if ($paytype != "remaining")
		{
			$element["Amount"] = $Amount;
		}

		$obj["TemplateElements"][] = $element;
	}

}
else
{
	$obj = array();
	$obj["TemplateName"] = "Not Yet Set";
	$obj["MinPrice"] = 0;
	$obj["TemplateElements"] = Array();
	$obj["TemplateElements"][0] = "Bob";
}

echo "we has a new json object now!... ". json_encode($obj);


?>


OBJECT<br>
Template Name: <? echo $obj["TemplateName"]; ?> <br />
Minimum Price: <? echo $obj["MinPrice"]; ?><br /><br />
Template Elements:<br>
<? 
$n = 0;
foreach ($obj["TemplateElements"] as $e)
{
	foreach ((Array)$e as $key => $value)
	echo $key.": ".$value."<br>";
	?><a href="#" id="lbDelElement<? echo $n; ?>">Remove</a>

	<?$n++;
	echo "<br><br>";
}

?>
	
<BR>


<FORM ID="theForm" method="POST" ACTION="">
TEMPLATE ATTRIBUTES FORM<br>
Template Name: <input type="text" id="tbTemplateName" name="TemplateName"><br>
Minimum Price: <input type="text" id="tbMinPrice" name="MinPrice"><br>
<input type="hidden" id="hv_JsonObject2" name="JsonObject2" Value='<? echo json_encode($obj); ?>'>
<input type="submit">
</FORM>


<FORM ID="templateForm" method="POST" ACTION="">
ELEMENT FORM<br>
Payee: 
<SELECT name="Payee" id="ddlPayee">
	<option value="corporate">Corporate</option>
	<option value="employee">Employee</option>
	<option value="split">All Involved (split)</option>
</SELECT><br>

Payment Type:
<SELECT name="PaymentType" id="ddlPaymentType">
	<option value="flat" SELECTED>Flat Rate</option>
	<option value="percentage">Percentage</option>
	<option value="remaining">All Remaining</option>
</SELECT><br>

<div id="flatRateDiv">
Amount
<input type="text" id="tbFlatAmount" name="FlatAmount">
</div>

<div id="percentageDiv" style="display:none;">
Percentage
<input type="text" id="tbPercentage" name="Percentage">
</div>

<input type="hidden" id="hv_JsonObject2" name="JsonObject2" Value='<? echo json_encode($obj); ?>'>
<input type="submit">
</FORM>


<FORM ID="postBackForm" METHOD="POST" ACTION="">
	<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
	<INPUT TYPE="HIDDEN" id="hv_Argument" NAME="Argument" Value="0">
	<input type="hidden" id="hv_JsonObject2" name="JsonObject2" Value='<? echo json_encode($obj); ?>'>
</FORM>


<script type="text/javascript">
	$(document).ready(function() {
		$("#ddlPaymentType").change(function () {
			var value = $(this).val();
			if (value == 'flat')
			{
				$("#flatRateDiv").css("display", "block");
				$("#percentageDiv").css("display", "none");
			}
			if (value == 'percentage')
			{
				$("#flatRateDiv").css("display", "none");
				$("#percentageDiv").css("display", "block");
			}
			if (value == 'remaining')
			{
				$("#flatRateDiv").css("display", "none");
				$("#percentageDiv").css("display", "none");
			}
		});

		$("a[id^=lbDelElement]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var arg = id.replace("lbDelElement", "");

			$("#hv_Argument").val(arg);
			$("#hv_Action").val("DelElement");

			$("#postBackForm").submit();	

				});

		});


	});
</script>


<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>