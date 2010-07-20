<?php 

include( $_SERVER['DOCUMENT_ROOT']."/$ROOTPATH/Includes/FormElements.php");




// Disable Magic Quotes, otherwise JSON posts get trashed.
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);

        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

function plural( $singular ) {
	if ($singular == "Product") return "Products";
	if ($singular == "Accessory") return "Accessories";
	return $singular;
}

function format_phone($phone)
{
	$phone = preg_replace("/[^0-9]/", "", $phone);

	if(strlen($phone) == 7)
		return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
	elseif(strlen($phone) == 10)
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
	else
		return $phone;
}


function firstOfMonth() {
return date("m/d/Y", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
return date("m/d/Y", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
}

function today() {
	return date("m/d/Y");
}

function thirtydaysago() {
	return date("m/d/Y", strtotime("last month"));
}

function prettyDate( $dateString )
{	
	return date("F d, Y   G:i:s A", strtotime($dateString) );
}



	function getClass($name)
	{
		$currentUrl = $_SERVER["REQUEST_URI"];
		if (strpos($currentUrl, $name))
		{
			return "navBulletSelected";
		}
		else
		{
			return "";
		}
	}



class conn
{

	var $myConn;

	function connect()
	{
		include( "findconfig.php");
		//echo "Connecting...";
		$this->myConn = mysql_connect("localhost", $dbuser, $dbpass);
		@mysql_select_db($dbname) or die("Unable to Select DB");
	}

	function close()
	{
		//echo "Closing...";
		mysql_close($this->myConn);
	}

	function cleanInput($input) {
	 
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);
	 
		$output = preg_replace($search, '', $input);
		return $output;
	}

	
	function sanitize($input) {
		if (is_array($input)) {
			foreach($input as $var=>$val) {
				$output[$var] = sanitize($val);
			}
		}
		else {
			if (get_magic_quotes_gpc()) {
				$input = stripslashes($input);
			}
			$input  = $this->cleanInput($input);
			$output = mysql_real_escape_string($input);
		}
		return $output;
	}
	
	
	function execute_nonquery($sql)
	{
		$result = mysql_query($sql);
		if (!$result) {
			echo "Could not successfully run query ($sql) from DB: " . mysql_error();
			return;
		}
		return mysql_affected_rows();
	}

	function insert($sql)
	{
		$result = mysql_query($sql);
		if (!$result) {
			echo "Could not successfully run query ($sql) from DB: " . mysql_error();
			return;
		}
		$identity = mysql_insert_id ($this->myConn);
		return $identity;
	}

	function query_scalar($sql, $default_value="undefined") {
	    $result = mysql_query($sql);
	    if (mysql_num_rows($result)==0)
	        return $default_value;
	    else
	        return mysql_result($result,0);
	}	
	function query($sql)
	{
				
		$result = mysql_query($sql);

		if (!$result) {
			echo "Could not successfully run query ($sql) from DB: " . mysql_error();
			return;
		}

		if (mysql_num_rows($result) == 0) {
			//echo "No rows found.";
			return;
		}

		return $result;
	}


	function getCommissionTemplates($price)
	{
		$this->connect();
		$sql = "select * from commission_templates";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
			return "none";
		}

		$retArray = array();
		while ($row = mysql_fetch_assoc($result))
		{
			$templateMin = 0;
			$elements = json_decode($row["template_elements"]);

			$templateID = $row["id"];	
			$minPrice = $row["min_price"];
			$maxPrice = $row["max_price"];
			$templateName = $row["template_name"];
			$elements = $row["template_elements"];

			if ($price <= $maxPrice && $price >= $minPrice)
			{
				
				$newRow = Array();
				$newRow["templateID"] = $templateID;
				$newRow["templateName"] = $templateName;
				$newRow["template_elements"] = $elements;
				
				$retArray[] = $newRow;
			}
		}
		$this->close();
		return $retArray;

	}

	function getStorageLocations()
	{
		$this->connect();
		$sql = "select * from storagelocations";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Storage locations have been added. You must add at least one Storage location before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
//		$this->close();
		return $retArray;
	}

	function getPermissionRoles()
	{
		$this->connect();
		$sql = "select * from permission_roles";
		$result = $this->query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		return $retArray;
		$this->close();
	}

	function getDealerRoles()
	{
		$this->connect();
		$sql = "select * from dealer_roles";
		$result = $this->query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		return $retArray;
		$this->close();
	}


	function getInventoryStatuses()
	{
		$this->connect();
		$sql = "select * from inventory_status";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Inventory Statuses have been added. You must add at least one Inventory Status before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
//		$this->close();
		return $retArray;
	}



	function getTeams()
	{
		$this->connect();
		$sql = "select teams.*, users.FirstName, users.LastName from teams join users on teams.team_leader = users.user_id where teams.status != 'deleted'";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Teams have been added. You must add at least one Team before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		//$this->close();
		return $retArray;
	}
	function getProducts($type="%")
	{
		$this->connect();
		$_type = $this->sanitize($type);
		$sql = "select * from products where product_type LIKE '%".$_type."%'";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Products locations have been added. You must add at least one Product before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		//$this->close();
		return $retArray;
	}
	function getProductTypes()
	{
		$this->connect();
		$_type = $this->sanitize($type);
		$sql = "select DISTINCT product_type from products";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Products locations have been added. You must add at least one Product before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		//$this->close();
		return $retArray;
	}	

	function getUsers()
	{
		$this->connect();
		$sql = "select * from users where status != 'deleted'";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Users have been added. You must add at least one User before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		//$this->close();




		return $retArray;
	}	

	function getUserStatuses()
	{
		$this->connect();
		$sql = "select * from user_statuses";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Users have been added. You must add at least one User before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		//$this->close();
		return $retArray;
	}

	function getContactTypes()
	{
		$this->connect();
		$sql = "select * from contact_types order by contact_type_name";
		$result = mysql_query($sql);
		if (mysql_num_rows($result) == 0) {
				echo "No Users have been added. You must add at least one User before using this tool.";
				return;
		}
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
		}
		//$this->close();
		return $retArray;
	}


	function addHistory( $table, $userid, $action, $data )
	{


		$table = $this->sanitize($table);
		$userid = $this->sanitize($userid);
		$action = $this->sanitize($action);
		$data = $this->sanitize($data);

		$sql = "insert into admin_history (user_id, table_name, action_name, value) VALUES ('" . $userid . "', '" . $table . "', '" . $action . "', '" . $data . "')";
		
		$this->execute_nonquery($sql);

	}

	function validateUser($user, $pass)
	{
		$this->connect();
		$sql = "select count(*) from users where username = '$user' and user_password = '$pass'";
		$result = $this->query_scalar($sql);
		$this->close();
		if ($result == 0)
		{
			return false;
		}
		if ($result == 1)
		{
			return true;
		}
	}

	function addContact($firstname, $lastname, $displayname, $email, $address, $city, $state, $zipcode, $country, $phone, $phonedetails, $notes="", $contacttype, $county="", $address2="", $home_status="", $home_type="", $license="", $licensestate="", $social="")
	{
		$sql = "INSERT INTO contacts (contact_firstname, contact_lastname, contact_DisplayName, contact_email, contact_phone, contact_phonedetails, contact_address, contact_city, contact_state, contact_zipcode, contact_country, contact_notes, contact_type_id, county, contact_address2, contact_home_status, contact_home_type, contact_license, contact_license_state, contact_social) VALUES ('".$firstname."', '".$lastname."', '".$displayname."', '".$email."', '".$phone."', '".$phonedetails."', '".$address."', '".$city."', '".$state."', '".$zipcode."', '".$country."', '".$notes."', '".$contacttype."', '".$county."', '".$address2."', '".$home_status."', '".$home_type."', '".$license."', '".$licensestate."', '".$social."')";

		$identity = $this->insert($sql);
		return $identity;
	}


	function getContactInfo($contact_id)
	{
		$this->connect();
		$sql = "SELECT * FROM contacts where contact_id = ".$contact_id;
		$result = $this->query($sql);
		if ($result)
		{
			if (mysql_num_rows($result) == 1)
			{
				while ($row = mysql_fetch_assoc($result))
				{
					$retArray[] = $row;
				}
			}
		}
		return $retArray;

	}


	function getOrderStatuses()
	{
		$this->connect();
		$sql = "SELECT * FROM order_status";
		$result = $this->query($sql);
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$retArray[] = $row;
			}
		}
		return $retArray;
	}



	function searchContacts($val)
	{
		$this->connect();
		$sql = <<<SQLEND
		SELECT * FROM contacts 
			WHERE contact_firstname LIKE '%$val%'
			OR contact_lastname LIKE '%$val%'
			OR contact_address LIKE '%$val%'
			OR contact_phone LIKE '%$val%'
SQLEND;

		$result = $this->query($sql);
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				$retArray[] = $row;
			}
		}
		return $retArray;
	}

}



function echoCountryOptions()
{
	?>

		<option value="United States">United States</option>
		<option value="Australia">Australia</option>
		<option value="Austria">Austria</option>
		<option value="Belgium">Belgium</option>
		<option value="Brazil">Brazil</option>
		<option value="Canada">Canada</option>
		<option value="China">China</option>
		<option value="Denmark">Denmark</option>
		<option value="Finland">Finland</option>
		<option value="France">France</option>
		<option value="Germany">Germany</option>
		<option value="Hong Kong">Hong Kong</option>
		<option value="Italy">Italy</option>
		<option value="Japan">Japan</option>

	<?
}


function echoUsStateOptions()
{
	?>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>

	<?
}

?>