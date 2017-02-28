<?php
class PostsController {
    public function home() {
        global $twig;
        echo $twig->render('home.twig', [
            "name" => "",
            "avatar" => ""
        ]);
    }

    public function register() {
        global $twig;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom    = isset($_POST["nom"]) ? htmlspecialchars($_POST["nom"]) : "";
            $pnom   = isset($_POST["prenom"]) ? htmlspecialchars($_POST["prenom"]) : "";
            $email  = isset($_POST["mail"]) ? htmlspecialchars($_POST["mail"]) : "";
            $pass   = isset($_POST["pwd"]) ? htmlspecialchars($_POST["pwd"]) : "";
            $user = new UserModel($nom, $pnom, $email, $pass);
            $user->NewUser();
        } else {
            echo $twig->render('accueil.twig');
        }
    }

    public function error() {
        die('<h1>404 not found');
    }
}
