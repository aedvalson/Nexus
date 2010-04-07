<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>

<?

	if ($_REQUEST)
	{
		if (isset($_REQUEST["Action"]))
		{
			if ($_REQUEST["Action"] == "insertCustomer")
			{
				$DB = new conn();
				$DB->connect();

				$firstname = $DB->sanitize($_REQUEST["FirstName"]);
				$lastname = $DB->sanitize($_REQUEST["LastName"]);
				$displayname = $firstname . " " . $lastname;
				$email = $DB->sanitize($_REQUEST["Email"]);
				$address = $DB->sanitize($_REQUEST["Address"]);
				$city = $DB->sanitize($_REQUEST["City"]);
				$state = $DB->sanitize($_REQUEST["State"]);
				$zipcode = $DB->sanitize($_REQUEST["ZipCode"]);
				$country = $DB->sanitize($_REQUEST["Country"]);
				$phone = $DB->sanitize($_REQUEST["Phone"]);
				$phonedetails = $DB->sanitize($_REQUEST["PhoneDetails"]);
				$notes = $DB->sanitize($_REQUEST["Notes"]);
				$contacttype = $DB->sanitize($_REQUEST["ContactType"]);
				$county = $DB->sanitize($_REQUEST["County"]);


				$newCustomer = $DB->addContact($firstname, $lastname, $displayname, $email, $address, $city, $state, $zipcode, $country, $phone, $phonedetails, $notes, $contacttype, $county);
			}
		}
	}

?>




<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Sales / Leads</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet" id="custBullet"><a href="#" id="custBulletLink">Customer Info</a></div>
		<div class="navBullet" id="equipmentBullet"><a href="#" id="equipmentBulletLink">Equipment</a></div>
		<div class="navBullet" id="paymentBullet"><a href="#" id="paymentBulletLink">Payment Info</a></div>
		<div class="navBullet" id="dealerBullet"><a href="#" id="dealerBullerLink">Dealer</a></div>
		<div class="navBullet" id="statusBullet"><a href="#" id="statusBulletLink">Other</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>






<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
		<a href="#" id="lbSave">Save</a>
	</div>

	<div class="contentDiv">


<!-- BEGIN CUSTOMER DIV -->

		<div class="formDiv" id="customerFormDiv"><h1>Customer Info</h1>

			<div class="commandBox">
				<h1>Customer Details</h1>
				<span class="label">Customer ID:</span><span class="value" id="spanCustomerID"></span>
				<span class="label">Contact Info:</span><span class="value contact" id="spanContactInfo"></span>
				<div style="clear:both"></div>
			</div>

			<div class="commandBox">
				<a href="#" id="lbCreateNewContact" onClick="displayAddContactForm(); return false;">Create New Contact</a> |
				<a href="#" id="lbSelectExistingContact" onClick="displaySelectContactForm(); return false;">Select Existing Contact</a>
			</div>

			<div class="formBoxDialog" id="fBoxCreateNewCustomer">
				<h1>Create New Customer Form</h1>
				<? $F = new FormElements(); ?>
				<form id="formCreateNew" method="post" action="">
				<ul class="form">
					<div style="float:left; width:260px; padding:15px">
						<? $F->ddlContactType(); ?>
						<? $F->tbVal("FirstName"); ?>
						<? $F->tbVal("LastName"); ?>
						<? $F->tbNotVal("Email"); ?>
						<? $F->tbNotVal("Phone"); ?>
						<? $F->tbNotVal("PhoneDetails"); ?>
						<? $F->tbContactNotes(); ?>

					</div>
					<div style="float:left; width:260px; padding:15px">
						<? $F->tbNotVal("Address"); ?>
						<? $F->tbNotVal("City"); ?>
						<? $F->ddlStates(); ?>
						<? $F->tbNotVal("ZipCode"); ?>
						<? $F->ddlGeneric("County", "County"); ?>
						<? $F->ddlCountries(); ?>
						<? $F->submitButton("Create Customer"); ?>						
					</div>
					<div style="clear:both">
					<input type="hidden" value="insertCustomer" name="Action">
					
				</ul>
				</form>
			</div>
			
			<div class="formBoxDialog" id="fBoxSelectExistingCustomer">
				<h1>Select Customer</h1>
				<form id="formFindExisting" method="post" action="">
					<ul class="form">	
						<div style="float:left; width:260px; padding:15px">
							<? $F->tbNotVal("Search_For") ?>
							<? $F->submitButton("Search_Customers"); ?>
						</div>
						<div style="float:left; width:330px; padding:15px" id="custSearchResults">
						</div>
						<div style="clear:both"></div>
					</ul>
				</form>
			</div>
			
			<div class="commandBox" style="text-align: right; padding: 12px; border:0px;" id="customerContinueDiv">
				<a href="#">Save and Continue</a> | <a href="#">Continue without Saving</a> | <a href="#">Save and Exit</a>
			</div>

		</div>

<!-- END CUSTOMER DIV -->


<!-- BEGIN ORDER STATUS DIV -->
		<div class="formDiv" id="statusFormDiv">
			<h1>Order Status</h1>
			<div class="commandBox">
				<h1>Order Details</h1>
				<span class="label">Order ID:</span><span class="value" id="spanOrderID"></span>
				<span class="label">Order Status:</span><span class="value"><? $F->ddlOrderStatuses($DB); ?></span>

				<div class="formOptionSet" id="orderCompleteOptions">
					<span class="label">Date Completed:</span>
					<span class="value"><input type="text" id="tbDateCompleted" class="datepicker"></input>
				</div>

				<div style="clear:both"></div>
				<ul class="form">
					
				</ul>
			</div>
		</div>
<!-- END ORDER STATUS DIV -->



