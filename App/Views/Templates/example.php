<?php

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
    <link rel="icon" href="./Public/images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <link rel="stylesheet" type="text/css" href="Public/CSS/style.css">
</head>
<body>
    <?= $content;?>

    
    <script src="Public/JS/index.js" defer></script> 
</body>
</html>