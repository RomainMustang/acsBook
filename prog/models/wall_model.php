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
        $query  = $datab->pdo->prepare("SELECT * from posts where id_utilisateur = :id ORDER by id DESC");
        $query->bindParam(':id', $id);
        $query->execute();
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

    public function getMsgAll($id){
        global $datab, $user, $friend;
        $info   = [];
        $query  = $datab->pdo->query("SELECT * from posts ORDER by id DESC");
        $fetch  = $query->fetchAll();
        foreach($fetch as $key => $value) {
            if (($friend->getFriendStatus($id, $value["id_utilisateur"]) !== false || $id == $value["id_utilisateur"])) {
                $info[] = [
                    "id" => $value["id"],
                    "message" => $value["message"],
                    "date" => $this->time_elapsed_string($value["date"]),
                    "nom_util" => $user->getNameById($value["id_utilisateur"]),
                    "id_util" => $value["id_utilisateur"],
                    "avatar" => $user->getInfoById($value["id_utilisateur"])["photos"]
                ];
            }
        }
        return sizeof($info)  == 0 ? false : print json_encode($info);
    }

    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'an',
            'm' => 'mois',
            'w' => 'semaine',
            'd' => 'jour',
            'h' => 'heure',
            'i' => 'minute',
            's' => 'seconde',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string)  : 'à l\'instant';
    }
}
