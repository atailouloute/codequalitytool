<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;
use Symfony\Component\Process\Process;

class PhpCSProcessor extends AbstractPhpProcessor
{
    private const BIN = __DIR__ . '/../../bin/phpcs';

    private const FILES_TO_EXCLUDE = [
        '/App(Cache|Kernel).php$/',
        '/SymfonyRequirements.php$/',
        '/web\/app.php$/',
        '/web\/app_([a-z]+).php$/',
        '/web\/config.php$/',
    ];

    public function process(string $file): void
    {
        foreach (self::FILES_TO_EXCLUDE as $filePattern) {
            if (preg_match($filePattern, $file)) {
                return;
            }
        }

        $process = new Process(sprintf('%s --standard=PSR2 %s', self::BIN, $file));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessorException($process->getOutput());
        }
    }

    public function getTitle(): string
    {
        return 'PHP Coding Style';
    }
}