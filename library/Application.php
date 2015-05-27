<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

namespace App;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class Application {
    /**
     * @var
     */
    private $dispatcher;

    private $twig;

    public $dir;

    private $currentLanguage;

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function bootstrap()
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
            $r->addRoute('GET', '/', 'default');
            $r->addRoute('GET', '/about', 'about');
            $r->addRoute('GET', '/article/{title}/', 'article');
        });

        chdir(__DIR__ . '/../app');

        $loader = new \Twig_Loader_Filesystem('templates');

        $this->twig = new \Twig_Environment($loader, array(
            'cache' => 'data/twig_cache',
            'auto_reload' => true
        ));
        $this->twig->addExtension(new \App\Twig\Extension());
        $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());

        if (is_null($this->currentLanguage)) {
            $this->currentLanguage = $this->getBestLanguage();
            if ($this->currentLanguage == 'en' && $_SERVER['SERVER_NAME'] != 'en.dmtry.me') {
                header('Location: http://en.dmtry.me/');
            }
        }

        $locales = array(
            'ru' => 'ru_RU',
            'en' => 'en_US'
        );
        $locale = $locales[$this->currentLanguage];

        setlocale(LC_ALL, $locale.'.utf8');
        bind_textdomain_codeset('default', 'UTF-8');
        bindtextdomain('default', $this->getDir() . '/locale');
        textdomain('default');

        return $this;
    }

    private $supportLanguages = array(
        'ru' => 1,
        'en' => 1
    );

    private $config;

    /**
     * @return array
     */
    public function getConfig()
    {
        if (is_null($this->config)) {
            return $this->config = include_once $this->dir . '/config/config.php';
        }

        return $this->config;
    }

    public function getBestLanguage()
    {
        $langs = array();

        if (isset($_COOKIE['language'])) {
            $lang = $_COOKIE['language'];
        } else {
            $lang = false;
        }

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

            if (count($lang_parse[1])) {
                // create a list like "en" => 0.8
                $langs = array_combine($lang_parse[1], $lang_parse[4]);

                // set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ($val === '') $langs[$lang] = 1;
                }

                // sort list based on value
                arsort($langs, SORT_NUMERIC);
            }
        }

        foreach ($langs as $lang => $val) {
            if (isset($supportLanguages[$lang])) {
                $language = $lang;
                break;
            }
        }

        $language = 'ru';

        setcookie('language', $language, time()+60*24*365, '/', '.' . $this->getConfig()['domain'], false, false);

        return $language;
    }

    private function getArticles()
    {
        $articles = json_decode(file_get_contents($this->dir . '/_posts/' . $this->currentLanguage . '/articles.json'));

        return $articles;
    }

    public function run()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
            case Dispatcher::NOT_FOUND:
                header('Status: 404 Not Found');
                echo $this->twig->render('not-found.twig');
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                switch ($handler) {
                    case 'article':
                        $articles = $this->getArticles();

                        foreach($articles as $article) {
                            if ($article->name == $vars['title']) {
                                break;
                            }
                        }

                        if (!isset($article->twittId)) {
                            $article->twittId = 603125485028114432;
                        }

                        $intro_text = file($this->dir . '/data/cache/'. $this->currentLanguage . '/' . $article->name.'.html');
                        $article->intro_text = implode('', array_slice($intro_text, $article->intro_text_start_html_line, $article->intro_text_end_html_line-$article->intro_text_start_html_line));

                        $article->og_description = strip_tags($article->intro_text);
                        echo $this->twig->render('article.twig', array(
                            'article' => $article,
                            'article_html' => file_get_contents($this->dir . '/data/cache/'. $this->currentLanguage . '/' .$article->name.'.html'),
                            'currentLanguage' => $this->currentLanguage,
                            'anotherLanguage' => $this->getAnotherLanguage(),
                            'languageChangeUrl' => $this->getLanguageChangeUrl()
                        ));
                        break;
                    case 'default':
                        $articles = $this->getArticles();

                        foreach ($articles as $key => $article) {
                            if (!$article->published) {
                                unset($articles[$key]);
                                continue;
                            }

                            $intro_text = file($this->dir . '/data/cache/'. $this->currentLanguage . '/' .$article->name.'.html');
                            $articles[$key]->intro_text = implode('', array_slice($intro_text, $article->intro_text_start_html_line, $article->intro_text_end_html_line-$article->intro_text_start_html_line));
                        }

                        echo $this->twig->render('index.twig', array(
                            'articles' => $articles,
                            'language' => $this->currentLanguage,
                            'anotherLanguage' => $this->getAnotherLanguage(),
                            'languageChangeUrl' => $this->getLanguageChangeUrl()
                        ));
                        break;
                    case 'about':
                        echo $this->twig->render('about.twig');
                        break;
                }
                break;
        }
    }

    protected function getAnotherLanguage()
    {
        return $this->currentLanguage == 'ru' ? 'en' : 'ru';
    }

    protected function getLanguageChangeUrl()
    {
        return $this->currentLanguage == 'ru' ? '//en.' . ($this->getConfig()['domain']).'/' : '//'.$this->getConfig()['domain'] . '/';
    }


    /**
     * @param mixed $currentLanguage
     */
    public function setCurrentLanguage($currentLanguage)
    {
        $this->currentLanguage = $currentLanguage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }
}
