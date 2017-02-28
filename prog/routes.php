<?php

require "vendor/autoload.php";

function road($controller, $page){

	/*  Appel du controller spécifier  */
	require_once('controllers/' . $controller . '_controller.php');

	/*  page par défaut  */
	$page = 'index';

	if(isset($_GET['p'])) {
		$page = $_GET['p'];
	}

	$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
	$twig   = new Twig_Environment($loader, [
		'cache' => false
	]); 

	/*  Chargement de la page choisie  */
	switch ($page){

		case 'home':
			echo $twig->render('views/home.html');
		break;

		case 'friends':
			echo $twig->render('views/friends.html');
		break;

		case 'profil':
			echo $twig->render('views/profil.html');
		break;

		default:
			echo $twig->render('views/index.html');
		break;
	}
}

?>