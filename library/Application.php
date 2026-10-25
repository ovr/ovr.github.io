<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

namespace App;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class Application {
    /**
     * Where /about sends visitors now that the page itself is gone.
     */
    const RESUME_URL = 'https://resume.dmtry.me/';

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

        $loader = new \Twig\Loader\FilesystemLoader('templates');

        $this->twig = new \Twig\Environment($loader, array(
            'cache' => 'data/twig_cache',
            'auto_reload' => true
        ));
        // Templates translate through the `trans` filter of this extension:
        // twig/extensions, which used to provide the {% trans %} tag, is abandoned.
        $this->twig->addExtension(new \App\Twig\Extension());

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

    /**
     * Path of the HTML the `markdown` console command renders for an article.
     */
    private function getArticleCachePath($article)
    {
        return $this->dir . '/data/cache/' . $this->currentLanguage . '/' . $article->name . '.html';
    }

    /**
     * Intro lines of an article, or '' when its cache has not been generated:
     * file() returns false for a missing file, which used to take down the
     * whole page through array_slice().
     */
    private function getArticleIntroText($article)
    {
        $lines = @file($this->getArticleCachePath($article));

        if ($lines === false) {
            return '';
        }

        return implode('', array_slice(
            $lines,
            $article->intro_text_start_html_line,
            $article->intro_text_end_html_line - $article->intro_text_start_html_line
        ));
    }

    public function run()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::METHOD_NOT_ALLOWED:
            case Dispatcher::NOT_FOUND:
                $this->renderNotFound();
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                switch ($handler) {
                    case 'article':
                        $article = null;

                        foreach($this->getArticles() as $candidate) {
                            if ($candidate->name == $vars['title']) {
                                $article = $candidate;
                                break;
                            }
                        }

                        // Without this the loop left the last article of the list
                        // in $article, so an unknown slug served that one.
                        $article_html = $article === null
                            ? false
                            : @file_get_contents($this->getArticleCachePath($article));

                        if ($article_html === false) {
                            $this->renderNotFound();
                            break;
                        }

                        if (!isset($article->twittId)) {
                            $article->twittId = 603125485028114432;
                        }

                        $article->intro_text = $this->getArticleIntroText($article);

                        $article->og_description = strip_tags($article->intro_text);
                        echo $this->twig->render('article.twig', array(
                            'article' => $article,
                            'article_html' => $article_html,
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

                            $articles[$key]->intro_text = $this->getArticleIntroText($article);
                        }

                        echo $this->twig->render('index.twig', array(
                            'articles' => $articles,
                            'language' => $this->currentLanguage,
                            'anotherLanguage' => $this->getAnotherLanguage(),
                            'languageChangeUrl' => $this->getLanguageChangeUrl()
                        ));
                        break;
                    case 'about':
                        // The about page now lives at resume.dmtry.me; the route is
                        // kept only to redirect old links and bookmarks.
                        header('Location: ' . self::RESUME_URL, true, 301);
                        break;
                }
                break;
        }
    }

    /**
     * `Status:` is only read by CGI/FastCGI, so under php -S every missing page
     * answered 200; http_response_code() sets the status on both.
     */
    protected function renderNotFound()
    {
        http_response_code(404);
        echo $this->twig->render('not-found.twig');
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
