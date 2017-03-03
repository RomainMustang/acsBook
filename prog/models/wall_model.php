<?php
class WallModel{
    public $message, $date, $id_user;
    public function setMsg($message, $id) {
        $this->message  = $message;
        $this->date     = time();
        $this->id_user  = $id;
        $this->stockMsg();
    }
    public function stockMsg() {
        global $datab;
        $query      = "INSERT INTO posts (message, date, id_utilisateur) VALUES(:message, NOW(), :id_utilisateur)";
        $execute    = $datab->pdo->prepare($query);
        $execute->bindParam(':message', $this->message);
        $execute->bindParam(':id_utilisateur', $this->id_user);
        return $execute->execute();
    }
    public function getMsg($id){
        global $datab;
        $info   = [];
        $query  = $datab->pdo->query("SELECT * from posts where id_utilisateur = '{$id}'");
        $fetch  = $query->fetchAll();
        foreach($fetch as $key => $value) {
            $info[] = [
                "id" => $value["id"],
                "message" => $value["message"],
                "date" => $value["date"]
            ];
        }
        return sizeof($info)  == 0 ? false : print json_encode($info);
    }
}
