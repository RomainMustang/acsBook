<?php
/*  Utilise le controller que l'on a spécifié  */
function road($controller, $action){
	require_once('controllers/' . $controller . '_controller.php');
	switch($controller){
		case 'pages':
			$controller = new PagesController();
		break;
		case 'posts':
			require_once('models/friends_model.php');
			require_once('models/post_model.php');
			require_once('models/user_model.php');
			require_once('models/wall_model.php');
			$controller = new PostsController();
		break;
	}
	$controller->{ $action }();
}
/*  Toutes les pages du modele MVC  */
$controllers = [
    "pages" => [
        "index",
        "error"
    ],
    "posts" => [
        "home",
        "login",
        "register",
        "profil",
        "friends",
        "logout",
        "wall",
        "wallView"
    ]
];

$models = [
    "friend" => "friends",
    "wall" => "wall",
    "post" => "post",
    "user" => "user"
];
foreach($models as $key => $value) {
    require 'models/' . $value . '_model.php';
    if (class_exists(ucfirst($value)."Model")) {
        switch($key) {
            case "user":
                $user = new UserModel();
                break;
            case "friend":
                $friend = new FriendsModel();
                break;
            case "wall":
                $wall = new WallModel();
                break;
            default:
                die('Failed to load some class'.$key);
                break;
        }
    }
}
/*  Vérifie si le controller fait partie de la liste de controller  */
if (array_key_exists($controller, $controllers)) {
	
	/*  Vérifie si l'action est dans la liste du controller  */
	if (in_array($action, $controllers[$controller])) {
    
    /*  Appel le controller  */
    road($controller, $action);
    } 
    
    /*  Sinon affiche une page d'erreur  */
    else {
    
      road('pages', 'error');
    
    }
} 
else {
	road('pages', 'error');
}
?>
