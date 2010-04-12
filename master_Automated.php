<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php

	if (isset($_REQUEST["output"]))
	{
		if ($_REQUEST["output"] == 'excel')
		{
			$export_file = "nexus_report.htm";
			ob_end_clean();
			ini_set('zlib.output_compression','Off');
		   
			header('Pragma: public');
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");                  // Date in the past   
			header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
			header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
			header ("Pragma: no-cache");
			header("Expires: 0");
			header('Content-Transfer-Encoding: none');
			header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
			header("Content-type: application/x-msexcel");                    // This should work for the rest
			header('Content-Disposition: attachment; filename="'.basename($export_file).'"');
		}
	}
?>

<?
	if (isset($_REQUEST["output"]))
	{	?>

<? if ($_REQUEST["output"] == 'excel') { ?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<?
}
	else {
		?>
<html>
<? } ?>

 <head>
<meta http-equiv="Content-Type" content="text/html;charset=windows-1252" />
<!--[if gte mso 9]><xml>
<x:ExcelWorkbook>
	<x:ExcelWorksheets>
		<x:ExcelWorksheet>
		<x:Name>NexusReport</x:Name>
			<x:WorksheetOptions>

			<x:Panes>
			</x:Panes>
			</x:WorksheetOptions>
		</x:ExcelWorksheet>
	</x:ExcelWorksheets>
</x:ExcelWorkbook>
</xml><![endif]-->

  <title> Nexus </title>

	<? $time = time(); ?>
	<link rel="StyleSheet" href="<?= $FQDN ?>/<?= $ROOTPATH ?>/CSS/main.css.php?<?= $time ?>" />

 </head>

 <body style="background-image:none">

<div >
<?   }   ?>



<?
echo $pagemaincontent;
?>

<?
	if (isset($_REQUEST["output"]))
	{
?>
	</div>
 </body>
</html>

<? }  ?>
