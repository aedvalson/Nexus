<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" ;


  
$DB = new conn();
$DB->connect();

// Get Contact Types
$sql = "select * from contact_types";
$result = $DB->query($sql);
$types = array();
if ($result)
{
	while ($row = mysql_fetch_assoc($result))
	{
		$types[] = $row;
	}
}


$firephp->log($types);


// Form Vars
	if ($_REQUEST)
	{
		if (isset($_REQUEST["Action"]))
		{
			$action = $DB->sanitize($_REQUEST["Action"]);
			$firephp->log($action);
			if ($action == "addNew")
			{
				$firstname = $DB->sanitize($_REQUEST["FirstName"]);
				$lastname = $DB->sanitize($_REQUEST["LastName"]);
				$displayname = $firstname . " " . $lastname;
				$email = $DB->sanitize($_REQUEST["Email"]);
				$address = $DB->sanitize($_REQUEST["Address"]);
				$address2 = $DB->sanitize($_REQUEST["Address2"]);
				$city = $DB->sanitize($_REQUEST["City"]);
				$state = $DB->sanitize($_REQUEST["State"]);
				$zipcode = $DB->sanitize($_REQUEST["ZipCode"]);
				$country = $DB->sanitize($_REQUEST["Country"]);
				$phone = $DB->sanitize($_REQUEST["Phone"]);
				$phonedetails = $DB->sanitize($_REQUEST["PhoneDetails"]);
				$notes = $DB->sanitize($_REQUEST["Notes"]);
				$contacttype = $DB->sanitize($_REQUEST["ContactType"]);
				$county = $DB->sanitize($_REQUEST["County"]);
				$home_status = $DB->sanitize($_REQUEST["HomeStatus"]);
				$home_type = $DB->sanitize($_REQUEST["HomeType"]);
				$license = $DB->sanitize($_REQUEST["license"]);
				$licensestate = $DB->sanitize($_REQUEST["licenseState"]);
				$social = $DB->sanitize($_REQUEST["social"]);


				
				$DB->addContact($firstname, $lastname, $displayname, $email, $address, $city, $state, $zipcode, $country, $phone, $phonedetails, $notes, $contacttype, $county, $address2, $home_status, $home_type, $license, $licensestate, $social);
				header("Location: ManageContacts.php");
			}
		}
	}


$DB->close();

?>




<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="Action" value="addNew">
<div class="navMenu" id="navMenu">
	<div id="bullets">
		<div class="navHeaderdiv"><h1>Contacts</h1></div>
		<div class="navBulletBorderTop"></div>
		<div class="navBullet navBulletSelected" id="custBullet"><a href="#" id="custBulletLink">Add Contact</a></div>
		<div class="navBulletBorderBottom"></div>
	</div>
	<div class="navPageSpacing"></div>
</div>


<div class="pageContent" id="pageContent">
	<div class="contentDiv">

		<div class="formDiv" style="display: block; background-color: #EDECDC">
			<h1>Add Contact</h1>
				<? $F = new FormElements(); ?>
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

						<? $F->submitButton("Create Customer", "btnSubmit", "clear:both"); ?>						
					</ul>
</div>
</div>
</div>

</form>

<SCRIPT type="text/javascript">
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

	$(document).ready( function() {
		bindCountyBox();
		$('#ddlState').change( function() {   bindCountyBox();   });
	});
</SCRIPT>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>

