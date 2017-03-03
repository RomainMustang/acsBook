<?php
class PostsController {
    private $nom, $prenom, $email, $pwd;
    public function home() {
        global $twig, $user;
        if (!isset($_COOKIE["user_token"])) {
            echo $twig->render('templates/views/index.html', [
                "error" => "danger",
                "message" => "Veuillez vous connecter pour accéder à cette page."
            ]);
        } else {
            $cookie = preg_replace("/[^a-zA-Z0-9s]/", "", $_COOKIE["user_token"]);
            if ((!empty($cookie)) && (is_array($user->checkCookie($cookie)))) {
                $info = $user->checkCookie($cookie);
                echo $twig->render("templates/views/home.html", [
                    "id" => $info["id"],
                    "name" => $info["prenom"],
                    "avatar" => "http://i.imgur.com/xWhH1Xp.png"
                ]);
            } else {
                echo $twig->render("templates/views/index.html", [
                    "error" => "danger",
                    "message" => "Veuillez vous reconnecter pour accéder à cette page."
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
            echo $twig->render('accueil.twig', [
                "error" => "info",
                "message" => "Merci de votre visite, à la prochaine."
            ]);
        } else {
            echo $twig->render('accueil.twig');
        }
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
            if (!empty($this->id)) {
                $wall->getMsgAll($this->id);
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
    public function error() {
        global $twig;
        echo $twig->render('templates/views/error.html');
    }
}

?>
