<?php
namespace App\Sublime;

class RouteRegistry {
    private array $routes = [];
    
    public function addRoute(string $method, string $path, string $file, array $params): void {
        $this->routes[$method][$path] = [
            'file' => $file,
            'params' => $params
        ];
    }
    
    public function save(): void {
        file_put_contents(
            ROOT_COMPILER.DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'API'.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Router'.DIRECTORY_SEPARATOR.'compiled_routes.php',
            "<?php\nreturn ".var_export($this->routes, true).";"
        );
    }
}