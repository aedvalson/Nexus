<?php
if (!defined('E_DEPRECATED')) {
        define('E_DEPRECATED',0);
}
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED); 
include( $_SERVER['DOCUMENT_ROOT']."/php/class_inc.php");

$currentUrl = $_SERVER["REQUEST_URI"];
$pos = strpos(strtolower($currentUrl), "login.php");
if ($pos === false)
{
	session_start();
	if (!isset($_SESSION["username"]) || !isset($_SESSION["password"]))
	{
			header("Location: /php/Login.php?ReturnUrl=".$currentUrl);
		exit();
	}
	else
	{
		$username = $_SESSION["username"];
		$password = $_SESSION["password"];
		
		$DB = new conn();
		if (!$DB->validateUser($username, $password))
		{
			header("Location: /php/Login.php?ReturnUrl=".$currentUrl);
			exit();
		}
	}
}

  //Buffer larger content areas like the main page content
  ob_start();


?>