<?php

	class WallModel{

		private $message, $date, $id_user;

		public function createMsg($msg){

			$this->setMsg($msg);

		} 

		private function setMsg($msg){
			$this->message = $msg[0];
			$this->date    = $msg[1];
			$this->id_user = $msg[2];
		}

		public function stockMsg($msg){
			global $datab;
			$query   = "INSERT INTO posts (message,date, id_utilisateur) VALUES(:message, NOW(), :id_utilisateur)";
			$execute = $datab->pdo->prepare($query);
        	$execute->bindParam(':message', $this->message);
        	$execute->bindParam(':id_utilisateur', $this->id_user);
        	return $execute->execute();
		}

	}

?>