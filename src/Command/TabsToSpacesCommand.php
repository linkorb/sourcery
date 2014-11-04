<?php

namespace Sourcery\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class TabsToSpacesCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('sourcery:tabstospaces')
            ->setDescription(
                'Convert tabs to spaces'
            );

        $this->addOption(
            'spaces',
            null,
            InputOption::VALUE_REQUIRED,
            'Amount of spaces',
            4
        );
        
        $this->addFinderOptions();
    }
    
    protected function fixup($content)
    {
        $spaces = $this->input->getOption('spaces');
        $content = str_replace("\t", str_repeat(' ', $spaces), $content);
        return $content;
    }
}
