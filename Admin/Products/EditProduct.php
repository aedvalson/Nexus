<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/"$ROOTPATH."/php/Includes/Top.php" 
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
		if ($action == "edit")
		{
			// ALL FORM INPUTS MUST BE SANITIZED

			$ProductName = $DB->sanitize($_REQUEST["ProductName"]);
			$ProductModel = $DB->sanitize($_REQUEST["ProductModel"]);
			$ProductDescription = $DB->sanitize($_REQUEST["ProductDescription"]);
			
			$sql = "UPDATE PRODUCTS SET product_name = '".$ProductName."', product_model = '".$ProductModel."', product_description = '".$ProductDescription."' where product_id = ".$product_id;
			$DB->execute_nonquery($sql);
			header("Location: ManageProducts.php");
		}
	}

	


	$sql = "select * from products where product_id = ".$product_id;
	$result = $DB->query($sql); 
	if (!$result )    // Validate the results.
	{
		die("Invalid Product ID");
	}
	while ($row = mysql_fetch_assoc($result)) // Do Stuff.
	{
		

		?>
		<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
		
		
	   <ul class="form">
		 <li id="ddlProductTypeLabel_li">
	                  <label for="r_ddlProductTypeLabel">Product Type:</label>
	                  <div id="ddlProductTypeLabel_img"></div>
					  <b><span style="margin-left:25px"><?php  echo $row["product_type"]; ?></span></b>
					  <input type="hidden" id="ddlProductTypeLabel_val" value="success">
	                  <div id="ddlProductTypeLabel_msg"></div>
	          </li>
		 <li class="validated" id="tbProductName_li">
	                  <label for="r_tbProductName">Product Name:</label>
	                  <div id="tbProductName_img"></div>
	                  <input class="validated" name="ProductName" id="tbProductName" type="text" maxlength="20" value="<?php  echo $row["product_name"]; ?>"  />
					  <input type="hidden" id="tbProductName_val" value="waiting">
	                  <div id="tbProductName_msg"></div>
	          </li>   
		 <li class="validated" id="tbProductModel_li">
	                  <label for="r_tbProductModel">Product Model:</label>
	                  <div id="tbProductModel_img"></div>
	                  <input class="validated" name="ProductModel" id="tbProductModel" type="text" maxlength="20" value="<?php  echo $row["product_model"]; ?>"  />
	                  <div id="tbProductModel_msg"></div>
	          </li>
		 <li class="validated" id="tbProductDescription_li">
	                  <label for="r_tbProductDescription">Product Description:</label>
	                  <div id="tbProductDescription_img"></div>
	                  <textarea class="validated" name="ProductDescription" id="tbProductDescription" type="text" maxlength="20" ><?php  echo $row["product_description"]; ?></textarea>
	                  <div id="tbProductDescription_msg"></div>
	          </li>
		 <li class="validated" id="btnSubmit_li">
	                  <label for="r_btnSubmit"></label>
	                  <div id="btnSubmit_img"></div>
	                  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"></input>
	                  <input type="hidden" name="Action" value="edit"></input>
	                  <input id="btnSubmit" type="Submit" maxlength="20" value="Submit"  />
	                  <div id="btnSubmit_msg"></div>
	          </li> 	          
       </ul>   
       
       </form>
       <?php 
	}
	
	
	
	
	
	$DB->close();
}


?>


<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>