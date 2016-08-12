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
    const ERROR_EXIT_CODE = 1;
    const GOOD_CODE = 0;

    protected function configure()
    {
        $this
            ->setName('lint')
            ->setHelp("Lint files, use: psrlint <filename>")
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
            return self::GOOD_CODE;
        }
        if (is_dir($filename)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filename));
            $files = new \RegexIterator($files, '/\.php$/');
            $files = iterator_to_array($files);
        } else {
            $files = [new \SplFileInfo($filename)];
        }
        $linter = new Linter();
        $reports = array_reduce($files, function ($acc, $file) use ($linter) {
            $code = file_get_contents($file);
            $acc[$file->getFilename()] = $linter->lint($code);
            return $acc;
        }, []);

        $render = new ConsoleRender();

        $output->write($render->render($reports));

        $result = array_reduce($reports, function ($acc, $item) {
            return $acc || !empty($item->getWarnings()) || !empty($item->getErrors());
        }, false);

        return $result ? self::ERROR_EXIT_CODE : self::GOOD_CODE;
    }
}
