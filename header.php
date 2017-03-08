<?php
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time();
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="author" content="John Arnold">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>Web Terminology Database</title>
</head>
<body>
<nav class="navbar navbar-inverse">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">Web Terminology</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myCollapsingList">
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse" id="myCollapsingList">
        <ul class="nav navbar-nav navbar-right">
          <li><a data-toggle="collapse" data-target=".navbar-collapse" href="index.php">Home</a></li>
          <?php 
          if(isset($_SESSION['loginusername']))
          {
            echo "<li><a data-toggle='collapse' data-target='.navbar-collapse'  href='logout.php'>Logout ";
            echo $_SESSION['loginusername'];
            echo"</a></li>";
          }

          else {
            echo "<li><a data-toggle='collapse' data-target='.navbar-collapse' href='login.php'>Log In</a></li>";
          };
          ?>          
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
            <ul class="dropdown-menu">

              <li><a data-toggle="collapse" data-target=".navbar-collapse" href='create_login.php'>Create Login</a></li>
              <li><a data-toggle="collapse" data-target=".navbar-collapse" href='create_terms.php'>Insert Terminology</a></li>
              <li><a data-toggle="collapse" data-target=".navbar-collapse" href='search.php'>Search Terminology</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    