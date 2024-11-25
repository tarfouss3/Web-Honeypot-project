<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/vendor/autoload.php';

$log = new Logger('honeypot');
$log->pushHandler(new StreamHandler(__DIR__ . '/logs/honeypot.log'));

return $log;