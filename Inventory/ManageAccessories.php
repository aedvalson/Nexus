<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>

<?php 
if (!isset($_REQUEST["product_id"]))
{
	echo("Product ID not specified");
}
else
{
	$DB = new conn();
	$DB->connect();
	$product_id = $DB->sanitize($_REQUEST["product_id"]);	
	if (!is_numeric($product_id)) { die("Invalid Product_ID"); } // Make sure product_id is a number
	
	
	// Actions
	if (isset($_REQUEST["Action"]) )
	{
		$action = $_REQUEST["Action"];
		//echo $action;
		if ($action == "Delete")
		{
			// ALL FORM INPUTS MUST BE SANITIZED

			$ProductID = $DB->sanitize($_REQUEST["hProduct_ID"]);
			$AccessoryID = $DB->sanitize($_REQUEST["hAccessory_ID"]);
			
			$sql = "DELETE from relproducts_accessories WHERE Product_ID = ".$ProductID." AND Accessory_ID = ".$AccessoryID;
			$DB->execute_nonquery($sql);
			//header("Location: ManageProducts.php");
		}
		if ($action == "add")
		{
			// ALL FORM INPUTS MUST BE SANITIZED

			$ProductID = $DB->sanitize($_REQUEST["hProduct_ID"]);
			$AccessoryID = $DB->sanitize($_REQUEST["Accessory_ID"]);

			$sql = "DELETE from relproducts_accessories WHERE Product_ID = ".$ProductID." AND Accessory_ID = ".$AccessoryID;
			$result = $DB->execute_nonquery($sql);
			if ($result > 0)
			{
				?><font color="red">Accessory already associated with this product.</font><br /><br /> <?php 
			}
			
			
			$sql = "INSERT into relproducts_accessories (Product_ID, Accessory_ID) VALUES (".$ProductID.", ".$AccessoryID.")";
			$DB->execute_nonquery($sql);
		}		
	}
	$sql = "select * from relproducts_accessories r join products on r.accessory_id = products.product_id where r.product_id = ".$product_id;
	$result = $DB->query($sql);
	
	$sql = "select * from products where product_id = ".$product_id;
	$prodInfo = $DB->query($sql);

	$sql = "select * from products where product_type = 'Accessory' and status = 'Active'";
	$accessories = $DB->query($sql);
	
	
	if ($prodInfo)
	{
		while ($row = mysql_fetch_assoc($prodInfo))
		{
		?>Product: <b><?php  echo $row["product_name"];?></b><?php
		}
	}
	
	if (!$result ) { echo ("<br /><br />No accessories for product yet."); }   // Validate the results.
	else {
		?> <TABLE class="data">
			<th>product_id</th>
			<th>Product Model</th>
			<th>Product Name</th>
			<th style="width:350px">Product Description</th>
			<th>Status</th>
			<th style="width:150px">Commands</th>
			 <?		
		while ($row = mysql_fetch_assoc($result)) // Do Stuff.
		{
		    ?>
			<TR><TD><? echo $row["product_id"]; ?></TD>
			<TD><? echo $row["product_model"]; ?></TD>
		    <TD><? echo $row["product_name"]; ?> </TD>
		    <TD><? echo $row["product_description"]; ?> </TD>
			<TD><? echo $row["status"]; ?> </TD>
			<TD><a id="lbDelete_<? echo $product_id; ?>_<? echo $row["product_id"]; ?>" href="#">Delete</a><br />
			</TR>
			<?
		}
		?></TABLE><?php
	}
	
	?><br />
	
	<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {

		$("a[id^=lbDelete]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var elements = id.split("_");

					$("#hv_product_id").val(elements[1]);
					$("#hv_accessory_id").val(elements[2]);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete this item?"))
					{
						$("#theForm").submit();	
					}
					return false;
				});
			});

		});
	</SCRIPT>
	
	
	<br /><?
	
	if ($accessories)
	{
		
		?> 
		<FORM id="theForm"  action="" method="post">
		Add Accessory to product: 
		<SELECT Name="Accessory_ID"> <?php
		while ($row = mysql_fetch_assoc($accessories)) // Generate DDL
		{
			?><OPTION VALUE="<?php echo $row["product_id"];?>"><?php echo $row["product_name"];?></OPTION> <?php 
		}
		?> </SELECT> 
		 
		<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="add">
		<INPUT TYPE="HIDDEN" id="hv_product_id" NAME="hProduct_ID" Value="<?php echo $product_id; ?>">
		<INPUT TYPE="HIDDEN" id="hv_accessory_id" NAME="hAccessory_ID" Value="<?php echo $product_id; ?>">
		 
		 <input type="submit"></input>
		 
		</FORM><?php
		}

	
	
}
?>


<? include $_SERVER['DOCUMENT_ROOT']."/php/Includes/Bottom.php" ?>