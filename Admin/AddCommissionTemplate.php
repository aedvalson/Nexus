<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>
<? $F = new FormElements(); ?>
<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Templates</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" id="custBulletLink">Add New Template</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
	</div>



<div class="commandBox" style="background-color: #EDECDC">
<h1>Add Template Element</h1>
<FORM ID="templateForm" method="POST" ACTION="">
	<div style="float: left; width: 43%;">
	<ul class="form">

		<?	 $F->tbVal("TemplateName", "Template Name");
			$F->ddlPayeeTypes(); ?>	
		
		<div id="dealerSelectDiv">
			<? $F->ddlDealerRoles(); ?>
		</div>

		<? $F->ddlCommPaymentTypes(); ?>

		<div id="flatRateDiv">
			<? $F->tbVal("Amount", "Amount", "0.00"); ?>
		</div>

		<div id="percentageDiv" style="display:none;">
			<? $F->tbVal("Percentage", "Percentage", "0"); ?>
		</div>

		<? $F->submitButton("Submit"); ?>
	</ul>
	</div>

	<div style="float:left; width: 55%; " id="dealerDiv">
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
		<ul class="form" style="margin-left:-30px">
		<?
			$F->tbVal("MinPrice", "Minimum Project Price");
			$F->tbVal("MaxPrice", "Maximum Project Price"); ?>
		</ul>

	</div>

	<div style="clear:both"></div>
</FORM>
</div>
		</div>
	</div>
</div>

<!-- STORAGE -->
<input type="hidden" id="hTemplateData">

