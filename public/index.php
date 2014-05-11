<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

ini_set('display_errors', 1);
error_reporting(-1);

include_once __DIR__ . '/../vendor/autoload.php';

use Zend\Debug\Debug;

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/', 'default');
	$r->addRoute('GET', '/about', 'about');
	$r->addRoute('GET', '/article/{title}/', 'article');
});

chdir(__DIR__ . '/../app');

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
	'cache' => 'data/twig_cache',
	'auto_reload' => true
));

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
				echo $twig->render('article.twig', array(
					'article' => json_decode(file_get_contents('articles.json'))[0],
					'article_html' => file_get_contents('data/cache/phalcon_It_is_not_the_best.html')
				));
				break;
			case 'default':
				echo $twig->render('index.twig', array('articles' => json_decode(file_get_contents('articles.json'))));
				break;
			case 'about':
				echo $twig->render('about.twig');
				break;
		}
		break;
}