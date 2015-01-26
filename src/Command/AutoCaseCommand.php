<?php

namespace Sourcery\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class AutoCaseCommand extends BaseCommand
{

    protected function configure()
    {
        $this->setName('sourcery:autocase')
            ->setDescription('PHP source file auto-casing');
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Basepath'
        )->addOption(
            'extension',
            null,
            InputOption::VALUE_REQUIRED,
            'Filter for file-extension',
            'php'
        )->addOption(
            'dryrun',
            null,
            InputOption::VALUE_NONE,
            'Dryrun'
        )->addOption(
            'diff',
            null,
            InputOption::VALUE_NONE,
            'Diff'
        );
    }
    
    protected function fixup($content)
    {
        $escapeX = true;

        // escape the ->X...
        $pattern = $escapeX ? '|->\s*[^X]\w|' : '|->\s*\w|';
        
        $content = preg_replace_callback(
            $pattern,
            function ($matches) {
                // print_r($matches);
                return strtolower($matches[0]);
            },
            $content
        );

        return $content;
    }
}
