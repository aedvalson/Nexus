<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
?>



<?
	session_destroy();
	header("Location: /$ROOTPATH/Login.php");
	exit();
?>

<? include $_SERVER['DOCUMENT_ROOT']."/$ROOTPATH/Includes/Bottom.php" ?>