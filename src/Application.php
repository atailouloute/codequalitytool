<?php

namespace AT\CodeQualityTool;

use AT\CodeQualityTool\Processor\ComposerProcessor;
use AT\CodeQualityTool\Processor\DebugCodeProcessor;
use AT\CodeQualityTool\Processor\PhpCSFixerProcessor;
use AT\CodeQualityTool\Processor\PhpCSProcessor;
use AT\CodeQualityTool\Processor\PhpLintProcessor;
use AT\CodeQualityTool\Processor\PhpMDProcessor;
use AT\CodeQualityTool\Processor\ProcessorInterface;
use AT\CodeQualityTool\Processor\TwigLintProcessor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct()
    {
        parent::__construct('Code Quality Tool', '1.0');
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $files = $this->extractFiles();

        if (empty($files) || (1 === count($files) && preg_match('/^[a-z0-9]{40}$/', $files[0]))) {
            return;
        }

        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('%s, v%s', $this->getName(), $this->getVersion()));

        foreach ($this->getCheckers() as $checker) {
            $filesToProcess = array_filter($files, function ($file) use ($checker) {
                return $checker->supports($file);
            });

            if (empty($filesToProcess)) {
                continue;
            }

            $io->write($checker->getTitle());

            try {
                $checker->before();
                array_walk($filesToProcess, [$checker, 'process']);
                $checker->after();

                $io->writeln(' <info>✔</info>');
            } catch (\Throwable $e) {
                $io->block(trim($e->getMessage()), null, 'fg=white;bg=red', ' ', true);
                exit(1);
            }
        }

        $io->success('Good Job dude!');
    }

    private function extractFiles()
    {
        $output = array();
        $rc = 0;
        exec('git rev-parse --verify HEAD 2> /dev/null', $output, $rc);
        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';

        if ($rc == 0) {
            $against = 'HEAD';
        }

        exec("git diff-index --cached --name-status $against | egrep '^(A|M)' | awk '{print $2;}'", $output);

        return $output;
    }

    /**
     * @return \Generator|ProcessorInterface[]
     */
    private function getCheckers(): \Generator
    {
        yield new DebugCodeProcessor();
        yield new ComposerProcessor();
        yield new PhpLintProcessor();
        yield new PhpCSFixerProcessor();
        yield new PhpCSProcessor();
        yield new PhpMDProcessor();
        yield new TwigLintProcessor();
    }
}
