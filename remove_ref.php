<?php
require ('code/connect.php');

if(isset($_GET["term_id"]) AND isset($_GET["ref_id"])) {
$term_id = $_GET["term_id"];
$ref_id = $_GET["ref_id"];
echo $term_id . " " . $ref_id;

$result = mysqli_query($dbc,"CALL delete_term_ref_sp('$term_id', '$ref_id')");

$dbc->next_result();

$result = mysqli_query($dbc,"CALL delete_ref_id('$ref_id')");

}

$returnURL = "edit.php?id=". $term_id;
header( 'Location: ' . $returnURL ) ;
?>