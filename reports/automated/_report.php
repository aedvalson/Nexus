<? 
include "./findconfig.php";
include( $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/class_inc.php");

	$DB = new conn();
	$DB->connect();
	$sql = "select data from reports where id = " . $DB->sanitize($_REQUEST["report_id"]);
	$result = $DB->query_scalar($sql);


	$time = time(); 
	$css = "<link rel=\"StyleSheet\" href=\"" .  $FQDN . "/" . $ROOTPATH . "/CSS/main.css.php?" . $time . "/>";

	$result = str_replace("<HTML><HEAD></HEAD>", "<HTML><HEAD>" . $css . "</HEAD>", $result);

	require_once("wk.php");
	$pdf = new WKPDF();
	$pdf->set_html($result);
	$pdf->render();
	
	$output = $DB->sanitize($_REQUEST["output"]);
	if ($output)
	{
		if ($output == "pdf");
		{
			$pdf->output(WKPDF::$PDF_DOWNLOAD,'sample.pdf');
		}
	}
	else
	{
		$pdf->output(WKPDF::$PDF_EMBEDDED,'sample.pdf');
	}
	
	

?>

