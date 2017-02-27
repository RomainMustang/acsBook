<?php

require "vendor/autoload.php";

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
		echo $twig->render('home.html');
	break;

	case 'friends':
		echo $twig->render('friends.html');
	break;

	case 'profil':
		echo $twig->render('profil.html');
	break;

	default:
		echo $twig->render('index.html');
	break;
}

?>