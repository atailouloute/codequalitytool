<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;
use Symfony\Component\Process\Process;

class PhpCSProcessor extends AbstractPhpProcessor
{
    private const BIN = __DIR__ . '/../../bin/phpcs';

    public function process(string $file): void
    {
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