<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;
use Symfony\Component\Process\Process;

class PhpLintProcessor extends AbstractPhpProcessor
{
    public function process(string $file): void
    {
        $process = new Process('php -l ' . $file);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessorException(trim($process->getOutput()));
        }
    }

    public function getTitle(): string
    {
        return 'PHP Lint';
    }
}