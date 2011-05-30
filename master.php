<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->


<html>
 <head>
  <title> Executive Suite </title>
  
<? $time = time(); ?>
	<link rel="StyleSheet" type="text/css" href="/<?= $ROOTPATH ?>/CSS/main.css.php">
	<link rel="StyleSheet" href="/<?= $ROOTPATH ?>/CSS/smoothness/smoothness.css" >

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" ></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/jquery.maskedinput-1.2.2.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/tablesorter.min.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/Validation.js.php"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/json2.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/jeditable.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/master.js.php"></script>


 </head>

 <body>
<div class="divHeadBackground">
	<div class="divHeadTopBar"></div>
	<div class="divHeadline">
		<img style="float:left;" src="/<?= $ROOTPATH ?>/images/mainLogo.png" alt="Nexus Logo" />
		<?
			if (isset($_SESSION["firstname"]))
			{
				?><span style="padding:2px; color: white; float:right;">Logged in as <?= $_SESSION["firstname"] ?> <?= $_SESSION["lastname"] ?> (<a href="/<?= $ROOTPATH ?>/LogOut.php">Log Out</a>)</span><?
			} ?>
		<div class="spacer"></div>
	</div>
	<div class="divHeadNavBar">
		<ul class="horizontal">
			<li ><a href="/<?= $ROOTPATH ?>/index.php">Home</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Inventory/ManageInventory.php">Inventory</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Sales/ManageSales.php">Sales / Leads</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Customers/ManageContacts.php">Contacts</a></li>
			<li><a href="/<?= $ROOTPATH ?>/reports/index.php">Reports</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Admin/index.php">Admin</a></li>
		</ul>
	</div>
</div>


<div class="contentBody">

<? 
	$currentUrl = $_SERVER["REQUEST_URI"];
?>


<?
echo $pagemaincontent;
?>

<? //echo "Sane"; ?>
	<div class="spacer"></div>
</div>

<div id="divFooter">
	<div style="z-index: 2; position:absolute; top:5px; background-color: #7690bf; height: 20px;width:100%"></div>
	<div id="divFooterContent">
		<div style="vertical-align:top; text-align:center; height:1em;">About Us | <a href="mailto:esuite@republictech.net">Contact Us</a> | <a href="mailto:esuite@republictech.net">Feature Request</a></div>

		<div style="position: absolute; bottom:2px; width:100%; text-align:center;">
		&copy; 2011 Republic Technologies Corporation. All Rights Reserved. <br>
		Version 1.0.0
		</div>
		
	</div>
</div>




 </body>
</html>