<!-- BEGIN PAYMENT DIV -->
		<div class="formDiv" id="paymentFormDiv">
			<h1>Payment</h1>
			<div class="commandBox">
				<h1>Financing Info</h1>
					<span class="label">Sale Price:</span>
					<input type="text" class="value" id="tbSalePrice" value="0.00"/>

					<span class="label">Sales Tax:</span>
					<input type="text" disabled="disabled" class="value" id="tbSalesTax" />

					<span class="label">Net Price:</span>
					<input type="text" class="value" id="tbNetSale" />

					<span class="label">Reserve:</span>
					<input type="text" disabled="disabled" class="value" id="tbReserve" />


					<div class="spacer"></div>

					<div style="padding:15px;" id="divFinancingInfo">
						<p>No Methods added yet</p>
					</div>
			</div>

			<div id="changeFinancingDiv" class="commandBox">
				<h1>Change Financing</h1>
				<form id="formAddPayment">
					<ul class="form">
						<?
							$F->ddlPaymentType("ddlDealerPaymentType");
						?>
				</form>
						<div id="cashOptions" class="formOptionSet Visible">
							<form id="formCashOptions">
							<?
								$F->tbVal("cashAmount", "Dollar Amount");
								$F->submitButton("Add Financing Method");
							?>
							</form>
						</div>
						<div id="checkOptions" class="formOptionSet">
							<form id="formCheckOptions">
							<?
								$F->tbVal("checkAmount", "Dollar Amount");
								$F->tbVal("checkBank", "Issuing Bank");
								$F->tbVal("checkNumber", "Check Number");
								$F->tbVal("checkAccount", "Account Number");
								$F->tbVal("checkRouting", "Routing Number");
								$F->submitButton("Add Financing Method");
							?>
							</form>
						</div>
						<div id="creditOptions" class="formOptionSet">
							<form id="formCreditOptions">
							<?
								$F->tbVal("creditAmount", "Dollar Amount");
								$F->ddlCreditTypes();
								$F->tbVal("creditNumber", "Credit Card Number");
								$F->tbVal("creditCVC", "CVC Code");
								$F->creditExpiration();
								$F->tbVal("creditName", "Card Holder's Name");
								$F->submitButton("Add Financing Method");
							?>
							</form>
						</div>
						<div id="financeOptions" class="formOptionSet">
							<form id="formFinanceOptions">
							<?
								$F->ddlGeneric("FinanceOptions", "Finance Option");
								$F->tbVal("financeAmount", "Dollar Amount");
								$F->submitButton("Add Financing Method");
							?>
							<input type="hidden" id="hReserveRate" />
							</form>
						</div>
					</ul>
				</form>
			</div>
		</div>
<!-- END PAYMENT DIV -->



<!-- BEGIN DEALER DIV -->
		<div class="formDiv" id="dealerFormDiv">
		<h1>Dealer Information</h1>
			<div class="commandBox" id="dealerBox">
			<h1>Commission Structure</h1>
				
			</div>



			<div class="commandBox" id="editSplit" style="display:none;">
			<h1>Edit Split</h1>
				<ul class="form">
				<li style="padding-left:15px; width:100%;">

				<div id="divRowEditInfo" style="display:false">
					<span class="label">Row to Edit</span>
					<span class="value" id="spanEditIndex">Not Yet Set</span>
				</div>

				<div id="divUserInfo">
					<span class="label">users</span>
					<span class="value">Add Code to Update This please</span>
				</div>

				<span class="label">Amount</span>
				<input class="value" id="tbAmount" name="tbAmount"></input>

				<div class="spacer"></div>
				</li>

				<? $F->submitButton("Update", "btnUpdateSplit"); ?>
					
				<div id="divUserAdd">	
					
					<li style="padding-top:8px; padding-bottom:0px; "><h3 style="margin:auto 0px auto 0px;">Add User to Split</h3></li>
				
					<? $F->ddlStaff($DB, "Staff", "Select User"); ?>
					<? $F->submitButton("Add User", "btnAddUser"); ?>
				</div>

				<div id="divuserset">	
					<li style="padding-top:8px; padding-bottom:0px; "><h3 style="margin:auto 0px auto 0px;">Set User</h3></li>
					<? $F->ddlStaff($DB, "setStaff", "Select User"); ?>
					<? $F->submitButton("Set User", "btnSetUser"); ?>
				</div>
				</ul>

				<div class="spacer"></div>
			</div>


			<div class="commandBox" id="divSelectTemplate">
			<h1>Add Template Block</h1>	
				<ul class="form">
					<? $F->ddlCommissionTemplates($DB, 1100); ?>
					<? $F->submitButton("Add", "btnAddBlock"); ?>
				</ul>
			</div>



			<div class="commandBox" id="addUserDiv" style="background-color: #EDECDC">
			<h1>Add Custom Commission</h1>
				<div style="float:left; width: 43%">
				<ul class="form">
					<? $F->ddlPayeeTypes(); ?>

					<div id="dealerSelectDiv">
						<? $F->ddlDealerRoles(); ?>
					</div>


					<? $F->ddlCommPaymentTypes("ddlDealerPaymentType"); ?>
					<div id="flatRateDiv">
						<? $F->tbVal("Flat_Amount", "Flat Amount", "0.00"); ?>
					</div>

					<div id="percentageDiv" style="display:none;">
						<? $F->tbVal("Percentage", "Percentage", "0"); ?>
					</div>

					<? $F->submitButton("Submit", "btnAddLine"); ?>
				</ul>
				</div>

				<div style="float:left; width: 53%">
					<table class="data" id="dealerTable" style="margin-top:25px;">
						<thead><tr><th style="width: 18px"></th>
							<th>Dealer Role</th>
							<th>Amount</th>
							</tr><thead>
						<tbody><tr class="error">
								<td colspan="3">
									No Dealers added yet. At least one dealer must be added to complete this Template
								</td>
							</tr></tbody>
					</table>
				</div>
				<div style="clear:both;"></div>
			</div>


		</div>
<!-- END DEALER DIV -->





<!-- BEGIN EQUIPMENT DIV -->
		<div class="formDiv" id="equipmentFormDiv">
			<h1>Equipment</h1>
			<div class="commandBox">
				<h1>Current Equipment</h1>
				<div id="equipmentDiv" style="padding:15px;">No Equipment Added</div>
			</div>

			<div class="commandBox">
				<h1>Add Product</h1>
				<form id="formAddProduct" method="POST" action="">
					<ul class="form">
					<? 
						$DB = new conn();
						$F->ddlProducts($DB, "Product", "prodProdID");
						$F->ddlGeneric( "Serial", "Serial Number" );
						$F->submitButton("Add Product", "btnSubmitProduct");
					?>
					</ul>
				</form>
			</div>

			<div class="commandBox">
				<h1>Add Accessories</h1>
				<form id="formAddAccessory" method="POST" action="">
					<ul class="form">
					<? 
						$DB = new conn();
						$F->ddlProducts($DB, "Accessory", "accProdID", "Accessory");
						$F->tbVal("accQuantity", "Quantity");
						$F->submitButton("Add Accessory");
					?>
					</ul>
				</form>
			</div>
		</div>
