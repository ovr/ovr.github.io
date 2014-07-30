<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

include_once __DIR__ . '/../vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', 1);

$application = new \App\Application(realpath(__DIR__ . '/../app'));
$application->bootstrap()->run();
