<?php

namespace App\Controller;

class MainController{

    public static function render($page, $template) : void
    {
        ob_start();
        require ROOT.DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'Pages'.DIRECTORY_SEPARATOR.$page.'.php';
        $content = ob_get_clean();
        require ROOT.DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.$template.'.php';
    }
}