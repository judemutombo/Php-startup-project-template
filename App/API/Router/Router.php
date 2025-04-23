<?php
namespace API\Router;
class Router{

    private static $init = false;
    private static $middlewares = array();
    private static $globalMiddlewares = array();
    private static $uri = REQUEST_URI;
    private static $method = REQUEST_METHOD;
    private static $segments = REQUEST_URI_SEGMENTS;
    private static $api_index = API_INDEX;
    
    public static function setEndPointMiddleWare(string $endpoint, string $middleware) : void
    {
        $middleware_path = API_PATH.DIRECTORY_SEPARATOR.'MiddleWare'.DIRECTORY_SEPARATOR.$middleware.'.php';
       
        if(file_exists($middleware_path)){
            if(array_key_exists($endpoint, self::$middlewares)){
                array_push(self::$middlewares[$endpoint], $middleware);
            }else{
                self::$middlewares[$endpoint] = array($middleware); 
            }
        }else{
            throw new \Exception("Error : the given middleware($middleware) does not exist.", 1);
        }

    }

    public static function setGlobalMiddleWare(string $middleware) : void
    {
        $middleware_path = API_PATH.DIRECTORY_SEPARATOR.'MiddleWare'.DIRECTORY_SEPARATOR.$middleware.'.php';
       
        if(file_exists($middleware_path)){
            array_push(self::$globalMiddlewares, $middleware);
        }else{
            throw new \Exception("Error : the given middleware($middleware) does not exist.", 1);
        }
    } 

    public static function serve() : void
    {
        if (self::$api_index !== false) {
            $resource_path = array_slice(self::$segments, self::$api_index + 1);
            $endpoint = implode('/', $resource_path);

            $file_path = API_PATH .DIRECTORY_SEPARATOR.self::$method.DIRECTORY_SEPARATOR.$endpoint.".php";
            if (file_exists($file_path)) {
                $gmiddlewares = self::$globalMiddlewares;
                foreach ($gmiddlewares as $middleware) {
                    $class = "API\MiddleWare\\$middleware";
                
                    if (class_exists($class) && method_exists($class, 'serve')) {
                        $class::serve();
                    } else {
                        throw new \Exception("Middleware class or method not found: $class::serve()");
                    }
                }
                $emiddlewares = self::$middlewares[$endpoint] ?? [];;
                foreach ($emiddlewares as $middleware) {
                    $class = "API\MiddleWare\\$middleware";
                
                    if (class_exists($class) && method_exists($class, 'serve')) {
                        $class::serve();
                    } else {
                        throw new \Exception("Middleware class or method not found: $class::serve()");
                    }
                }
                require $file_path;
            } else {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Endpoint not found'
                ]);
            }
        } else {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid API structure'
            ]);
        }        
    }
}