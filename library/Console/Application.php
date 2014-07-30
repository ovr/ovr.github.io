<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

namespace App\Console;

class Application extends \Symfony\Component\Console\Application
{
    private $dir;

    public function __construct($dir)
    {
        $this->dir = $dir;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include_once $this->dir . '/config/config.php';
    }
}