<?php

namespace Sourcery\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class TokenTestCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('sourcery:tokentest')
            ->setDescription(
                'Tokentest'
            );
        
        $this->addFinderOptions();
    }
    
    protected function fixup($content)
    {
        $tokens = token_get_all($content);
//        print_r($tokens);
        foreach ($tokens as $token) {
            //print_r($token);
            if (is_string($token)) {
                echo "   STRING \"$token\"\n";
            } else {
                $name = token_name($token[0]);
                $str = str_replace("\n", "\\n", $token[1]);
                echo $name . " \"" . $str . "\" ";
                if (count($token)>1) {
                    echo "(" . $token[2] . ")";
                }
                echo "\n";
            }
        }
        return $content;
    }
}
