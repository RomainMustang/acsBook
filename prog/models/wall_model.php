<?php

class WallModel{

	private $message, $date, $id_user;


	function createMsg($msg){

		$this->setMsg($message);
	} 

	private function setMsg($msg){
		$this->message = $msg[0];
		$this->date = $msg[1];
		$this->id_user = $msg[2];
	}

	public function stockMsg($msg) {
        global $datab;
        $query      = "INSERT INTO posts (message, date, id_utilisateur) VALUES(:message, NOW(), :id_utilisateur)";
        $execute    = $datab->pdo->prepare($query);
        $execute->bindParam(':message', $this->message);
        $execute->bindParam(':id_utilisateur', $this->id_user);
        return $execute->execute();
	}

	public function getMsg($msg){
		 global $datab;
        $query  = $datab->pdo->query("SELECT * from posts where message = '{$msg}'");
        $fetch  = $query->fetch();
        return count($fetch) > 1 ? $fetch : false;
	}
}