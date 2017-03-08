<?php
require ('header.php');
?>

<?php
require 'code/connect.php';
?>

<div class="container">
<h1>Insert Web Term</h1>
<form action="insert.php" method="POST">
	<input type="text" name="term_name" class="form-control" placeholder="Enter Web term"><br><br>
	<textarea class="form-control" name="term_description" rows="10"></textarea><br><br>
	<input type="submit" class="btn btn-primary" name="submit" value="submit term">
</form>
</div>
<?php
$term_name = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ((isset($_POST['term_name'])) AND  (isset($_POST['term_description']))) {
		$term_name = $_POST['term_name'];
		$term_description = $_POST['term_description'];
	}
}
?>
<?php

if ($term_name) {

	echo $term_name . " " . $term_description;
}

?>
<?php
require ('footer.php');
?>