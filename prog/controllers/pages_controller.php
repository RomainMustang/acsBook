<?php

	class PagesController{

	public function index() {
		global $twig;
    	echo $twig->render('templates/views/index.html');
    }

    public function error() {
    	global $twig;
    	echo $twig->render('templates/views/error.html');
    }

}

?>