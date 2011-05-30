<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/class_inc.php";
	require_once( $DOCROOT.$ROOTPATH."/firephp/FirePHP.class.php");
	$firephp = FirePHP::getInstance(true);
  
  ob_start();
  session_start();
   if (isset($_POST["id"]))
   {
   }
   else
   {
	   foreach ($_POST as $k->$v)
	   {
		echo ($k."-".$v);
	   }
	   die;
   }
	   
	$id = $_POST["id"];
	$success = "success";
	$error = "Successful";
	$output = "";
	
	if ($id == "tbUsername")
	{
		
		$DB = new conn();
		$DB->connect();
		$username = $DB->sanitize($_POST["value"]);
		
		$sql = "Select count(*) from users where username = '".$username."' and status != 'deleted'";
		$result = $DB->query_scalar($sql);

		if ($result > 0)
		{
			$success = "fail";
			$error = "Username Already Exists!";
		}
		$DB->close();
	}

	if ($id == "getStorageLocations")
	{
		$DB = new conn();
		$output = $DB->getStorageLocations();
	}

	if ($id == "getUsers")
	{
		$DB = new conn();
		$output = $DB->getUsers();
	}


	if ($id == "createCustomer")
	{
		$DB = new conn();
		$DB->connect();
		
		$firephp->log($_REQUEST);
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
		$contact_alternate_address = $DB->sanitize($_REQUEST["contact_alternate_address"]);
		$contact_alternate_address2 = $DB->sanitize($_REQUEST["contact_alternate_address2"]);
		$contact_alternate_city = $DB->sanitize($_REQUEST["contact_alternate_city"]);
		$contact_alternate_state = $DB->sanitize($_REQUEST["contact_alternate_state"]);
		$contact_alternate_zipcode = $DB->sanitize($_REQUEST["contact_alternate_zipcode"]);
		$contact_alternate_country = $DB->sanitize($_REQUEST["contact_alternate_country"]);


		$newCustomer = $DB->addContact($firstname, $lastname, $displayname, $email, $address, $city, $state, $zipcode, $country, $phone, $phonedetails, $notes, $contacttype, $county, $address2, $home_status, $home_type, $license, $licensestate, $contact_alternate_address, $contact_alternate_address2, $contact_alternate_city, $contact_alternate_state, 		$contact_alternate_zipcode, $contact_alternate_country);

		$output = $newCustomer;
	}


	if ($id == "submitOrder")
	{
		$DB = new conn();
		$DB->connect();
		
		$orderStatus		= $DB->sanitize($_REQUEST["orderStatus"]);
		$customer_id		= $DB->sanitize($_REQUEST["customer_id"]);
		$cobuyer_id			= $DB->sanitize($_REQUEST["cobuyer_id"]);
		if ($cobuyer_id == "Not Yet Set") { $cobuyer_id = 0; }
		$amount				= $DB->sanitize($_REQUEST["amount"]);
		$CommStructureString= $_REQUEST["CommStructureString"];
		$ProductsString		= $_REQUEST["ProductsString"];
		$AccessoriesString	= $DB->sanitize($_REQUEST["AccessoriesString"]);
		$PaymentString		= $DB->sanitize($_REQUEST["PaymentString"]);
		$order_id			= $DB->sanitize($_REQUEST["order_id"]);
		$user_id			= $DB->sanitize($_REQUEST["user_id"]);
		$dateCompleted		= $DB->sanitize($_REQUEST["dateCompleted"]);
		$dealerArray		= $_REQUEST["dealerArray"];
		$tax				= $DB->sanitize($_REQUEST["tax"]);
		if (!$tax || !is_numeric($tax)) { $tax = 0; }
		$date = "";
		
		if ($dateCompleted != "" && $orderStatus == 5) // We only add a completed date if order is actually completed.
		{
			$ts = strtotime($dateCompleted);
			$date = date("Y-m-d", $ts);
		}
		else $date = "";

		$firephp->log("ORDERSTATUS: " . $orderStatus);

		if ($orderStatus < 5 || $orderStatus == 7)		$newStatus = 4;
		if ($orderStatus == 5 || $orderStatus == 6)		$newStatus = 5;
		if ($orderStatus == 8)							$newStatus = 1;

		$products = json_decode($ProductsString, true);
		$prod2 = json_encode($products["products"]);
		$prodArray = json_decode($prod2, true);

		if ($dealerArray)
		{
			// Insert Real Dealer Names from DB
			$dealerArrayObject = json_decode($dealerArray, true);
			$rolesArray = $dealerArrayObject["roles"];
			$newRolesArray = array();
			foreach ($rolesArray as $role)
			{
				$username = $role["userText"];
				$sql = "select CONCAT(FirstName, ' ', LastName) as DisplayName from users where Username = '" . $username . "'";
				$role["displayName"] = $DB->query_scalar($sql);
				$newRolesArray[] = $role;
				$firephp->log($newRolesArray);
			}
			$dealerArrayObject["roles"] = $newRolesArray;
		}

		if (is_numeric($order_id))
		{
			$query2 = 'UPDATE orders SET order_status_id = ' . $orderStatus . ', contact_id = ' . $customer_id . ', cobuyer_contact_id = ' . $cobuyer_id . ',  tax=' . $tax . ', amount = ' . $amount . ', CommStructure = \'' . $CommStructureString . '\', ProductsArray = \'' . $ProductsString . '\', AccessoriesArray = \'' . $AccessoriesString . '\', PaymentArray = \'' . $PaymentString . '\', dealerArray = \'' . json_encode($dealerArrayObject) . '\' WHERE order_id = ' . $order_id;

			$DB->execute_nonquery($query2);
			$output = $order_id;
		}

		else
		{
			$query1 = 'insert into orders (order_status_id, contact_id, cobuyer_contact_id, amount, CommStructure, ProductsArray, AccessoriesArray, PaymentArray, AddedBy, dealerArray, tax) VALUES (' . $orderStatus . ', \'' . $customer_id . '\', \'' . $cobuyer_id . '\', ' . $amount . ', \'' . $CommStructureString . '\', \'' . $ProductsString . '\', \'' . $AccessoriesString . '\', \'' . $PaymentString . '\', ' . $user_id . ', \'' . $dealerArray . '\', ' . $tax . ')';
			$output = $DB->insert($query1);
			$order_id = $output;
		}



		// Get First storage location from DB. TODO: Get DEFAULT
		$sql = "select * from storagelocations";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$storagelocation_id = $row["storagelocation_id"];
		$storagelocation_name = $row["storagelocation_name"];


		// Update inventory status for items in order.
		if ($prodArray)
		{
			if ($newStatus == 1)  // Free up inventory - sale is cancelled
			{
				// Reset all inventory that is contained in this product to default locationa
				$sql_update = "update inventory set status = 1, status_data = " . $storagelocation_id . " , status_data_text = '" . $storagelocation_name . "' WHERE (status = 4 or status = 5) AND status_data = " . $order_id;
				$firephp->log($sql_update);
				$DB->execute_nonquery($sql_update);

				$sql_update = "update orders set ProductsArray = '[]' where order_id = $order_id";
				$firephp->log($sql_update);
				$DB->execute_nonquery($sql_update);
			}

			else  // Inventory stays in sale. update it.
			{
				foreach ($prodArray as $product)
				{
					$serial = $product['Serial'];
					$sql = "UPDATE inventory set status = " . $newStatus . ", status_data = " . $order_id . ", status_data_text = " . $order_id . " WHERE serial = " . $serial;
					$DB->execute_nonquery($sql);
				}
			}
		}

		// Update CompletedDate for order if set
		if ($date && $orderStatus == 5) $sql = "UPDATE orders SET DateCompleted = '".$date."' WHERE order_id = " . $order_id;
		else $sql = "UPDATE orders SET DateCompleted = null WHERE order_id = " . $order_id;
		$DB->execute_nonquery($sql);
	}


	if ($id == "updateStorage")
	{
		if (!UserMay("Admin_EditStorage")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$description = $DB->sanitize($_REQUEST["description"]);
		$location_id = $DB->sanitize($_REQUEST["Location_ID"]);
		$name = $DB->sanitize($_REQUEST["name"]);

		$sql = "UPDATE storagelocations SET storagelocation_name = '".$name."', description = '".$description."' where storagelocation_id = ".$location_id;

		$success = "success";
		$DB->execute_nonquery($sql);
		$DB->close();
	}

	if ($id == "updateProduct")
	{
		if (!UserMay("Admin_EditProducts")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$Product_id = $DB->sanitize($_REQUEST["Product_id"]);
		$Product_name = $DB->sanitize($_REQUEST["Product_name"]);
		$description = $DB->sanitize($_REQUEST["description"]);
		$id = $DB->sanitize($_REQUEST["id"]);
		$model = $DB->sanitize($_REQUEST["model"]);
		$status = $DB->sanitize($_REQUEST["status"]);
		$type = $DB->sanitize($_REQUEST["type"]);

		$sql = "UPDATE products set product_name = '".$Product_name."', product_description = '".$description."', product_model = '".$model."', status = '".$status."', product_type = '".$type."' WHERE product_id = ".$Product_id;

		$DB->execute_nonquery($sql);
		$DB->close();
	}


	if ($id == "updateTemplate")
	{
		if (!UserMay("Admin_EditComm")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$template_id = $DB->sanitize($_REQUEST["template_id"]);
		$min_price = $DB->sanitize($_REQUEST["min_price"]);
		$max_price = $DB->sanitize($_REQUEST["max_price"]);
		$template_name = $DB->sanitize($_REQUEST["template_name"]);

		$sql = "UPDATE commission_templates SET template_name = '".$template_name."', min_price = ".$min_price.", max_price = ".$max_price." WHERE id = ".$template_id;

		$DB->execute_nonquery($sql);

		$sql = "select * from commission_templates WHERE id = ".$template_id;

		$error = $sql;
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}

		$DB->close();
	}

	if ($id == "deleteTemplate")
	{
		if (!UserMay("Admin_EditComm")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$template_id = $DB->sanitize($_REQUEST["template_id"]);
		
		$sql = "DELETE from commission_templates WHERE id = ".$template_id;

		$error = $sql;
		$DB->execute_nonquery($sql);

		$DB->close();
	}

	if ($id == "deleteFinanceOption")
	{
		if (!UserMay("Admin_EditFinance")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$f_id = $DB->sanitize($_REQUEST["f_id"]);
		
		$sql = "DELETE from finance_options WHERE id = ".$f_id;

		$error = $sql;
		$DB->execute_nonquery($sql);

		$DB->close();
	}

	if ($id == "deleteTaxRate")
	{
		if (!UserMay("Admin_EditTax")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$f_id = $DB->sanitize($_REQUEST["f_id"]);
		
		$sql = "DELETE from TaxRates WHERE id = ".$f_id;

		$error = $sql;
		$DB->execute_nonquery($sql);

		$DB->close();
	}



	if ($id == "deleteUser")
	{
		if (!UserMay("Admin_EditUsers")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$user_id = $DB->sanitize($_REQUEST["user_id"]);
		
		$sql = "UPDATE users set status = 'deleted', username = CONCAT('DELETED_USER: ', username) where user_id = ".$user_id;

		$error = $sql;
		$DB->execute_nonquery($sql);

		$DB->close();
	}

	if ($id == "deleteTeam")
	{
		if (!UserMay("Admin_EditTeams")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$user_id = $DB->sanitize($_REQUEST["team_id"]);
		
		$sql = "UPDATE teams set status = 'deleted', team_name = CONCAT('DELETED_TEAM: ', team_name) where team_id = ".$user_id;

		$error = $sql;
		$DB->execute_nonquery($sql);

		$DB->close();
	}

	if ($id == "updateFinanceOption")
	{
		if (!UserMay("Admin_EditFinance")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$f_id = $DB->sanitize($_REQUEST["f_id"]);
		$CompanyName = $DB->sanitize($_REQUEST["Company"]);
		$ContactName = $DB->sanitize($_REQUEST["ContactName"]);
		$Email = $DB->sanitize($_REQUEST["Email"]);

		$sql = "UPDATE finance_options SET CompanyName = '".$CompanyName."', ContactName = '".$ContactName."', Email = '".$Email."' WHERE id = ".$f_id;

		$DB->execute_nonquery($sql);
		$error = $sql;
		$DB->close();
	}

	if ($id == "updateTaxRate")
	{
		if (!UserMay("Admin_EditTax")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$f_id = $DB->sanitize($_REQUEST["f_id"]);
		$rate = $DB->sanitize($_REQUEST["rate"]);

		$sql = "UPDATE TaxRates SET rate = ".$rate." WHERE id = ".$f_id;

		$DB->execute_nonquery($sql);
		$error = $sql;
		$DB->close();
	}
	


	if ($id == "updateTeam")
	{
		if (!UserMay("Admin_EditTeams")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$team_id = $DB->sanitize($_REQUEST["team_id"]);
		$team_leader = $DB->sanitize($_REQUEST["team_leader"]);
		$team_name = $DB->sanitize($_REQUEST["team_name"]);

		$sql = "UPDATE teams SET team_leader = ".$team_leader.",  team_name = '".$team_name."' WHERE team_id = ".$team_id;
		$DB->execute_nonquery($sql);

		$sql = <<<SQLEND
				
			SELECT teams.*, users.firstname, users.lastname
				from teams
			left outer join users on teams.team_leader = users.user_id
			where teams.status != 'deleted' and teams.team_id = $team_id

SQLEND;
		$error = $sql;
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}

		$DB->close();
	}

	if ($id == "updateUser")
	{
		if (!UserMay("Admin_EditUsers")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();
		
		$username = $DB->sanitize($_REQUEST["Username"]);
		$FirstName = $DB->sanitize($_REQUEST["FirstName"]);
		$LastName = $DB->sanitize($_REQUEST["LastName"]);
		$user_id = $DB->sanitize($_REQUEST["user_id"]);
		$team_id = $DB->sanitize($_REQUEST["team_id"]);
		$status = $DB->sanitize($_REQUEST["Status"]);
		$perm = $DB->sanitize($_REQUEST["perm"]);
		$sender = $DB->sanitize($_REQUEST["sender"]);

		$sql = "UPDATE users SET Username = '".$username."', FirstName = '".$FirstName."', LastName = '".$LastName."', team_id = ".$team_id.", Status='".$status."', permission_role = ".$perm." WHERE user_id = ".$user_id;

		
		$DB->execute_nonquery($sql);

		$sql = "SELECT users.*, teams.team_name from users left outer join teams on users.team_id = teams.team_id where user_id = ".$user_id."  and users.status != 'deleted'";
		$error = $sql;
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}


		$DB->addHistory( 'users', $sender,  "update", $username );

		$DB->close();
	}


	if ($id == "updateInventoryStatus")
	{
		if (!UserMay("EditInventory")) { AccessDenied(); }

		$DB = new conn();
		$DB->connect();

		$date = $DB->sanitize($_REQUEST["date"]);
		$inventory_id = $DB->sanitize($_REQUEST["inventory_id"]);
		$status = $DB->sanitize($_REQUEST["status"]);
		$statusdata = $DB->sanitize($_REQUEST["statusdata"]);
		$statusdate = $DB->sanitize($_REQUEST["date"]);
		$receivedDate = $DB->sanitize($_REQUEST["receivedDate"]);
		$statusdatatext = '';
		$sqlparam = "";

		if ($status == 1)
		{
			$sql = "select storagelocation_name from storagelocations where storagelocation_id = " .$statusdata;
			$statusdatatext = $DB->query_scalar($sql);
			$sqlparam = ", storagelocation_id = " . $statusdata;

		}
		elseif ($status == "2") #checked out
		{
			$sql = "select username from users where user_id = " . $statusdata;
			$statusdatatext = $DB->query_scalar($sql);

		}
		elseif ($status == "3") #transferred
		{
			$sql = "UPDATE inventory set dtoffice = $statusdata where inventory_id = $inventory_id";
			$DB->execute_nonquery($sql);
		}
		else
		{
			$statusdatatext = '';
		}
		
		if ($status != "3")
		{
			$sql = "UPDATE inventory SET status = $status, status_data = $statusdata, status_data_text = '" . $statusdatatext . "', status_date =  STR_TO_DATE('".$statusdate."', '%m/%d/%Y'), DateReceived = STR_TO_DATE('".$receivedDate."', '%m/%d/%Y')" . $sqlparam . " WHERE inventory_id = ".$inventory_id;

			$DB->execute_nonquery($sql);
			$error = $sql;
		}

		$sql = "select inventory.*, inventory_status.status_name, inventory_status.preposition from inventory join inventory_status on inventory.status = inventory_status.status_id, inventory.dtoffice  where inventory_id = " . $inventory_id;
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}

		

		$DB->close();

	}

		
		
		

	if ($id == "tbLocationName")
	{
		
		$DB = new conn();
		$DB->connect();
		$val = $DB->sanitize($_POST["value"]);
		
		$sql = "Select count(*) from storagelocations where LCASE(storagelocation_name) = LCASE('".$val."')";
		$result = $DB->query_scalar($sql);

		if ($result > 0)
		{
			$success = "fail";
			$error = "Location already Exists!";
		}
		$DB->close();
	}
	
	if ($id == "tbTeamName")
	{
		
		$DB = new conn();
		$DB->connect();
		$val = $DB->sanitize($_POST["value"]);
		
		$sql = "Select count(*) from teams where LCASE(team_name) = LCASE('".$val."')";

		
		$result = $DB->query_scalar($sql);

		if ($result > 0)
		{
			$success = "fail";
			$error = "Team already Exists!";
		}
		$DB->close();
	}	
	


	if ($id == "getOrderInfo")
	{
		$DB = new conn();
		$DB->connect();
		$val = $DB->sanitize($_POST["value"]);

		$sql = "select orders.* from orders where order_id = " . $val;

		
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
	}

	if ($id == "getCommBlock")
	{
		$DB = new conn();
		$DB->connect();
		$val = $DB->sanitize($_POST["selected"]);

		$sql = "select * from commission_templates where id = " . $val;
		
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
	}


	
	if ($id == "getSerials")
	{
		$DB = new conn();
		$DB->connect();
		$product_id = $DB->sanitize($_REQUEST["product_id"]);

		$sql = "select serial from inventory where product_id = '" . $product_id . "' and status < 3";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}

		$DB->close();
	}
	if ($id == "getCounties")
	{
		$DB = new conn();
		$DB->connect();
		$state = $DB->sanitize($_REQUEST["state"]);

		$sql = "select distinct county from TaxRates where state = '" . $state . "'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}

		$DB->close();
	}

	if ($id == "getItemizedInventory")
	{
				
		$DB = new conn();
		$DB->connect();
		$val = $DB->sanitize($_POST["value"]);
		
		$sql = <<<SQLEND
		Select  products.product_name, products.product_model, sum(inventory.quantity) as quantity, storagelocations.storagelocation_name from Products 
	 	INNER JOIN (Inventory, storagelocations) ON (inventory.product_id = products.product_id and inventory.storagelocation_id = storagelocations.storagelocation_id)
	 	where products.product_id = $val 
	 	GROUP BY  products.product_name, products.product_model, inventory_invoice
SQLEND;

		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();

	}

	if ($id == "getInventoryDetails")
	{
		
		$DB = new conn();
		$DB->connect();
		$val = $DB->sanitize($_POST["value"]);

		$sql = <<<SQLEND
			select inventory.*, sl.storagelocation_name, inventory_status.status_name
			from inventory 
			join storagelocations sl on inventory.storagelocation_id = sl.storagelocation_id 
			join inventory_status on inventory.status = inventory_status.status_id
			where inventory.inventory_id = $val
SQLEND;

		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();

	}

	
	if ($id == ("getCommissionTemplates"))	
	{
		$DB = new conn();
		$DB->connect();
		if (isset($_REQUEST["price"]))
		{
			$price = $DB->sanitize($_REQUEST["price"]);
		}
		else
		{
			$price = 0;
		}
		$templates = $DB->getCommissionTemplates($price);
		$output = $templates;
	}


	if ($id == ("getTaxRate"))	
	{
		$DB = new conn();
		$DB->connect();
		$state= $DB->sanitize($_REQUEST["state"]);
		$county = $DB->sanitize($_REQUEST["county"]);
		
		$sql = "select rate from TaxRates where state = '" . $state . "' AND county = '" . $county . "'";
		$result = $DB->query_scalar($sql);
		$output = $result;
		$DB->close();
	}


	if ($id == ("getTemplates"))
	{
		if (!UserMay("Admin_ViewComm")) { AccessDenied(); }		
		$DB = new conn();
		$DB->connect();

		
		$sql = <<<SQLEND
			select * from commission_templates
SQLEND;
		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}

	if ($id == ("getNewTaxRatesTable"))
	{
		if (!UserMay("Admin_ViewTax")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$sql = <<<SQLEND
			select * from TaxRates
			
SQLEND;
	$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
	}

	if ($id == ("addTaxRate"))
	{
		if (!UserMay("Admin_EditTax")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();

		$county = $DB->sanitize($_REQUEST["county"]);
		$rate = $DB->sanitize($_REQUEST["rate"]);
		$state = $DB->sanitize($_REQUEST["state"]);

		$sql = "insert into TaxRates (county, state, rate) VALUES ('" . $country . "', '" . $state ."', " . $rate . ")";

		$DB->execute_nonquery($sql);
		$DB->close();
	}

	if ($id == ("getNewFinanceOptionsTable"))
	{
		if (!UserMay("Admin_ViewFinance")) { AccessDenied(); }		
		$DB = new conn();
		$DB->connect();


		$sql = <<<SQLEND
			select * from finance_options
			
SQLEND;

		$and = false;

		if (isset($_POST["address"]))
		{
			$address = $DB->sanitize($_POST["address"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "(Address LIKE '%".$address."%' OR City LIKE '%".$address."%' OR State LIKE '%".$address."%' OR ZipCode LIKE '%".$address."%') ";
			$and = true;
		}

		if (isset($_POST["company"]))
		{
			$company = $DB->sanitize($_POST["company"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "CompanyName LIKE '%".$company."%' ";
			$and = true;
		}

		if (isset($_POST["contact"]))
		{
			$contact = $DB->sanitize($_POST["contact"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "ContactName LIKE '%".$contact."%' ";
			$and = true;
		}

		if (isset($_POST["options"]))
		{
			$contact = $DB->sanitize($_POST["options"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "LoanOptions LIKE '%".$options."%' ";
			$and = true;
		}

		if (isset($_POST["email"]))
		{
			$email = $DB->sanitize($_POST["email"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "Email LIKE '%".$email."%' ";
			$and = true;
		}

		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}


	if ($id == ("getNewContactTable"))
	{
		if (!UserMay("ViewContacts")) { AccessDenied(); }		
		$DB = new conn();
		$DB->connect();


		$sql = <<<SQLEND
			select contacts.*, contact_types.contact_type_name as contacttype 
			from contacts 
			join contact_types on contacts.contact_type_id = contact_types.contact_type_id
			
SQLEND;


		$and = false;

		if (isset($_POST["first"]))
		{
			$first = $DB->sanitize($_POST["first"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "contacts.contact_firstname LIKE '".$first."'";
			$and = true;
		}
		if (isset($_POST["last"]))
		{
			$last = $DB->sanitize($_POST["last"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "contacts.contact_lastname LIKE '".$last."'";
			$and = true;
		}
		if (isset($_POST["contacttype"]))
		{
			$last = $DB->sanitize($_POST["contacttype"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "contacts.contact_type_id LIKE '".$last."'";
			$and = true;
		}

		if (isset($_POST["Email"]))
		{
			$last = $DB->sanitize($_POST["Email"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "contacts.contact_email LIKE '".$last."'";
			$and = true;
		}

		if (isset($_POST["Phone"]))
		{
			$last = $DB->sanitize($_POST["Phone"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "contacts.contact_phone LIKE '".$last."'";
			$and = true;
		}
		if (isset($_POST["Notes"]))
		{
			$last = $DB->sanitize($_POST["Notes"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "contacts.contact_notes LIKE '%".$last."%'";
			$and = true;
		}

		$sql = $sql . " order by contact_types.contact_type_name, contact_lastname ";



		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}


	if ($id == ("generateReport"))
	{
		if (!UserMay("PDFReports")) { AccessDenied(); }		
		$DB = new conn();
		$DB->connect();

		$text = mysql_real_escape_string($_REQUEST["value"]);

		$sql = "INSERT INTO reports (data) VALUES ('" . $text . "')";

		$id = $DB->insert($sql);
		$output = $id;
	}




	if ($id == ("getContact"))
	{
		
		$DB = new conn();
		$DB->connect();

		$val = $DB->sanitize($_REQUEST["value"]);
		$array = $DB->getContactInfo($val);

		$output = $array;
	}

	if ($id == "searchContacts")
	{
		
		$DB = new conn();
		$DB->connect();

		$val = $DB->sanitize($_REQUEST["value"]);
		$array = $DB->searchContacts($val);

		$output = $array;
	}


	if ($id == "getNewProductTable")
	{
		if (!UserMay("Admin_ViewProducts")) { AccessDenied(); }
		$DB=new conn();
		$DB->connect();

		$sql = <<<SQLEND
				
			SELECT * from products

SQLEND;

		$and = false;


		if (isset($_POST["productDescription"]))
		{
			$productDescription = $DB->sanitize($_POST["productDescription"]);
			if ($productDescription != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "product_description LIKE '%".$productDescription."%' ";
				$and = true;
			}
		}

		if (isset($_POST["productModel"]))
		{
			$productModel = $DB->sanitize($_POST["productModel"]);
			if ($productModel != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "product_model LIKE '%".$productModel."%' ";
				$and = true;
			}
		}

		if (isset($_POST["productType"]))
		{
			$productType = $DB->sanitize($_POST["productType"]);
			if ($productType != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "product_type LIKE '%".$productType."%' ";
				$and = true;
			}
		}

		if (isset($_POST["productName"]))
		{
			$productName = $DB->sanitize($_POST["productName"]);
			if ($productName != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "product_name LIKE '%".$productName."%' ";
				$and = true;
			}
		}

		$error = $sql;
		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}


	if ($id == "getNewStorageTable")
	{
		$DB=new conn();
		$DB->connect();

		$sql = <<<SQLEND
				
			SELECT * from storagelocations

SQLEND;

		$error = $sql;
		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}


	if ($id == "getNewTeamTable")
	{
		if (!UserMay("Admin_ViewTeams")) { AccessDenied(); }
		$DB=new conn();
		$DB->connect();

		$sql = <<<SQLEND
				
			SELECT teams.*, users.firstname, users.lastname, (select count(*) from users where users.team_id = teams.team_id) as team_users
				from teams
			left outer join users on teams.team_leader = users.user_id
			where teams.status != 'deleted'

SQLEND;


		$and = true;


		if (isset($_POST["date"]))
		{
			$dateAdded = $DB->sanitize($_POST["date"]);
			if ($dateAdded != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "DATE(teams.date_added) = STR_TO_DATE('".$dateAdded."', '%m/%d/%Y')";
				$and = true;
			}
		}

		if (isset($_POST["startDate"]))
		{
			$startDate = $DB->sanitize($_POST["startDate"]);
			if ($startDate != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "DATE(teams.date_added) >= STR_TO_DATE('".$startDate."', '%m/%d/%Y')";
				$and = true;
			}
		}

		if (isset($_POST["endDate"]))
		{
			$endDate = $DB->sanitize($_POST["endDate"]);
			if ($endDate != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "DATE(teams.date_added) <= STR_TO_DATE('".$endDate."', '%m/%d/%Y')";
				$and = true;
			}
		}

		if (isset($_POST["users"]))
		{
			$users = $DB->sanitize($_POST["users"]);
			if ($users != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . " (select count(*) from users where users.team_id = teams.team_id) = " .$users." ";
				$and = true;
			}
		}

		if (isset($_POST["teamId"]))
		{
			$teamId = $DB->sanitize($_POST["teamId"]);
			if ($teamId != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . " teams.team_id = ".$teamId." ";
				$and = true;
			}
		}

		if (isset($_POST["teamId2"]))
		{
			$teamId2 = $DB->sanitize($_POST["teamId2"]);
			if ($teamId2 != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . " teams.team_id = ".$teamId2." ";
				$and = true;
			}
		}


		$error = $sql;
		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}




	if ($id == ("getNewUserTable"))
	{
		if (!UserMay("Admin_ViewUsers")) { AccessDenied(); }		
		$DB = new conn();
		$DB->connect();


		$sql = <<<SQLEND
			select users.*, teams.team_name, permission_roles.name as permname from users 
			left outer join teams on users.team_id = teams.team_id
			left outer join permission_roles on users.permission_role = permission_roles.id
			where users.status != 'deleted'
SQLEND;


		$and = true;

		if (isset($_POST["username"]))
		{
			$username = $DB->sanitize($_POST["username"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "users.username LIKE '%".$username."%'";
			$and = true;
		}
		if (isset($_POST["status"]))
		{
			$status = $DB->sanitize($_POST["status"]);
			if ($status != "%")
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "users.status = '".$status."'";
				$and = true;
			}
		}
		if (isset($_POST["first"]))
		{
			$first = $DB->sanitize($_POST["first"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "users.FirstName LIKE '%".$first."%'";
			$and = true;
		}
		if (isset($_POST["last"]))
		{
			$last = $DB->sanitize($_POST["last"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "users.LastName LIKE '%".$last."%'";
			$and = true;
		}

		if (isset($_POST["perm"]))
		{
			$perm = $DB->sanitize($_POST["perm"]);
			if ($perm != "%")
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "users.permission_role = ".$perm." ";
				$and = true;
			}
		}

		if (isset($_POST["team"]))
		{
			$team = $DB->sanitize($_POST["team"]);
			if ($team != "%")
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "users.team_id = ".$team." ";
				$and = true;
			}
		}

		$error = $sql;
		$result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($result))
		{
			$row["sql"] = $sql;
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}


	if ($id == ("addTemplateToDatabase"))
	{
		if (!UserMay("Admin_EditComm")) { AccessDenied(); }		
		$DB = new conn();
		$DB->connect();

		if (isset($_REQUEST["maximum"]))
			$max = $DB->sanitize($_REQUEST["maximum"]);
		else
			$max = 9999999;

		
		if (isset($_REQUEST["minimum"]))
			$min = $DB->sanitize($_REQUEST["minimum"]);
		else
		{
			$success = "failure";
			return false;
		}

		if (isset($_REQUEST["templateName"]))
			$name = $DB->sanitize($_REQUEST["templateName"]);
		else
		{
			$success = "failure";
			return false;
		}

		if (isset($_REQUEST["templateElements"]))
			$elements = $DB->sanitize($_REQUEST["templateElements"]);
		else
		{
			$success = "failure";
			return false;
		}

		$sql = "INSERT INTO commission_templates (template_name, template_elements, min_price, max_price) ";
		$sql .= " VALUES ('".$name."', '".$elements."', ".$min.", ".$max.")";

		$newRow = $DB->insert($sql);
		$output = $newRow;
	}

	if ($id == ("addTemplateBlockToDatabase"))
	{
		if (!UserMay("Admin_EditComm")) { AccessDenied(); }		
		$DB = new conn();
		$DB->connect();
		$user = getLoggedUser($DB);

		if (isset($_REQUEST["maximum"]))
			$max = $DB->sanitize($_REQUEST["maximum"]);
		else
			$max = 9999999;

		$min = $DB->sanitize($_REQUEST["minimum"]);
		$name = $DB->sanitize($_REQUEST["templateName"]);
		$dealers = $DB->sanitize($_REQUEST["dealers"]);
		$payeeType = $DB->sanitize($_REQUEST["payeeType"]);
		$paymentType = $DB->sanitize($_REQUEST["paymentType"]);
		$amount = $DB->sanitize($_REQUEST["amount"]);
	

		$sql = "INSERT INTO commission_templates (template_name, dealers, min_price, max_price, payee_type, payment_type, amount) ";
		$sql .= " VALUES ('".$name."', '".$dealers."', ".$min.", ".$max.", '".$payeeType."', '".$paymentType."', ".$amount.")";

		$newRow = $DB->insert($sql);
		$output = $newRow;
	}

	if ($id == "getSalesTable")
	{
		if (!UserMay("ViewOrder")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();
		$user = getLoggedUser($DB);
		$sql = <<<SQLEND
			select orders.*, order_status.order_status_name as order_status,
				users.username, users.FirstName, users.LastName, contacts.contact_DisplayName
			
				from orders

				left outer join contacts on orders.contact_id = contacts.contact_id
				left outer join order_status on orders.order_status_id = order_status.order_status_id
				left outer join users on orders.AddedBy = users.user_id
SQLEND;


		$and = false;

		if (isset($_POST["order_id"]))
		{
			$order_id = $DB->sanitize($_POST["order_id"]);
			if ($order_id != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "orders.order_id LIKE '".$order_id."'";
				$and = true;
			}
		}

		if (isset($_POST["displayname"]))
		{
			$displayname = $DB->sanitize($_POST["displayname"]);
			if ($displayname != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "contacts.contact_DisplayName LIKE '%".$displayname."%'";
				$and = true;
			}
		}


		if (isset($_POST["amount"]))
		{
			$amount = $DB->sanitize($_POST["amount"]);
			if ($amount != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "orders.amount = ".$amount;
				$and = true;
			}
		}

		if (isset($_POST["amountMax"]))
		{
			$amountMax = $DB->sanitize($_POST["amountMax"]);
			if ($amountMax != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "orders.amount <= ".$amountMax;
				$and = true;
			}
		}

		if (isset($_POST["amountMin"]))
		{
			$amountMin = $DB->sanitize($_POST["amountMin"]);
			if ($amountMin != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "orders.amount >= ".$amountMin;
				$and = true;
			}
		}

		if (isset($_POST["orderstatus"]))
		{
			$order_status = $DB->sanitize($_POST["orderstatus"]);
			if ($order_status != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "orders.order_status_id LIKE '".$order_status."'";
				$and = true;
			}
		}

		if (isset($_POST["date"]))
		{
			$dateAdded = $DB->sanitize($_POST["date"]);
			if ($dateAdded != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "DATE(orders.DateAdded) = STR_TO_DATE('".$dateAdded."', '%m/%d/%Y')";
				$and = true;
			}
		}

		if (isset($_POST["startDate"]))
		{
			$startDate = $DB->sanitize($_POST["startDate"]);
			if ($startDate != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "DATE(orders.DateAdded) >= STR_TO_DATE('".$startDate."', '%m/%d/%Y')";
				$and = true;
			}
		}

		if (isset($_POST["endDate"]))
		{
			$endDate = $DB->sanitize($_POST["endDate"]);
			if ($endDate != '%')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "DATE(orders.DateAdded) <= STR_TO_DATE('".$endDate."', '%m/%d/%Y')";
				$and = true;
			}
		}

		if (isset($_POST["sellers"]))
		{
			$sellers = $DB->sanitize($_POST["sellers"]);
			if ($sellers != '')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "CommStructure LIKE '%\"user\":\"".$sellers."\"%'";
				$and = true;
			}
		}

		if (isset($_POST["addedby"]))
		{
			$addedby = $DB->sanitize($_POST["addedby"]);
			if ($addedby != '')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "AddedBy = ".$addedby;
				$and = true;
			}
		}

		if (isset($_POST["products"]))
		{
			$products = $DB->sanitize($_POST["products"]);
			if ($products != '')
			{
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . " ( ProductsArray LIKE '%".$products."%' OR AccessoriesArray LIKE '%".$products."%') ";
				$and = true;
			}
		}


		$firephp->log($sql);


		$result = mysql_query($sql);
	
		if ($result) {
			while ($row = mysql_fetch_assoc($result))
			{
				# Get approved DT's
				$_ProductsString = $row["ProductsArray"];
				$products = json_decode($_ProductsString, true);
				$dts = array();
				if ($products["products"])
				{
					foreach ($products["products"] as $product)
					{
						$_sql = "select dtoffice from inventory where Serial = " . $product["Serial"];
						$_result = mysql_query($_sql);
						while ($_row = mysql_fetch_assoc($_result))
						{
							$dt = $_row["dtoffice"];
							if (!in_array($dt, $dts))
							{
								$dts[] = $dt;
							}
						}
					}
				}
				$row["dts"] = $dts;
				#see if user can see this

				if ($user["dtoffice"] == "" || $user["dtoffice"] == "_" || in_array($user["dtoffice"], $dts))
				{
					$retArray[] = $row;
				}
			
			}
		}

		$output = $retArray;
		
		
		//$output = $sql;
		$DB->close();
	}


	if ($id == ("getNewInventoryTable"))
	{
		if (!UserMay("ViewInventory")) { AccessDenied(); }
		$DB = new conn();
		$DB->connect();
		$user = getLoggedUser($DB);

		$sql = <<<SQLEND
			select inventory.inventory_id, inventory.product_id, inventory.invoice, inventory.dtoffice, products.product_model, products.product_name, inventory.serial, inventory.status, inventory.status_date, inventory.status_data, inventory.storagelocation_id, sl.storagelocation_name as slname, inventory_status.status_name, inventory_status.preposition, inventory.status_data_text, inventory.DateAdded, inventory.DateReceived, users.username AS AddedByName
			from inventory
			join products on inventory.product_id = products.product_id
			join storagelocations sl on inventory.storagelocation_id = sl.storagelocation_id
			join inventory_status on inventory.status = inventory_status.status_id
			join users on inventory.AddedBy = users.User_ID
SQLEND;


		$and = false;

		if (isset($_POST["productID"]))
		{
			$product_id = $DB->sanitize($_POST["productID"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "inventory.product_id LIKE '".$product_id."'";
			$and = true;
		}
		if (isset($_POST["products"]))
		{
			$productArray = $DB->sanitize($_POST["products"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "inventory.product_id IN (".$productArray.")";
		}
		if (isset($_POST["invoice"]))
		{
			$invoice = $DB->sanitize($_POST["invoice"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "inventory.invoice LIKE '".$invoice."'";
			$and = true;
		}
		if (isset($_POST["status"]))
		{
			$status = $DB->sanitize($_POST["status"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "inventory.status LIKE '".$status."'";
			$and = true;
		}
		if (isset($_POST["serial"]))
		{
			$serial = $DB->sanitize($_POST["serial"]);
			if ($and) $sql = $sql . " AND ";
			else $sql = $sql . " WHERE ";
			$sql = $sql . "inventory.serial LIKE '".$serial."'";
			$and = true;
		}
		if (isset($_POST["location"]) && $_POST["location"] != "null")
		{
			if ($_POST["location"] != "%")
			{
				$location = $DB->sanitize($_POST["location"]);
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "inventory.status_data LIKE  '".$location."'";
				$and = true;
			}
		}

		$sql = $sql . " ORDER BY inventory.DateAdded DESC ";

		$result = mysql_query($sql);

		$error = $sql;
		
		while ($row = mysql_fetch_assoc($result))
		{
			#see if user can see this

			if ($user["dtoffice"] == "" || $user["dtoffice"] == "_" || $user["dtoffice"] == $row["dtoffice"])
			{
				$retArray[] = $row;
			}

#			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}
		
	
	
	$json = array();
	$json['success'] = $success;
	$json['error'] = $error;
	$json['output'] = $output;
	
	// Uncomment to debug
//	$json['sql'] = $sql;
	 

	// encode array $json to JSON string
	$encoded = json_encode($json);
	die($encoded);
		
   
   ?>
