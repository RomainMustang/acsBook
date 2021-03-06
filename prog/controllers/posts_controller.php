<?php
class PostsController {
    private $nom, $prenom, $email, $pwd, $message, $id, $id1, $id2, $option;

    public function home() {
        global $twig, $user, $wall;
        if ($this->isLogged() == false) {
            echo $twig->render("templates/views/index.html", [
                "error" => "danger",
                "message" => "Veuillez vous connecter/enregistrer pour accéder à cette page."
            ]);
        } else {
            $cookie = preg_replace("/[^a-zA-Z0-9s]/", "", $_COOKIE["user_token"]);
            $info = $user->checkCookie($cookie);
            echo $twig->render("templates/views/home.html", [
                "id" => $info["id"],
                "name" => $info["prenom"],
                "avatar" => $info["photos"]
            ]);
        }
    }

    public function profil() {
        global $twig, $user, $wall;
        if ($this->isLogged() == false) {
            echo $twig->render("templates/views/index.html", [
                "error" => "danger",
                "message" => "Veuillez vous connecter/enregistrer pour accéder à cette page."
            ]);
        } else {
            $cookie = preg_replace("/[^a-zA-Z0-9s]/", "", $_COOKIE["user_token"]);
            $info = $user->checkCookie($cookie);
            if ((isset($_GET["id"])) && (is_numeric($_GET["id"]))) {
                if ($user->getInfoById($_GET["id"])) {
                    echo $twig->render("templates/views/profil.html", [
                        "id" => $info["id"],
                        "id2" => $_GET["id"],
                        "name" => $info["prenom"],
                        "avatar" => $info["photos"],
                        "mur" => $user->getInfoById($_GET["id"])
                    ]);
                } else {
                    die("NOT FOUND.");
                }
            } else {
                echo $twig->render("templates/views/profil.html", [
                    "id" => $info["id"],
                    "name" => $info["prenom"],
                    "avatar" => $info["photos"],
                    "mur" => $user->getInfoById($info["id"])
                ]);
            }
        }
    }

    public function register() {
        global $twig, $user;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach($_POST as $key => $value) {
                $this->$key = htmlspecialchars($value);
            }
            $user->setRegister(
                $this->nom,
                $this->prenom,
                $this->mail,
                $this->pwd
            );
        } else {
            echo $twig->render('templates/views/index.html');
        }
    }

    public function login() {
        global $twig, $user;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach($_POST as $key => $value) {
                $this->$key = htmlspecialchars($value);
            }
            $user->setLogin(
                $this->email,
                $this->pass
            );
        } else {
            echo $twig->render('templates/views/login.html');
        }
    }

    public function logout() {
        global $twig;
        if (isset($_COOKIE["user_token"])) {
            setcookie("user_token", "", time()-3600);
            echo $twig->render('templates/views/index.html', [
                "error" => "info",
                "message" => "Merci de votre visite, à la prochaine."
            ]);
        } else {
            echo $twig->render('templates/views/index.html');
        }
    }

    public function isLogged() {
        global $user;
        if (!isset($_COOKIE["user_token"])) {
            return false;
        } else {
            $cookie = preg_replace("/[^a-zA-Z0-9s]/", "", $_COOKIE["user_token"]);
            if ((empty($cookie)) && (!is_array($user->checkCookie($cookie)))) {
                return false;
            }
        }
        return true;
    }

    public function wall() {
        global $wall;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach($_POST as $key => $value) {
                $this->$key = htmlspecialchars($value);
            }
            $wall->setMsg(
                $this->message,
                $this->id
            );
        }
    }

    public function wallView() {
        global $wall;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach($_POST as $key => $value) {
                $this->$key = htmlspecialchars($value);
            }
            if ((!isset($_GET["profil"]) && (!empty($this->id)))) {
                $wall->getMsgAll($this->id);
            } elseif ((isset($_GET["profil"])) && (!empty($this->id))) {
                $wall->getMsg($this->id);
            } else {
                die(json_encode([
                    'error' => true,
                    'message' => 'ID is missing'
                ], JSON_PRETTY_PRINT));
            }
        } else {
            die(json_encode([
                'error' => true,
                'message' => 'GET not supported'
            ], JSON_PRETTY_PRINT));
        }
    }

    public function friend() {
        global $friend;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach($_POST as $key => $value) {
                $this->$key = htmlspecialchars($value);
            }
            if ((sizeof($_POST) == 1) && (!isset($_GET["list"]))) { // notifications
                $friend->getNotif($this->id);
            } elseif ((sizeof($_POST) == 1) && (isset($_GET['list']))) { // get all friends in a json c:
                $friend->getAllFriends($this->id);
            } else { // someone accepted/refuse a request.
                $friend->setRequest(
                    $this->id1,
                    $this->id2,
                    $this->option,
                    false
                );
            }
        } else {
            die(json_encode([
                'error' => true,
                'message' => 'GET not supported'
            ], JSON_PRETTY_PRINT));
        }
    }

    public function friends() {
        global $twig, $user;
        $cookie = preg_replace("/[^a-zA-Z0-9s]/", "", $_COOKIE["user_token"]);
            $info = $user->checkCookie($cookie);
            echo $twig->render("templates/views/friends.html", [
                "id" => $info["id"],
                "name" => $info["prenom"],
                "avatar" => $info["photos"]
            ]);
    }

    public function search(){
        global $twig, $user;
        if(isset($_POST['srch-term'])){
            $user->search($_POST['srch-term']);
        }
        $info = $user->checkCookie($cookie);
        echo $twig->render("templates/views/friends.html", [
            "id" => $info["id"],
            "name" => $info["prenom"],
            "avatar" => $info["photos"]
        ]);
    }

    public function error() {
        global $twig;
        echo $twig->render('templates/views/error.html');
    }
}
