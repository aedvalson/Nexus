<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" ?>

<? $firephp->log($_SESSION["perms"]); ?>
<? $firephp->log($_SESSION["roleid"]); ?>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>