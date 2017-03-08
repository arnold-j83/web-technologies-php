<?php
require ('header.php');
require ('code/connect.php');
?>
<script type="text/javascript">
	
	$(document).ready(function() {
    	$("#chkIncludeDesc").attr("disabled", true);
    	var searchTextLen = ($("#searchText").val().length);
    		if (searchTextLen > 1){
    			$("#chkIncludeDesc").attr("disabled", false);
    		}
    	$('#searchText').keyup(function () { 
    		var searchTextLen = ($("#searchText").val().length);
    		if (searchTextLen > 1){
    			$("#chkIncludeDesc").attr("disabled", false);
    		}
    	});
    	if( $('#catID').length )         // use this if you are using id to check
		{
     		var catID = $("#catID").text();
     		$('#category').val(catID);	
		}
    	
	});

</script>
<div class="container">
	<h1>Search for Web Terminology</h1>
	<div class="box">
		<form action="search.php" method="POST" name="searchForm1" id="searchForm1">
			<fieldset>
				<div class="box">
					<div class="col-md-3">
						<label for="category">Search for Category</label>
					</div>
					<div class="col-md-6">
						<select name="category" id="category" class="form-control">
						<option value=""></option>
						<?php
							$result = mysqli_query($dbc,"CALL categories_sp()");
							//$result = mysqli_query($dbc,"CALL login_sp('$username', '$hashpassword')");
							while($row = mysqli_fetch_array($result))
					    	{
					      		echo "<option value='" . $row['id'] . ':'. $row['cat_name'] . "'>" . $row['cat_name'] . "</option>";
						    };
						    $result->close();
							$dbc->next_result();
						?>
						</select>
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-warning" name="searchCategoryButton" value="Search for Categories">
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="box">	
		<form action="search.php" method="POST" name="searchForm2" id="searchForm2">
			<fieldset>
				<div class="box">
					<div class="col-md-3">
						<label for="searchText">Search for Text</label>
					</div>
					<div class="col-md-6">
						<input type="text" name="searchText" id="searchText" class="form-control" value="<?php echo isset($_POST['searchText']) ? $_POST['searchText'] : '' ?>" >
						<br>
						<label for="chkIncludeDesc">Search Description?</label>&nbsp;&nbsp;
						<input type="checkbox" name="chkIncludeDesc" id="chkIncludeDesc" value="Y" <?php if(isset($_POST['chkIncludeDesc'])) echo "checked='checked'"; ?>>
					</div>
					<div class="col-md-3">
						<input type="submit" class="btn btn-warning" name="searchTextButton" value="Search for Text">
					</div>
				</div>		
			</fieldset>	
		</form>
	</div>
</div>
<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST["searchTextButton"])) {
			$searchText = $_POST['searchText'];
			if (isset($_POST['chkIncludeDesc'])) {
				$chkIncludeDesc = "YES";	
			} else {
			$chkIncludeDesc = "NO";
			}
			if (strlen($searchText) > 0) {
			if ($chkIncludeDesc == "NO"){
				$result = mysqli_query($dbc,"CALL search_termname_sp('$searchText')");	
			}
			else {
				$result = mysqli_query($dbc,"CALL select_terms_sp('$searchText')");
			}
			$row_count = $result->num_rows;
			echo "<div class=\"container\">";
			echo "<h2 id=\"searchSummary\">You Searched For: " . $searchText ." Number of Results: " .  $row_count . "</h2>";

			if($row_count > 0) {
				echo "<table class='table'>";
				echo "<thead>";
					echo "<tr>";
						echo "<th>Term Name</th>";
						echo "<th>Term description</th>";
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
	
				while($row = mysqli_fetch_array($result))
					{ 
						echo "<tr>";
						echo "<td><a href='term_details.php?id=" . $row['id'] . "'>". $row['term_name'] . "</a></td>";
						echo "<td>" . $row['term_description'] . "</td>";
						echo "</tr>";
						
					}
					echo "</tbody>";
				echo "</table>";	
				} 
			} 
			else {
				echo "<div class=\"container\">";
				echo "<h2>No Search Text</h2>";
			}
		}
		else if(isset($_POST["searchCategoryButton"]))
		{
			$category = $_POST['category'];	
			if (strlen($category) > 0) {
				$colonPos = strpos($category, ":");
				$catNum = substr($category,0,$colonPos);
				echo $catNum;
				$result = mysqli_query($dbc,"CALL search_categories_sp('$catNum')");
				$row_count = $result->num_rows;
				echo "<div class=\"container\">";
				echo "<h2 id=\"searchSummary\">You Searched For: <span id='catID'>" .$category . "</span> Number of Results: " . $row_count. "<br></h2>";
				if($row_count > 0) {
			
					echo "<table class='table'>";
						echo "<thead>";
							echo "<tr>";
								echo "<th>Term Name</th>";
								echo "<th>Term description</th>";
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
					while($row = mysqli_fetch_array($result))
					{ 
						echo "<tr>";
							echo "<td><a href='term_details.php?id=" . $row['id'] . "'>". $row['term_name'] . "</a></td>";
							echo "<td>" . $row['term_description'] . "</td>";
						echo "</tr>";
					}
					echo "</tbody>";
				echo "</table>";	
				}
			}
			else {
				echo "<div class=\"container\">";
				echo "<h2>No Search Category Selected</h2>";
			}			
		} 
	}
?>
</div>
<?php
require ('footer.php');
?>