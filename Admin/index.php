<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>
<? 

	$DB = new conn();

	$DB->connect();

	$sql = "select count(*) from users where status != 'deleted'";
	$usercount = $DB->query_scalar($sql, 0);

	$sql = "select * from admin_history join users on admin_history.user_id = users.user_id WHERE table_name = 'users' ORDER BY datetime DESC LIMIT 1";
	$result = $DB->query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$users_username = $row["Username"];
		$users_firstname = $row["FirstName"];
		$users_lastname = $row["LastName"];
		$users_date = $row["datetime"];
	}


	$sql = "select count(*) from teams where status = 'Active'";
	$teamcount = $DB->query_scalar($sql, 0);

	$sql = "select * from admin_history join users on admin_history.user_id = users.user_id WHERE table_name = 'teams' ORDER BY datetime DESC LIMIT 1";
	$result = $DB->query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$teams_username = $row["Username"];
		$teams_firstname = $row["FirstName"];
		$teams_lastname = $row["LastName"];
		$teams_date = $row["datetime"];
	}

	$sql = "select count(*) from products where status = 'Active'";
	$productcount = $DB->query_scalar($sql, 0);
	$sql = "select * from admin_history join users on admin_history.user_id = users.user_id WHERE table_name = 'products' ORDER BY datetime DESC LIMIT 1";
	$result = $DB->query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$products_username = $row["Username"];
		$products_firstname = $row["FirstName"];
		$products_lastname = $row["LastName"];
		$products_date = $row["datetime"];
	}


	$sql = "select count(*) from storagelocations";
	$storagecount = $DB->query_scalar($sql, 0);
	$sql = "select * from admin_history join users on admin_history.user_id = users.user_id WHERE table_name = 'storagelocations' ORDER BY datetime DESC LIMIT 1";
	$result = $DB->query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$storagelocations_username = $row["Username"];
		$storagelocations_firstname = $row["FirstName"];
		$storagelocations_lastname = $row["LastName"];
		$storagelocations_date = $row["datetime"];
	}

	$sql = "select count(*) from finance_options";
	$financecount = $DB->query_scalar($sql, 0);
	$sql = "select * from admin_history join users on admin_history.user_id = users.user_id WHERE table_name = 'finance_options' ORDER BY datetime DESC LIMIT 1";
	$result = $DB->query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$finance_options_username = $row["Username"];
		$finance_options_firstname = $row["FirstName"];
		$finance_options_lastname = $row["LastName"];
		$finance_options_date = $row["datetime"];
	}
	$sql = "select count(*) from commission_templates";
	$templatecount = $DB->query_scalar($sql, 0);
	$sql = "select * from admin_history join users on admin_history.user_id = users.user_id WHERE table_name = 'commission_templates' ORDER BY datetime DESC LIMIT 1";
	$result = $DB->query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$commission_templates_username = $row["Username"];
		$commission_templates_firstname = $row["FirstName"];
		$commission_templates_lastname = $row["LastName"];
		$commission_templates_date = $row["datetime"];
	}
	$sql = "select count(*) from TaxRates";
	$taxratecount = $DB->query_scalar($sql, 0);
	$sql = "select * from admin_history join users on admin_history.user_id = users.user_id WHERE table_name = 'TaxRAtes' ORDER BY datetime DESC LIMIT 1";
	$result = $DB->query($sql);
	while ($row = mysql_fetch_assoc($result))
	{
		$taxrates_username = $row["Username"];
		$taxrates_firstname = $row["FirstName"];
		$taxrates_lastname = $row["LastName"];
		$taxrates_date = $row["datetime"];
	}
	$DB->close();

?>

