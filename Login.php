<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
?>


<?
if ($_REQUEST)
{
	if (isset($_REQUEST["username"]) && isset($_REQUEST["password"]))
	{
		// ALL FORM VARS MUST BE VALIDATED
		$DB = new conn();
		$DB->connect();
		$username = $DB->sanitize($_REQUEST["username"]);
		$password = $DB->sanitize($_REQUEST["password"]);

		$url = "/$ROOTPATH/index.php";	
		if (isset($_REQUEST["ReturnUrl"]))
		{
			$url = $DB->sanitize($_REQUEST["ReturnUrl"]);
		}

		$DB->close();

		if ($DB->validateUser($username, md5($password)))
		{

			$sql = "select users.*, permission_roles.permission from users join permission_roles on users.permission_role = permission_roles.id where username = '".$username."' and user_password = '".md5($password)."'";
			$DB->connect();
			$result = $DB->query($sql);

			$userInfo = mysql_fetch_assoc($result);

				$firstname = $userInfo["FirstName"];
				$lastname = $userInfo["LastName"];
				$user_id = $userInfo["user_id"];
				$permLevel = $userInfo["permission"];


				$_SESSION["username"] = $username;
				$_SESSION["password"] = md5($password);
				$_SESSION["firstname"] = $firstname;
				$_SESSION["lastname"] = $lastname;
				$_SESSION["user_id"] = $user_id;
				$_SESSION["perm_level"] = $permLevel;

				header("Location: ".$url);
			}
		else
		{
			?> <font color="red">Your username and password were not recognized. Please try again.</font> <?
		}
	}


}


?>




<div class="navMenu">
	<div class="navHeaderdiv">
		<h1>Home</h1>
	</div>
	<div class="navBulletBorderTop"></div>
	<div id="bulletLogin" class="navBullet <? echo getClass("Login"); ?>"><A href="/php/Inventory/Login.php">Log In</a></div>
	<div id="bulletForgotPassword" class="navBullet <? echo getClass("ForgotPassword"); ?>"><A href="/php/Inventory/ForgotPassword.php">Forgot Password</a></div>
	<div class="navBulletBorderBottom"></div>
	<div class="navSpacer"></div>

</div>


<div class="pageContent">

	<div class="contentHeaderDiv"></div>
	<div class="contentDiv">


	<p>Please enter your username and password to sign into Nexus.</p>

	<form name="theForm" Method="POST">
	<table border="0">
		<tr>
			<td>Username</td>
			<td><input type="text" name="username" id="tbUsername"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" id="tbPassword"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit"></td>
		</tr>
	</table>
	</form>		

	</div>
</div>

<script type="text/javascript">
	$().ready( function() {
		$('#tbUsername').focus();
	});
</script>

<? include $_SERVER['DOCUMENT_ROOT']."/$ROOTPATH/Includes/Bottom.php" ?>