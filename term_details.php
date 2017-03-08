<?php
require ('header.php');
require ('code/connect.php');
?>
<div class="container">
	<?php

	if(isset($_GET["id"])) {
		$term_id = (int)$_GET["id"];
		$result = mysqli_query($dbc,"CALL term_details_sp('$term_id')");
		$row_count = $result->num_rows;




		if($row_count == 1) {
			echo "<h2 id=\"termExists\">Term Details</h2>";

			if(isset($_SESSION['loginusername']))
        	{
        		echo "<p><a href=\"edit.php?id=" . $term_id . "\"><i class='fa fa-pencil-square-o' style='font-size:36px'></i></a></p>";
        	};
			if($row_count > 0) {
				while($row = mysqli_fetch_array($result)) { 
					echo "<div class=\"col-md-8 term_details_box\">";
					echo "<h3>" . $row['term_name'] . "</h3>";
					echo "<h4>" . $row['term_description'] . "</h4>";
					echo "</div>";
				}
				$dbc->next_result();
				$result = mysqli_query($dbc,"CALL term_categories_sp('$term_id')");
				$row_count = $result->num_rows;
				echo "<div class=\"col-md-4 term_details_box\">";
				if ($row_count > 0) {
					echo "<h3>Categories</h3>";
					echo "<ul>";
					while($row = mysqli_fetch_array($result)) { 
						echo "<li>" . $row['cat_name'] . "</li>";
					}
					echo "</ul>";	

				}
				else {
					echo "<h3>No Categories</h3>";
				}
				echo "</div>";
				echo "<br>";
				$dbc->next_result();
				echo "<br>";
				echo "<div class=\"col-md-8 term_details_box\">";
				$result = mysqli_query($dbc,"CALL term_references_sp('$term_id')");
				$row_count = $result->num_rows;
				
				if ($row_count > 0) {
					echo "<h3>References:</h3>";
					
					while($row = mysqli_fetch_array($result)) { 
						echo "<h4>" . $row['reference_name'] . "</h4>";
						echo "<p>" . $row['reference_description'] . "</p>";
						echo "<p><a href=\"" . $row['reference_url'] ."\">". $row['reference_url'] . "</a>";
					}
					echo "<br><hr><br>";	

				}
				else {
					echo "<h3>No References</h3>";
				}
				echo "</div>";
				$dbc->next_result();

				$result = mysqli_query($dbc,"CALL show_associated_terms_sp('$term_id')");
				$row_count = $result->num_rows;
				echo "<div class=\"col-md-4 term_details_box\">";
				if ($row_count > 0) {
					echo "<h3>See Also</h3>";
					echo "<ul>";
					while($row = mysqli_fetch_array($result)) { 
						echo "<li><a href=\"term_details.php?id=". $row['term_id_2'] . "\">" . $row['term_name'] . "</li>";
					}
					echo "</ul>";	

				}
				else {
					echo "<h3>No Categories</h3>";
				}
				echo "</div>";
				$dbc->next_result();
			} 

       		
			
		else {
			echo "<h2 id=\"termFail\">No Web terminology Details Available</h2>";
		}
	}

	else {
		echo "No ID";
	}
}
	?>
</div>
<?php
require ('footer.php');
?>