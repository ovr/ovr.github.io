<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

include_once __DIR__ . '/../vendor/autoload.php';

$application = new \App\Application(realpath(__DIR__ . '/../app'));

if (getenv('LANGUAGE')) {
    $application->setCurrentLanguage(getenv('LANGUAGE'));
}

$application->bootstrap()->run();
