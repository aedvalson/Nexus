<?php
	session_start();
if (!defined('E_DEPRECATED')) {
        define('E_DEPRECATED',0);
}
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED); 
include( $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/class_inc.php");

$currentUrl = $_SERVER["REQUEST_URI"];
$pos = strpos(strtolower($currentUrl), "login.php");
if ($pos === false)
{
	if (!isset($_SESSION["username"]) || !isset($_SESSION["password"]))
	{
		header("Location: /".$ROOTPATH."/Login.php?ReturnUrl=".$currentUrl);
		exit();
	}
	else
	{
		$username = $_SESSION["username"];
		$password = $_SESSION["password"];

		$DB = new conn();
		if (!$DB->validateUser($username, $password))
		{

			header("Location: /".$ROOTPATH."/Login.php?ReturnUrl=".$currentUrl);
			exit();
		}
	}
}

  //Buffer larger content areas like the main page content
  
	require_once( $DOCROOT.$ROOTPATH."/firephp/FirePHP.class.php");
	$firephp = FirePHP::getInstance(true);
 
  ob_start();

?>