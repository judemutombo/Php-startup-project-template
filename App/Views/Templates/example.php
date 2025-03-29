<?php
/* define("root",dirname(dirname(dirname(__DIR__))));
require_once root.'/App/App.php'; */
App::load();

if(isset($_GET['url']))
{
    $url = explode("/",$_GET['url']);
}
else{
    $url = array("");
}
$base = App::urlbase($url);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
   
    <?= '<base href="'.$base.'">' ?>
    <meta charset="utf-8">
    <title><?= App::getInstance()->getTitle()?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" >
</head>
<body>
    <?= $content;?>
</body>
</html>