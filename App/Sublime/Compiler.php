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
            $params = self::extractParams($file);
            
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
    
    private static function extractParams(\SplFileInfo $file): array {
        $content = @file_get_contents($file->getPathname());
        if ($content === false || !preg_match('/RouteParameterValidator::set\(["\'](.+?)["\']\)/', $content, $matches)) {
            return [];
        }
        return self::extractUrlParams( $matches[1]);
    }

    private static function extractUrlParams(string $match): array {
        $params = [];
        $queryAlreadySet = false;
    
        $parts = preg_split("/([\/\?&])/", $match, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        for ($i = 0; $i < count($parts); $i++) {
            $part = $parts[$i];
       
            if (!in_array($part, ["/", "?", "&"])) {
      
                if (preg_match("/[^a-zA-Z0-9_:]/", $part)) {
                    throw new \Exception("An URL parameter can only contain letters, numbers, and _", 1);
                }
    
                $prev = $parts[$i - 1] ?? null;
    
                if (($i === 0 || $prev === "/") && !empty(trim($part))) {
                    if ($queryAlreadySet) {
                        throw new \Exception("A path parameter can't be set after a query parameter", 1);
                    }
                    
                    if (!str_starts_with($part, ":")) {
                        var_dump($part);
                        throw new \Exception("Missing ':' before path parameter '{$parts[$i]}'", 1);
                    }
                    $params[] = $part;
    
                } elseif ($prev === "?" || $prev === "&") {
                    $queryAlreadySet = true;
                    $params[] = "&" . $part;
                }
            }
        }
    
        return $params;
    }
    
}