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

echo "\n\033[1;37m Compilation Results: \033[0m\n";
echo "┌────────────────────────┬────────────────┐\n";
printf("│ \033[1;33m %-20s \033[0m │ \033[1;33m %-s \033[0m │\n", "Methods", "Routes Found");
echo "├────────────────────────┼────────────────┤\n";
foreach ($stats['methods'] as $method => $count) {
    printf("│ %-22s │ \033[32m %12d \033[0m │\n", $method, $count);
}
echo "├────────────────────────┼────────────────┤\n";
printf("│ \033[1;33m %-20s \033[0m │ \033[1;32m %12d \033[0m │\n", "Total Routes", $stats['total']);
echo "├────────────────────────┼────────────────┤\n";
printf("│ \033[1;33m %-20s \033[0m │ \033[33m %12d \033[0m │\n", "Parameterized Routes", $stats['parameterized']);
echo "├────────────────────────┼────────────────┤\n";
printf("│ \033[1;33m %-20s \033[0m │ \033[36m %-8s sec \033[0m │\n", "Compilation Time", strval($time));
echo "└────────────────────────┴────────────────┘\n";

$path = __DIR__.DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."API".DIRECTORY_SEPARATOR."Configuration".DIRECTORY_SEPARATOR."Router".DIRECTORY_SEPARATOR."compiled_routes.php";
echo "\n\033[1;32m ✓ Successfully compiled to ".$path." \033[0m\n";
echo "\033[3;90m" . date('Y-m-d H:i:s') . "\033[0m\n";