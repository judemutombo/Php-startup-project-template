<?php
namespace App\StyleLoader;

class StyleLoader{

    private static $tailwindconfigFile = array();
    private static $tailwindCssFile = array();
    
    public static function loadTailwindConfig() : void
    {
        $styleConfig = include ROOT.DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Config".DIRECTORY_SEPARATOR."style.config.php";
        
        if(isset($styleConfig["tailwind"])) : 
            foreach (self::$tailwindconfigFile as $configfile) {
                echo '<script src="Public'.DIRECTORY_SEPARATOR.'Externals'.DIRECTORY_SEPARATOR.'tailwind'.DIRECTORY_SEPARATOR.$configfile.'.js" type="text/javascript"></script>'.PHP_EOL;
            }

            echo '<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>'.PHP_EOL;
            
            foreach (self::$tailwindCssFile as $cssfile) {
                echo '<link rel="stylesheet" type="text/css" href="Public'.DIRECTORY_SEPARATOR.'Externals'.DIRECTORY_SEPARATOR.'tailwind'.DIRECTORY_SEPARATOR.$cssfile.'.css" />'.PHP_EOL;
            }
        endif;
    }

    public static function addTailwindConfigFile($filename) : void 
    {
        array_push(self::$tailwindconfigFile, $filename);
    }

    public static function addTailwindCssFile($filename) : void 
    {
        array_push(self::$tailwindCssFile, $filename);
    }
}