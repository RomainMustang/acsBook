<?php
class UserModel {
    private $nom, $prenom, $email, $pass, $CryPass, $error = [];

    public function setRegister($n, $pn, $e, $p) {
        $this->nom      = $n;
        $this->prenom   = $pn;
        $this->email    = $e;
        $this->pass     = $p;
        $this->CryPass  = $this->createPassword($p);
        $this->NewRegister();
    }

    public function setLogin($email, $password) {
        $this->email    = $email;
        $this->pass     = $password;
        $this->CryPass  = $this->createPassword($password);
        $this->newLogin();
    }

    public function newLogin() {
        global $twig;
        if ((empty($this->email)) || (empty($this->pass))) {
            $this->error = [
                'error' => 'danger',
                'message' => 'Tu dois remplir tous les champs'
            ];
            echo $twig->render('login.html', $this->error);
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->error = [
                'error' => 'danger',
                'message' => "L'adresse email doit être valide.",
                'data' => [
                    'pass' => $this->pass
                ]
            ];
            echo $twig->render('login.twig', $this->error);
        } else if (!is_array($this->userInfo($this->email))) {
            $this->error = [
                'error' => 'danger',
                'message' => "L'adresse email est associée à aucun compte."
            ];
            echo $twig->render('login.twig', $this->error);
        } elseif (!$this->checkPass($this->email, $this->pass)) {
            $this->error = [
                'error' => 'danger',
                'message' => "Le mot de passe est incorrect."
            ];
            echo $twig->render('login.html', $this->error);
        } else {
            $this->setToken($this->email);
            $this->error = [
                'error' => 'success',
                'message' => "Vous êtes maintenant connectés sur le site!"
            ];
            echo $twig->render('home.html', [
                "name" => $this->userInfo($this->email)["prenom"],
                "avatar" => "http://pre14.deviantart.net/e12b/th/pre/i/2012/206/0/6/fb_page_avatar___happy_by_muller_saru-d58lfe4.png"
            ]);
        }
    }

    public function NewRegister() {
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
        } else if (is_array($this->userInfo($this->email))) {
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
        echo $twig->render('accueil.html', $this->error);
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

    public function userInfo($email) {
        global $datab;
        $query  = $datab->pdo->query("SELECT * from utilisateurs where mail = '{$email}'");
        $fetch  = $query->fetch();
        return count($fetch) > 1 ? $fetch : false;
    }

    public function setToken($email) {
        global $datab;
        $token = bin2hex(random_bytes(30));
        setcookie('user_token', $token, time() + 3600);
        $datab->pdo->query("update utilisateurs set token = '{$token}' where mail = '{$email}'");
    }

    public function checkCookie($cookie) {
        global $datab;
        $query  = $datab->pdo->query("SELECT * from utilisateurs where token = '{$cookie}'");
        $fetch  = $query->fetch();
        return count($fetch) > 1 ? $fetch : false;
    }

    public function createPassword($pass) {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    public function checkPass($email, $pass) {
        $info = $this->userInfo($email);
        return password_verify($pass, $info["password"]) ? true : false;
    }

}
