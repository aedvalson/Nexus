<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
?>
<?

$DB = new conn();
$DB->connect();


// Form Vars
	if ($_REQUEST)
	{
		if (isset($_REQUEST["Action"]) )
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
				
				$sql = "INSERT INTO products (product_type, product_name, product_model, product_description) VALUES ('".$ProductType."', '".$ProductName."', '".$ProductModel."', '".$ProductDescription."')";
				$DB->execute_nonquery($sql);

				$DB->addHistory( 'products', $_SESSION["user_id"],  "insert", "" );

				header("Location: ManageProducts.php");
			}
		}
	}


$DB->close();

?>

<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Products</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" id="custBulletLink">Add New Product</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
		<a href="#" id="lbSave">Save</a>
	</div>

	<div class="contentDiv">

		<div class="formDiv" style="display: block">
			<h1>Product Details</h1>


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
		</div>
	</div>
</div>



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>