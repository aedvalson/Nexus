<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
$F = new FormElements();
?>
<?

$DB = new conn();
$DB->connect();


// Form Vars
	if ($_REQUEST)
	{
		if ($_POST)
		{
			if ($_REQUEST["Action"] )
			{
				$action = $_REQUEST["Action"];
				if ($action == "addNew")
				{
					// ALL FORM INPUTS MUST BE SANITIZED
	
					$ProductId = $DB->sanitize($_REQUEST["ProductId"]);
					$LocationId= $DB->sanitize($_REQUEST["LocationId"]);
					$Invoice = $DB->sanitize($_REQUEST["Invoice"]);

					$serials = Array();
					foreach ( $_REQUEST as $key => $value)
					{
						$pos = strpos($key, "Serial");

						if ($pos !== false)
						{
							$serials[] = $DB->sanitize($value);
						}
					}

					foreach ($serials as $serial)
					{
						$sql = "INSERT INTO inventory (product_id, storagelocation_id, invoice, serial, status, status_data, AddedBy) VALUES (".$ProductId.", ".$LocationId.", '".$Invoice."', '".$serial."', '1', ".$LocationId.", ". $_SESSION["user_id"] .")";
						$newId = $DB->insert($sql);
						
						$sql = "SELECT storagelocation_name from storagelocations where storagelocation_id = ".$LocationId;
						$locText = $DB->query_scalar($sql);
					
						$statusdate = date("m/d/y");
						$sql = "UPDATE inventory set status_data_text = '".$locText."', status_date =  STR_TO_DATE('".$statusdate."', '%m/%d/%Y') WHERE inventory_id = ".$newId;
						$DB->execute_nonquery($sql);
					}
					header("Location: ManageInventory.php");
				}
			}
		}
		if ($_GET)
		{

		}
	}


$DB->close();

?>
<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Inventory</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" id="custBulletLink">Add Inventory</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">
	<div class="contentDiv">

		<div class="formDiv" style="display: block; background-color: #EDECDC">
			<h1>Add Inventory</h1>




	<div style="float:left;">
	   <ul class="form">
	   
		 <li class="validated" id="ddlProductId_li">
					  <label for="r_ddlProductId">Product:</label>
					  <div id="ddlProductId_img"></div>
						<select class="validated" name="ProductId" id="ddlProductId" >
							<?php 
							$DB = new conn();

							foreach ($DB->getProducts() as $loc)
							{
								?><option value="<? echo $loc["product_id"]; ?>"
								<?
								if (isset($_GET["product_id"]))
								{

									$prod_id = $_GET["product_id"];
									if ($prod_id == $loc["product_id"] )
									{
										echo " selected "; 
									}	

								}
								?>
								><? echo $loc["product_name"];?></option><?
							}
							?>	
						</select>	
						<input type="hidden" id="ddlProductId_val" value="waiting">
					  <div id="ddlProductId_msg"></div>
			  </li>
			  
			  
				 
		 <li class="validated" id="ddlLocationId_li">
					  <label for="r_ddlLocationId">Location:</label>
					  <div id="ddlLocationId_img"></div>
						<select class="validated" name="LocationId" id="ddlLocationId" >
							<option value="">Select a Location</option>
							<?php 
							$DB = new conn();

							foreach ($DB->getStorageLocations() as $loc)
							{
								?><option value="<? echo $loc["storagelocation_id"]; ?>"><? echo $loc["storagelocation_name"];?></option><?
							}
							?>	
						</select>	
						<input type="hidden" id="ddlLocationId_val" value="waiting">
					  <div id="ddlLocationId_msg"></div>
			  </li>

		 <li class="validated" id="tbInvoice_li">
					  <label for="r_tbInvoice">Invoice:</label>
					  <div id="tbInvoice_img"></div>
						<input class="validated" name="Invoice" id="tbInvoice" type="text" maxlength="20" value=""  />		
						<input type="hidden" id="tbInvoice_val" value="waiting">
					  <div id="tbInvoice_msg"></div>
			  </li>     
			  
		<? $F->tbNotVal("receivedDate", "Date Received", "datepicker"); ?>		   

		 <li class="validated" id="btnSubmit_li">
					  <label for="r_btnSubmit"></label>
					  <div id="btnSubmit_img"></div>
					  <input type="hidden" name="Action" value="addNew"></input>
					  <input id="btnSubmit" type="Submit" maxlength="20" value="Submit"  />
					  <div id="btnSubmit_msg"></div>
			  </li>          


	   </ul>
	</div>
	<div style="float:left">
		<ul class="form">
		 <li  id="tbAmtToAdd_li">
					  <label for="r_tbAmtToAdd">Number to Add:</label>
					  <div id="tbAmtToAdd_img"></div>
						<input class="validated" style="width:35px" name="Quantity" id="tbAmtToAdd" type="text" maxlength="2" value=""  />		
						<input type="button" style="width: 60px" id="updateSerialBoxesButton" Text="Update" Value="Update">
						<input type="hidden" id="tbAmtToAdd_val" value="true">
					  <div id="tbAmtToAdd_msg"></div>
			  </li>    
		 <li  id="tbSerial_li">
					  <label for="r_tbSerial">Serial Number(s):</label>
					  <div id="tbSerial_img"></div>
					  <div id="tbSerial" class="validated">
						<input class="validated" name="Serial1" id="tbSerial1" type="text" maxlength="20" value=""  />		
						<input class="validated" name="Serial2" id="tbSerial2" type="text" maxlength="20" value=""  />		
						<input class="validated" name="Serial3" id="tbSerial3" type="text" maxlength="20" value=""  />		
						<input type="hidden" id="tbSerial_val" value="false">
					  </div>
					  <div id="tbSerial_msg"></div>
			  </li>     
		</ul>

	</div>
	<div class="spacer">


</div>
</div>
</div>
</form>

<script type="text/javascript">
	$('#tbAmtToAdd').change(function() {
		updateSerialBoxes();
	});
	$('#updateSerialBoxesButton').click(function() {
		updateSerialBoxes();
	});

	function updateSerialBoxes()
	{
		var newTotal = $('#tbAmtToAdd').val();
		if (newTotal > 15)
		{
			newTotal = 15;
			$('#tbAmtToAdd').val(15);
		}
		var oldTotal = $('#tbSerial>input.validated').size();

		if (newTotal > oldTotal)
		{
			j = newTotal - oldTotal;
			i = 0;

			while (i < j)
			{
				$('#tbSerial').append('<input class="validated" name="Serial' + i + '" id="tbSerial' + i + '" type="text" maxlength="20" value=""  />');
				i++;
			}
		}

		if (newTotal < oldTotal)
		{
			i = 0;
			$('#tbSerial>input.validated').each(function() {
				if (i >= newTotal)
				{
					$(this).remove();
				}
				i++;
			});
		}

	}
</script>
<? include $_SERVER['DOCUMENT_ROOT']."/". $ROOTPATH ."/Includes/Bottom.php" ?>