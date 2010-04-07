<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>

<?

$DB = new conn();
$DB->connect();


// Form Vars
	if ($_REQUEST)
	{
		if ($_REQUEST["Action"] )
		{
			$action = $_REQUEST["Action"];
			//echo $action;
			if ($action == "addNew")
			{
				// ALL FORM INPUTS MUST BE SANITIZED

				$ProductType = $DB->sanitize($_REQUEST["ProductType"]);
				$ProductName = $DB->sanitize($_REQUEST["ProductName"]);
				$ProductModel = $DB->sanitize($_REQUEST["ProductModel"]);
				$ProductDescription = $DB->sanitize($_REQUEST["ProductDescription"]);
				
				$sql = "INSERT INTO PRODUCTS (product_type, product_name, product_model, product_description) VALUES ('".$ProductType."', '".$ProductName."', '".$ProductModel."', '".$ProductDescription."')";
				$DB->execute_nonquery($sql);
				header("Location: ManageProducts.php");
			}
		}
	}


$DB->close();

?>



<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

   <ul class="form">
	 <li class="validated" id="ddlProductType_li">
                  <label for="r_ddlProductType">Product Type:</label>
                  <div id="ddlProductType_img"></div>
				  <select class="validated" name="ProductType" id="ddlProductType">
					<option Value="Product">Product</option>
					<option Value="Accessory">Accessory</option>
				  </select>
				  <input type="hidden" id="ddlProductType_val" value="waiting">
                  <div id="ddlProductType_msg"></div>
          </li>   
	 <li class="validated" id="tbProductName_li">
                  <label for="r_tbProductName">Product Name:</label>
                  <div id="tbProductName_img"></div>
                  <input class="validated" name="ProductName" id="tbProductName" type="text" maxlength="20" value=""  />
				  <input type="hidden" id="tbProductName_val" value="waiting">
                  <div id="tbProductName_msg"></div>
          </li>   
	 <li class="validated" id="tbProductModel_li">
                  <label for="r_tbProductModel">Product Model:</label>
                  <div id="tbProductModel_img"></div>
                  <input class="validated" name="ProductModel" id="tbProductModel" type="text" maxlength="20"  />
                  <div id="tbProductModel_msg"></div>
          </li>
	 <li class="validated" id="tbProductDescription_li">
                  <label for="r_tbProductDescription">Product Description:</label>
                  <div id="tbProductDescription_img"></div>
                  <textarea class="validated" name="ProductDescription" id="tbProductDescription" type="text" maxlength="20" ></textarea>
                  <div id="tbProductDescription_msg"></div>
          </li>
	 <li class="validated" id="btnSubmit_li">
                  <label for="r_btnSubmit"></label>
                  <div id="btnSubmit_img"></div>
                  <input type="hidden" name="Action" value="addNew"></input>
                  <input id="btnSubmit" type="Submit" maxlength="20" value="Submit"  />
                  <div id="btnSubmit_msg"></div>
          </li>          
   </ul>


</form>



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>