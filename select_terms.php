<?php
require ('header.php');
?>
<?php
require 'code/connect.php';
?>
<div class="container">
<h1>All Terms</h1>
<?php
$result = mysqli_query($dbc,"CALL select_terms_all_sp()");
//$result = mysqli_query($dbc,"CALL categories_sp()");
?>
<table class="table">
	<thead>
		<tr>
			<th>Term Name</th>
			<th>Term Description</th>
		</tr>
	</thead>
	<tbody>	
	<?php

		while($row = mysqli_fetch_array($result))

		{	
			echo "<tr>";
	  		echo "<td>" . $row['term_name'] . "</td>";
	  		echo "<td>" . $row['term_description'] . "</td>";
	  		echo "</tr>";
	    };
	    $result->close();
		$dbc->next_result();
	?>															
	</tbody>
</table>	
</div>
<?php

?>
<?php
require ('footer.php');
?>