<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>

<?

	$DB = new conn();
	$DB->connect();

	if ($_REQUEST)
	{
		if (isset($_REQUEST["order_id"]))
		{
			$id = $DB->sanitize($_REQUEST["order_id"]);
			$sql = "SELECT * FROM orders WHERE order_id = '".$id."'";
			$firephp->log($sql);

			$result = $DB->query($sql);
			$firephp->log($result);
			if ($result) {
				$order = mysql_fetch_assoc($result);
				$firephp->log($order);
			}
		}

	}

?>
<? $F = new FormElements(); ?>
<div style="display: none">
<?= $F->ddlDealerRoles(false, "baseDealers"); ?>
</div>


<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Sales / Leads</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet" id="custBullet"><a href="#" id="custBulletLink">Customer Info</a></div>
		<div class="navBullet" id="equipmentBullet"><a href="#" id="equipmentBulletLink">Equipment</a></div>
		<div class="navBullet" id="paymentBullet"><a href="#" id="paymentBulletLink">Payment Info</a></div>
		<div class="navBullet" id="rolesBullet"><a href="#" id="rolesBulletLink">Dealers</a></div>
		<div class="navBullet" id="dealerBullet"><a href="#" id="dealerBulletLink">Commissions</a></div>
		<div class="navBullet" id="statusBullet"><a href="#" id="statusBulletLink">Other</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>






<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv" style="text-align: right">
						<input type="button" class="saveAndContinueLink" value="Save and Continue" /> <input type="button" class="continueLink" value="Continue without Saving" /> <input type="button" class="saveAndQuitLink" value="Save and Exit" />
	</div>

	<div class="contentDiv">


<!-- BEGIN CUSTOMER DIV -->

		<div class="formDiv" id="customerFormDiv"><h1>Customer Info</h1>

			<div class="commandBox">
				<h1>Customer Details</h1>
				<?
				$errordisplay = $order["contact_id"] ? "none" : "block";
				$divdisplay   = $order["contact_id"] ? "block" : "none";
				?>
				<div id="divCustomerNotSet" style="padding: 1em; display: <?= $errordisplay ?>;">
					No Customer Set yet.
				</div>
				<div id="divCustomerSet" style="display: <?= $divdisplay ?>;">
					<span class="label">Customer ID:</span><span class="value" id="spanCustomerID"></span>
					<span class="label">Contact Info:</span><span class="value contact" id="spanContactInfo"></span>
					<div style="clear:both"></div>
				</div>
				<div style="padding: 1em">
					<a href="#" id="lbCreateNewContact" onClick="displayAddContactForm(); return false;">Create New Customer</a> |
					<a href="#" id="lbSelectExistingContact" onClick="displaySelectContactForm(); return false;">Select Existing Contact</a>
				</div>
			</div>

			<div class="commandBox">
				<h1>Co-Buyer Details</h1>
				<?
				$errordisplay = $order["cobuyer_id"] ? "none" : "block";
				$divdisplay   = $order["cobuyer_id"] ? "block" : "none";
				?>
				<div id="divCobuyerNotSet" style="padding: 1em; display: <?= $errordisplay ?>;">
					No Cobuyer Set
				</div>
				<div id="divCobuyerSet" style="display: <?= $divdisplay ?>;">
					<span class="label">Customer ID:</span><span class="value" id="spanCobuyerID"></span>
					<span class="label">Contact Info:</span><span class="value contact" id="spanCobuyerInfo"></span>
					<div style="clear:both"></div>
				</div>

				<div style="padding: 1em">
					<a href="#" id="lbCreateNewCobuyer" onClick="displayAddContactForm('cobuyer'); return false;">Create New Cobuyer</a> |
					<a href="#" id="lbSelectExistingCobuyer" onClick="displaySelectContactForm('cobuyer'); return false;">Select Existing Contact</a>
				</div>
			</div>


			<div class="formBoxDialog" id="fBoxCreateNewCustomer" style="background-color: #EDECDC">
				<h1>Create New Customer Form</h1>
				<form id="formCreateNew" method="post" action="">
				<h1 style="background-color:silver; color:#365181; font-size:1.1em;margin:2em 0 0.5em 1.25em; padding:2px 0 2px 5px; width:350px;">General Information</h1>
						<ul class="form">
							<? $F->ddlContactType(); ?>

							<? $F->tbVal("FirstName", "First Name", "", "float:left;"); ?>
							<? $F->tbVal("LastName", "Last Name", "", "float:left; padding-left:0"); ?>			

							<? $F->tbNotVal("Email", "Email", "",  "float:left; clear:both;"); ?>
							<? $F->tbNotVal("Phone", "Phone", "",  "float:left; padding-left:0"); ?>
							<? $F->tbNotVal("PhoneDetails", "Phone Details", "",  "float:left; padding-left:0"); ?>

							<? $F->tbNotVal("license", "Driver's License", "", "float:left;  clear:both;"); ?>
							<? $F->ddlStates("AL", "licenseState", "Driver's License Issuing State", "float:left; padding-left:0"); ?>
						</ul>
						<div style="clear:both"></div>


						<h1 style="clear:both; background-color:silver; color:#365181; font-size:1.1em;margin:2em 0 0.5em 1.25em; padding:2px 0 2px 5px; width:350px;">Address</h1>

						<ul class="form" >

						<? $F->ddlHomeType("float:left;"); ?>
						<? $F->ddlHomeStatus("float: left; padding-left:0;"); ?>
						<? $F->tbContactNotes("float: left; padding-left:0;"); ?>

						
						<? $F->tbNotVal("Address", "Address", "", "float:left; clear:both;"); ?>
						<? $F->tbNotVal("Address2", "Address Line 2", "", "float:left; padding-left:0"); ?>

						<? $F->tbNotVal("City", "City", "", "float:left; clear:both; "); ?>
						<? $F->ddlStates("FL", "State", "State", "float:left; padding-left:0"); ?>
						<? $F->tbNotVal("ZipCode", "Zip Code", "", "float:left; padding-left:0"); ?>

						<? $F->ddlGeneric("County", "County", "float:left; clear:both;"); ?>
						<? $F->ddlCountries("Country", "Country", "float:left; padding-left:0"); ?>

						</ul>

						<br />
						<a href="#" style="display: block; clear:both; margin-left:30px" onclick="$('#buyerAlternateDiv').show(); return false;">Add Alternate Address</a>

						<div id="buyerAlternateDiv" style="display: none">
						<h1 style="clear:both; background-color:silver; color:#365181; font-size:1.1em;margin:2em 0 0.5em 1.25em; padding:2px 0 2px 5px; width:350px;">Alternate Address</h1>
						<ul class="form" style="clear:both">
						<? $F->tbNotVal("AlternateAddress", "Alternate Address", "", "float:left; clear:both;"); ?>
						<? $F->tbNotVal("AlternateAddress2", "Alternate Address Line 2", "", "float:left; padding-left:0;"); ?>

						<? $F->tbNotVal("AlternateCity", "Alternate City", "", "float:left; clear:both;"); ?>
						<? $F->ddlStates("FL", "AlternateState", "Alternate State", "float:left; padding-left:0;"); ?>
						<? $F->tbNotVal("AlternateZipCode", "Alternate Zip Code", "", "float:left; padding-left:0;"); ?>

						<? $F->ddlCountries("AlternateCountry", "Alternate Country", "float:left; clear:both;"); ?>
						</ul>
					</div>
					<div style="clear:both"></div>
					<ul class="form">
						<? $F->submitButton("Create Customer", "btnSubmit", "clear:both"); ?>
					</ul>
					<input type="hidden" value="insertCustomer" id="insertCustomerAction" name="Action" />
				</form>
			</div>
			
			<div class="formBoxDialog" id="fBoxSelectExistingCustomer">
				<h1>Select Customer</h1>
				<form id="formFindExisting" method="post" action="">
					<div style="float:left; width:260px; padding:15px">
						<ul class="form">	
							<? $F->tbNotVal("Search_For") ?>
							<? $F->submitButton("Search_Customers"); ?>
						</ul>
					</div>
					<div style="float:left; width:330px; padding:15px" id="custSearchResults">
					</div>
					<div style="clear:both"></div>
				</form>
			</div>
		</div>