<script type="text/javascript">

	$storage = $('#hTemplateData');


	$().ready( function() {
		setVisiblePaymentDiv($("#ddlPaymentType").val());
		maybeDisplayDealers();
		$('#ddlPayeeType').change( function() { maybeDisplayDealers(); });
		$('#btnAddDealer').click( function() { addDealer(); });
		$('#tbAmount').change( function() { updateAmounts(); });
		$('.deleteDealer').live("click", function() {
			$(this).parents('tr:first').remove();
			checkDealers();
			return false;
		});
		$('#ddlPaymentType, #tbAmount, #tbPercentage').change( function() { checkDealers(); });
		updateForm();
	});


	$("#ddlPaymentType").change(function () {
		var value = $(this).val();
		setVisiblePaymentDiv(value);
	});

	
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

	function updateAmounts()
	{
		var amount = parseFloat($('#tbAmount').val());
		var percentage = parseFloat($('#tbPercentage').val());
		var dealers = $('#dealerTable tbody tr.dealer').length;
		var amountEach = amount / dealers;
		var percentEach = percentage / dealers;
		var paymentType = $('#ddlPaymentType').val();

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

	function maybeDisplayDealers()
	{
		$dealerElements = $('#dealerSelectDiv, #dealerTable');
		if ($('#ddlPayeeType').val() == 'employee')
		{
			$dealerElements.show();
		}
		else $dealerElements.hide();

		
	}


	$('#templateForm').submit( function() {

		if (validateForm(this) == true)
		{
			var theObject = {dealers: []};	
			if ($('#ddlPayeeType').val() == 'employee')
			{
				// Create new Object to add to the array
				var thisElement = {};
				var i = 0;
				thisElement.Index = i;

				$('#dealerTable tbody tr.dealer').each( function() {
					var dealerRole = {};
					dealerRole.role = $(this).children('#tdDealerText').html();
					theObject.dealers[i] = dealerRole;
					i++;
				});
			}
			addTemplateToDatabase(JSON.stringify(theObject));
		}
		
		else alert('Form does not validate');
		return false;

	});


	function deleteElement(delIndex)
	{
		var theObject = JSON.parse($storage.val());
		for (var i = 0; i < theObject.elements.length; i++ )
		{
			if (delIndex == theObject.elements[i].Index)
			{
				delete theObject.elements[i];

				// Clear Null nodes and rewrite products array
				var j = 0;
				var newElements = [];
				for (var k in theObject.elements)
				{
					if (theObject.elements[k] != null)
					{
						var newElement = theObject.elements[k];
						newElements[newElements.length] = newElement;
					}
				}
				theObject.elements = newElements;
			}
		}
		$storage.val(JSON.stringify(theObject));
		updateForm();
	}


	function addTemplateToDatabase(dealers)
	{
		
		var minPrice = $('#tbMinPrice').val();
		var maxPrice = $('#tbMaxPrice').val();
		var payeeType = $('#ddlPayeeType').val();
		var paymentType = $('#ddlPaymentType').val();
		
		var amount = 0;
		if (paymentType == 'flat') amount = $('#tbAmount').val();
		if (paymentType == 'percentage') amount = $('#tbPercentage').val();
		
		
		
		if (maxPrice == '' )
		{
			maxPrice = 999999;
		}
		var name = $('#tbTemplateName').val();

		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "addTemplateBlockToDatabase",  
																				dealers: dealers,
																				minimum: minPrice, 
																				maximum: maxPrice,
																				payeeType: payeeType,
																				paymentType: paymentType,
																				amount: amount,
																				templateName: name, 
																				sender: <?= $_SESSION["user_id"] ?> }, 
		function(json) {
			eval("var args = " + json);		
			if (args.success == "success")
			{
				if (args.output)
				{
					alert('Template #' + args.output + ' successfully added.');
					window.location = "ManageTemplates.php";
				}
				else
				{

				}
			}
			else
			{
				 alert("Ajax failed. Please check your Connection.");
			}
		  });

	}


	function validateTemplate()
	{
		if ($storage.val() != "")
		{

			var theObject = JSON.parse($storage.val());
			var minPrice = $('#tbMinPrice').val();
			var maxPrice = $('#tbMaxPrice').val();
			var name = $('#tbTemplateName').val();

			if (name == '')
			{
				alert('Template Name is Required');
				return false;
			}

			if (minPrice == '')
			{
				alert('Minimum Price must be defined.');
				return false;
			}

			var totalElementPrice = 0;
			for (var i = 0; i < theObject.elements.length ; i++ )
			{
				if (theObject.elements[i].paymentType == 'flat')
				{
					totalElementPrice = totalElementPrice + parseFloat(theObject.elements[i].flatAmount);
				}

				
				if (theObject.elements[i].paymentType == 'percentage')
				{
					totalElementPrice = totalElementPrice + (parseFloat(minPrice) * (parseFloat(theObject.elements[i].percentage) / 100));
				}
			}

			if (totalElementPrice > minPrice)
			{
				alert('Minimum Price is too low for this commission model. Adjust template steps or increase minimum sale price. Minimum allowed price is ' + formatCurrency(totalElementPrice) + "\n\n(If percentages are used, minimum allowed price will depend on minimum sale price.)");
				return false;
			}

			return true;
		}
		else
		{
			alert('No Template Elements have been added. Please add Template Objects to Proceed.');
			return false;
		}
	}


	function updateForm()
	{
		
		if ($storage.val() != "")
		{
			var theObject = JSON.parse($storage.val());
			var text = "<h3 class=\"tableHeadline\">Template Elements</h3><table class=\"data\"><tr><th>Payee Type</th><th>PaymentType</th><th>Amount</th><th>Commands</th></tr>";
			var $templateBox = $('#templateBox');
			var row = 0;
			for (var i in theObject.elements)
			{
				var data = theObject.elements[i];
				var c1 = data.payeeType;
				var c2 = data.paymentType;
				
				if (c2 == 'flat')
					var c3 = formatCurrency(data.flatAmount);

				else if (c2 == 'percentage')
					var c3 = data.percentage + "%";

				else var c3 = '';
				//var c3 = data.amount
				text = text + "<tr class=\"row" + row + "\"><td>" + c1 + "</td><td>" + c2 + "</td><td>" + c3 + "</td><td><a href='#' id='del" + data.Index + "'>Delete</a></td></tr>";
				row = 1 - row;
			}	
			text = text + "</table>";
			$('#tableDiv').html('');
			$('#tableDiv').html(text);

			
			$('a[id^=del]').click( function() {
				var id = this.id.replace('del', '');
				deleteElement(id);
			});


		}
	}


</script>



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>