<?php
require ('header.php');
require 'code/connect.php';
?>
<script type="text/javascript">
	$(document).ready(function() {
		if( $('#createSuccess').length )         // use this if you are using id to check
		{
     		$("#createButton").attr("disabled", true);
     		$("#createButton").val("You Have Created A New User");
		};
	});
</script>
<div class="container">
	<div class="col-md-6">
<?php
	if(isset($_SESSION['loginusername']))
        {
		?>
		
				<h1>Create user Login</h1>
				<p>This form create a new username and password, the password has a SHA-256 hash algorithm applied to it to add to the security of the system.</p>
				<form action="create_login.php" method="POST" name="login_form">
					<input type="text" name="username" class="form-control" placeholder="Enter Username" minlength="8"><br><br>
					<input type="password" class="form-control" name="password" minlength="8"></textarea><br><br>
					<input type="submit" class="btn btn-primary" name="createButton" id="createButton" value="Create User Log In">
				</form>
		<?php
		$username = null;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if ((isset($_POST['username'])) AND  (isset($_POST['password']))) {
				$username = $_POST['username'];
				$password = $_POST['password'];
			}
		}
		if ($username) {
			
			//concatenate a SALT to the password
			$saltpassword = $password . "4b8ys9b"; 
			$hashpassword = hash("sha256", $saltpassword);
			//execute stored procedure
			$result = mysqli_query($dbc,"CALL create_login_sp('$username', '$hashpassword')");
			echo "<h2 id=\"createSuccess\">You have Successfully Created a New User</h2>";			}
        }
    else
        {
        	echo "<h2>You are not an Administrator!  Only Administrators can create new users.</h2>";
        }
        ?>
    </div>	
</div>
<?php
require ('footer.php');
?>