<!-- END CUSTOMER DIV -->


<!-- BEGIN ORDER STATUS DIV -->
		<div class="formDiv" id="statusFormDiv">
			<h1>Order Status</h1>
			<div class="commandBox">
				<h1>Order Details</h1>
				<span class="label">Order ID:</span><span class="value" id="spanOrderID"></span>

				<div class="formOptionSet" id="viewReport">
					<span class="label">Reports:</span>
					<span class="value"><a id="saleReportLink">Individual Sale Report</a>
				</div>

				<span class="label">Order Status:</span><span class="value"><? $F->ddlOrderStatuses($DB); ?></span>


				<div class="formOptionSet" id="orderCompleteOptions">
					<span class="label">Date Completed:</span>
					<input type="text" id="tbDateCompleted" class="datepicker value" />
				</div>

				<div style="clear:both"></div>

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
					<input type="text" disabled="disabled" class="value" id="tbReserve" value="0.00" />


					<div class="spacer"></div>

					<div style="padding:15px;" id="divFinancingInfo">
						<p>No Methods added yet</p>
					</div>
			</div>

			<div id="changeFinancingDiv" class="commandBox">
				<h1>Change Financing</h1>
				<form id="formAddPayment">
					<ul class="form">
						<?	$F->ddlPaymentType("ddlDealerPaymentType");	?>
					</ul>
				</form>
						<div id="cashOptions" class="formOptionSet Visible">
							<form id="formCashOptions">
							<ul class="form">
							<?
								$F->tbVal("cashAmount", "Dollar Amount");
								$F->submitButton("Add Financing Method");
							?>
							</ul>
							</form>
						</div>
						<div id="checkOptions" class="formOptionSet">
							<form id="formCheckOptions">
							<ul class="form">
							<?
								$F->tbVal("checkAmount", "Dollar Amount");
								$F->tbVal("checkBank", "Issuing Bank");
								$F->tbVal("checkNumber", "Check Number");
								$F->tbVal("checkAccount", "Account Number");
								$F->tbVal("checkRouting", "Routing Number");
								$F->submitButton("Add Financing Method");
							?>
							</ul>
							</form>
						</div>
						<div id="creditOptions" class="formOptionSet">
							<form id="formCreditOptions">
							<ul class="form">
							<?
								$F->tbVal("creditAmount", "Dollar Amount");
								$F->ddlCreditTypes();
								$F->tbVal("creditNumber", "Credit Card Number");
								$F->tbVal("creditCVC", "CVC Code");
								$F->creditExpiration();
								$F->tbVal("creditName", "Card Holder's Name");
								$F->submitButton("Add Financing Method");
							?>
							</ul>
							</form>
						</div>
						<div id="financeOptions" class="formOptionSet">
							<form id="formFinanceOptions">
							<ul class="form">
							<?
								$F->ddlGeneric("FinanceCompany", "Finance Company");
								$F->ddlGeneric("LoanOption", "Loan Option");
								$F->tbVal("financeAmount", "Dollar Amount");
								$F->submitButton("Add Financing Method");
							?>
							</ul>
							<input type="hidden" id="hReserveRate" />
							</form>
						</div>
			</div>
		</div>
<!-- END PAYMENT DIV -->


<!-- BEGIN ROLES DIV -->
		<div class="formDiv" id="rolesFormDiv">
		<h1>Dealer Roles</h1>
			<div class="commandBox">
				<table id="roleTable" class="data" style="margin:0px">
					<thead>
						<tr>
							<th>Dealer</th>
							<th>Role</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="3">No Dealers have been added to this Sale.</td>
						</tr>
					</tbody>
				</table></div>

			<div class="commandBox">
				<h1>Add Dealer</h1>
					<form id="roleForm" method="post">
					<ul class="form">
						<?
						$F->ddlStaff($DB);
						$F->ddlDealerRoles(false, "DealerRoles2");
						$F->submitButton("Add Dealer", "btnAddDealerRole");
						?>
					</ul>
					</form>
			</div>
		</div>
