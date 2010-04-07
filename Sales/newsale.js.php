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
						if ($('#h_contact_id').val() == "Not Yet Set")
							$('#h_contact_id').val(contact_id);
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

	});

	function bindLoanOptions()
	{
		var financeCompany = $('#ddlFinanceCompany').val();
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
		updateCommLines();

		$('#' + $('#ddlPaymentType').val() + 'Options').css('display', 'block');


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

		var dealerObject = { dealers:[] };
		var i = 0;
		$('#dealerTable tr.dealer').each( function() {
			var dealer = {};

			dealer.role = $(this).children('#tdDealerText').html();
			// See if there is a Dealer assigned to the role.
			var role = dealer.role;
			var dealerInfo = getDealerByRole(role);
			if (dealerInfo.userText)
			{
				dealer.userText = dealerInfo.userText;
				dealer.user = dealerInfo.user;
			}
			dealerObject.dealers[i] = dealer
			i++;
		});
		
		AddCommLine(payeeType, paymentType, amount, JSON.stringify(dealerObject));
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
						var dealer = getDealerByRole(role);
						if(JSON.stringify(dealer) == '""')
						{
							var userText = "";
							var user = "";
						}
						else
						{
							var userText = dealer.userText ? dealer.userText : "";
							var user = dealer.user ? dealer.user : "";
						}
						theObject.elements[z].dealers[j].userText = userText;
						theObject.elements[z].dealers[j].user = user;
					}
				}
			}

			// Store the new Object
			$('#hCommissionArray').val(JSON.stringify(theObject));
		}
		
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



		for (j=0; j < dealersobj.dealers.length ; j++ )
		{
			var role = dealersobj.dealers[j].role;
			var dealer = getDealerByRole(role);
			if(JSON.stringify(dealer) == '""')
			{
				var userText = "";
				var user = "";
			}
			else
			{
				var userText = dealer.userText ? dealer.userText : "";
				var user = dealer.user ? dealer.user : "";
			}
			dealersobj.dealers[j].userText = userText;
			dealersobj.dealers[j].user = user;
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
		
		theObject.elements[i] = thisElement;
		var storage = JSON.stringify(theObject);

		$('#hCommissionArray').val(storage);
		buildDefaultCommTable(false);
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
								dealerText = dealerText + dealers[i].userText + " - " + dealers[i].role + "<br />";
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
		var dealerArray = $('#hRolesArray').val();
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
				dealerArray: dealerArray,
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
	}
		
	// Roles Panel Scripts
	function addRole(user, role, userText, roleText)
	{
		//
		// Delete Role from the dropdown
		//
		$('#ddlDealerRoles2 option').each( function() {
		if ($(this).html() == roleText)
			{
				$(this).remove();
			}
		});


		//
		// Add Stuff to the JSON element in the hidden field
		//
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

		if (theObject.roles.length > 0)
		{
			$('#roleTable tbody > tr').remove();
			for (i=0; i < theObject.roles.length ; i++)
			{
				$('#roleTable tbody').append('<tr><td>' + theObject.roles[i].userText + '</td><td>' + theObject.roles[i].roleText + '</td></tr>');
			}
		}
	}


	// Listeners
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

