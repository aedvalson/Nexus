<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top_Automated.php" 
?>

<?

	$DB = new conn();
	$DB->connect();
	$sql = "select data from reports where id = " . $DB->sanitize($_REQUEST["report_id"]);
	$result = $DB->query_scalar($sql);
	echo $result;
?>


<script type="text/javascript">


</script>


<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom_Automated.php" ?>