<!-- END ROLES DIV -->


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
				<input class="value" id="tbAmount" name="tbAmount" />

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
				<div id="divCustomCommissionMask" style="padding: 2em; display: none;">
					<p>Custom Commission Added. <a href="#" id="linkShowCommissionForm" onclick="ShowCommissionForm();">Click Here</a> to add another Custom Commission.</p>
				</div>
				<div id="divCustomCommissions">
					<div style="float:left; width: 43%">
					<ul class="form">
						<? $F->ddlPayeeTypes(); ?>

						<div id="dealerSelectDiv">
							<? //$F->ddlDealerRoles(); ?>
							 <li class="validated" id="tbDealerRole_li">
									  <label for="r_tbDealerRole">Dealer Role:</label>
									  <div id="tbDealerRole_img"></div>
										<select id="ddlDealerRole" class="validated" name="DealerRole">
											<option value=""></option>
										</select>
								<input type="button" id="btnAddDealer" value="Add Another Dealer">
											  <div id="tbDealerRole_msg"></div>
										  </li>
						</div>

						<div id="commissionAmountDiv">
							<? $F->ddlCommPaymentTypes("ddlDealerPaymentType"); ?>
							<div id="flatRateDiv">
								<? $F->tbVal("Flat_Amount", "Flat Amount", "0.00"); ?>
							</div>

							<div id="percentageDiv" style="display:none;">
								<? $F->tbVal("Percentage", "Percentage", "0"); ?>
							</div>
						</div>


						<!-- Adjustment Stuff -->
						<div id="adjustmentDiv" style="display:none;">
							<? $F->ddlStaff($DB, "staffAdjustment"); ?>
							<? $F->tbNotVal("tbAdjustmentAmount", "Amount (-)"); ?>
						</div>

						<? $F->submitButton("Submit", "btnAddLine"); ?>
					</ul>
					</div>

					<div style="float:left; width: 53%">
						<table class="data" id="dealerTable" style="margin-top:25px; display: none;">
							<thead><tr><th style="width: 18px"></th>
								<th>Dealer Role</th>
								<th>Amount</th>
								</tr><thead>
								<tbody>
									<tr class="error">
										<td colspan="3">
											No Dealers added yet. At least one dealer must be added to complete this Commission Element
										</td>
									</tr>
								</tbody>
						</table>
					</div>
					<div style="clear:both;"></div>
				</div>


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

			<div style="clear:both"></div>
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
<input type="hidden" name="hRolesArray" id="hRolesArray" value="">
<input type="hidden" name="hContactDestination" id="hContactDestination" value="h_contact_id" />
<input type="hidden" name="contact_id" id="h_contact_id" value="<? 
	if (isset($newCustomer)) echo $newCustomer;
	elseif (isset($order["contact_id"]) && $order["contact_id"] > 0) echo $order["contact_id"];
	else echo "Not Yet Set"
	?>">
<input type="hidden" name="cobuyer_contact_id" id="h_cobuyer_contact_id" value="<?
	$firephp->log($newCobuyer);
	if (isset($newCobuyer)) echo $newCobuyer;
	elseif (isset($order["cobuyer_id"])) echo $order["cobuyer_id"];
	else echo "Not Yet Set"
	?>">
<input type="hidden" name="h_contact_id_previous" id="h_contact_id_previous" value="NOTSET">
<input type="hidden" name="hremaining" id="hremaining" value="">
<input type="hidden" name="dirty" id="dirty" value="">


