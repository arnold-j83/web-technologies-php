<?php
include_once ('header.php');
require ('code/connect.php');
?>
<script type="text/javascript">
	$(document).ready(function(){

	});
</script>		
<div class="container">
	<?php
	ob_start();
	if(isset($_GET["id"])) {
		$term_id = (int)$_GET["id"];

		if(isset($_POST['editButton'])) {
			$term_name = $_POST['term_name'];
			$term_name = filter_var($term_name, FILTER_SANITIZE_SPECIAL_CHARS);
			$term_description = $_POST['term_description'];
			$term_description = filter_var($term_description, FILTER_SANITIZE_SPECIAL_CHARS);

			$result = mysqli_query($dbc,"CALL update_term_details_sp('$term_id', '$term_name', '$term_description')");
			echo "<h2 id=\"web_term_updated\">Web Terminology Updated</h2>";
		}


		$result = mysqli_query($dbc,"CALL term_details_sp('$term_id')");
		$row_count = $result->num_rows;
		if($row_count == 1) {
			echo "<h2 id=\"termExists\">Update Term Details</h2>";

			if(!isset($_SESSION['loginusername']))
        	{
        		echo "<h1>ONLY ADMINISTRATORS CAN EDIT TERMINOLOGY</h1>";
        	}

        	else 
        	{	
        		
			if($row_count > 0) {
				while($row = mysqli_fetch_array($result)) { 
					echo "<form action=\"edit.php?id=" . $term_id ."\" method=\"post\">";
					echo "<input  class=\"form-control\" type=\"text\" name=\"term_name\" id=\"term_name\" value=\"" . $row['term_name'] . "\"><br>";
					echo "<textarea rows=\"3\" class=\"form-control\" name=\"term_description\" id=\"term_description\">" . $row['term_description'] . "</textarea><br>";
					echo "<input type=\"submit\" class=\"btn btn-warning\" value=\"Update Web Terminology\" name=\"editButton\" id=\"editButton\" >";
					echo "</form>";
				}
			} 
			$dbc->next_result();
			if(isset($_POST['associateButton']) AND isset($_POST['associateTerm']) AND isset($_POST['associateTermType'])) {
				$associateTerm = (int)$_POST['associateTerm'];
				$associateTermType = (int)$_POST['associateTermType'];
				
				$result = mysqli_query($dbc,"CALL add_associate_term_sp('$term_id', '$associateTerm', '$associateTermType')"); 
				$dbc->next_result();
				$returnURL = "edit.php?id=". $term_id;
				header( 'Location: ' . $returnURL ) ;
				
			}


			$dbc->next_result();
			echo "<div class=\"row\">";
			echo "<div class=\"box\">";
			echo "<div class=\"col-md-3\">";
			echo "<h3>Associated Terminology</h3>";
			echo "</div>";
			
			echo "<div class=\"col-md-3\">";
			echo "<form action=\"edit.php?id=" . $term_id ."\" method=\"post\">";
			echo "<label for=\"associateTerm\">Select Terminology to Associate With</label>";
			echo "<select name=\"associateTerm\" id=\"associateTerm\" class=\"form-control\" required=\"required\">";
			echo "<option value=\"\"></option>";
			$result = mysqli_query($dbc,"CALL select_terms_all_sp()");
			while($row = mysqli_fetch_array($result))
	    	{
	      		echo "<option value='" . $row['id'] ."'>" . $row['term_name'] . "</option>";
		    };
		    $result->close();
			$dbc->next_result();
			echo "</select>";


			echo "</div>";
			echo "<div class=\"col-md-3\">";

			echo "<label for=\"associateTermType\">Select Type of Relationship</label>";
			echo "<select name=\"associateTermType\" id=\"associateTermType\" class=\"form-control\" required=\"required\">";
			echo "<option value=\"\"></option>";
			echo "<option value=\"1\">Parent</option>";
			echo "<option value=\"2\">Child</option>";
			echo "<option value=\"3\">Sibling</option>";
			echo "</select>";
			echo "</div>";
			echo "<div class=\"col-md-3\">";
			echo "<label for=\"associateButton\">Button to Associate With Another Term</label>";
			echo "<input type=\"submit\" class=\"btn btn-warning\" value=\"Associate This Term With Another Term\" name=\"associateButton\" id=\"associateButton\" >";
			echo "</div>";
					echo "</form>";
			echo "</div>";
			echo "</div>";
			echo "<div class=\"row\">";
			$dbc->next_result();
			echo "<div class=\"col-md-4\">";
			echo "<div class=\"box\">";
			if(isset($_POST['addCategory']) AND isset($_POST['category'])) {
				$addCategory = $_POST['addCategory'];
				$category = $_POST['category'];
				$category = filter_var($category, FILTER_SANITIZE_SPECIAL_CHARS);
				$result3 = mysqli_query($dbc,"CALL create_term_cat_sp('$term_id', '$category')");
	    		
	    		$dbc->next_result();
	    		$returnURL = "edit.php?id=". $term_id;
				header( 'Location: ' . $returnURL ) ;
	    		

			}

			$result = mysqli_query($dbc,"CALL term_categories_sp('$term_id')");
			$row_count = $result->num_rows;
			if ($row_count > 0) {
				
				echo "<h3>Categories</h3>";
				echo "<table class=\"table\">";
				while($row = mysqli_fetch_array($result)) { 
					echo "<tr><td><a href=\"remove_cat.php?cat_id=" . $row['cat_id'] . "&term_id=" . $term_id ."\"><i class='fa fa-trash-o' style='font-size:24px'></i></a></td><td>" . $row['cat_name'] . "</td></tr>";
				}
				echo "</table>";	

			}
			else {
				echo "<h3>No Categories</h3>";
			}
			echo "</div>";
			echo "</div>";
			
			$dbc->next_result();
			echo "<div class=\"col-md-4\">";
			echo "<div class=\"box\">";
			if(isset($_POST['addReference']) AND isset($_POST['ref_name'])) {
				$ref_name = $_POST['ref_name'];
	    	$ref_name = filter_var($ref_name, FILTER_SANITIZE_SPECIAL_CHARS);
	    	$ref_url = $_POST['ref_url'];
	    	$ref_url = filter_var($ref_url, FILTER_SANITIZE_URL);
	    	$ref_description = $_POST['ref_description'];
	    	$ref_description = filter_var($ref_description, FILTER_SANITIZE_SPECIAL_CHARS);
	    	$result = mysqli_query($dbc,"CALL create_reference_sp('$ref_name', '$ref_url', '$ref_description' )");
	    	$dbc->next_result();
	    	
	    	$returnURL = "edit.php?id=". $term_id;
			header( 'Location: ' . $returnURL ) ;
	    	$result2 = mysqli_query($dbc,"CALL newest_reference_sp()");
			while($row = mysqli_fetch_array($result2))
			{	
				$last_reference_id = $row['id'];
		    };

		    $dbc->next_result();

		    $result3 = mysqli_query($dbc,"CALL create_term_ref_sp('$term_id', '$last_reference_id')");
	    	$dbc->next_result();

	    	
			}

			$result = mysqli_query($dbc,"CALL term_references_sp('$term_id')");
			$row_count = $result->num_rows;
			if ($row_count > 0) {
				
				//echo "<h3>References</h3><br>";
				echo "<table class=\"table\">";
				while($row = mysqli_fetch_array($result)) { 
					echo "<tr><td><a href=\"remove_ref.php?ref_id=" . $row['ref_id'] . "&term_id=" . $term_id . "\"><i class='fa fa-trash-o' style='font-size:24px'></i></a></td><td>" . $row['reference_name'] . "</td></tr>";
					echo "<tr><td>" . $row['reference_description'] . "</td><td>" . $row['reference_url'] . "</td></tr>";
					
				}
				echo "</table>";	
				$dbc->next_result();
			}
			else {
				echo "<h3>No References</h3>";
			}
			echo "</div>";
			echo "</div>";

			$dbc->next_result();
			echo "<div class=\"col-md-4\">";
			echo "<div class=\"box\">";
			echo "<h3>See Also</h3>";


			$result = mysqli_query($dbc,"CALL show_associated_terms_sp('$term_id')");
			$row_count = $result->num_rows;
			if ($row_count > 0) {
				echo "<ul>";
				while($row = mysqli_fetch_array($result)) { 
				echo "<li>" . $row['term_name'] . "</li>"; 
				}
				echo "</ul>";
			}


			$dbc->next_result();
			echo "</div>";
			echo "</div>";
			//echo "<div class=\"box\">";
			?>
			</div>
			<div class="row">
				<div class="col-md-4">
					<h2>Add Category</h2>
					<form action="edit.php?id=<?php echo $term_id ?>" method="POST">
						<select name="category" id="category" class="form-control" required="required">
						<option value=""></option>
						<?php
							$result = mysqli_query($dbc,"CALL categories_sp()");
							while($row = mysqli_fetch_array($result))
					    	{
					      		echo "<option value='" . $row['id'] ."'>" . $row['cat_name'] . "</option>";
						    };
						    $result->close();
							$dbc->next_result();
						?>
						</select>
						<br><br>
						<input type="submit" class="btn btn-success" id="addCategory" name="addCategory" value="ADD CATEGORY">
					</form>
				</div>

				<div class="col-md-8">
					<h2>Add Reference</h2>
					<form action="edit.php?id=<?php echo $term_id ?>" method="POST">
					<label for="ref_name">Reference Name</label>
						<input type="text" name="ref_name" id="ref_name" placeholder="Web Terminology Reference Name" class="form-control" value="<?php echo isset($_POST['ref_name']) ? $_POST['ref_name'] : '' ?>">
						<br>
						<label for="ref_description">Reference Description</label>
						<textarea name="ref_description" id="ref_description" rows="3" class="form-control" required="required"><?php echo isset($_POST['ref_description']) ? $_POST['ref_description'] : '' ?></textarea>
						<br>
						<label for="ref_url">Reference URL</label>
						<input type="text" name="ref_url" id="ref_url" placeholder="Web Terminology Reference URL" class="form-control" value="<?php echo isset($_POST['term_url']) ? $_POST['term_url'] : '' ?>">
						<br>
						<input type="submit" class="btn btn-success" id="addReference" name="addReference">
					</form>
				</div>
			</div>	
			<?php
		}

	}
		else {
			echo "<h2 id=\"termFail\">No Web terminology Details Available</h2>";
		}
	}


	else {
		echo "No ID";
	}
	?>
</div>
<?php
require ('footer.php');
?>