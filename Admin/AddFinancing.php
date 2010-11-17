<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 

if (!UserMay("Admin_EditFinance")) { AccessDenied(); }

$DB = new conn();
$DB->connect();
$F = new FormElements();


$CompanyName = "";
$Address = "";
$City = "";
$State = "";
$ZipCode = "";
$ContactName = "";
$Phone = "";
$Extension = "";
$Email = "";
$Reserve = "0";
$LoanOptions = "";
$Action = "addNew";

// Form Vars
	if ($_REQUEST)
	{
		if ( isset($_REQUEST["id"]) )
		{
			$id = $DB->sanitize($_REQUEST["id"]);
			$sql = "SELECT * FROM finance_options WHERE id = '".$id."'";

			$result = $DB->query($sql);
			if ($result) {
				$financeDetails = mysql_fetch_assoc($result);

				$CompanyName = $financeDetails["CompanyName"];
				$Address = $financeDetails["Address"];
				$City = $financeDetails["City"];
				$State = $financeDetails["State"];
				$ZipCode = $financeDetails["ZipCode"];
				$ContactName = $financeDetails["ContactName"];
				$Phone = $financeDetails["Phone"];
				$Extension = $financeDetails["Extension"];
				$Email = $financeDetails["Email"];
				$Reserve = $financeDetails["Reserve"];
				$LoanOptions = $financeDetails["LoanOptions"];
				$Action = "update";
			}
		}
		if ( isset($_REQUEST["Action"]) )
		{

			// ALL FORM INPUTS MUST BE SANITIZED			
			
			$action = $_REQUEST["Action"];
			$CompanyName = $DB->sanitize($_REQUEST["CompanyName"]);
			$Address = $DB->sanitize($_REQUEST["Address"]);
			$City = $DB->sanitize($_REQUEST["City"]);
			$State = $DB->sanitize($_REQUEST["State"]);
			$ZipCode = $DB->sanitize($_REQUEST["ZipCode"]);
			$ContactName = $DB->sanitize($_REQUEST["ContactName"]);
			$Phone = $DB->sanitize($_REQUEST["Phone"]);
			$Extension = $DB->sanitize($_REQUEST["Extension"]);
			$Email = $DB->sanitize($_REQUEST["Email"]);
			$LoanOptions = $DB->sanitize($_REQUEST["LoanOptions"]);
			$Reserve = $DB->sanitize($_REQUEST["Reserve"]);
			
			if ($action == "addNew")
			{
				$sql = "INSERT INTO finance_options (CompanyName, Address, City, State, ZipCode, ContactName, Phone, Extension, Email, LoanOptions, Reserve) VALUES ('".$CompanyName."', '".$Address."', '".$City."', '".$State."', '".$ZipCode."', '".$ContactName."', '".$Phone."', '".$Extension."', '".$Email."', '".$LoanOptions."', '".$Reserve."')";
				$DB->execute_nonquery($sql);

				$DB->addHistory( 'users', $_SESSION["user_id"],  "insert", "" );
				header("Location: ManageFinancing.php");
//				
			}

			if ($action == "update")
			{
				$id = $DB->sanitize($_REQUEST["id"]);
				$sql = "UPDATE finance_options SET CompanyName = '" . $CompanyName ."', Address = '" . $Address ."', City = '" . $City ."', State = '" . $State ."', ZipCode = '" . $ZipCode ."', ContactName = '" . $ContactName ."', Phone = '" . $Phone ."', Extension = '" . $Extension ."', Email = '" . $Email ."', LoanOptions = '" . $LoanOptions ."', Reserve = '" . $Reserve . "' WHERE id = '" . $id . "'";

				$DB->execute_nonquery($sql);

				$DB->addHistory( 'users', $_SESSION["user_id"],  "update", $id );

				header("Location: ManageFinancing.php");
			}
		}
	}


$DB->close();

?>

