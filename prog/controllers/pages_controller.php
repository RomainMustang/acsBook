<?php

	class PagesController{

	public function index() {
    	require_once('templates/views/index.html');
    }

    public function error() {
    	require_once('templates/views/error.html');
    }

}

?>