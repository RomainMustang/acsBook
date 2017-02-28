<?php

require "vendor/autoload.php";

/*  Utilise le controller que l'on a spécifié  */
function road($controller, $action){

	require_once('controllers/' . $controller . '_controller.php');

	switch($controller){
		case 'pages':
			$controller = new PagesController();
		break;
		case 'posts':
			$controller = new PostsController();
		break;
	}
	$controller->{ $action }();
}

/*  Toutes les pages du modele MVC  */
$controllers = array('pages' => ['index', 'error'],
					 'posts' => ['home', 'friends', 'profil']);

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