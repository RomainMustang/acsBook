<?php

class Controller{

	private $firstName, $name, $mail, $password;
	
	function __construct($user){

		setParam($user);
	
	}

	private function setParam($user){

		$this->firstName = $user[0];
		$this->name      = $user[1];
		$this->mail      = $user[2];
		$this->password  = $user[3];
	}

}

?>