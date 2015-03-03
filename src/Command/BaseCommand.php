<?php

namespace Sourcery\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use SebastianBergmann\Diff\Differ;
use RuntimeException;

class BaseCommand extends Command
{
    
    protected $output;
    protected $input;
    protected $dryrun = false;
    protected $diff = false;
    

    protected function addFinderOptions()
    {
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Basepath'
        );
        
        $this->addOption(
            'extension',
            null,
            InputOption::VALUE_REQUIRED,
            'Filter for file-extension'
        );
        
        $this->addOption(
            'dryrun',
            null,
            InputOption::VALUE_NONE,
            'Dryrun'
        );
        
        $this->addOption(
            'diff',
            null,
            InputOption::VALUE_NONE,
            'Diff'
        );
    }
    
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        if ($this->input->getOption('diff')!='') {
            $this->dryrun = true;
            $this->diff = true;
        }
        if ($this->input->getOption('dryrun')!='') {
            $this->dryrun = true;
        }

        if ($this->dryrun) {
            $output->writeLn("Dryrun: enabled");
        }
        if ($this->diff) {
            $output->writeLn("Diff: enabled");
        }

        $this->processPath($input->getArgument('path'));
    }

    protected function processPath($path)
    {
        if (is_dir($path)) {
            $finder = $this->getFinder();
            $this->processFiles($finder);
        } elseif (file_exists($path)) {
            $this->processFile($path);
        } else {
            throw new RuntimeException('Invalid path: ' . $path);
        }
    }

    protected function getFinder()
    {
        $path = $this->input->getArgument('path');
        $finder = new Finder();
        $finder->files()->in($path);
        $extension = $this->input->getOption('extension');
        if ($extension) {
            $finder->name('/\.'  . $extension . '$/');
        }
        return $finder;
    }
    
    protected function processFiles($finder)
    {
        foreach ($finder as $file) {
            $filename = $file->getPath() . '/' . $file->getFilename();
            $this->processFile($filename);
        }
    }

    protected function processFile($filename)
    {
        $original = file_get_contents($filename);
        $fixed = $this->fixup($original);
        
        if ($fixed != $original) {
            $this->output->writeln("Changed: " . $filename);
            
            if ($this->diff) {
                $differ = new Differ();
                print $differ->diff($original, $fixed);
            }
            if (!$this->dryrun) {
                file_put_contents($filename, $fixed);
            }
        }
    }

    protected function fixup($content)
    {
        return $content;
    }
}
