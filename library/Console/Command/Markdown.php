<?php
/**
 * @author Patsura Dmitry <talk@dmtry.me>
 */

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Markdown extends Command
{
    protected function configure()
    {
        $this->setName('markdown');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start generating markdown");
    }
}