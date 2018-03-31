<?php

namespace AT\CodeQualityTool;

use AT\CodeQualityTool\Processor\PhpCSFixerProcessor;
use AT\CodeQualityTool\Processor\PhpCSProcessor;
use AT\CodeQualityTool\Processor\PhpLintProcessor;
use AT\CodeQualityTool\Processor\PhpMDProcessor;
use AT\CodeQualityTool\Processor\ProcessorInterface;
use AT\CodeQualityTool\Processor\ComposerProcessor;
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
        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('%s, v%s', $this->getName(), $this->getVersion()));

        $files = $this->extractFiles();

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
                return;
            }
        }

        $io->success('Good Job dude!');
    }

    private function extractFiles()
    {
        return [
            //'composer.json',
            'composer.lock',
            'a.php',
        ];
    }

    /**
     * @return \Generator|ProcessorInterface[]
     */
    private function getCheckers(): \Generator
    {
        yield new ComposerProcessor();
        yield new PhpLintProcessor();
        yield new PhpCSFixerProcessor();
        yield new PhpCSProcessor();
        yield new PhpMDProcessor();
        yield new TwigLintProcessor();
    }
}