<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" ;


  
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

				$FirstName = $DB->sanitize($_REQUEST["FirstName"]);
				$LastName = $DB->sanitize($_REQUEST["LastName"]);
				$Email = $DB->sanitize($_REQUEST["Email"]);
				$Phone = $DB->sanitize($_REQUEST["Phone"]);
				$PhoneDetails = $DB->sanitize($_REQUEST["PhoneDetails"]);
				$Address = $DB->sanitize($_REQUEST["Address"]);
				$City = $DB->sanitize($_REQUEST["City"]);
				$State = $DB->sanitize($_REQUEST["State"]);
				$ZipCode = $DB->sanitize($_REQUEST["ZipCode"]);
				$Country = $DB->sanitize($_REQUEST["Country"]);
				$Notes = $DB->sanitize($_REQUEST["Notes"]);
				$ContactType = $DB->sanitize($_REQUEST["ContactType"]);



				
				$sql = "INSERT INTO CONTACTS (contact_firstname, contact_lastname, contact_email, contact_phone, contact_phonedetails, contact_address, contact_city, contact_state, contact_zipcode, contact_country, contact_notes, contact_type_id) VALUES ('".$FirstName."', '".$LastName."', '".$Email."', '".$Phone."', '".$PhoneDetails."', '".$Address."', '".$City."', '".$State."', '".$ZipCode."', '".$Country."', '".$Notes."', '".$ContactType."')";
				$DB->execute_nonquery($sql);
				header("Location: ManageContacts.php");
			}
		}
	}


$DB->close();

?>




<form name="theForm" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
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

   <ul class="form">
	 <li class="validated" id="tbContactType_li">
                  <label for="r_tbContactType">ContactType:</label>
                  <div id="tbContactType_img"></div>
					<select class="validated" name="ContactType" id="ddlContactType" >
						<OPTION Value="2">Lead</OPTION>
						<OPTION Value="3">Customer</OPTION>
					</select>	
                  <div id="tbContactType_msg"></div>
          </li>
	 <li class="validated" id="tbFirstName_li">
                  <label for="r_tbFirstName">FirstName:</label>
                  <div id="tbFirstName_img"></div>
                  <input class="validated" name="FirstName" id="tbFirstName" type="text" maxlength="20"  />
                  <div id="tbFirstName_msg"></div>
          </li>
	 <li class="validated" id="tbLastName_li">
                  <label for="r_tbLastName">LastName:</label>
                  <div id="tbLastName_img"></div>
                  <input class="validated" name="LastName" id="tbLastName" type="text" maxlength="20"   />
                  <div id="tbLastName_msg"></div>
          </li>
	 <li class="validated" id="tbEmail_li">
                  <label for="r_tbEmail">Email:</label>
                  <div id="tbEmail_img"></div>
                  <input class="validated" name="Email" id="tbEmail" type="text" maxlength="20"   />
                  <div id="tbEmail_msg"></div>
          </li>
	 <li class="validated" id="tbPhone_li">
                  <label for="r_tbPhone">Phone:</label>
                  <div id="tbPhone_img"></div>
                  <input class="validated" name="Phone" id="tbPhone" type="text" maxlength="20"   />
                  <div id="tbPhone_msg"></div>
          </li>
	 <li class="validated" id="tbPhoneDetails_li">
                  <label for="r_tbPhoneDetails">PhoneDetails:</label>
                  <div id="tbPhoneDetails_img"></div>
                  <input class="validated" name="PhoneDetails" id="tbPhoneDetails" type="text" maxlength="20"   />
                  <div id="tbPhoneDetails_msg"></div>
          </li>
	 <li class="validated" id="tbAddress_li">
                  <label for="r_tbAddress">Address:</label>
                  <div id="tbAddress_img"></div>
                  <input class="validated" name="Address" id="tbAddress" type="text" maxlength="20"   />
                  <div id="tbAddress_msg"></div>
          </li>
	 <li class="validated" id="tbCity_li">
                  <label for="r_tbCity">City:</label>
                  <div id="tbCity_img"></div>
                  <input class="validated" name="City" id="tbCity" type="text" maxlength="20"   />
                  <div id="tbCity_msg"></div>
          </li>
	 <li class="validated" id="tbState_li">
                  <label for="r_tbState">State:</label>
                  <div id="tbState_img"></div>
					<select class="validated" name="State" id="ddlState" >
						<? echoUsStateOptions(); ?>
					</select>	
                  <div id="tbState_msg"></div>
          </li>
	 <li class="validated" id="tbZipCode_li">
                  <label for="r_tbZipCode">ZipCode:</label>
                  <div id="tbZipCode_img"></div>
                  <input class="validated" name="ZipCode" id="tbZipCode" type="text" maxlength="20"   />
                  <div id="tbZipCode_msg"></div>
          </li>
	 <li class="validated" id="tbCountry_li">
                  <label for="r_tbCountry">Country:</label>
                  <div id="tbCountry_img"></div>
					<select class="validated" name="Country" id="ddlCountry" >
						<? echoCountryOptions(); ?>
					</select>	
                  <div id="tbCountry_msg"></div>
          </li>

	 <li class="validated" id="tbNotes_li">
                  <label for="r_tbNotes">Notes:</label>
                  <div id="tbNotes_img"></div>
					<textarea name="Notes" id="tbNotes"></textarea>
                  <div id="tbNotes_msg"></div>
          </li>

	 <li class="validated" id="btnSubmit_li">
                  <label for="r_btnSubmit"></label>
                  <div id="btnSubmit_img"></div>
                  <input type="hidden" name="Action" value="addNew"></input>
                  <input id="btnSubmit" type="Submit" maxlength="20" value="Submit"  />
                  <div id="btnSubmit_msg"></div>
          </li>          
               
   </ul>

</div>
</div>
</div>

</form>

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>

