<?php

namespace Configuration\App;

use App\Autoloader\Autoloader;
use App\Database\SQLDatabase;
use App\Singleton\SingletonTrait;

class App{
    private $db_instance = null;
    private static $title = "The Best Group";

    use SingletonTrait;

    public static function load() : void
    {
        require_once ROOT.'/vendor/autoload.php';
    }

    public static function ActiveError() : void 
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    }
    
    public function get_Db() : Database
    {
        if($this->db_instance == null)
        {
            $this->db_instance = SQLDatabase::getInstance();
        }
        return $this->db_instance;
    }


}
