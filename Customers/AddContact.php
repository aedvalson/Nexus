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
					<div style="float:left; width:260px; padding:15px">
					<? $F = new FormElements(); ?>
					<ul class="form">
						<? $F->ddlContactType(); ?>
						<? $F->tbVal("FirstName"); ?>
						<? $F->tbVal("LastName"); ?>
						<? $F->tbNotVal("Email"); ?>
						<? $F->tbNotVal("Phone"); ?>
						<? $F->tbNotVal("PhoneDetails"); ?>
						<? $F->ddlHomeType(); ?>
						<? $F->ddlHomeStatus(); ?>
						<? $F->tbContactNotes(); ?>
					</ul>
					</div>
					<div style="float:left; width:260px; padding:15px">
					<ul class="form">
						<? $F->tbNotVal("Address"); ?>
						<? $F->tbNotVal("Address2", "Address Line 2"); ?>
						<? $F->tbNotVal("City"); ?>
						<? $F->ddlStates(); ?>
						<? $F->tbNotVal("ZipCode"); ?>
						<? $F->ddlGeneric("County", "County"); ?>
						<? $F->ddlCountries(); ?>
						<? $F->tbNotVal("license", "Driver's License"); ?>
						<? $F->ddlStates("AL", "licenseState", "Driver's License Issuing State"); ?>
						<? $F->tbNotVal("social", "Social Security #"); ?>
						<? $F->submitButton("Create Customer"); ?>						
					</ul>
					</div>
					<div style="clear:both"></div>
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

