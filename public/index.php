<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

include_once __DIR__ . '/../vendor/autoload.php';

use Zend\Debug\Debug;

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/', 'default');
	$r->addRoute('GET', '/about', 'about');
	$r->addRoute('GET', '/article/{title}/', 'article');
});

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
	case FastRoute\Dispatcher::NOT_FOUND:
		// ... 404 Not Found
		break;
	case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$allowedMethods = $routeInfo[1];
		// ... 405 Method Not Allowed
		break;
	case FastRoute\Dispatcher::FOUND:
		$handler = $routeInfo[1];
		$vars = $routeInfo[2];

		switch ($handler) {
			case 'article':
				include_once __DIR__ . '/../app/article.php';
				break;
			case 'default':
				include_once __DIR__ . '/../app/index.php';
				break;
			case 'about':
				include_once __DIR__ . '/../app/about.php';
				break;
		}

		break;
}