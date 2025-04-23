<?php
namespace App\Sublime;

require ROOT_COMPILER.DIRECTORY_SEPARATOR.'/vendor/autoload.php';

class Compiler {
    private static array $stats = [
        'total' => 0,
        'parameterized' => 0,
        'methods' => [
            'GET' => 0,
            'POST' => 0,
            'PUT' => 0,
            'DELETE' => 0
        ]
    ];

    public static function compile(): array {
        $registry = new RouteRegistry();
        
        try {
            foreach (array_keys(self::$stats['methods']) as $method) {
                self::$stats['methods'][$method] = self::processMethod($method, $registry);
                self::$stats['total'] += self::$stats['methods'][$method];
            }
            
            $registry->save();
            return self::$stats;
            
        } catch (\Exception $e) {
            throw new \RuntimeException("Compilation failed: " . $e->getMessage());
        }
    }
    
    private static function processMethod(string $method, RouteRegistry $registry): int {
        $count = 0;
        $methodDir = ROOT_COMPILER . '/App/API/Methods/' . $method;

        if (!is_dir($methodDir)) {
            return 0;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($methodDir)
        );

        foreach ($files as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $routeKey = self::getRouteKey($file, $method);
            $params = self::extractParameters($file);
            
            if (!empty($params)) {
                self::$stats['parameterized']++;
            }

            $registry->addRoute(
                $method,
                $routeKey,
                $file->getRealPath(),
                $params
            );
            $count++;
        }

        return $count;
    }
    
    private static function getRouteKey(\SplFileInfo $file, string $method): string {
        $basePath = ROOT_COMPILER . '/App/API/Methods/' . $method . '/';
        $relativePath = str_replace($basePath, '', $file->getPathname());
        
        return str_replace(
            ['.php', '[', ']'],
            ['', '{', '}'],
            $relativePath
        );
    }
    
    private static function extractParameters(\SplFileInfo $file): array {
        $content = @file_get_contents($file->getPathname());
        if ($content === false || !preg_match('/RouteParameterValidator::set\(["\'](.+?)["\']\)/', $content, $matches)) {
            return [];
        }
        
        return array_map('trim', explode('/', $matches[1]));
    }
}