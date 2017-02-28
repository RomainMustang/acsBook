<?php
include 'vendor/autoload.php';
include 'db/database.class.php';

$datab  = new Connection("localhost", "root", "", "acsbook");
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig   = new Twig_Environment($loader);

$controller = isset($_GET["controller"]) ? $_GET["controller"] : "pages";
$action     = isset($_GET["action"]) ? $_GET["action"] : "home";

require 'routes.php';
