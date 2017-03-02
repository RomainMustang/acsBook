<?php

	class WallModel{

		private $message, $data, $id_user;

		function __construct($msg){

			$this->setMsg($msg);

		} 

		private function setMsg($msg){
			$this->message = $msg[0];
			$this->data    = $msg[1];
			$this->id_user = $msg[2];
		}

	}

?>