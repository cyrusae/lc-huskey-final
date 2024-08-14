<?php

require __DIR__ . '/../../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\LogglyHandler;
use Monolog\Formatter\LogglyFormatter;
// Details about the Loggly Monolog format: https://github.com/Seldaek/monolog/blob/main/src/Monolog/Formatter/LogglyFormatter.php
$logglyToken = $_ENV["LOGGLY_TOKEN"];

$logger = new Logger('UW HusKey Manager');
$logger->pushHandler(new LogglyHandler($logglyToken.'/tag/monolog', Logger::INFO));

$logger->info('Loggly Sending Informational Message');
?>