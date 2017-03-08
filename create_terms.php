<?php
require ('header.php');
?>
<script type="text/javascript">
	$(document).ready(function(){
		console.log("ready");
		$("#ref_url").attr("disabled", true);
		$("#ref_description").attr("disabled", true);
		var ref_name = $("#ref_name").val();

		if (ref_name.length > 1) {
			$("#ref_url").attr("disabled", false);
			$("#ref_url").attr("required", true);
			$("#ref_description").attr("disabled", false);
			$("#ref_description").attr("required", true);
		};

		$('#ref_name').keyup(function () { 
			var ref_name = $("#ref_name").val();
		if (ref_name.length > 1) {
			$("#ref_url").attr("disabled", false);
			$("#ref_url").attr("required", true);
			$("#ref_description").attr("disabled", false);
			$("#ref_description").attr("required", true);
		};
	});
		if( $('#createSuccess').length )         // use this if you are using id to check
		{
     		$("#createButton").attr("disabled", true);
     		$("#createButton").val("You Have Created A New Web Terminology");
		};

	});
</script>
<?php
require 'code/connect.php';
?>
<div class="container">
<h1>Insert Web Term</h1>
<p>The following form will insert new Terminology to the Database</p>

	<form name="insert_terms_form" action="create_terms.php" method="POST">
	<label for="category">Please Select a Category</label>
		<select name="category" id="category" class="form-control" required="required">
		<option value=""></option>
		<?php
			$result = mysqli_query($dbc,"CALL categories_sp()");
			//$result = mysqli_query($dbc,"CALL login_sp('$username', '$hashpassword')");
			while($row = mysqli_fetch_array($result))
	    	{
	      		echo "<option value='" . $row['id'] ."'>" . $row['cat_name'] . "</option>";
		    };
		    $result->close();
			$dbc->next_result();
		?>
		</select>
		<label for="term_name">Give Your Terminology a Name</label>
		<input type="text" name="term_name" placeholder="Web Terminology Name" class="form-control" required="required" value="<?php echo isset($_POST['term_name']) ? $_POST['term_name'] : '' ?>">
		<br>
		<label for="term_description">Give Your Terminology a Description</label>
		<textarea name="term_description" rows="5" class="form-control" required="required"><?php echo isset($_POST['term_description']) ? $_POST['term_description'] : '' ?></textarea>
		<br>
		<label for="ref_name">Reference Name</label>
		<input type="text" name="ref_name" id="ref_name" placeholder="Web Terminology Reference Name" class="form-control" value="<?php echo isset($_POST['ref_name']) ? $_POST['ref_name'] : '' ?>">
		<br>
		<label for="ref_description">Reference Description</label>
		<textarea name="ref_description" id="ref_description" rows="5" class="form-control" required="required"><?php echo isset($_POST['ref_description']) ? $_POST['ref_description'] : '' ?></textarea>
		<br>
		<label for="ref_url">Reference URL</label>
		<input type="text" name="ref_url" id="ref_url" placeholder="Web Terminology Reference URL" class="form-control" value="<?php echo isset($_POST['term_url']) ? $_POST['term_url'] : '' ?>">
		<br>
		<input type="submit" name="submit" class="btn btn-success" id="createButton" value="INSERT NEW WEB TERMINOLOGY">
	</form>


<?php
$term_name = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ((isset($_POST['term_name'])) AND  (isset($_POST['term_description'])) AND  (isset($_POST['category']))) {
		$term_name = $_POST['term_name'];
		$term_name = filter_var($term_name, FILTER_SANITIZE_SPECIAL_CHARS);
		$term_description = $_POST['term_description'];
		$term_description = filter_var($term_description, FILTER_SANITIZE_SPECIAL_CHARS);
		$term_category = $_POST['category'];
		$term_category = filter_var($term_category, FILTER_SANITIZE_SPECIAL_CHARS);
		$result = mysqli_query($dbc,"CALL create_term_sp('$term_name', '$term_description')");
		$dbc->next_result();

		$result2 = mysqli_query($dbc,"CALL newest_term_sp()");
		while($row = mysqli_fetch_array($result2))

		{	
			$last_term_id = $row['id'];
	    };

		$dbc->next_result();	    
	    $result3 = mysqli_query($dbc,"CALL create_term_cat_sp('$last_term_id', '$term_category')");
	    $dbc->next_result();

	    if((isset($_POST['ref_name'])) AND  (isset($_POST['ref_url'])) AND  (isset($_POST['ref_description']))) {
	    	$ref_name = $_POST['ref_name'];
	    	$ref_name = filter_var($ref_name, FILTER_SANITIZE_SPECIAL_CHARS);
	    	$ref_url = $_POST['ref_url'];
	    	$ref_url = filter_var($ref_url, FILTER_SANITIZE_URL);
	    	$ref_description = $_POST['ref_description'];
	    	$ref_description = filter_var($ref_description, FILTER_SANITIZE_SPECIAL_CHARS);
	    	$result = mysqli_query($dbc,"CALL create_reference_sp('$ref_name', '$ref_url', '$ref_description' )");
	    	$dbc->next_result();

	    	$result2 = mysqli_query($dbc,"CALL newest_reference_sp()");
			while($row = mysqli_fetch_array($result2))
			{	
				$last_reference_id = $row['id'];
		    };

		    $dbc->next_result();

		    $result3 = mysqli_query($dbc,"CALL create_term_ref_sp('$last_term_id', '$last_reference_id')");
	    	$dbc->next_result();


	    }

		$dbc->next_result();
	    echo "<h2 id=\"createSuccess\">New Web Terminology Created</h2>";

		
	}
}
?>
</div>
<?php
require ('footer.php');
?>