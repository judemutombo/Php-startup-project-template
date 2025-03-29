<?php

use App\Autoloader\Autoloader;

class App{
    private static $instance = null;
    private $db_instance = null;
    private $title = "example";

    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$instance = new App;
        }
        return self::$instance;
    }

    public static function load()
    {
        require 'Autoloader.php';
        Autoloader::register();
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($newTitle)
    {
         $this->title = $newTitle;
    }

    public function get_Db()
    {
        if($this->db_instance == null)
        {
            //$this->db_instance = MyDatabase::getInstance();
        }
        return $this->db_instance;
    }

    public static function urlbase($url){
        $base;
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
}