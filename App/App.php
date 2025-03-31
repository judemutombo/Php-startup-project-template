<?php

use App\Autoloader\Autoloader;
use App\Database\Database;

class App{
    private static $instance = null;
    private $db_instance = null;
    private $title = "example";

    public static function getInstance() : App
    {
        if(self::$instance == null)
        {
            self::$instance = new App;
        }
        return self::$instance;
    }

    public static function load() : void
    {
        require_once ROOT.'/vendor/autoload.php';
    }

    public function getTitle() : string
    {
        return $this->title;
    }
    
    public function setTitle($newTitle) : void
    {
         $this->title = $newTitle;
    }

    public function get_Db() : Database
    {
        if($this->db_instance == null)
        {
            //$this->db_instance = MyDatabase::getInstance();
        }
        return $this->db_instance;
    }

    public static function urlbase($url) : string 
    {
        $base = null;
        if(count($url) == 1)
        {
            $base = "public";
        }elseif(count($url) == 3)
        {
            $base ="../../";
        }elseif(count($url) == 2)
        {
            $base ="../";
        }elseif(count($url) == 4)
        {
            $base ="../../../";
        }elseif(count($url) == 5)
        {
            $base ="../../../../";
        }
        return $base;
    }

    public static function sessionstart() : void 
    {
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            if(session_status() == PHP_SESSION_NONE) {
                session_start(array(
                'cache_limiter' => 'private',
                'read_and_close' => true,
                ));
            }
        }
        else if (version_compare(PHP_VERSION, '5.4.0') >= 0)
        {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }
        else
        {
            if(session_id() == '') {
                session_start();
            }
        }
    }
}