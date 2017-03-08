<?php

require 'code/connect.php';

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    //echo "this is a get request\n";
    //echo $_GET['fruit']." is the fruit\n";
    //echo "I want ".$_GET['quantity']." of them\n\n";
    if (isset($_GET['searchType'])) {
    	$searchType = $_GET['searchType'];

		if ($searchType == "categories") {
			$result = mysqli_query($dbc,"CALL categories_sp()");
			$json = array();
		    while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $json[] = $row;
    		}
		    $result->close();
			$dbc->next_result();
			header('Content-type: application/json');
	 		echo json_encode($json);
		}

    } 

    else {


	    if (isset($_GET['searchterm'])) {
			$searchTerm = (string)$_GET['searchterm'];
			//$result = mysqli_query($dbc,"CALL select_terms_sp('$searchTerm')");
			$result = mysqli_query($dbc,"CALL search_term_with_category_sp('$searchTerm')");
			
		} 
			else

		{
			$result = mysqli_query($dbc,"CALL select_terms_all_sp()");
		}

		$json = array();
		$json2 = array();
		while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $json[] = $row;
    	}
		$result->close();
		$dbc->next_result();
		header('Content-type: application/json');
 		echo json_encode($json);
 		//echo $json3;
	} 

} elseif($_SERVER['REQUEST_METHOD'] == 'PUT') {
		parse_str(file_get_contents("php://input"),$post_vars);
    	$api_key = $post_vars['api_key'];
		$api_key2 = $post_vars['api_key2'];
		$salt_api_key = $api_key . "4b8ys9b"; 
		$hash_api_key = hash("sha256", $salt_api_key);

		if ($api_key2 == $hash_api_key) 
		{
			echo $api_key;
			echo $hash_api_key;
			$allowed_to_PUT = FALSE;
	    	echo "this is a PUT request\n";
	    	$result = mysqli_query($dbc,"CALL login_REST_sp('$hash_api_key')");
    		$row_count = $result->num_rows;
			if($row_count == 1) {
				echo "<h2 id=\"loginSuccess\">User Exists and has Authority to POST AND PUT</h2>";
				while($row = mysqli_fetch_array($result))
				{
					//echo $row['username'];	
					$allowed_to_PUT = TRUE;

				}
			}
				else {
				echo "<h2 id=\"loginFail\">Login Failed</h2>";
			}

			echo $allowed_to_PUT;
	    }
	    else {
	    	echo "API Key Incorrect";
	    }
	    $dbc->next_result();
	    if($allowed_to_PUT)
    {
    	echo "Allowed to PUT";
    	if ((isset($post_vars['term_name'])) AND  (isset($post_vars['term_description'])) AND  (isset($post_vars['term_id']))) {
    		$term_name = $post_vars['term_name'];
	    	$term_name = filter_var($term_name, FILTER_SANITIZE_SPECIAL_CHARS);
	    	echo $term_name;
			$term_description = $post_vars['term_description'];
			$term_description = filter_var($term_description, FILTER_SANITIZE_SPECIAL_CHARS);
			$term_id = (int)$post_vars['term_description'];
			echo $term_name . " " . $term_description . " " . $term_id;

			echo "<h2 id=\"createSuccess\">New Web Terminology Updated</h2>";
    	}
    }
    } 
    

 elseif($_SERVER['REQUEST_METHOD'] == 'POST') {
    parse_str(file_get_contents("php://input"),$post_vars);
	$allowed_to_POST = FALSE;
	$api_key = $post_vars['api_key'];
	$api_key2 = $post_vars['api_key2'];
	$salt_api_key = $api_key . "4b8ys9b"; 
	$hash_api_key = hash("sha256", $salt_api_key);
	echo $allowed_to_POST;
	if ($api_key2 == $hash_api_key) 
	{
		echo $api_key;
		echo $hash_api_key;
    	echo "This is a POST request\n";
    	$result = mysqli_query($dbc,"CALL login_REST_sp('$hash_api_key')");
    	$row_count = $result->num_rows;
		if($row_count == 1) {
			echo "<h2 id=\"loginSuccess\">User Exists and has Authority to POST AND PUT</h2>";
			while($row = mysqli_fetch_array($result))
				{
					//echo $row['username'];	
					$allowed_to_POST = TRUE;
				}
			}
				else {
				echo "<h2 id=\"loginFail\">Login Failed</h2>";
			}


    }
    else {
    	echo "API Key Incorrect";
    }	
    $dbc->next_result();
    if($allowed_to_POST)
    {
    	echo "Allowed TO POST";

    	if ((isset($post_vars['term_name'])) AND  (isset($post_vars['term_description'])) AND  (isset($post_vars['category']))) {
    	$term_name = $post_vars['term_name'];
    	$term_name = filter_var($term_name, FILTER_SANITIZE_SPECIAL_CHARS);
    	echo $term_name;
		$term_description = $post_vars['term_description'];
		$term_description = filter_var($term_description, FILTER_SANITIZE_SPECIAL_CHARS);
		echo $term_description;
		$term_category = $post_vars['category'];
		$term_category = filter_var($term_category, FILTER_SANITIZE_SPECIAL_CHARS);
		echo $term_category;
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

	    if((isset($post_vars['ref_name'])) AND  (isset($post_vars['ref_url'])) AND  (isset($post_vars['ref_description']))) {
	    	$ref_name = $post_vars['ref_name'];
	    	$ref_name = filter_var($ref_name, FILTER_SANITIZE_SPECIAL_CHARS);
	    	$ref_url = $post_vars['ref_url'];
	    	$ref_url = filter_var($ref_url, FILTER_SANITIZE_URL);
	    	$ref_description = $post_vars['ref_description'];
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
    
}
?>