<!-- END EQUIPMENT DIV -->

	</div>
</div>

<!--  Storage Area -->
<input type="hidden" name="hCounty" id="hCounty" value="">
<input type="hidden" name="hState" id="hState" value="">
<input type="hidden" name="hTaxRate" id="hTaxRate" value="">
<input type="hidden" name="hPageStatus" id="hPageStatus" value="">
<input type="hidden" name="hOrderId" id="hOrderId" value="">
<input type="hidden" name="hProductArray" id="hProductArray" value="">
<input type="hidden" name="hAccessoryArray" id="hAccessoryArray" value="">
<input type="hidden" name="hCommTemplatesArray" id="hCommTemplatesArray" value="">
<input type="hidden" name="hCommissionArray" id="hCommissionArray" value="">
<input type="hidden" name="hPaymentArray" id="hPaymentArray" value="">
<input type="hidden" name="contact_id" id="h_contact_id" value="<? 
	if (isset($newCustomer)) echo $newCustomer;
	else echo "Not Yet Set"
	?>">

<SCRIPT type="text/javascript">

	var pageStatus = $('#hPageStatus').val();
	var orderId = $('#hOrderId').val();
	var productArray = $('#hProductArray').val();


	// Update Form as soon as DOM is Ready
	$().ready(function() {
		setVisiblePaymentDiv($("#ddlDealerPaymentType").val());
		maybeDisplayDealers();
		$('#ddlPayeeType').change( function() { maybeDisplayDealers(); });
		$('#btnAddDealer').click( function() { addDealer(); });
		$('#tbFlat_Amount, #tbPercentage').change( function() { updateAmounts(); });
		$('.deleteDealer').live("click", function() {
			$(this).parents('tr:first').remove();
			checkDealers();
			return false;
		});
		$('#ddlDealerPaymentType, #tbAmount, #tbPercentage').change( function() { checkDealers(); });
		$('a').click(function() { fixHeight() });

		// If Order_ID is specified, populate Form

		var qs = new Querystring();
		var order_id = qs.get("order_id", "");
		if (order_id != "")
		{
			$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getOrderInfo",  value: order_id }, function(json) 
			{
				eval("var args = " + json);		
				if (args.success == "success")
				{
					if (args.output)
					{
						var o = args.output[0];
						var order_id = o.order_id;
						var order_status = o.order_status_id;
						var amount = o.amount;
						var contact_id = o.contact_id;
						var commStructure = o.CommStructure;
						var ProductsArray = o.ProductsArray;
						var AccessoriesArray = o.AccessoriesArray;
						var PaymentArray = o.PaymentArray;
						var dateCompleted = o.DateCompleted;
						if (dateCompleted != null)
						{
							var _date = dateCompleted.split(" ")[0];
							_date = replaceAll(_date, "-", "/");
							var myDate = new Date(_date);
							var month = myDate.getMonth() + 1;
							var prettyDate = month + '/' + myDate.getDate() + '/' + myDate.getFullYear();
							$('#tbDateCompleted').val(prettyDate);
						}

						$('#hOrderId').val(order_id);
						$('#ddlOrderStatuses').val(order_status);
						if ($('#h_contact_id').val() == "Not Yet Set")
							$('#h_contact_id').val(contact_id);
						$('#tbSalePrice').val(amount);
						$('#hCommissionArray').val(commStructure);
						$('#hProductArray').val(ProductsArray);
						$('#hAccessoryArray').val(AccessoriesArray);
						$('#hPaymentArray').val(PaymentArray);
						
//						updateSalesTax();
						if ($('#hCommissionArray').val() != null)
						{
							updateForm();
							updateSalesTax();
							buildDefaultCommTable(false);
						}
					}
				}
			});
		}

		bindSerialBox();
		bindCountyBox();
		bindFinanceBox();
		updateForm();
		pager();

		// Handlers
		$('#ddlprodProdID').change( function() {  bindSerialBox();  });
		$('#ddlState').change( function() {   bindCountyBox();   });
		$('#tbSalePrice').change( function() { updateSalesTax(); });
		$('#ddlFinanceOptions').change( function() { updateFinanceReserve(); });

	});


	function updateFinanceReserve()
	{
		var reserveRate = parseFloat( $('#ddlFinanceOptions').val().split('|||')[1] );
		$('#hReserveRate').val(reserveRate);
	}

	function bindFinanceBox()
	{
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getNewFinanceOptionsTable" }, function(json) 
		{
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#ddlFinanceOptions option').remove();

				if (args.output.length == 0)
				{
					$('#ddlFinanceOptions').append('<option value="">No Finance Options Added - Add some in Admin</option>');
					$('#ddlFinanceOptions').attr('disabled', 'disabled');
				}

				else
				{
					for (i = 0; i < args.output.length ; i++ )
					{
						$('#ddlFinanceOptions').append('<option value="' + args.output[i].id + '|||' + args.output[i].Reserve + '">' + args.output[i].CompanyName + ' (' + args.output[i].Reserve + '% Reserve)</option>');
					}
					$('#ddlFinanceOptions').removeAttr('disabled');
				}
				fixHeight();
			}
		});
	}

	function bindCountyBox()
	{
		var state = $('#ddlState').val();
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getCounties",  state: state }, function(json) 
		{
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#ddlCounty option').remove();

				if (args.output.length == 0)
				{
					$('#ddlCounty').append('<option value="">No Counties for ' + state + ' - Add some in Admin</option>');
					$('#ddlCounty').attr('disabled', 'disabled');
				}

				else
				{
					for (i = 0; i < args.output.length ; i++ )
					{
						$('#ddlCounty').append('<option value="' + args.output[i].county + '">' + args.output[i].county + '</option>');
					}
					$('#ddlCounty').removeAttr('disabled');
				}
				fixHeight();
			}
		});
	}

	function bindSerialBox()
	{
		var product_id = $('#ddlprodProdID').val();
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getSerials",  product_id: product_id }, function(json) 
		{
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#ddlSerial option').remove();
				if (args.output)
				{
					$('#ddlSerial option').remove();
					for (i = 0; i < args.output.length ; i++ )
					{
						$('#ddlSerial').append('<option value="' + args.output[i].serial + '">' + args.output[i].serial + '</option>');
					}
					$('#ddlSerial').removeAttr("disabled");
					$('#btnSubmitProduct').removeAttr("disabled");
				}
				else
				{
					$('#ddlSerial').append("<option>No Inventory Checked in</option>");
					$('#ddlSerial').attr("disabled", "disabled");
					$('#btnSubmitProduct').attr("disabled", "disabled");
				}
				fixHeight();
			}
		});		
	}


	function updateSalesTax()
	{
		var county = $('#hCounty').val();
		var state = $('#hState').val();
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getTaxRate",  state: state, county: county }, function(json) 
		{
			eval("var args = " + json);		
			if (args.success == "success")
			{
				var sale = parseFloat($('#tbSalePrice').val());
				if (args.output != 'undefined')
				{
					var tax =  sale * ( parseFloat(args.output) / 100);
				}
				else
				{
					var tax = 0;
				}

				var net = sale + tax;
				$('#tbSalesTax').val( formatCurrency(tax) );
				$('#tbNetSale').val( formatCurrency(net) );

				buildDefaultCommTable();
				fixHeight();
			}
		});
	}


	// Main function to display information based on hidden fields.
	function updateForm()
	{
		calculateReserves();

		var contact_id = $('#h_contact_id').val()
		var orderId = $('#hOrderId').val();
	
		// Populate CustomerID
		$('#spanCustomerID').html(contact_id);

		// Populate Order Status
		var $sOrderID = $('#spanOrderID');
		if (orderId != "")
		{
			$sOrderID.html(orderId);
		}
		else
		{
			$sOrderID.html('Order Not Yet Saved into Database');
		}
		

		// Hide Dialogs
		$('.formBoxDialog').css('display', 'none');

		// Populate Customer Info
		if (isNumeric(contact_id))
		{
			$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getContact",  value: contact_id }, function(json) 
			{
				eval("var args = " + json);		
				if (args.success == "success")
				{
					if (args.output)
					{
						var firstName = args.output[0]["contact_firstname"];
						var lastName = args.output[0]["contact_lastname"];
						var address = args.output[0]["contact_address"];
						var city = args.output[0]["contact_city"];
						var state = args.output[0]["contact_state"];
						var zip = args.output[0]["contact_zipcode"];
						var county = args.output[0]["county"];
	
						$('#hCounty').val(county);
						$('#hState').val(state);
						$('#spanContactInfo').html(firstName + " " + lastName + "<br>" + address + "<br>" + city + ", " + state + " " + zip);
					}
					fixHeight();
				}
			});
		}

	
		// Populate Commission Templates based on Price
		var salePrice = $('#tbSalePrice').val();
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getCommissionTemplates",  price: salePrice }, function(json) 
		{
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$ddlTemplates = $('#ddlcommTemplate');
				if (args.output.length > 0)
				{
					$('#hCommTemplatesArray').val(JSON.stringify(args.output));
					$('#ddlcommTemplate option').remove();
					for (var i = 0; i < args.output.length ; i++ )
					{
						$ddlTemplates.append("<option value='" + args.output[i]["templateID"] + "'>" + args.output[i]["templateName"] + "</option>");
						$ddlTemplates.removeAttr("disabled");
					}
					$('#btnAddBlock').unbind();
					$('#btnAddBlock').click( function() {
						var selectedElement = $('#ddlcommTemplate').val();
						$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getCommBlock", selected: selectedElement }, function(json)
						{
							eval("var args2 = " + json);
							if (args2.success == "success")
							{
								if (args.output)
								{								
									var _r = args2.output[0];
									AddCommLine(_r.payee_type, _r.payment_type, _r.amount, _r.dealers);
								}
							}
						});
					});
				}
				else
				{
					$('#ddlcommTemplate option').remove();
					$ddlTemplates.append("<option value='none'>No Commission Templates for this Price</option>");
					$ddlTemplates.attr("disabled", "true");
				}
				fixHeight();
			}
		});




		// Populate Equipment
		var append = false;

		if ($('#hProductArray').val() != "")
		{
			var theObject = JSON.parse($('#hProductArray').val());
			var text = "<h3 class=\"tableHeadline\">Products</h3><table class=\"data\"><tr><th>Product_ID</th><th>Product Name</th><th>Serial</th><th>Commands</th></tr>";
			var $equipmentDiv = $('#equipmentDiv');
			var row = 0;
			for (var i in theObject.products)
			{
				var prod = theObject.products[i];
				var quant = prod.Serial;
				var prod_id = prod.Product_ID;
				var prod_name = prod.Name;
				text = text + "<tr class=\"row" + row + "\"><td>" + prod_id + "</td><td>" + prod_name + "</td><td>" + quant + "</td><td></td></tr>";
				row = 1 - row;
			}	
			text = text + "</table>";
			$equipmentDiv.html(text);

			append = true;
		}

		if ($('#hAccessoryArray').val() != "")
		{
			var text = '';
			if (append == true)
			{
				text = $equipmentDiv.html();
			}
			var theObject = JSON.parse($('#hAccessoryArray').val());
			text = text + "<h3 class=\"tableHeadline\">Accessories</h3><table class=\"data\"><tr><th>Product_ID</th><th>Product Name</th><th>Quantity</th><th>Commands</th></tr>";
			var $equipmentDiv = $('#equipmentDiv');
			var row = 0;
			for (var i in theObject.products)
			{
				var method = theObject.products[i];
				var quant = method.Quantity;
				var prod_id = method.Product_ID;
				var prod_name = method.Name;
				text = text + "<tr class=\"row" + row + "\"><td>" + prod_id + "</td><td>" + prod_name + "</td><td>" + quant + "</td><td></td></tr>";

				row = 1 - row;
			}	
			text = text + "</table>";
			$equipmentDiv.html(text);
		}

		// Populate Financing
		if ($('#hPaymentArray').val() != "")
		{
			var $paymentDiv = $('#divFinancingInfo');

			var theObject = JSON.parse($('#hPaymentArray').val());
			text = "<h3 class=\"tableHeadline\">Payment Methods</h3><table class=\"data\"><tr><th>Payment Type</th><th>Amount</th><th>Details</th><th>Commands</th></tr>";

			var row = 0;
			for (var i in theObject.paymentMethods)
			{
				var prod = theObject.paymentMethods[i];
				var type = prod.paymentType;
				var amount = prod.amount;
				var details = "I'm not smart yet";
				var commands = "I can't do anything yet";
				text = text + "<tr class=\"row" + row + "\"><td>" + type + "</td><td>" + amount + "</td><td>" + details + "</td><td>" + commands + "</td></tr>";
				row = 1 - row;
			}	
			text = text + "</table>";
			$paymentDiv.html(text);
		}

		// Populate Status Div
		displayStatusOptions();

	}

	function AddNewCommLine()
	{
		var payeeType = $('#ddlPayeeType').val();
		var paymentType = $('#ddlDealerPaymentType').val();		
		if (paymentType == 'flat')
		{
			var amount = $('#tbFlat_Amount').val();
		}
		if (paymentType == 'percentage')
		{
			var amount = $('#tbPercentage').val();
		}

		var dealerObject = { dealers:[] };
		var i = 0;
		$('#dealerTable tr.dealer').each( function() {
			dealerObject.dealers[i] = $(this).children('#tdDealerText').html();
			i++;
		});
		
		AddCommLine(payeeType, paymentType, amount, JSON.stringify(dealerObject));
	}


	function AddCommLine(payeeType, paymentType, amount, dealersString)
	{

		// See if we already have a value in the field
		if ($('#hCommissionArray').val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($('#hCommissionArray').val());
		}
		else
		{
			// Create a new JSON object
			var theObject = {elements: []};	
		}

		var i = theObject.elements.length;

		// Create new Object to add to the array
		var thisElement = {};
		thisElement.Index = i;

		// Get Info and add it to object
		var dealersobj = JSON.parse(dealersString);
		thisElement.dealers = dealersobj.dealers;
		thisElement.payeeType = payeeType;
		thisElement.paymentType = paymentType;
		if (paymentType == 'flat')
		{
			thisElement.flatAmount = amount;
		}
		
		if (paymentType == 'percentage')
		{
			thisElement.percentage = amount;
		}
		
		theObject.elements[i] = thisElement;
		var storage = JSON.stringify(theObject);

		$('#hCommissionArray').val(storage);
		buildDefaultCommTable(false);
		return false;
	}

	function addDealer()
	{
		var Dealer = $('#ddlDealerRole').val();
		var DealerText = $('#ddlDealerRole option:selected').html();
		var Amount = 0;

		$('#dealerTable tbody tr.error').remove();

		$('#dealerTable tbody').append('<tr class="dealer"><td><a href="#" class="deleteDealer">' + deleteLinkContent() + '</a></td><td id="tdDealerText">' + DealerText + '</td><td class="amount">' + Amount + '</td></tr>');

		checkDealers();
	}

	function checkDealers()
	{
		if ($('#dealerTable tbody tr').length == 0)
		{
			$('#dealerTable tbody').append('<tr class="error"><td colspan="3">No Dealers added yet. At least one dealer must be added to complete this Template</td></tr>');
		}
		updateAmounts();
	}
	
	function maybeDisplayDealers()
	{
		$dealerElements = $('#dealerSelectDiv, #dealerTable');
		if ($('#ddlPayeeType').val() == 'employee')
		{
			$dealerElements.show();
		}
		else $dealerElements.hide();
	}

	function updateAmounts()
	{
		var amount = parseFloat($('#tbFlat_Amount').val());
		var percentage = parseFloat($('#tbPercentage').val());
		var dealers = $('#dealerTable tbody tr.dealer').length;
		var amountEach = amount / dealers;
		var percentEach = percentage / dealers;
		var paymentType = $('#ddlDealerPaymentType').val();

		if (paymentType == 'flat')
		{

			$('#dealerTable tbody tr.dealer').each( function() {
				$(this).children("td.amount").html(formatCurrency(amountEach));
			});
		}
		else if (paymentType == 'percentage')
		{
			$('#dealerTable tbody tr.dealer').each( function() {
				$(this).children("td.amount").html(percentEach.toFixed(3) + "%");
			});
		}
	}

	function addUserToComm(index, username, userid)
	{
	
		var comm = JSON.parse($('#hCommissionArray').val());
		var Commission = comm.elements;

		for (var i=0; i<Commission.length ; i++ )
		{
			if (index == Commission[i].Index)
			{
				currentusers = Commission[i].users;
				if (currentusers == undefined)
				{
					currentusers = {users: []};
					Commission[i].users = currentusers;
				}
				else
				{
					currentusers.users = Commission[i].users;
				}
				var addedUser = {};
				addedUser["username"] = username;
				addedUser["user_id"] = userid; 

				currentusers.users[currentusers.users.length] = addedUser;
				Commission[i].users = currentusers.users;

				comm.elements = Commission;
				$('#hCommissionArray').val(JSON.stringify(comm));
				buildDefaultCommTable(false, index);
			}
		}
		
		return false;
	}

	
	function deleteCommissionElement(index)
	{
//		alert('deleting ' + index);
		var comm = JSON.parse($('#hCommissionArray').val());
//		delete comm.elements[index];

		for (var j in comm.elements)
		{
			if (comm.elements[j].Index == index)
			{
				delete comm.elements[j];
			}
		}


		// Clear Null nodes and rewrite products array
		var j = 0;
		var newElements = [];
		for (var k in comm.elements)
		{
			if (comm.elements[k] != null)
			{
				var newElement = comm.elements[k];
				newElements[newElements.length] = newElement;
			}
		}
		comm.elements = newElements;
		$('#hCommissionArray').val(JSON.stringify(comm));
//		alert(JSON.stringify(comm));
		buildDefaultCommTable(false);
	}


	function editSplit(index)
	{
		var comm = JSON.parse($('#hCommissionArray').val());
		var Commission = comm.elements;

		$('#spanEditIndex').html(index);



//			alert(JSON.stringify(comm));
		
		var payeeType = Commission[index].payeeType;
		var paymentType = Commission[index].paymentType;

		if (payeeType == 'split')
		{
			$('#divUserAdd').css("display", "block");
			$('#divuserset').css("display", "none");
			$('#divUserInfo').css("display", "block");
		}
		else if (payeeType == 'employee')
		{
			$('#divUserAdd').css("display", "none");
			$('#divuserset').css("display", "block");
			$('#divUserInfo').css("display", "block");
		}
		else if (payeeType == 'corporate')
		{
			$('#divUserAdd').css("display", "none");
			$('#divuserset').css("display", "none");
			$('#divUserInfo').css("display", "none");
		}

		var amount='';
		if (paymentType == 'flat')
		{
			amount = Commission[index].flatAmount;
		}

		$('#btnUpdateSplit').unbind();
		$('#btnUpdateSplit').click( function() {
			var newAmount = $('#tbAmount').val();
			if (!isNumeric(newAmount))
			{
				alert("Not Numeric");
				return false;
			}
			else
			{
				comm.elements[index].flatAmount = newAmount;
				$('#hCommissionArray').val(JSON.stringify(comm));
				buildDefaultCommTable(false, index);
				$('#tableCommStructure tr').removeClass('editSelected');
				$('#editSplit').css("display", "none");
				return false;
			}
		});


		$('#btnAddUser').unbind();
		$('#btnAddUser').click( function() {
			var username = $('#ddlStaff option:selected').text()
			var userid = $('#ddlStaff').val();
			addUserToComm(index, username, userid);
		});


		$('#btnSetUser').unbind();
		$('#btnSetUser').click( function() {
			delete comm.elements[index].users;
			$('#hCommissionArray').val(JSON.stringify(comm));
			var username = $('#ddlsetStaff option:selected').text()
			var userid = $('#ddlsetStaff').val();
			addUserToComm(index, username, userid);
		});


		$('#tbAmount').val(amount);
		$('#editSplit').css("display", "block");

		return false;


	}

	function buildDefaultCommTable(def, edit)
	{
		var templateID = $('#ddlcommTemplate').val();

				if (def)
				{
					var commTemplates = JSON.parse($('#hCommTemplatesArray').val());
					//alert(JSON.stringify(commTemplates));
					for (var i=0; i<commTemplates.length ; i++ )
					{
						var index = commTemplates[i].templateID;
						if (index == templateID)
						{
							var elements = JSON.parse(commTemplates[i].template_elements).elements;
							$('#hCommissionArray').val(commTemplates[i].template_elements);
						}
					}

				}
				else
				{
					var elements = JSON.parse($('#hCommissionArray').val()).elements;
				}
				
				var div = $('#dealerBox');

				var text = "<table style=\"margin-bottom: 0px\" id='tableCommStructure' class='data'><thead><th>Commands</th><th>Payee</th><th>Payment Type</th><th>Amount</th><th>Actual</th><th>Remaining</th></thead><tbody>";
				var row = 0;
				var SalePrice = parseFloat($('#tbSalePrice').val().replace("$", "").replace(",",""));
				var reserve = parseFloat($('#tbReserve').val().replace("$", "").replace(",",""));
				var remaining = SalePrice - reserve;
				var price = remaining;

				var defaultEditRow = '';
				if (edit != undefined)
				{
					defaultEditRow = edit;
				}
				
				

				for (var j=0; j<elements.length ; j++ )
				{

					data = elements[j];
					var dealerText = "";
					if (defaultEditRow == '')
					{
						if (data.payeeType != 'corporate')
						{
							defaultEditRow = data.Index;
						}
					}


					var c1 = data.payeeType;					
					if (data.payeeType == 'employee')
					{
						if (data.dealers)
						{
							var dealers = data.dealers;

							for (var i = 0; i < dealers.length; i++)
							{
								dealerText = dealerText + " - " + dealers[i] + "<br />";
							}						
							c1 = dealerText;
						}
					}
											

					var c2 = data.paymentType;
					
					if (c2 == 'flat')
					{
						var rowAmount = data.flatAmount;
						var c3 = formatCurrency(rowAmount);
						var c4 = c3;
					}
					else if (c2 == 'percentage')
					{
						
						var c3 = data.percentage + "%";
						var rowAmount = price * data.percentage / 100;
						var c4 = formatCurrency(rowAmount);
					}
					else 
					{
						var rowAmount = remaining;
						var c3 = formatCurrency(remaining);
						var c4 = formatCurrency(remaining);
					}

					remaining = remaining - rowAmount;


					text = text + "<tr class='row"+row+" clickable' id=\"row"+data.Index+"\">";
					text = text + "<td><!--<a class=\"linkList\" href=\"#\" id=\"edit"+data.Index+"\">Edit</a>--><a class=\"linkList\" href=\"#\" id=\"delete"+data.Index+"\">Delete</a>";
					text = text + "<td>"+c1+"</td><td>"+c2+"</td><td>"+c3+"</td><td>"+c4+"</td><td>"+formatCurrency(remaining)+"</td>";

					text = text + "</tr>";

					if (data.users != undefined)
					{
						for (var k=0; k < data.users.length ;k++ )
						{
							var username = data.users[k].username;
							var userAmount = rowAmount / data.users.length;
							
							text = text + "<tr class=\"row"+row+"\"><td></td><td style=\"padding-left:1em\"> - "+username+"</td><td style=\"padding-left:1em\"> @ "+formatCurrency(userAmount)+"</td><td></td><td></td><td></td></tr>"
						}
					}


					row = 1-row;
				}
				text = text + "</tbody><tfoot><tr class='row"+row+"'>";
				text = text + "<td colspan='5'><span style='float:right'>TOTAL REMAINING:</span></td><td><b>" + formatCurrency(remaining) + "</b></td>";
				text = text + "</tr></tfoot>";
				text = text + "</table>";

				//text = text + "<a href='#' id='lbAddItem'>Add</a>";

				div.html(text);


				if (defaultEditRow != '')
				{
					var $tr = $('#tableCommStructure #row' + defaultEditRow);
					//$tr.addClass('editSelected');
					//editSplit(defaultEditRow);
				}
 

					

				$('a[id^=edit]').unbind();
				$('a[id^=edit]').click( function() {
					editSplit(this.id.replace('edit', ''));
					return false;
				});

				$('a[id^=delete]').unbind();
				$('a[id^=delete]').click( function() {
					var id = this.id.replace('delete', '');
					deleteCommissionElement(id);
					return false;
				});

				//$('#tableCommStructure tr.clickable').click( function() {
				//	$('#tableCommStructure tr').removeClass('editSelected');
				//	$(this).addClass('editSelected');
				//	editSplit(this.id.replace('row', ''));
				//});
			}



	function setVisiblePaymentDiv(value)
	{
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
	}


	function pager()
	{
		// Open appropriate form divs, based on pageStatus
		if (pageStatus == "" || !pageStatus)
		{
			pageStatus = "customerInfo";
			$('#hPageStatus').val("customerInfo");
		}
		$('.navBullet').removeClass('navBulletSelected');
		$('.formDiv').css('display', 'none');

		$('.formOptionSet').css('display', 'none');
		$('#' + $PaymentTypeDDL.val() + 'Options').css('display', 'block');

		if (pageStatus == "customerInfo")
		{
			$('#custBullet').addClass('navBulletSelected');
			$('#customerFormDiv').css('display', 'block');
		}

		if (pageStatus == "equipment")
		{
			$('#equipmentBullet').addClass('navBulletSelected');
			$('#equipmentFormDiv').css('display', 'block');
		}

		if (pageStatus == "status")
		{
			$('#statusBullet').addClass('navBulletSelected');
			$('#statusFormDiv').css('display', 'block');
		}
		if (pageStatus == "payment")
		{
			$('#paymentBullet').addClass('navBulletSelected');
			$('#paymentFormDiv').css('display', 'block');
		}
		if (pageStatus == "dealer")
		{
			$('#dealerBullet').addClass('navBulletSelected');
			$('#dealerFormDiv').css('display', 'block');
			updateForm();
		}
		fixHeight();
	}

	// Ajax function to save Order to DB
	function saveOrder()
	{
		var orderId = $('#hOrderId').val();
		var customerID = $('#h_contact_id').val();

		if (orderId == "")
		{
			alert("Do you wish to add this order to the database?");
		}
		else
		{
			if (!confirm("Do you wish to save your changes to order #" + orderId + " to the database?"))
			{
				return false;
				//alert("Add Code to EDIT ORDER #" + orderId + " here.");
			}
			
		}

		var orderStatus = $('#ddlOrderStatuses').val();
		var customer_id = $('#h_contact_id').val();
		var amount = $('#tbSalePrice').val();
		var CommStructureString = $('#hCommissionArray').val();
		var ProductsString = $('#hProductArray').val();
		var AccessoriesString = $('#hAccessoryArray').val();
		var PaymentString = $('#hPaymentArray').val();
		var dateCompleted = $('#tbDateCompleted').val();
		var user_id = "<?= $_SESSION["user_id"] ?>";


		if (!IsNumeric(customer_id))
		{
			customer_id = 0;
		}

		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { 
				id: "submitOrder",
				order_id: orderId,
				orderStatus: orderStatus,
				customer_id: customer_id,
				amount: amount,
				CommStructureString: CommStructureString,
				ProductsString: ProductsString,
				AccessoriesString: AccessoriesString,
				PaymentString: PaymentString,
				user_id: user_id,
				dateCompleted: dateCompleted
			}, function(json)
		{
			eval("var args = " + json);
			if (args.success == "success")
			{
				if (args.output)
				{
					var order_id = args.output;
				}
				else
				{
					var order_id = null;
				}
				$('#hOrderId').val(order_id);
				alert("Order #" + order_id + " was successfully saved to the Database");
				updateForm();
			}
		});			


		//var query2 = 'insert into 




	}


	// Customer Panel Scripts
	$('#formFindExisting').submit( function() {
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "searchContacts", value: $('#tbSearch_For').val() }, function(json)
		{
			eval("var args = " + json);
			if (args.success == "success")
			{
				if (args.output)
				{
					$('#custSearchResults').html('');
					for (r in args.output)
					{
						var _r = args.output[r];
						$('#custSearchResults').append("<div id='custRowDiv_"+_r["contact_id"]+"' class='detailsRow'>" + _r["contact_firstname"] + " " + _r["contact_lastname"] + " | <a href='#' id='selectCustomer_"+_r["contact_id"]+"'>Select</a> </div>");
					}
					$('a[id^=selectCustomer_]').click( function() {
						var contact_id = this.id.replace('selectCustomer_', '');
						$('#h_contact_id').val(contact_id);
						updateForm();
					});
				}
			}
		});
		return false;
	});


	// Finance Panel Scripts
	var $PaymentTypeDDL = $('#ddlPaymentType');
	$PaymentTypeDDL.change( function() {
		$('.formOptionSet').css('display', 'none');
		$('#' + $PaymentTypeDDL.val() + 'Options').css('display', 'block');
	});
	var $addPaymentForm = $('#formCheckOptions, #formCreditOptions, #formCashOptions, #formFinanceOptions');
	$addPaymentForm.submit( function() {
		var $paymentArrayHiddenField = $('#hPaymentArray');

		if (validateForm(this) == true)
		{
			// See if we already have a value in the field
			if ($paymentArrayHiddenField.val() != '')
			{
				// Read the existing object
				var theObject = JSON.parse($paymentArrayHiddenField.val());
			}
			else
			{
				// Create a new JSON object
				var theObject = {paymentMethods: []};	
			}


			var i = theObject.paymentMethods.length;

			// Create new Object to add to the array
			var thisMethod = {};
			thisMethod.Index = i;

			// Get the Payment Method and add it to object
			thisMethod.paymentType = $PaymentTypeDDL.val();

			// Get values based on Payment Type and add them.
			if (thisMethod.paymentType == 'check')
			{
				thisMethod.amount = $('#tbcheckAmount').val();
				thisMethod.checkBank = $('#tbcheckBank').val();
				thisMethod.checkNumber = $('#tbcheckNumber').val();
				thisMethod.checkAccountNumber = $('tbcheckAccount').val();
				thisMethod.checkRoutingNumber = $('#tbcheckRouting').val();
			}

			if (thisMethod.paymentType == 'cash')
			{
				thisMethod.amount = $('#tbcashAmount').val();
			}

			if (thisMethod.paymentType == 'credit')
			{
				thisMethod.amount = $('#tbcreditAmount').val();
				thisMethod.creditType = $('#tbcreditType').val();
				thisMethod.creditNumber = $('#tbcreditNumber').val();
				thisMethod.creditCVC = $('#tbcreditCVC').val();
				thisMethod.creditExpMonth = $('#tbcreditExpMonth').val();
				thisMethod.creditExpYear = $('#tbcreditExpYear').val();
				thisMethod.creditCardHolder = $('#tbcreditName').val();
			}

			if (thisMethod.paymentType == 'finance')
			{
				thisMethod.amount = $('#tbfinanceAmount').val();
				thisMethod.financeOption = $('#ddlFinanceOptions').val().split("|||")[0];
				thisMethod.reserveRate = $('#ddlFinanceOptions').val().split("|||")[1];
			}
			theObject.paymentMethods[i] = thisMethod;
			var storage = JSON.stringify(theObject);

			$paymentArrayHiddenField.val(storage);
			calculateReserves();
			updateForm();
			return false;
		}
		
		return false;

	});


	function calculateReserves()
	{
		var $paymentArrayHiddenField = $('#hPaymentArray');
		if ($paymentArrayHiddenField.val() != "")
		{
			var theObject = JSON.parse($paymentArrayHiddenField.val());
			var paymentMethods = theObject.paymentMethods;
			var fullReserve = 0;

			for (var method in paymentMethods)
			{
				if(paymentMethods[method].paymentType == 'finance')
				{
					var rate = paymentMethods[method].reserveRate / 100;
					var amount = paymentMethods[method].amount;
					var reserve = rate * amount;
					fullReserve = fullReserve + reserve;
				}
				$('#tbReserve').val(formatCurrency(fullReserve));
			}
		}
	}


	// Equipment Panel Scripts
	var $addProductLink = $('#formAddProduct, #formAddAccessory');
	$addProductLink.submit( function() {

		if (this.id == 'formAddProduct')
		{
			var $prodArrayHiddenField = $('#hProductArray');
			var $dropdown = $('#ddlprodProdID');
			var $serial = $('#ddlSerial');
			var $quantity = $('#tbprodQuantity');
			var $nameOption = $('#ddlprodProdID option:selected');
		}
		if (this.id == 'formAddAccessory')
		{
			var $prodArrayHiddenField = $('#hAccessoryArray')
			var $dropdown = $('#ddlaccProdID');
			var $quantity = $('#tbaccQuantity');
			var $nameOption = $('#ddlaccProdID option:selected');
		}

		if (validateForm(this) == true)
		{
	// See if we already have a value in the field
			if ($prodArrayHiddenField.val() != '')
			{
				// Read the existing object
				var theObject = JSON.parse($prodArrayHiddenField.val());
			}
			else
			{
				// Create a new JSON object
				var theObject = {products: []};	
			}
			
			var i = theObject.products.length;

			// Create new object for product
			var thisProduct = {};
			thisProduct.Index = i;
			var prodID = $dropdown.val();
			thisProduct.Product_ID = prodID;
			thisProduct.Serial = $serial.val();
			thisProduct.Name = $nameOption.text();


			for (var product in theObject.products)
			{
				// Check to see if product_id is already in array
				if (prodID == theObject.products[product].Product_ID)
				{
					// Delete the Old one (not deleting anymore since we use serials)
					//delete theObject.products[product];
				}
			}

			// Add created product to Object
			theObject.products[i] = thisProduct;


			// Clear Null nodes and rewrite products array
			var j = 0;
			var newObjects = [];
			for (var i in theObject.products)
			{
				if (theObject.products[i] != null)
				{
					var newProduct = {};
					newProduct.Index = theObject.products[i].Index;
					newProduct.Product_ID = theObject.products[i].Product_ID;
					newProduct.Serial = theObject.products[i].Serial;
					newProduct.Name = theObject.products[i].Name;
					
					newObjects[newObjects.length] = newProduct;
				}
			}
			theObject.products = newObjects;


			// Store object back in Hidden Variable
			var storage = JSON.stringify(theObject);
			$prodArrayHiddenField.val(storage);

			// Update Form and Return false to prevent postback.
			updateForm();
		}
		return false;
	});


	// Order Status Scripts
	function displayStatusOptions()
	{
		$('.formOptionSet').css("display", "none");
		if ($('#ddlOrderStatuses').val() == 5)
		{
			$('#orderCompleteOptions').css("display", "block");
			if ($('#tbDateCompleted').val() == "")
			{
				var myDate = new Date();
				var month = myDate.getMonth() + 1;
				var prettyDate = month + '/' + myDate.getDate() + '/' + myDate.getFullYear();
				$("#tbDateCompleted").val(prettyDate);	
			}
		}
	}
		


	// Listeners



	$('#ddlOrderStatuses').change( function() {

		displayStatusOptions();

	});

	$("#ddlDealerPaymentType").change(function () {
		var value = $(this).val();
		setVisiblePaymentDiv(value);
	});

	$("#btnAddLine").click( function() {
		AddNewCommLine();
	});


	$('#custBullet, #custBulletLink').click( function() {
		pageStatus = "customerInfo";
		$('#hPageStatus').val(pageStatus);
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "doNothing" }, function(json) {} );
		pager();
	});

	$('#equipmentBullet, #equipmentBulletLink').click( function() {
		pageStatus = 'equipment';
		$('#hPageStatus').val(pageStatus);
		pager();
	});

	$('#statusBullet, #statusBulletLink').click( function() {
		pageStatus = 'status';
		$('#hPageStatus').val(pageStatus);
		pager();
	});

	$('#paymentBullet, #paymentBulletLink').click( function() {
		pageStatus = 'payment';
		$('#hPageStatus').val(pageStatus);
		updateSalesTax();
		pager();
	});

	$('#dealerBullet, #dealerBulletLink').click( function() {
		pageStatus = 'dealer';
		$('#hPageStatus').val(pageStatus);
		updateSalesTax();
		pager();
	});

	$('#lbSave').click( function() {
		saveOrder()
	});

	

	// Events, assigned to Listeners or Elements
	function displayAddContactForm()
	{
		$('.formBoxDialog').css('display', 'none');
		$('#fBoxCreateNewCustomer').css('display', 'block');
	}

	function displaySelectContactForm()
	{
		$('.formBoxDialog').css('display', 'none');
		$('#fBoxSelectExistingCustomer').css('display', 'block');
	}

	// Goodbye function - Offers to save Data to database
	function goodbye(e) {
		if(!e) e = window.event;
		//e.cancelBubble is supported by IE - this will kill the bubbling process.
		e.cancelBubble = true;
		e.returnValue = 'You have not yet saved your data. I am not yet smart enough to save your data. Do you still wish to leave the page?'; //This is displayed on the dialog

		//e.stopPropagation works in Firefox.
		if (e.stopPropagation) {
			e.stopPropagation();
			e.preventDefault();
		}
	}
	window.onbeforeunload=goodbye;


</SCRIPT>




<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>