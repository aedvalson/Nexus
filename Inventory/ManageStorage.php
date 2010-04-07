<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 
?>
<? 

$DB = new conn();
$DB->connect();
$sql = "Select * from StorageLocations";
$result = $DB->query($sql);

if ($result)
{
	?> <TABLE class="data">
		<th>id</th>
		<th>Location Name</th>
		<th>Commands</th>
		 <?
	while ($row = mysql_fetch_assoc($result)) {
	    ?>
		<TR><TD><? echo $row["storagelocation_id"]; ?></TD>
	    <TD><? echo $row["storagelocation_name"]; ?> </TD>
		<TD><a href="#">Edit</a></TD>
		</TR>
		<?
	}
	?> </TABLE> <?
}

$DB->close();  ?>


<Br><a href="AddLocation.php">Add a New Storage Location</a>


<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>