#!/usr/bin/env php
<?php
require __DIR__.DIRECTORY_SEPARATOR.'/vendor/autoload.php';
define("ROOT_COMPILER",__DIR__);
echo "\033[1;36m\n";
echo "╔══════════════════════════════════════════╗\n";
echo "║ 🚀 PHP Sublime - Route Compiler          ║\n";
echo "╚══════════════════════════════════════════╝\n";
echo "\033[0m";


$start = microtime(true);
$stats = \App\Sublime\Compiler::compile();
$time = round(microtime(true) - $start, 3);

// Visual output
echo "\n\033[1;37mCompilation Results:\033[0m\n";
echo "┌────────────────────────┬───────────────┐\n";
echo "│ \033[1;33mMethod\033[0m             │ \033[1;33mRoutes Found\033[0m │\n";
echo "├────────────────────────┼───────────────┤\n";
foreach ($stats['methods'] as $method => $count) {
    printf("│ %-22s │ \033[32m%13d\033[0m │\n", $method, $count);
}
echo "├────────────────────────┼───────────────┤\n";
printf("│ \033[1;33mTotal Routes\033[0m       │ \033[1;32m%13d\033[0m │\n", $stats['total']);
echo "├────────────────────────┼───────────────┤\n";
printf("│ \033[1;33mParameterized Routes\033[0m│ \033[33m%13d\033[0m │\n", $stats['parameterized']);
echo "├────────────────────────┼───────────────┤\n";
printf("│ \033[1;33mCompilation Time\033[0m   │ \033[36m%10.3f sec\033[0m │\n", $time);
echo "└────────────────────────┴───────────────┘\n";

echo "\n\033[1;32m✓ Successfully compiled to compiled_routes.php\033[0m\n";
echo "\033[3;90m" . date('Y-m-d H:i:s') . "\033[0m\n";