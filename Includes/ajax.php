<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/class_inc.php";

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


	if ($id == "submitOrder")
	{
		$DB = new conn();
		$DB->connect();
		
		$orderStatus = $DB->sanitize($_REQUEST["orderStatus"]);
		$customer_id = $DB->sanitize($_REQUEST["customer_id"]);
		$amount = $DB->sanitize($_REQUEST["amount"]);
		$CommStructureString = $_REQUEST["CommStructureString"];
		$ProductsString = $_REQUEST["ProductsString"];
		$AccessoriesString = $DB->sanitize($_REQUEST["AccessoriesString"]);
		$PaymentString = $DB->sanitize($_REQUEST["PaymentString"]);
		$order_id = $DB->sanitize($_REQUEST["order_id"]);
		$user_id = $DB->sanitize($_REQUEST["user_id"]);
		$dateCompleted = $DB->sanitize($_REQUEST["dateCompleted"]);
		$dealerArray = $DB->sanitize($_REQUEST["dealerArray"]);
		$date = "";
		
		if ($dateCompleted != "")
		{
			$ts = strtotime($dateCompleted);
			$date = date("Y-m-d", $ts);
			$date = ", dateCompleted = '" . $date . "'";
		}

		if ($orderStatus < 5 || $orderStatus = 7)		$newStatus = 4;
		if ($orderStatus == 5 || $orderStatus == 6)		$newStatus = 5;
		if ($orderStatus == 8)							$newStatus = 1;

		$products = json_decode($ProductsString, true);
		$prod2 = json_encode($products["products"]);
		$prodArray = json_decode($prod2, true);

		// Update inventory status for items in order.
		if ($prodArray)
		{
			foreach ($prodArray as $product)
			{
					$serial = $product['Serial'];
					$sql = "UPDATE inventory set status = " . $newStatus . ", status_data = " . $order_id . ", status_data_text = " . $order_id . " WHERE serial = " . $serial;
					$DB->execute_nonquery($sql);
			}
		}


		if (is_numeric($order_id))
		{
			$query2 = 'UPDATE orders SET order_status_id = ' . $orderStatus . ', contact_id = ' . $customer_id . ', amount = ' . $amount . ', CommStructure = \'' . $CommStructureString . '\', ProductsArray = \'' . $ProductsString . '\', AccessoriesArray = \'' . $AccessoriesString . '\', PaymentArray = \'' . $PaymentString . '\', dealerArray = \'' . $dealerArray . '\' WHERE order_id = ' . $order_id;

			$DB->execute_nonquery($query2);
			$output = $order_id;
		}

		else
		{
			$query1 = 'insert into orders (order_status_id, contact_id, amount, CommStructure, ProductsArray, AccessoriesArray, PaymentArray, AddedBy, dealerArray) VALUES (' . $orderStatus . ', \'' . $customer_id . '\', ' . $amount . ', \'' . $CommStructureString . '\', \'' . $ProductsString . '\', \'' . $AccessoriesString . '\', \'' . $PaymentString . '\', ' . $user_id . ', \'' . $dealerArray . '\')';

			$output = $DB->insert($query1);
		}
	}


	if ($id == "updateStorage")
	{
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
		$DB = new conn();
		$DB->connect();

		$date = $DB->sanitize($_REQUEST["date"]);
		$inventory_id = $DB->sanitize($_REQUEST["inventory_id"]);
		$status = $DB->sanitize($_REQUEST["status"]);
		$statusdata = $DB->sanitize($_REQUEST["statusdata"]);
		$statusdate = $DB->sanitize($_REQUEST["date"]);
		$statusdatatext = '';

		if ($status == 1)
		{
			$sql = "select storagelocation_name from storagelocations where storagelocation_id = " .$statusdata;
			$statusdatatext = $DB->query_scalar($sql);

		}
		elseif ($status == "2" || $status == "3")
		{
			$sql = "select username from users where user_id = " . $statusdata;
			$statusdatatext = $DB->query_scalar($sql);

		}
		else
		{
			$statusdatatext = '';
		}

		$sql = "UPDATE INVENTORY SET status = $status, status_data = $statusdata, status_data_text = '" . $statusdatatext . "', status_date =  STR_TO_DATE('".$statusdate."', '%m/%d/%Y') WHERE inventory_id = ".$inventory_id;

		$DB->execute_nonquery($sql);
		$error = $sql;

		$sql = "select inventory.*, inventory_status.status_name, inventory_status.preposition from inventory join inventory_status on inventory.status = inventory_status.status_id  where inventory_id = " . $inventory_id;
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
			$retArray[] = $row;
			$output = $retArray;
		}
		$DB->close();
		
	}


	if ($id == ("addTemplateToDatabase"))
	{
		
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
		
		$DB = new conn();
		$DB->connect();

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
		$DB = new conn();
		$DB->connect();
		$sql = <<<SQLEND
			select orders.*, order_status.order_status_name as order_status,
				users.username, contacts.contact_DisplayName
			
				from orders

				left outer join contacts on orders.contact_id = contacts.contact_id
				left outer join order_status on order_status.order_status_id = orders.order_status_id
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
				$sql = $sql . "CommStructure LIKE '%\"user_id\":\"".$sellers."\"%'";
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


		


		$result = mysql_query($sql);
	

		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
			$output = $retArray;
		}
		$retArray[] = $sql;
		
		
		//$output = $sql;
		$DB->close();
	}


	if ($id == ("getNewInventoryTable"))
	{
		
		$DB = new conn();
		$DB->connect();


		$sql = <<<SQLEND
			select inventory.inventory_id, inventory.product_id, inventory.invoice, products.product_model, products.product_name, inventory.serial, inventory.status, inventory.status_date, inventory.status_data, inventory.storagelocation_id, sl.storagelocation_name as slname, inventory_status.status_name, inventory_status.preposition, inventory.status_data_text, inventory.DateAdded, users.username AS AddedByName
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
		if (isset($_POST["location"]))
		{
			if ($_POST["location"] != "%")
			{
				$location = $DB->sanitize($_POST["location"]);
				if ($and) $sql = $sql . " AND ";
				else $sql = $sql . " WHERE ";
				$sql = $sql . "inventory.status_data LIKE  '".$location."' AND inventory.status = 1 ";
				$and = true;
			}
		}
		$result = mysql_query($sql);

		$error = $sql;
		while ($row = mysql_fetch_assoc($result))
		{
			$retArray[] = $row;
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