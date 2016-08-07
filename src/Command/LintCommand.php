<?php

namespace HexletPSRLinter\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use HexletPSRLinter\File;
use HexletPSRLinter\Linter;
use HexletPSRLinter\Render\ConsoleRender;

class LintCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('lint')
            ->setHelp("Lint file, use: psrlint <filename>")
            ->setDescription('Describe args behaviors')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('file', InputArgument::OPTIONAL),
                    new InputOption('fix', 'f', InputOption::VALUE_OPTIONAL),
                ))
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('file');
        if (!$filename) {
            $output->writeln($this->getHelp());
            return null;
        }
        if (is_dir($filename)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filename));
            $files = new \RegexIterator($files, '/\.php$/');
            $files = iterator_to_array($files);
        } else {
            $files = [$filename];
        }
        $linter = new Linter();
        $reports = array_map(function ($file) use ($linter) {
            $code = file_get_contents($file);
            $report = $linter->lint($code);
            $report->setName($file);
            return $report;
        }, $files);
        $render = new ConsoleRender();
        $output->write($render->render($reports));
    }
}