<SCRIPT type="text/javascript">
<!--
	// Globals
	var pageStatus = $('#hPageStatus').val();
	var orderId = $('#hOrderId').val();
	var productArray = $('#hProductArray').val();

	function SetDirty()
	{
		$('#dirty').val("1");
	}


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
						var cobuyer_id = o.cobuyer_contact_id;
						var commStructure = o.CommStructure;
						var ProductsArray = o.ProductsArray;
						var AccessoriesArray = o.AccessoriesArray;
						var PaymentArray = o.PaymentArray;
						var dateCompleted = o.DateCompleted;
						var RolesArray = o.dealerArray;
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

						if ($('#h_contact_id').val() == "Not Yet Set" && contact_id != "0")
							$('#h_contact_id').val(contact_id);

						if ($('#h_cobuyer_contact_id').val() == "Not Yet Set" && cobuyer_id != "0")						
							$('#h_cobuyer_contact_id').val(cobuyer_id);
						$('#tbSalePrice').val(amount);
						$('#hCommissionArray').val(commStructure);
						$('#hProductArray').val(ProductsArray);
						$('#hAccessoryArray').val(AccessoriesArray);
						$('#hPaymentArray').val(PaymentArray);
						$('#hRolesArray').val(RolesArray);
						
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
		bindLoanOptions();
		updateForm();
		pager();

		// Handlers
		$('#ddlprodProdID').change( function() {  bindSerialBox();  });
		$('#ddlState').change( function() {   bindCountyBox();   });
		$('#tbSalePrice').change( function() { updateSalesTax(); });
		$('#ddlFinanceCompany').change( function() { bindLoanOptions(); });
		$('form').submit( function() { SetDirty() });

	});

	function bindLoanOptions()
	{
		var financeCompany = $('#ddlFinanceCompany').val();
		if (financeCompany)
		{
			$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getNewFinanceOptionsTable", financeCompany: financeCompany }, function(json) 
			{
				eval("var args = " + json);		
				if (args.success == "success")
				{
					var theOutput = args.output;
					for (i = 0; i < theOutput.length ; i++ )
					{
						if (theOutput[i].id == financeCompany)
						{
							var myCompany = theOutput[i];
							if (myCompany.LoanOptions)
							{
								var myLoanOptions = JSON.parse(myCompany.LoanOptions);
							}
						}
					}
					$('#ddlLoanOption option').remove();
					if (!myLoanOptions || myLoanOptions.length == 0)
					{
						$('#ddlLoanOption').append('<option value="">No Loan Options Added - Add some in Admin</option>');
						$('#ddlLoanOption').attr('disabled', 'disabled');
					}
					else
					{
						// Parse the JSON thingy
						for (i = 0; i < myLoanOptions.loanOptions.length ; i++ )
						{
							$('#ddlLoanOption').append('<option value="' + myLoanOptions.loanOptions[i].optionName + '|||' + myLoanOptions.loanOptions[i].reserve + '">' + myLoanOptions.loanOptions[i].optionName + ' (' + myLoanOptions.loanOptions[i].reserve + '% Reserve)</option>');
						}
						$('#ddlLoanOption').removeAttr('disabled');
					}

					var reserveRate = parseFloat( $('#ddlLoanOption').val().split('|||')[1] );
					$('#hReserveRate').val(reserveRate);
					fixHeight();
				}
			});
		}
	}

	function bindFinanceBox()
	{
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getNewFinanceOptionsTable" }, function(json) 
		{
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#ddlFinanceCompany option').remove();

				if (args.output.length == 0)
				{
					$('#ddlFinanceCompany').append('<option value="">No Finance Companies Added - Add some in Admin</option>');
					$('#ddlFinanceCompany').attr('disabled', 'disabled');
				}

				else
				{
					for (i = 0; i < args.output.length ; i++ )
					{
						$('#ddlFinanceCompany').append('<option value="' + args.output[i].id + '">' + args.output[i].CompanyName + '</option>');
					}
					$('#ddlFinanceCompany').removeAttr('disabled');
					bindLoanOptions();
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
		$('#ddlSerial option').remove();
		$('#ddlSerial').attr("diabled", "disabled");
		$('#ddlSerial').append("<option value=''>Checking Available Inventory...</option>");
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getSerials",  product_id: product_id }, function(json) 
		{
			eval("var args = " + json);		
			if (args.success == "success")
			{
				$('#ddlSerial option').remove();
				if (args.output)
				{
					for (i = 0; i < args.output.length ; i++ )
					{
						// See if serial is included in this order before adding to list.
						var $prodArrayHiddenField = $('#hProductArray');
						var used = 0; // Checking to see if serial is in use in this order

						// See if we already have a value in the field
						if ($prodArrayHiddenField.val() != '')
						{
							// Read the existing object
							var theObject = JSON.parse($prodArrayHiddenField.val());
							for (var j in theObject.products)
							{
								if (theObject.products[j].Serial == args.output[i].serial)
								{
									used = 1;
								}
							}
						}
						if (!used)
						{
							$('#ddlSerial').append('<option value="' + args.output[i].serial + '">' + args.output[i].serial + '</option>');
						}
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
		updateCommLines();

		$('#' + $('#ddlPaymentType').val() + 'Options').css('display', 'block');


		var contact_id = $('#h_contact_id').val();
		var cobuyer_id = $('#h_cobuyer_contact_id').val();
		var orderId = $('#hOrderId').val();
	


		// Populate Cobuyer ID
		$('#spanCobuyerID').html(cobuyer_id);

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

		// Populate Cobuyer Info
		if (isNumeric(cobuyer_id))
		{
			$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getContact",  value: cobuyer_id }, function(json) 
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

						$('#spanCobuyerInfo').html(firstName + " " + lastName + "<br>" + address + "<br>" + city + ", " + state + " " + zip);
						$('#h_cobuyer_id_previous').val(cobuyer_id);

						$('#divCobuyerNotSet').hide();
						$('#divCobuyerSet').show();
					}
				}
			});
		}

		// Populate Customer Info
		if (isNumeric(contact_id) && contact_id-0 > 0)
		{
			// Populate CustomerID
			$('#spanCustomerID').html(contact_id);
			if ( $('#h_contact_id').val() != $('#h_contact_id_previous').val() )
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
							$('#h_contact_id_previous').val(contact_id);

							$('#divCustomerNotSet').hide();
							$('#divCustomerSet').show();
						}
						fixHeight();
					}
				});
			}
		}

	
		// Populate Commission Templates based on Price
		var salePrice = $('#tbSalePrice').val();
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "getCommissionTemplates",  price: salePrice }, function(json) 
		{
			eval("var args = " + json);
			if (args.success == "success")
			{
				$ddlTemplates = $('#ddlcommTemplate');
				if (args.output.length > 0 && args.output != 'none')
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
									AddCommLine(_r.payee_type, _r.payment_type, _r.amount, _r.dealers, false);
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

		// Populate Dealer Roles for Commissions based on added dealers
		if ($('#hRolesArray').val())
		{
			var rolesString = $('#hRolesArray').val();
			var theObject = JSON.parse(rolesString);
			$('#ddlDealerRole option').remove();
			if (theObject)
			{
				for (var i in theObject.roles)
				{
					$('#ddlDealerRole').append("<option value=\"" + theObject.roles[i].role + "\">" + theObject.roles[i].roleText + " (" + theObject.roles[i].userText + ")</option>");
				}
			}
		}


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
				var index = prod.Index;
				text = text + "<tr class=\"row" + row + "\"><td>" + prod_id + "</td><td>" + prod_name + "</td><td>" + quant + "</td><td><a href=\"#\" onclick=\"deleteProduct(" + index + "); return false;\">Delete</a></td></tr>";
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
				var quant = method.quantity;
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
				var details = "";
				var commands = '<a href="#" id="deletePaymentMethod_' + i + '" onclick="return deletePaymentMethod(this)">Delete</a>';

				if (theObject.paymentMethods[i].paymentType == 'finance')
				{
					var reserveRate = theObject.paymentMethods[i].reserveRate;
					var reserveAmount = theObject.paymentMethods[i].amount * reserveRate / 100;
					details = "Company: " + theObject.paymentMethods[i].financeCompany + "<br>Loan Option: " + theObject.paymentMethods[i].loanOption +  "<br>Reserve Rate: " +  reserveRate + "% (" + formatCurrency(reserveAmount) + ")";
				}
				
				text = text + "<tr class=\"row" + row + "\"><td>" + type + "</td><td>" + amount + "</td><td>" + details + "</td><td>" + commands + "</td></tr>";
				row = 1 - row;
			}	
			text = text + "</table>";
			$paymentDiv.html(text);
			$('#' + $('#ddlPaymentType').val() + 'Options').css('display', 'block');

		}

		// Populate Status Div
		displayStatusOptions();

		// Populate Roles
		populateRoles();

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
		if ($('#ddlPayeeType').val() == "adjustment")
		{
			var amount = 0 - parseFloat($('#tbtbAdjustmentAmount').val());
		}

		if ($('#dealerTable tr.dealer').length == 0)
		{
			if ( !addDealer() )
			{
				return 0;
			}
		}

		var dealerObject = { dealers:[] };
		var i = 0;
		$('#dealerTable tr.dealer').each( function() {
			var dealer = {};
			// See if there is a Dealer assigned to the role.

			var role = $(this).children('#tdDealerText').html();
			dealer.role =  role.split(" (")[0];
			var dealerInfo = getDealerByRole(dealer.role);
			dealerObject.dealers[i] = dealerInfo;
			i++;
		});


		if ($('#ddlPayeeType').val() == "adjustment")
		{
			payeeType = "adjustment";
			paymentType = "adjustment";

			var dealerObject = { dealers:[] };
			var dealer = {};
			dealer.userText = $('#ddlstaffAdjustment option:selected').html();
			dealer.user = $('#ddlstaffAdjustment').val();
			dealerObject.dealers[0] = dealer;
		}
		AddCommLine(payeeType, paymentType, amount, JSON.stringify(dealerObject), true);
	}

	function updateCommLines()
	{

		// See if we already have a value in the field
		if ($('#hCommissionArray').val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($('#hCommissionArray').val());
			for (z=0; z < theObject.elements.length ; z++ )
			{
				if (theObject.elements[z].payeeType == 'employee')
				{
					for (j=0; j < theObject.elements[z].dealers.length ; j++ )
					{
						var role = theObject.elements[z].dealers[j].role;
						var roleText = theObject.elements[z].dealers[j].roleText;
						var dealer = getDealerByRole(roleText);
						if(JSON.stringify(dealer) == '""')
						{
							var userText = "";
							var user = "";
						}
						else
						{
							theObject.elements[z].dealers[j] = dealer;
						}
					}
				}
			}

			// Store the new Object
			$('#hCommissionArray').val(JSON.stringify(theObject));
		}
		
	}


	function AddCommLine(payeeType, paymentType, amount, dealersString, HideCustomForm)
	{
		var remaining = $('#hremaining').val() ? $('#hremaining').val() : 0;
		if ((amount-0) > (remaining-0) && paymentType != "adjustment")
		{
			alert("Insufficient Funds remaining to add that Commission.");
			return false;
		}

		if (payeeType == "employee")
		{
			if (paymentType == "flat")
			{
				var amount = parseFloat($('#tbFlat_Amount').val());
				if ( amount <= 0 || isNaN(amount) )
				{
					alert("Invalid Commission Amount. Please enter a valid amount for this Commission.");
					$('#tbFlat_Amount').focus();
					return 0;
				}
			}

			if (paymentType == "percentage")
			{
				var percentage = parseFloat($('#tbPercentage').val());
				if ( percentage >= 100 || percentage <= 0 || isNaN(percentage) )
				{
					alert("Invalid Commission Percentage Amount. Please enter a valid percentage (1-100) for this Commission.");
					$('#tbPercentage').focus();
					return 0;
				}
			}
		}
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
	
		if (dealersobj)
		{
			for (j=0; j < dealersobj.dealers.length ; j++ )
			{
				var role = dealersobj.dealers[j].role;
				var dealer = getDealerByRole(role);
				if(JSON.stringify(dealer) == '""' && !dealersobj.dealers[j].userText && !dealersobj.dealers[j].user)
				{
					var userText = "";
					var user = "";
				}
				else if (dealersobj.dealers[j].userText && dealersobj.dealers[j].user)
				{
					var userText = dealersobj.dealers[j].userText;
					var user = dealersobj.dealers[j].user;
				}
				else
				{
					var userText = dealer.userText ? dealer.userText : "";
					var user = dealer.user ? dealer.user : "";
				}
				dealersobj.dealers[j].userText = userText;
				dealersobj.dealers[j].user = user;
			}
		}


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
		
		if ($('#ddlPayeeType').val() == "adjustment")
		{
			thisElement.amount = amount;
		}

		theObject.elements[i] = thisElement;
		var storage = JSON.stringify(theObject);

		$('#hCommissionArray').val(storage);
		buildDefaultCommTable(false);

		// Clear Commission Form
		$('#tbFlat_Amount').val("");
		$('#tbPercentage').val("");
		$('#dealerTable tbody tr').remove();
		checkDealers();


		if (HideCustomForm)
		{
			$('#divCustomCommissionMask').show();
			$('#divCustomCommissions').hide();
		}

		return false;
	}

	function ShowCommissionForm()
	{
		$('#divCustomCommissionMask').hide();
		$('#divCustomCommissions').show();
		return false;
	}


	function getDealerByRole(role)
	{
		// See if we already have a value in the field
		if ($('#hRolesArray').val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($('#hRolesArray').val());
			for (i=0; i<theObject.roles.length ; i++ )
			{
				if (theObject.roles[i].roleText == role)
				{
					return theObject.roles[i];
				}
			}
		}
		return "";
	}

	function addDealer()
	{
		var Dealer = $('#ddlDealerRole').val();
		var DealerText = $('#ddlDealerRole option:selected').html();
		var Amount = 0;

		$('#dealerTable tbody tr.error').remove();

		$('#dealerTable tbody').append('<tr class="dealer"><td><a href="#" class="deleteDealer">' + deleteLinkContent() + '</a></td><td id="tdDealerText">' + DealerText + '</td><td class="amount">' + Amount + '</td></tr>');

		checkDealers();
		return 1;
	}

	function maybeDisplayDealers()
	{
		$dealerElements = $('#dealerSelectDiv, #dealerTable');
		if ($('#ddlPayeeType').val() == 'employee')
		{
			$dealerElements.show();
			$('#adjustmentDiv').hide();
			$('#commissionAmountDiv').show();
		}
		else if ($('#ddlPayeeType').val() == 'adjustment')
		{
			$dealerElements.hide();
			$('#adjustmentDiv').show();
			$('#commissionAmountDiv').hide();
		}
		else 
		{
			$dealerElements.hide();
			$('#adjustmentDiv').hide();
			$('#commissionAmountDiv').show();
		}
		checkDealers();
	}


	function checkDealers()
	{
		if ($('#dealerTable tbody tr.dealer').length == 0)
		{
			$('#dealerTable tbody').append('<tr class="error"><td colspan="3">No Dealers added yet. At least one dealer must be added to complete this Commission Element</td></tr>');
			$('#dealerTable').hide();
		}

		else {
			$('#dealerTable').show();
		}
		updateAmounts();
	}
	

	function updateAmounts()
	{
		var amount = parseFloat($('#tbFlat_Amount').val());
		var percentage = parseFloat($('#tbPercentage').val());
		var dealers = $('#dealerTable tbody tr.dealer').length;
		var amountEach = amount / dealers;
		var percentEach = percentage / dealers;
		var adjAmount = $('#tbtbAdjustmentAmount').val();
		var paymentType = $('#ddlDealerPaymentType').val();
		var commType = $('#ddlPayeeType').val();

		if (commType == 'adjustment')
		{
			$('#dealerTable tbody tr.dealer').each( function() {
				$(this).children("td.amount").html(formatCurrency(adjAmount));
			});
		}
		else if (paymentType == 'flat')
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
					var elements = $('#hCommissionArray').val() ? JSON.parse($('#hCommissionArray').val()).elements : "";
				}
				
				var div = $('#dealerBox');

				var text = "<table style=\"margin-bottom: 0px\" id='tableCommStructure' class='data'><thead><th>Commands</th><th>Payee</th><th>Payment Type</th><th>Amount</th><th>Actual</th><th>Remaining</th></thead><tbody>";
				var row = 0;
				var SalePrice = parseFloat($('#tbSalePrice').val().replace("$", "").replace(",",""));
				var reserve = parseFloat($('#tbReserve').val().replace("$", "").replace(",",""));
				var remaining = SalePrice - reserve;
				$('#hremaining').val(remaining);
				var corpTotal = 0;
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

					var c1 = data.payeeType;					
					if (data.payeeType == 'employee' || data.payeeType == 'adjustment')
					{
						if (data.dealers)
						{
							var dealers = data.dealers;

							for (var i = 0; i < dealers.length; i++)
							{
								if (data.payeeType == 'employee')
								{
									dealerText = dealerText + dealers[i].userText + " - " + dealers[i].roleText + "<br />";
								}
								else
								{
									dealerText = dealerText + dealers[i].userText + "<br />";
								}
								
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
					else if (c2 == 'adjustment')
					{
						var rowAmount = data.amount;
						var c3 = formatCurrency(rowAmount);
						var c4 = c3;
					}
					else 
					{
						var rowAmount = remaining;
						var c3 = formatCurrency(remaining);
						var c4 = formatCurrency(remaining);
					}

					if ( data.payeeType != 'adjustment' )
					{
						remaining = remaining - rowAmount;
					}

					if ( data.payeeType == 'corporate' )
					{
						corpTotal = (corpTotal-0) + (rowAmount-0);
					}
					


					text = text + "<tr class='row"+row+" clickable' id=\"row"+data.Index+"\">";
					text = text + "<td><a class=\"linkList\" href=\"#\" id=\"delete"+data.Index+"\">Delete</a>";
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
				text = text + "</tbody><tfoot><tr class='row"+row+"' style=>";
				text = text + "<td colspan='5' style='border-top:1px solid #C1DAD7'><span style='float:right'>AVAILABLE FOR COMMISSION:</span></td><td style='border-top:1px solid #C1DAD7'><b>" + formatCurrency(remaining) + "</b></td>";
				text = text + "</tr><tr class='row"+row+"'>";
				text = text + "<td colspan='5'><span style='float:right'>CORPORATE TOTAL:</span></td><td><b>" + formatCurrency(corpTotal + remaining) + "</b></td>";
				text = text + "</tr></tfoot>";
				text = text + "</table>";

				//text = text + "<a href='#' id='lbAddItem'>Add</a>";

				div.html(text);
				$('#hremaining').val(remaining);

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
		$('#commandDiv').css('display', 'block');

		$('#' + $('#ddlPaymentType').val() + 'Options').css('display', 'block');
		if ($('#ddlPaymentType').val() == 'finance')
		{
			bindLoanOptions();
		}
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
		if (pageStatus == 'roles')
		{
			$('#rolesBullet').addClass('navBulletSelected');
			$('#rolesFormDiv').css('display', 'block');
			updateForm();
		}
		if (pageStatus == "dealer")
		{
			$('#dealerBullet').addClass('navBulletSelected');
			$('#dealerFormDiv').css('display', 'block');
			updateForm();
			ShowCommissionForm();
		}
		fixHeight();
	}

	// Ajax function to save Order to DB
	function saveOrder(then_exit)
	{
		var orderId = $('#hOrderId').val();
		var cobuyer_id = $('#h_cobuyer_contact_id').val();
		var orderStatus = $('#ddlOrderStatuses').val();
		var customer_id = $('#h_contact_id').val();
		var amount = $('#tbSalePrice').val();
		var CommStructureString = $('#hCommissionArray').val();
		var ProductsString = $('#hProductArray').val();
		var AccessoriesString = $('#hAccessoryArray').val();
		var PaymentString = $('#hPaymentArray').val();
		var dateCompleted = $('#tbDateCompleted').val();
		var dealerArray = $('#hRolesArray').val();
		var user_id = "<?= $_SESSION["user_id"] ?>";
		var tax = $('#tbSalesTax').val().replace("$", "");
		var remaining = $('#hremaining').val();

		// Validation for completed orders:
		
		if (!customer_id || customer_id == 0 || !isNumeric(customer_id) )
		{
			alert("No Customer has been specified for this Sale.\n\n A customer must be assigned before the order can be saved.");
			return false;
		}

		if (orderStatus == 5)
		{
			if ((remaining-0) > 0)
			{
				if (!confirm("Not all funds have been assigned.\n\n Unassigned Funds will go to Corporate until this is resolved. Do you wish to continue saving this order?"))
				{
					return false;
				}
			}
		}


		if (!IsNumeric(customer_id))
		{
			customer_id = 0;
		}

		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { 
				id: "submitOrder",
				order_id: orderId,
				orderStatus: orderStatus,
				customer_id: customer_id,
				cobuyer_id: cobuyer_id,
				amount: amount,
				CommStructureString: CommStructureString,
				ProductsString: ProductsString,
				AccessoriesString: AccessoriesString,
				PaymentString: PaymentString,
				dealerArray: dealerArray,
				user_id: user_id,
				dateCompleted: dateCompleted,
				tax: parseFloat(tax)
			}, function(json)
		{
			eval("var args = " + json);
			if (args.success == "success")
			{
				if (args.output)
				{
					var order_id = args.output;
					if (then_exit)
					{
						window.location = "ManageSales.php";
					}
				}
				else
				{
					var order_id = null;
				}
				$('#hOrderId').val(order_id);
				updateForm();
				$('#dirty').val("0");
			}
		});			

		return 1;
	}


	// Customer Panel Scripts
	$('#formCreateNew').submit( function() {
		if (validateForm(this) != true)
		{
			return false;
		}
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "createCustomer",
				FirstName: $('#tbFirstName').val(),
				LastName: $('#tbLastName').val(),
				Email: $('#tbEmail').val(),
				Address: $('#tbAddress').val(),
				Address2: $('#tbAddress2').val(),
				City: $('#tbCity').val(),
				State: $('#ddlState').val(),
				ZipCode: $('#tbZipCode').val(),
				Country: $('#ddlCountry').val(),
				Phone: $('#tbPhone').val(),
				PhoneDetails: $('#tbPhoneDetails').val(),
				Notes: $('#tbNotes').val(),
				ContactType: $('#ddlContactType').val(),
				County: $('#ddlCounty').val(),
				HomeStatus: $('#ddlHomeStatus').val(),
				HomeType: $('#ddlHomeType').val(),
				contact_alternate_address: $('#tbAlternateAddress').val(),
				contact_alternate_address2: $('#tbAlternateAddress2').val(),
				contact_alternate_city: $('#tbAlternateCity').val(),
				contact_alternate_state: $('#ddlAlternateState').val(),
				contact_alternate_zipcode: $('#tbAlternateZipCode').val(),
				contact_alternate_country: $('#ddlAlternateCountry').val(),
				License: $('#tblicense').val(),
				licenseState: $('#ddllicenseState').val()
			}, function(json)
				{
					eval("var args = " + json);
					if (args.success == "success")
					{
						if (args.output)
						{
							var newCustomer = args.output;
							if ($('#insertCustomerAction').val() == "insertCustomer")
							{
								$('#h_contact_id').val(newCustomer);
							}
							else if ($('#insertCustomerAction').val() == "insertCobuyer")
							{
								$('#h_cobuyer_contact_id').val(newCustomer);
							}
							clearCustomerForm();
							updateForm();
						}
					}
			});
			return false;
	});

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
						$('#' + $('#hContactDestination').val()).val(contact_id);
						updateForm();
					});
				}
			}
		});
		return false;
	});

	function clearCustomerForm()
	{
		$('#formCreateNew').each( function() { this.reset(); });
	}

	// Finance Panel Scripts
	var $PaymentTypeDDL = $('#ddlPaymentType');
	$PaymentTypeDDL.change( function() {
		$('.formOptionSet').css('display', 'none');
		$('#' + $PaymentTypeDDL.val() + 'Options').css('display', 'block');
		if ($('#' + $PaymentTypeDDL.val() == "finance"))
		{
			bindLoanOptions();
		}
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
				thisMethod.financeCompany = $('#ddlFinanceCompany option:selected').html();
				thisMethod.financeOption = $('#ddlFinanceCompany').val().split("|||")[0];
				thisMethod.loanOption = $('#ddlLoanOption').val().split("|||")[0];
				thisMethod.reserveRate = $('#ddlLoanOption').val().split("|||")[1];
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

	function deletePaymentMethod(link)
	{
		var method_id = link.id.split("_")[1];

		var $paymentArrayHiddenField = $('#hPaymentArray');
		if ($paymentArrayHiddenField.val())
		{
			var theObject = JSON.parse($paymentArrayHiddenField.val());
			var paymentMethods = theObject.paymentMethods;
			delete theObject.paymentMethods[method_id];

			// Clear Null nodes and rewrite products array
			var j = 0;
			var newObjects = [];
			for (var i in theObject.paymentMethods)
			{
				if (theObject.paymentMethods[i])
				{
					var newProduct = theObject.paymentMethods[i];
					newObjects[newObjects.length] = newProduct;
				}
			}
			theObject.paymentMethods = newObjects;


			// Store object back in Hidden Variable
			var storage = JSON.stringify(theObject);
			$paymentArrayHiddenField.val(storage);

			// Update Form and Return false to prevent postback.
			updateForm();
		}
		return false;
	}


	// Equipment Panel Scripts
	function deleteProduct (index)
	{
		SetDirty();
		var $prodArrayHiddenField = $('#hProductArray');

		// See if we already have a value in the field
		if ($prodArrayHiddenField.val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($prodArrayHiddenField.val());

			for (var j in theObject.products)
			{
				if (theObject.products[j].Index == index)
				{
					delete theObject.products[j];
				}
			}

			// Clear Null nodes and rewrite products array
			var j = 0;
			var newElements = [];
			for (var k in theObject.products)
			{
				if (theObject.products[k] != null)
				{
					var newElement = theObject.products[k];
					newElements[newElements.length] = newElement;
				}
			}

			theObject.products = newElements;

			// Store new object
			$prodArrayHiddenField.val(JSON.stringify(theObject));

			// Update Serial numbers
			bindSerialBox();

			updateForm();
		}
	}



	var $addProductLink = $('#formAddProduct, #formAddAccessory');
	$addProductLink.submit( function() {

		if (this.id == 'formAddProduct')
		{
			var $prodArrayHiddenField = $('#hProductArray');
			var $dropdown = $('#ddlprodProdID');
			var serial = $('#ddlSerial').val();
			var quantity = 1;;
			var $nameOption = $('#ddlprodProdID option:selected');
		}
		if (this.id == 'formAddAccessory')
		{
			var $prodArrayHiddenField = $('#hAccessoryArray')
			var $dropdown = $('#ddlaccProdID');
			var quantity = $('#tbaccQuantity').val();
			var $nameOption = $('#ddlaccProdID option:selected');
			var serial = "";
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
			thisProduct.Serial = serial;
			thisProduct.Name = $nameOption.text();
			thisProduct.quantity = quantity;


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
					newProduct.quantity = theObject.products[i].quantity;
					
					newObjects[newObjects.length] = newProduct;
				}
			}
			theObject.products = newObjects;


			// Store object back in Hidden Variable
			var storage = JSON.stringify(theObject);
			$prodArrayHiddenField.val(storage);

			// Remove serial from dropdown
			if (serial)
			{
				$('#ddlSerial option:selected').remove();
			}


			// Update Form and Return false to prevent postback.
			updateForm();
		}
		return false;
	});


	// Order Status Scripts
	function displayStatusOptions()
	{
		$('.formOptionSet').css("display", "none");
			$('#' + $('#ddlPaymentType').val() + 'Options').css('display', 'block');
			if ($('#ddlPaymentType').val() == 'finance')
			{
				bindLoanOptions();
			}
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
		var qs = new Querystring();
		var order_id = qs.get("order_id", "");
		
		if (order_id != "")
		{
			// Show Link Box
			$('#viewReport').css("display", "block");
			$('#saleReportLink').attr("href", "/<?= $ROOTPATH ?>/reports/NexusReport_IndividualOrder.php?OrderID=" + order_id);
		}

	}
		
	// Roles Panel Scripts
	function addRole(user, role, userText, roleText)
	{
		//
		// Add Stuff to the JSON element in the hidden field
		//
		SetDirty();
		var $roleStorage = $('#hRolesArray');
		// See if we already have a value in the field
		if ($roleStorage.val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($roleStorage.val());
		}
		else
		{
			// Create a new JSON object
			var theObject = {roles: []};	
		}

		if (!theObject)
		{
			// Create a new JSON object
			var theObject = {roles: []};	
		}

		var i = theObject.roles.length;

		// Create new object for role
		var thisRole = {};
		thisRole.Index = i;
		thisRole.user = user;
		thisRole.userText = userText;
		thisRole.role = role;
		thisRole.roleText = roleText;
		


		// Add created product to Object
		theObject.roles[i] = thisRole;

		// Store object back in Hidden Variable
		var storage = JSON.stringify(theObject);
		$roleStorage.val(storage);

		// Update Form and Return false to prevent postback.
		populateRoles();
	}

	function populateRoles()
	{
		var $roleStorage = $('#hRolesArray');
		// See if we already have a value in the field
		if ($roleStorage.val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($roleStorage.val());
		}
		else return; // no data, do nothing

		$('#roleTable tbody > tr').remove();
		if (theObject && theObject.roles.length > 0)
		{
			
			for (i=0; i < theObject.roles.length ; i++)
			{
				$('#roleTable tbody').append('<tr><td>' + theObject.roles[i].userText + '</td><td>' + theObject.roles[i].roleText + '</td><td><a href="#" onClick="deleteRole(' + theObject.roles[i].Index + '); return false;">Delete</a></tr>');

				
				// Delete Role from the dropdown
				$('#ddlDealerRoles2 option').each( function() {
				if ($(this).html() == theObject.roles[i].roleText)
					{
						$(this).remove();
					}
				});

			}
		}
		else
		{
			$('#roleTable tbody').append('<tr><td colspan="3">No Dealer Roles Added Yet.</td></tr>');
		}
	}

	function deleteRole(index)
	{
		SetDirty();
		// Clone ddlbaseDealers to dealerroles2
		$('#ddlDealerRoles2').html( $('#ddlbaseDealers').html() );

		var $roleStorage = $('#hRolesArray');
		// See if we already have a value in the field
		if ($roleStorage.val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($roleStorage.val());
		}
		for (var j in theObject.roles)
		{
			if (theObject.roles[j].Index == index)
			{
				delete theObject.roles[j];
			}
		}

		// Clear Null nodes and rewrite products array
		var j = 0;
		var newroles = [];
		for (var k in theObject.roles)
		{
			if (theObject.roles[k] != null)
			{
				var newElement = theObject.roles[k];
				newroles[newroles.length] = newElement;
			}
		}
		theObject.roles = newroles;
		$('#hRolesArray').val(JSON.stringify(theObject));
		populateRoles();
		return false;
	}


	// Listeners
	$('.saveAndContinueLink').click( function() {
		saveAndContinue();
		return false;
	});

	$('.continueLink').click( function() {
		continueLink();
		return false;
	});

	$('.saveAndQuitLink').click( function() {
		saveAndQuit();
	});


	function saveAndQuit()
	{
		var then_exit = 1;
		saveOrder(then_exit);
	}

	function continueLink()
	{
		nextPage();
	}

	function saveAndContinue()
	{
		if (saveOrder())
			nextPage();
	}

	function nextPage() // Advances to next page.
	{
		var nextPage = null;
		switch(pageStatus)
		{
			case "customerInfo":	nextPage = "equipment"; break;
			case "equipment":		nextPage = "payment"; break;
			case "payment":			nextPage = "roles"; break;
			case "roles":			nextPage = "dealer"; break;
			case "dealer":			nextPage = "status"; break;
			case "status":			nextPage = "customerInfo"; break;
		}
		pageStatus = nextPage;
		$('#hPageStatus').val(pageStatus);
		populateRoles();
		updateSalesTax();
		pager();
	}

	
	

	$('#roleForm').submit( function() {

		var user = $('#ddlStaff').val();
		var role = $('#ddlDealerRoles2').val();
		var userText = $('#ddlStaff option:selected').html();
		var roleText = $('#ddlDealerRoles2 option:selected').html();
		addRole(user, role, userText, roleText);

		return false;

	});

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
		bindSerialBox();
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

	$('#rolesBullet, #rolesBulletLink').click( function() {
		pageStatus = 'roles';
		$('#hPageStatus').val(pageStatus);
		populateRoles();
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
	function displayAddContactForm(mode)
	{
		$('.formBoxDialog').css('display', 'none');
		$('#fBoxCreateNewCustomer').css('display', 'block');

		if (mode == "cobuyer")
		{
			$('#insertCustomerAction').val("insertCobuyer");
		}
	}

	function displaySelectContactForm(mode)
	{
		$('.formBoxDialog').css('display', 'none');
		$('#fBoxSelectExistingCustomer').css('display', 'block');

		$('#hContactDestination').val( (mode == "cobuyer") ? "h_cobuyer_contact_id" :  "h_contact_id" );
	}

	// Goodbye function - Offers to save Data to database
	function goodbye(e) {
		if ($('#dirty').val() == "1")
		{
			if(!e) e = window.event;
			//e.cancelBubble is supported by IE - this will kill the bubbling process.
			e.cancelBubble = true;
			e.returnValue = 'You have unsaved Data on this page. If you leave the page now, this data will be lost. Clicking ok will destroy any changes you have made since the last time you saved.'; //This is displayed on the dialog

			//e.stopPropagation works in Firefox.
			if (e.stopPropagation) {
				e.stopPropagation();
				e.preventDefault();
			}
		}
	}
	window.onbeforeunload=goodbye;

-->
</SCRIPT>




<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>