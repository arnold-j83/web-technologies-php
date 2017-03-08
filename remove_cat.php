<?php
require ('code/connect.php');

if(isset($_GET["term_id"]) AND isset($_GET["cat_id"])) {
$term_id = $_GET["term_id"];
$cat_id = $_GET["cat_id"];
//echo $term_id . " " . $cat_id;

$result = mysqli_query($dbc,"CALL delete_term_cat_sp('$term_id', '$cat_id')");


}

$returnURL = "edit.php?id=". $term_id;
header( 'Location: ' . $returnURL ) ;
?>