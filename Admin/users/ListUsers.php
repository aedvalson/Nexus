<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php" 


$DB = new conn();
$DB->connect();


// Check for Form Variables
// Form Vars
	if ($_REQUEST)
	{
		if ($_REQUEST["Action"] )
		{
			$action = $DB->sanitize($_REQUEST["Action"]);
			if ($action == "Delete")
			{
				// ALL FORM INPUTS MUST BE SANITIZED
				$user_id = $DB->sanitize($_REQUEST["user_id"]);
				
				$sql = "UPDATE USERS SET Status = 'deleted', Username=CONCAT('_', NOW(), '_', Username) where user_id = ".$user_id;

				$DB->execute_nonquery($sql);
				header("Location: ListUsers.php");
			}
		}
	}


$sql = "Select * from Users where status != 'deleted'";
$result = $DB->query($sql);

if ($result)
{
	?> <TABLE class="data">
		<th>user_id</th>
		<th>Username</th>
		<th>FirstName</th>
		<th>LastName</th>
		<th>Status</th>
		<th>Commands</th>
		 <?
	while ($row = mysql_fetch_assoc($result)) {
	    ?>
		<TR><TD><? echo $row["user_id"]; ?></TD>
		<TD><? echo $row["Username"]; ?></TD>
	    <TD><? echo $row["FirstName"]; ?> </TD>
	    <TD><? echo $row["LastName"]; ?> </TD>
		<TD><? echo $row["Status"]; ?> </TD>
		<TD><a id="lbDelete<? echo $row["user_id"]; ?>" href="#">Delete</a> <a href="#">Edit</a></TD>
		</TR>
		<?
	}
	?> </TABLE> <?
}

$DB->close();  ?>


<a href="AddUser.php">Add a User</a>


<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {

		$("a[id^=lbDelete]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var user_id = id.replace("lbDelete", "");
					$("#hv_user_id").val(user_id);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+user_id+"?"))
					{
						$("#theForm").submit();	
					}
					return false;
				});
			});

		});
</SCRIPT>



<FORM id="theForm"  action="" method="post">

<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
<INPUT TYPE="HIDDEN" id="hv_user_id" NAME="user_id" Value="0">


</FORM>

<?

<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>
?>