<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Finance Options</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" style="font-size: small; line-height:34px;" id="custBulletLink">Add Finance Option</a></div>
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
			<h1>Financing Company Details</h1>


			<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

			   <ul class="form">
				<div style="float:left">

				 <li class="validated" id="tbCompanyName_li">
							  <label for="r_tbCompanyName">Company Name:</label>
							  <div id="tbCompanyName_img"></div>
							  <input class="validated" name="CompanyName" id="tbCompanyName" type="text" maxlength="200" value="<?= $CompanyName ?>"  />
							  <input type="hidden" id="tbCompanyName_val" value="waiting">
							  <div id="tbCompanyName_msg"></div>
					  </li> 
				 <li class="validated" id="tbAddress_li">
							  <label for="r_tbAddress">Address:</label>
							  <div id="tbAddress_img"></div>
							  <input class="validated" name="Address" id="tbAddress" type="text" maxlength="200" value="<?= $Address ?>"  />
							  <input type="hidden" id="tbAddress_val" value="waiting">
							  <div id="tbAddress_msg"></div>
					  </li>  
				 <li class="validated" id="tbCity_li">
							  <label for="r_tbCity">City:</label>
							  <div id="tbCity_img"></div>
							  <input class="validated" name="City" id="tbCity" type="text" maxlength="100" value="<?= $City ?>"  />
							  <input type="hidden" id="tbCity_val" value="waiting">
							  <div id="tbCity_msg"></div>
					  </li>  
				 <? $F->ddlStates($State); ?>
				 <li class="validated" id="tbZipCode_li">
							  <label for="r_tbZipCode">Zip Code:</label>
							  <div id="tbZipCode_img"></div>
							  <input class="validated" name="ZipCode" id="tbZipCode" type="text" maxlength="10" value="<?= $ZipCode ?>"  />
							  <input type="hidden" id="tbZipCode_val" value="waiting">
							  <div id="tbZipCode_msg"></div>
					  </li>
			</div><div style="float:left">
				 <li class="validated" id="tbContactName_li">
							  <label for="r_tbContactName">Contact Name:</label>
							  <div id="tbContactName_img"></div>
							  <input name="ContactName" id="tbContactName" type="text" maxlength="200" value="<?= $ContactName ?>"  />
							  <input type="hidden" id="tbContactName_val" value="waiting">
							  <div id="tbContactName_msg"></div>
					  </li>  
				 <li class="validated" id="tbPhone_li">
							  <label for="r_tbPhone">Phone:</label>
							  <div id="tbPhone_img"></div>
							  <input name="Phone" id="tbPhone" type="text" maxlength="50" value="<?= $Phone ?>"  />
							  <input type="hidden" id="tbPhone_val" value="waiting">
							  <div id="tbPhone_msg"></div>
					  </li>  
				 <li class="validated" id="tbExtension_li">
							  <label for="r_tbExtension">Extension:</label>
							  <div id="tbExtension_img"></div>
							  <input name="Extension" id="tbExtension" type="text" maxlength="20" value="<?= $Extension ?>"  />
							  <input type="hidden" id="tbExtension_val" value="waiting">
							  <div id="tbExtension_msg"></div>
					  </li>  
				 <li class="validated" id="tbEmail_li">
							  <label for="r_tbEmail">Email:</label>
							  <div id="tbEmail_img"></div>
							  <input class="validated" name="Email" id="tbEmail" type="text" maxlength="100" value="<?= $Email ?>"  />
							  <input type="hidden" id="tbEmail_val" value="waiting">
							  <div id="tbEmail_msg"></div>
					  </li>
			</div><div style="float:left; padding:10px;">
				Loan Options:
				<ul id="optionList" style="padding-left:15px; ">

				</ul>

				<span style="display: inline-block; margin-top:20px; margin-bottom: 5px">Add Loan Option:</span><br />
				<span style="display:inline-block; width: 88px; font-size: small">Name:</span><input style="width:100px; margin-left:5px;" type="text" id="tbLoanOption"><br /><span style="display:inline-block; width: 88px; font-size: small; margin-top:10px;">Reserve (%):</span><input style="width:100px; margin-left:5px;" type="text" id="tbReserve"><br />
				<span style="display:inline-block; width: 88px; font-size: small; margin-top:10px;">Display Order:</span><input id="tbDisplayOrder" style="width:100px; margin-left:5px;" type="text" id="tbDisplayOrder"><br />
				<input id="addOption" style="width: auto;" type="button" value="Add">
			</div>

			<div style="clear:both; width:400px">
			 <center><li class="validated" id="btnSubmit_li">
						  <label for="r_btnSubmit"></label>
						  <div id="btnSubmit_img"></div>
						  <input type="hidden" name="Action" value="<?= $Action ?>"></input>
						  <input id="btnSubmit" type="Submit" maxlength="20" value="Save to Database"  />
						  <div id="btnSubmit_msg"></div>
				  </li></center>
				 </div>
      
			   </ul>
			<!-- STORAGE -->
			<input type="hidden" id="hid" name="id" value="<?= $id ?>" />
			<input type="hidden" id="hLoanOptionData" name="LoanOptions" value='<?= $LoanOptions ?>' />

			</form>
		</div>
	</div>
