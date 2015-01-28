<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Rss
 * @package App\Console\Command
 *
 * @method \App\Console\Application getApplication();
 */
class Rss extends Command
{
    const RU_LANG = 1;
    const EN_LANG = 2;

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
    }
}