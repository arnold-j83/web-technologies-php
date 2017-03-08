<?php
class newUser {

	private $_username;
	private $_password;
	public function __construct($username, $password){
		$this->_username = $username;
		$this->_password = $password;
	}

	public function get_username(){
		return $this->_username;
	}

	public function get_password(){
		return $this->_username;
	}

	public function create_user() {
		$dbc = @mysqli_connect ( 'localhost', 'root', 'MyNewPass', 'web_tech' )
		/*if the connection fails call error message function */
		OR die ( mysqli_connect_error() ) ;

		/*encoding for PHP scripts with MySQL */
		mysqli_set_charset( $dbc, 'utf8' ) ;


		mysqli_query($dbc,"CALL login_sp($this->_username, $this->_password)");
		return ("done");
	}
}

?>