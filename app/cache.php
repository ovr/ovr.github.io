<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 11.05.2014
 * Time: 16:11
 */

include_once __DIR__ . '/../vendor/autoload.php';

use \Michelf\Markdown;

$articles = json_decode(file_get_contents(__DIR__ . '/articles.json'));

foreach($articles as $article) {
	$html = Markdown::defaultTransform(file_get_contents(__DIR__ . '/_posts/'.$article->name.'.md'));
	file_put_contents(__DIR__ . '/data/cache/'.$article->name.'.html', $html);
}
