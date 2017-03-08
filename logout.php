<?php
session_start();
$_SESSION["loginusername"] = "";
unset($_SESSION["loginusername"]);
$_SESSION["admin_level"] = "";
unset($_SESSION["admin_level"]);

header( 'Location: index.php' );  
?>