<table cellpadding="0" cellspacing="0" class="sectionIndexTable">
	<thead>
		<tr>
			<th style="width: 220px;" colspan="2"><span class="truecaps">A</span><span class="fakecaps">dministration</span></th>
			<th>#<span class="fakecaps"> Of Entries</span></th>
			<th style="text-align: right;">D<span class="fakecaps">ate of Last Edit</span></th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td class="iconCell"><img src="/<?= $ROOTPATH ?>/images/admin_user.png" /></td>
			<td class="linksCell">U<span class="fakecaps">sers</span><br />
			<a style="padding: 4px 0px 0px 0px" href="/<?= $ROOTPATH ?>/Admin/users/ManageUsers.php">M<span class="fakecaps">anage</span></a> | <a href="/<?= $ROOTPATH ?>/Admin/users/AddUser.php">A<span class="fakecaps">dd</span></a></td>
			<td class="countCell"><?= $usercount ?></td>
			<td class="dateCell"><?= prettyDate($users_date) ?><br>by <?= $users_firstname ?> <?= $users_lastname ?> (<?= $users_username ?>)</td>
		</tr>

		<tr class="odd">
			<td class="iconCell"><img src="/<?= $ROOTPATH ?>/images/admin_team.png" /></td>
			<td class="linksCell">T<span class="fakecaps">eams</span><br />
			<a style="padding: 4px 0px 0px 0px" href="/<?= $ROOTPATH ?>/Admin/users/ManageTeams.php">M<span class="fakecaps">anage</span></a> | <a href="/<?= $ROOTPATH ?>/Admin/users/AddTeam.php">A<span class="fakecaps">dd</span></a></td>
			<td class="countCell"><?= $teamcount ?></td>
			<td class="dateCell"><?= prettyDate($teams_date) ?><br>by <?= $teams_firstname ?> <?= $teams_lastname ?> (<?= $teams_username ?>)</td>
		</tr>

		<tr>
			<td class="iconCell"><img src="/<?= $ROOTPATH ?>/images/admin_products.png" /></td>
			<td class="linksCell">P<span class="fakecaps">roducts & Accessories</span><br />
			<a style="padding: 4px 0px 0px 0px" href="/<?= $ROOTPATH ?>/Admin/Products/ManageProducts.php">M<span class="fakecaps">anage</span></a> | <a href="/<?= $ROOTPATH ?>/Admin/Products/AddProduct.php">A<span class="fakecaps">dd</span></a></td>
			<td class="countCell"><?= $productcount ?></td>
			<td class="dateCell"><?= prettyDate($products_date) ?><br>by <?= $products_firstname ?> <?= $products_lastname ?> (<?= $products_username ?>)</td>
		</tr>

		<tr class="odd">
			<td class="iconCell"><img src="/<?= $ROOTPATH ?>/images/admin_location.png" /></td>
			<td class="linksCell">O<span class="fakecaps">ffice & Storage Locations</span><br />
			<a style="padding: 4px 0px 0px 0px" href="/<?= $ROOTPATH ?>/Admin/Products/ManageStorage.php">M<span class="fakecaps">anage</span></a> | <a href="/<?= $ROOTPATH ?>/Admin/Products/AddLocation.php">A<span class="fakecaps">dd</span></a></td>
			<td class="countCell"><?= $storagecount ?></td>
			<td class="dateCell"><?= prettyDate($storagelocations_date) ?><br>by <?= $storagelocations_firstname ?> <?= $storagelocations_lastname ?> (<?= $storagelocations_username ?>)</td>
		</tr>

		<tr>
			<td class="iconCell"><img src="/<?= $ROOTPATH ?>/images/admin_finance.png" /></td>
			<td class="linksCell">F<span class="fakecaps">inance Institutions</span><br />
			<a style="padding: 4px 0px 0px 0px" href="/<?= $ROOTPATH ?>/Admin/ManageFinancing.php">M<span class="fakecaps">anage</span></a> | <a href="/<?= $ROOTPATH ?>/Admin/AddFinancing.php">A<span class="fakecaps">dd</span></a></td>
			<td class="countCell"><?= $financecount ?></td>
			<td class="dateCell"><?= prettyDate($finance_options_date) ?><br>by <?= $finance_options_firstname ?> <?= $finance_options_lastname ?> (<?= $finance_options_username ?>)</td>
		</tr>

		<tr class="odd">
			<td class="iconCell"><img src="/<?= $ROOTPATH ?>/images/admin_commission.png" /></td>
			<td class="linksCell">C<span class="fakecaps">ommission Templates</span><br />
			<a style="padding: 4px 0px 0px 0px" href="/<?= $ROOTPATH ?>/Admin/ManageTemplates.php">M<span class="fakecaps">anage</span></a> | <a href="/<?= $ROOTPATH ?>/Admin/AddCommissionTemplate.php">A<span class="fakecaps">dd</span></a></td>
			<td class="countCell"><?= $templatecount ?></td>
			<td class="dateCell"><?= prettyDate($commission_templates_date) ?><br>by <?= $commission_templates_firstname ?> <?= $commission_templates_lastname ?> (<?= $commission_templates_username ?>)</td>
		</tr>

		<tr class="even">
			<td class="iconCell"><img src="/<?= $ROOTPATH ?>/images/calcIcon.png" /></td>
			<td class="linksCell">T<span class="fakecaps">ax Rates</span><br />
			<a style="padding: 4px 0px 0px 0px" href="/<?= $ROOTPATH ?>/Admin/ManageTaxRates.php">M<span class="fakecaps">anage</span></a></td>
			<td class="countCell"><?= $taxratecount ?></td>
			<td class="dateCell"><?= prettyDate($taxrates_date) ?><br>by <?= $taxrates_firstname ?> <?= $taxrates_lastname ?> (<?= $taxrates_username ?>)</td>
		</tr>
	</tbody>
</table>



<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>