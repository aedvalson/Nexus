<?
include "./findconfig.php";
include $DOCROOT . "/" . $ROOTPATH . "/class_inc.php";

require_once( $_SERVER['DOCUMENT_ROOT'] . "/" . $ROOTPATH . "/" . $tcpdfPath . '/config/lang/eng.php');
require_once( $_SERVER['DOCUMENT_ROOT'] . "/" . $ROOTPATH . "/" . $tcpdfPath . '/tcpdf.php');





		$DB = new conn();
		$DB->connect();
		$sql = "SELECT * FROM finance_options";
		$result = $DB->query($sql);
		while ($row = mysql_fetch_assoc($result))
		{
			$financeDetails[] = $row;
		}


foreach ($financeDetails as $row)
{

	echo $row["id"];
}
?>