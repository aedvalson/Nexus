<?php
if (!defined('E_DEPRECATED')) {
        define('E_DEPRECATED',0);
}
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED); 
include( $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/class_inc.php");



  //Buffer larger content areas like the main page content
  ob_start();


?>