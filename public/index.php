<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

ini_set('display_errors', 1);
error_reporting(-1);

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../library/Twig/Extension.php';

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
$twig->addExtension(new \App\Twig\Extension());

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
	case FastRoute\Dispatcher::NOT_FOUND:
        echo $twig->render('not-found.twig');
		break;
	case FastRoute\Dispatcher::FOUND:
		$handler = $routeInfo[1];
		$vars = $routeInfo[2];

		switch ($handler) {
			case 'article':
				$articles = json_decode(file_get_contents('articles.json'));

				foreach($articles as $article) {
					if ($article->name == $vars['title']) {
						break;
					}
				}

                $intro_text = file('data/cache/'.$article->name.'.html');
                $article->intro_text = implode('', array_slice($intro_text, $article->intro_text_start_html_line, $article->intro_text_end_html_line-$article->intro_text_start_html_line));

                $article->og_description = strip_tags($article->intro_text);
				echo $twig->render('article.twig', array(
					'article' => $article,
					'article_html' => file_get_contents('data/cache/'.$article->name.'.html')
				));
				break;
			case 'default':
				$articles = json_decode(file_get_contents('articles.json'));

				foreach ($articles as $key => $article) {
                    if (!$article->published) {
                        unset($articles[$key]);
                        continue;
                    }
					$intro_text = file('data/cache/'.$article->name.'.html');
					$articles[$key]->intro_text = implode('', array_slice($intro_text, $article->intro_text_start_html_line, $article->intro_text_end_html_line-$article->intro_text_start_html_line));
				}
				echo $twig->render('index.twig', array('articles' => $articles));
				break;
			case 'about':
				echo $twig->render('about.twig');
				break;
		}
		break;
}