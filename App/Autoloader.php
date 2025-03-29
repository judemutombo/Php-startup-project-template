<?php 

namespace App\Autoloader;

class Autoloader{
    public static function register()
    {
        spl_autoload_register(array(__CLASS__,'autoload'));
    }
    static function autoload($classname)
    {
        require ROOT.'/'.$classname.'.php';
    }
}