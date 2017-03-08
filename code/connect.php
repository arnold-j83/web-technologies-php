<?php 
/* Connect with passwords 'localhost', 'root' and on a server a password, here empty, to database 'cop'. */
$dbc = @mysqli_connect ( 'localhost', 'root', 'MyNewPass', 'web_tech' )
/*if the connection fails call error message function */
OR die ( mysqli_connect_error() ) ;

/*encoding for PHP scripts with MySQL */
mysqli_set_charset( $dbc, 'utf8' ) ;




?>