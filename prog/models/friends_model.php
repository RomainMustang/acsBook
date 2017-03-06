<?php
class FriendsModel {
    public $id1, $id2, $option;

    public function setRequest($id1, $id2, $option, $update = false) {
        $this->id1 = $id1;
        $this->id2 = $id2;
        $this->option = $option;
        var_dump($update);
        $update == true ? $this->createRequest() : $this->saveRequest();
    }

    public function saveRequest() {
        global $datab;
        $query = $datab->pdo->prepare("UPDATE amis SET valeur = :opt WHERE id_ami1 = :id1 and id_ami2 = :id2");
        $query->bindParam(":opt", $this->option);
        $query->bindParam(":id1", $this->id1);
        $query->bindParam(":id2", $this->id2);
        $query->execute();
        return print json_encode([
            "error" => false
        ]);
    }

    public function getNotif($id) {
        global $datab, $user;
        $query  = $datab->pdo->prepare("SELECT * from amis where id_ami1 = :id and valeur = '1' ORDER by id DESC");
        $query->bindParam(':id', $id);
        $query->execute();
        $fetch  = $query->fetchAll();
        $info   = ["friendcount" => sizeof($fetch)];
        foreach($fetch as $key => $value) {
            $info[] = [
                "id" => $value["id_ami2"],
                "av" => $user->getAvatarById($value["id_ami2"]),
                "nom" => $user->getNameById($value["id_ami2"])
            ];
        }
        return sizeof($info)  == 0 ? false : print json_encode($info);
    }

    public function getAllFriends($id) {
        global $datab, $user;
        $query  = $datab->pdo->prepare("SELECT * from amis where id_ami1 = :id and valeur = '2' ORDER by id DESC");
        $query->bindParam(':id', $id);
        $query->execute();
        $fetch  = $query->fetchAll();
        $info   = [];
        foreach($fetch as $key => $value) {
            $info[] = [
                "id" => $value["id_ami2"],
                "nom" => $user->getNameById($value["id_ami2"]),
                "av" => $user->getAvatarById($value["id_ami2"])
            ];
        }
        return sizeof($info)  == 0 ? false : print json_encode($info);
    }

    public function getFriendStatus($id, $id2) {
        global $datab;
        $query = $datab->pdo->prepare("SELECT * FROM amis WHERE id_ami1 = :id1 and id_ami2 = :id2");
        $query->bindParam(":id1", $id);
        $query->bindParam(":id2", $id2);
        $query->execute();
        $fetch = $query->fetch();
        return sizeof($fetch) > 1 && $fetch["valeur"] == 2 ? true : false;
    }
}
