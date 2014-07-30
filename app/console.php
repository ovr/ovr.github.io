<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

include_once __DIR__ . '/../vendor/autoload.php';

use App\Console\Command\Markdown;
use App\Console\Command\Rss;

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new Markdown);
$application->add(new Rss);
$application->run();
