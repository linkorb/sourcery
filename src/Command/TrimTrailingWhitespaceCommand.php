<?php

namespace Sourcery\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class TrimTrailingWhitespaceCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('sourcery:trimtrailingwhitespace')
            ->setDescription(
                'Trim trailing whitespace'
            );
        $this->addFinderOptions();
    }
    
    protected function fixup($content)
    {
        $first = true;
        $output = '';
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            
            if (trim($line, " \t")!='') {
                $line = rtrim($line, " \t");
            }
            if (!$first) {
                $output .= "\n";
            }
            $output .= $line;
            //echo "!$line#\n";
            $first = false;
        }
        return $output;
    }
}
