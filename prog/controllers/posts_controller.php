<?php
class PostsController {
    public $nom, $prenom, $mail, $pwd;
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
            foreach($_POST as $key => $value) {
                $this->$key = htmlspecialchars($value);
            }
            $user = new UserModel(
                $this->nom,
                $this->prenom,
                $this->mail,
                $this->pwd
            );
            $user->NewUser();
        } else {
            echo $twig->render('accueil.twig');
        }
    }

    public function error() {
        die('<h1>404 not found');
    }
}
