<?php
require "vendor/autoload.php";
require './App/App.php';

use App\Controller\ExampleController;

define("ROOT",__DIR__);
session_start();

if(isset($_GET['url']))
{
    $_GET['url'] =htmlentities( $_GET['url']);
    $_GET['url'] = htmlspecialchars( $_GET['url']);

    $url = explode("/", $_GET['url']);
}
else{
    header("Location:home");
}

$controller = new ExampleController;

$controller->example();