<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>
<? 

$DB = new conn();
$DB->connect();
$sql = "Select * from Products where product_type = 'Product' and status != 'deleted'";
?>

<?php 
$result = $DB->query($sql);

if ($result)
{
	?> <TABLE class="data">
		<th>product_id</th>
		<th>Product Model</th>
		<th>Product Name</th>
		<th style="width:350px">Product Description</th>
		<th>Status</th>
		<th style="width:150px">Commands</th>
		 <?
	while ($row = mysql_fetch_assoc($result)) {
		
		// Subquery for Accessories
		$sql2 = "select a.product_name from relProducts_Accessories r join products a on r.accessory_id = a.product_id where r.product_id = ".$row["product_id"];
		$acc = $DB->query($sql2);
	    ?>
		<TR><TD><? echo $row["product_id"]; ?></TD>
		<TD><? echo $row["product_model"]; ?></TD>
	    <TD><? echo $row["product_name"]; ?> </TD>
	    <TD><? echo $row["product_description"]; ?> 
	    
	    <?php
    		echo "<br /><br /><b>Accessories: </b>";
   
    		unset($accessories);
    		
	    	if ($acc) 
	    	{
	    		while ($_acc = mysql_fetch_assoc($acc))
	    		{
	    			$accessories[] = $_acc["product_name"];
	    		} 
	    		echo implode(", ", $accessories);
	    	}
	    	else { echo "None"; }
	    
	    ?>
	    
	    </TD>
		<TD><? echo $row["status"]; ?> </TD>
		<TD><a href="EditProduct.php?product_id=<?php echo $row["product_id"]; ?>">Edit</a><br />
		 <a href="AddInventory.php?product_id=<? echo $row["product_id"]; ?>">Add Inventory</a></TD>
		</TR>
		<?
	}
	?> </TABLE> <?
}


$sql = "Select * from Products where product_type = 'Accessory' and status != 'deleted'";

?><h2>Accessories</h2><?php 
$result = $DB->query($sql);

if ($result)
{
	?> <TABLE class="data">
		<th>product_id</th>
		<th>Product Model</th>
		<th>Product Name</th>
		<th style="width:350px">Product Description</th>
		<th>Status</th>
		<th style="width:150px">Commands</th>
		 <?
	while ($row = mysql_fetch_assoc($result)) {
	    ?>
		<TR><TD><? echo $row["product_id"]; ?></TD>
		<TD><? echo $row["product_model"]; ?></TD>
	    <TD><? echo $row["product_name"]; ?> </TD>
	    <TD><? echo $row["product_description"]; ?> </TD>
		<TD><? echo $row["status"]; ?> </TD>
		<TD><a href="#">Edit</a> | <a href="AddInventory.php?product_id=<? echo $row["product_id"]; ?>">Add Inventory</a></TD>
		</TR>
		<?
	}
	?> </TABLE> <?
}

$DB->close();  ?>


<a href="AddPRoduct.php">Add a New Product</a>


<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {

		$("a[id^=lbDelete]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var user_id = id.replace("lbDelete", "");
					$("#hv_user_id").val(user_id);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+user_id+"?"))
					{
						$("#theForm").submit();	
					}
					return false;
				});
			});

		});
</SCRIPT>



<FORM id="theForm"  action="" method="post">

<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
<INPUT TYPE="HIDDEN" id="hv_user_id" NAME="user_id" Value="0">


</FORM>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>