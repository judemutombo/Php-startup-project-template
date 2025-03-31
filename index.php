<?php
define("ROOT",__DIR__);
require ROOT.'/App/App.php';

App::load();

use App\Controller\ExampleController;


App::sessionstart();

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