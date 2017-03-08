<?php
require ('header.php');
require 'code/connect.php';
?>
<script type="text/javascript">
	$(document).ready(function() {
		if( $('#loginSuccess').length )         // use this if you are using id to check
		{
     		$("#loginButton").attr("disabled", true);
     		$("#loginButton").val("You Have Logged In");
		};

		if( $('#loginFail').length )         // use this if you are using id to check
		{
			$("#loginButton").val("Login Unsuccessful, Please Try Again");
		};
	});
</script>
<div class="container textcenter">
	<div class="col-md-8">
		<h1>Log In</h1>
		<form action="login.php" method="POST" name="login_form">
			<input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>"><br><br>
			<input type="password" class="form-control" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>"></textarea><br><br>
			<input type="submit" class="btn btn-primary" name="login" value="Log In" id="loginButton">
		</form>
		
<?php
$username = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ((isset($_POST['username'])) AND  (isset($_POST['password']))) {
		$username = $_POST['username'];
		$password = $_POST['password'];
	}
}
?>
<?php
if ($username) {
	$saltpassword = $password . "4b8ys9b"; 
	$hashpassword = hash("sha256", $saltpassword);
	//echo $hashpassword;
	//execute stored procedure
	$result = mysqli_query($dbc,"CALL login_sp('$username', '$hashpassword')");
	$row_count = $result->num_rows;
	if($row_count == 1) {
		echo "<h2 id=\"loginSuccess\">You have Successfully Logged In</h2>";
		while($row = mysqli_fetch_array($result))
		{
			$_SESSION["loginusername"] = $row['username'];	
		}
	}
	else {
		echo "<h2 id=\"loginFail\">Login Failed, Please Try Again</h2>";
	}
}
?>
	</div>
</div>
<?php
require ('footer.php');
?>