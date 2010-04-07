<? include $_SERVER['DOCUMENT_ROOT']."/php/Includes/Top.php" ?>


<?
	session_destroy();
	header("Location: /php/Login.php");
	exit();
?>

<? include $_SERVER['DOCUMENT_ROOT']."/php/Includes/Bottom.php" ?>