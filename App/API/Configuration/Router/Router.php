<?php
namespace API\Configuration\Router;
use API\Configuration\Transaction\Request;

class Router {
    private static array $middlewares = [];
    private static array $globalMiddlewares = [];
    private static string $uri = REQUEST_URI;
    private static string $method = REQUEST_METHOD;
    private static array $segments = REQUEST_URI_SEGMENTS;
    private static ?int $api_index = API_INDEX;
    private static array $compiledRoutes = [];
    private static array $requestSegments = [];

    public static function serve(): void {
        self::initialize();
        try {
            $route = self::findMatchingRoute();
            if ($route) {
                self::executeRoute($route);
            } else {
                self::respondNotFound();
            }
        } catch (\Exception $e) {
            self::respondError($e->getMessage(), 500);
        }
    }

    private static function initialize(): void {
        self::$compiledRoutes = require __DIR__.'/compiled_routes.php';
        self::$requestSegments = explode('/', trim(self::$uri, '/'));
    }

    private static function findMatchingRoute(): ?array {
        $path = implode('/', array_slice(self::$segments, self::$api_index + 1));
        $segments = explode('/', $path);

        // Check from most specific to least specific path
        for ($depth = count($segments); $depth > 0; $depth--) {
            $currentPath = implode('/', array_slice($segments, 0, $depth));
            $remainingSegments = array_slice($segments, $depth);

            if (isset(self::$compiledRoutes[self::$method][$currentPath])) {
                $route = self::$compiledRoutes[self::$method][$currentPath];
                
                // Verify parameter requirements are met
                if (count($remainingSegments) === count($route['params'])) {
                    return $route;
                }
            }
        }

        return null;
    }

    private static function executeRoute(array $route): void {
        // Extract parameters
        $path = implode('/', array_slice(self::$segments, self::$api_index + 1));
        $routePath = array_search($route, self::$compiledRoutes[self::$method]);
        $params = self::extractParams($routePath, $path, $route['params']);

        // Run middleware chain
        self::executeMiddlewareChain($route['file'], $params);
   

        // Create Request variable 
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);
        $headers = getallheaders();

        //$request = new Request(self::$method, $data, $params, self::$uri, $headers); 
        define('Request', new Request(self::$method, $data, $params, self::$uri, $headers));
        require $route['file'];
    }

    private static function extractParams(string $routePath, string $requestPath, array $paramDefinitions): array {
        $routeSegments = explode('/', $routePath);
        $requestSegments = explode('/', $requestPath);
        $params = [];

        foreach ($paramDefinitions as $index => $paramDef) {
            $paramName = ltrim($paramDef, ':');
            $paramValue = $requestSegments[count($routeSegments) + $index] ?? null;
            $params[$paramName] = $paramValue;
        }

        return $params;
    }

    private static function executeMiddlewareChain(string $routeFile, array $params): void {
        // Run global middleware
        foreach (self::$globalMiddlewares as $middleware) {
            $middleware::serve();
        }

        // Run route-specific middleware
        $routeKey = str_replace(API_PATH.'/Methods/', '', $routeFile);
        foreach (self::$middlewares[$routeKey] ?? [] as $middleware) {
            $middleware::serve();
        }
    }

    public static function setEndPointMiddleWare(string $endpoint, string $middleware): void {
        $middlewareClass = self::validateMiddleware($middleware);
        self::$middlewares[$endpoint][] = $middlewareClass;
    }

    public static function setGlobalMiddleWare(string $middleware): void {
        $middlewareClass = self::validateMiddleware($middleware);
        self::$globalMiddlewares[] = $middlewareClass;
    }

    private static function validateMiddleware(string $middleware): string {
        $file = API_PATH.'/MiddleWare/'.$middleware.'.php';
        if (!file_exists($file)) {
            throw new \InvalidArgumentException("Middleware file not found: $middleware");
        }
        return "API\\MiddleWare\\$middleware";
    }

    private static function respondNotFound(): void {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Endpoint not found']);
        exit;
    }

    private static function respondError(string $message, int $code): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit;
    }
}