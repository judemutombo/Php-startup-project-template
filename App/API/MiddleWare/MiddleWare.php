<?php
namespace API\MiddleWare;


abstract class MiddleWare{
    abstract public static function serve();

    protected static function interupt($error = NULL, int $code = 403){
        http_response_code($code);
        echo json_encode(['error' => $error]);
        die();
    }
}