</div>




<SCRIPT type="text/javascript">

	$(document).ready(function() {

		readOptions();

		$('#addOption').click(function() {
			addOption();
			return false;
		});
	});

	function addOption()
	{
		// Define the Storage Field
		var $storage = $('#hLoanOptionData');

		// See if we already have a value in the field
		if ($storage.val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($storage.val());
		}
		else
		{
			// Create a new JSON object
			var theObject = {loanOptions: []};	
		}

		var i = theObject.loanOptions.length;

		// Create new Object to add to the array
		var thisElement = {};
		thisElement.Index = i;

		// Get Info and add it to object
		thisElement.optionName = $('#tbLoanOption').val();
		thisElement.displayOrder = $('#tbDisplayOrder').val();
		thisElement.reserve = $('#tbReserve').val();

		theObject.loanOptions[i] = thisElement;

		var storage = JSON.stringify(theObject);

		$storage.val(storage);

		readOptions();

		$('#tbLoanOption, #tbDisplayOrder').val("");

	}

	function deleteOption(index)
	{
		// Define the Storage Field
		var $storage = $('#hLoanOptionData');

		var theObject = JSON.parse($storage.val());
		for (var i = 0; i < theObject.loanOptions.length; i++ )
		{
			if (index == theObject.loanOptions[i].Index)
			{
				delete theObject.loanOptions[i];

				// Clear Null nodes and rewrite products array
				var j = 0;
				var newElements = [];
				for (var k in theObject.loanOptions)
				{
					if (theObject.loanOptions[k] != null)
					{
						var newElement = theObject.loanOptions[k];
						newElements[newElements.length] = newElement;
					}
				}
				theObject.loanOptions = newElements;
			}
		}
		$storage.val(JSON.stringify(theObject));
		readOptions();
		return false;
	}


	function readOptions()
	{
		// Define the Storage Field
		var $storage = $('#hLoanOptionData');

		// See if we already have a value in the field
		if ($storage.val() != '')
		{
			// Read the existing object
			var theObject = JSON.parse($storage.val());
			
			$('#optionList li').remove();

			for (var i in theObject.loanOptions.sort(sort_by("displayOrder",false, parseFloat)))
			{
				var data = theObject.loanOptions[i];
				var optionName = data.optionName;
				var displayOrder = data.displayOrder;
				var reserve = data.reserve;
				var index = data.Index;


				$('#optionList').append("<li style=\"width:auto; margin:10px 0px 0px 0px; padding:0px;\"><span style=\"font-size: small; display:inline-block; width:150px\">" + optionName + " (" + displayOrder + ") (" + reserve + "%)</span><a href=\"#\" style=\"font-size: small; vertical-align: top;\" id=\"deleteLink_"  + index + "\" class=\"deleteLink\">Delete</a></li>");
			}

			$('.deleteLink').unbind();
			$('.deleteLink').click(function() {
				deleteOption(this.id.replace("deleteLink_", ""));
				return false;
			});

		}
		else
		{
			$('#optionList').append("<li style=\"width:auto; margin:10px 0px 0px 0px; padding:0px;\"><span style=\"font-size: small; display:inline-block; width:150px\">No Loan Options have been added.</span></li>");
		}


	}




</SCRIPT>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>