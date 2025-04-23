<?php

namespace API\ApiBootstrap;

class ApiBootstrap{
    private static $uri;
    private static $method;
    private static $segments;
    private static $api_index;

    public static function setup() : void {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        define("ROOT",dirname(dirname(dirname( __DIR__ ))));
        define("API_PATH", dirname(__DIR__));
        require_once ROOT.'/vendor/autoload.php';
        self::init();
        self::setEnssentialVariables();

        require API_PATH .DIRECTORY_SEPARATOR.'setup.php';
    }

    private static function init() : void
    {
        self::$method = strtoupper($_SERVER['REQUEST_METHOD']); // get, post, put, delete
        self::$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        // Remove anything before "api" (ex: /myapp/api/users → users)
        self::$segments = explode('/', self::$uri);
        self::$api_index = array_search('api', self::$segments);
    }
    
    private static function setEnssentialVariables() : void {
        define("REQUEST_METHOD", self::$method);
        define("REQUEST_URI", self::$uri);
        define("REQUEST_URI_SEGMENTS", self::$segments);
        define("API_INDEX", self::$api_index);
    }
    
}