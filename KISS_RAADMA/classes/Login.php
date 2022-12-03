<?php

  require_once($_SERVER['DOCUMENT_ROOT']."/KISS_RAADMA/constants.php");

 class Login{

	private $username = null;
	private $password = null;

	public function __construct($username = null, $password = null){
		$this->setUsername($username);
		$this->setPassword($password);

	}

	public function setUsername($username){
		$this->username = str_replace("'", "''", $username);
	}	

	public function getUsername(){
		return $this->username;
	}


	public function setPassword($password){
		if(isset($password)){
			$this->password = $password;
		}else{
			$this->password = null;
		}
	}

	public function getPassword(){
		return $this->password;
	}


}


?>