<?php
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";

$DB = new conn();
$DB->connect();
$DB->close();

require_once('wk.php');
$pdf = new WKPDF();
$pdf->set_html('http://www.google.com');
$pdf->render();
$pdf->output(WKPDF::$PDF_EMBEDDED,'tmp/sample.pdf');
?>

