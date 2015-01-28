<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Feed\Writer\Feed;

/**
 * Class Rss
 * @package App\Console\Command
 *
 * @method \App\Console\Application getApplication();
 */
class Rss extends Command
{
    const RU_LANG = 'ru';
    const EN_LANG = 'en';

    const RSS_AUTHOR = 'author';
    const RSS_TITLE = 'title';
    const RSS_LINK = 'link';

    protected $details = array(
        self::RU_LANG => array(
            self::RSS_TITLE => "Dmitry's P. Blog",
            self::RSS_LINK => 'http://en.dmtry.me/',
            self::RSS_AUTHOR => array(
                'name'  => 'Dmitry Patsura',
                'email' => 'talk@dmtry.me',
                'uri'   => 'http://dmtry.me/about'
            )
        ),
        self::EN_LANG => array(
            self::RSS_TITLE => 'Блог Дмитрия Пацура',
            self::RSS_LINK => 'http://dmtry.me/',
            self::RSS_AUTHOR => array(
                'name'  => 'Dmitry Patsura',
                'email' => 'talk@dmtry.me',
                'uri'   => 'http://en.dmtry.me/about'
            )
        )
    );

    protected function configure()
    {
        $this->setName('rss');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start generating rss");


        foreach ($this->details as $lang => $options) {
            $feed = new Feed;

            $feed->setTitle($options[self::RSS_TITLE]);
            $feed->setLink($options[self::RSS_LINK]);
            $feed->setFeedLink($options[self::RSS_LINK] . 'rss/' . $lang . '-atom.xml', 'atom');
            $feed->setDescription('My blog');

            $feed->addAuthor($options[self::RSS_AUTHOR]);

        }
    }
}