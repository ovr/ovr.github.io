<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

include_once __DIR__ . '/../vendor/autoload.php';

$feed = new Zend\Feed\Writer\Feed;
$feed->setTitle('Dmitry Patsura Blog');
$feed->setLink('http://dmtry.me/');
$feed->setFeedLink('http://dmtry.me/rss/atom.xml', 'atom');
$feed->setDescription('My blog');
$feed->addAuthor(array(
    'name'  => 'Dmitry Patsura',
    'email' => 'zaets28rus@gmail.com',
    'uri'   => 'http://dmtry.me/about',
));
$feed->setDateModified(time());

$articles = json_decode(file_get_contents(__DIR__ . '/articles.json'));

foreach($articles as $article) {
    $entry = $feed->createEntry();
    $entry->setId(''.$article->id);
    $entry->setTitle($article->title);
    $entry->setLink('http://dmtry.me/article/'.$article->name.'/');
    $entry->addAuthor(array(
        'name'  => 'Dmitry Patsura',
        'email' => 'zaets28rus@gmail.com',
        'uri'   => 'http://dmtry.me/about',
    ));

    $entry->setDateModified(strtotime($article->dateModified));
    $entry->setDateCreated(strtotime($article->dateCreated));

    $intro_text = file(__DIR__ . '/data/cache/'.$article->name.'.html');
    $article->intro_text = implode('', array_slice($intro_text, $article->intro_text_start_html_line, $article->intro_text_end_html_line-$article->intro_text_start_html_line));

    $entry->setDescription($article->intro_text);
    $feed->addEntry($entry);
}

file_put_contents(__DIR__ . '/../public/rss/atom.xml', $feed->export('Atom'));
file_put_contents(__DIR__ . '/../public/rss/rss.xml', $feed->export('Rss'));