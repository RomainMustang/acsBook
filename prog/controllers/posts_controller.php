<?php
class PostsController {
    private $nom, $prenom, $email, $pwd;

    public function home() {
        global $twig, $user;
        if (!isset($_COOKIE["user_token"])) {
            echo $twig->render('accueil.twig', [
                "error" => "danger",
                "message" => "Veuillez vous connecter pour accéder à cette page."
            ]);
        } else {
            $cookie = preg_replace("/[^a-zA-Z0-9s]/", "", $_COOKIE["user_token"]);
            if ((!empty($cookie)) && (is_array($user->checkCookie($cookie)))) {
                $info = $user->checkCookie($cookie);
                echo $twig->render("home.twig", [
                    "name" => $info["prenom"],
                    "avatar" => "http://pre14.deviantart.net/e12b/th/pre/i/2012/206/0/6/fb_page_avatar___happy_by_muller_saru-d58lfe4.png"
                ]);
            } else {
                echo $twig->render("accueil.twig", [
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
            echo $twig->render('accueil.twig');
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
            echo $twig->render('login.twig');
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

    public function error() {
        global $twig;
        echo $twig->render('error.twig');
    }
}
