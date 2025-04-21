<?php
define("ROOT",__DIR__);
require ROOT.DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'App.php';

App::load();

use App\Controller\ExampleController;


App::sessionstart();

if(isset($_GET['url']))
{
    $_GET['url'] =htmlentities( $_GET['url']);
    $_GET['url'] = htmlspecialchars( $_GET['url']);

    $url = explode("/", $_GET['url']);
}



var_dump($_GET);

$controller = new ExampleController;

$controller->example();