<?php
class UserModel {
    private $nom, $prenom, $email, $pass, $error = [];

    public function __construct($n, $pn, $e, $p) {
        $this->nom      = $n;
        $this->prenom   = $pn;
        $this->email    = $e;
        $this->pass     = $p;
        $this->CryPass  = $this->createPassword($p);
    }

    public function NewUser() {
        global $twig;
        if ((empty($this->nom)) || (empty($this->prenom)) || (empty($this->email)) || (empty($this->pass))) {
            $this->error = [
                'error' => 'danger',
                'message' => 'Tu dois remplir tous les champs'
            ];
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->error = [
                'error' => 'danger',
                'message' => "L'adresse email doit être valide.",
                'data' => [
                    'nom' => $this->nom,
                    'prenom' => $this->prenom,
                    'pass' => $this->pass
                ]
            ];
        } else if (strlen($this->pass) <= 6) {
            $this->error = [
                'error' => 'danger',
                'message' => 'Votre mot de passe doit être supérieur à 6 caractères.',
                'data' => [
                    'nom' => $this->nom,
                    'prenom' => $this->prenom,
                    'mail' => $this->email
                ]
            ];
        } else if ($this->emailTaken($this->email)) {
            $this->error = [
                'error' => 'danger',
                'message' => 'Cette adresse email est déjà prise!',
                'data' => [
                    'nom' => $this->nom,
                    'prenom' => $this->prenom,
                    'pass' => $this->pass
                ]

            ];
        } else {
            $this->insert();
            $this->error = [
                'error' => 'success',
                'message' => 'Votre compte a bien été créée!'
            ];
        }
        echo $twig->render('accueil.twig', $this->error);
    }

    public function insert() {
        global $datab;
        $query      = "INSERT INTO utilisateurs (nom, prenom, mail, password) VALUES(:nom, :prenom, :mail, :password)";
        $execute    = $datab->pdo->prepare($query);
        $execute->bindParam(':nom', $this->nom);
        $execute->bindParam(':prenom', $this->prenom);
        $execute->bindParam(':mail', $this->email);
        $execute->bindParam(':password', $this->CryPass);
        return $execute->execute();
    }

    public function emailTaken($email) {
        global $datab;
        $query  = $datab->pdo->query("SELECT * from utilisateurs where mail = '{$email}'");
        $fetch  = $query->fetch();
        return count($fetch) > 1 ? true : false;
    }

    public function createPassword($pass) {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

}
