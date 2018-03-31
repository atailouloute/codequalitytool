<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;
use Symfony\Component\Process\Process;

class PhpCSFixerProcessor extends AbstractPhpProcessor
{
    private const BIN = __DIR__ . '/../../../../bin/php-cs-fixer';

    public function process(string $file): void
    {
        $process = new Process(sprintf('%s --dry-run fix %s', self::BIN, $file));
        $process->run();

        if (!$process->isSuccessful()) {
            $process = new Process(sprintf('%s fix %s', self::BIN, $file));
            $process->run();

            // exec('git add ' . $file);

            throw new ProcessorException('Some Coding Style violations are fixed, commit again.');
        }
    }

    public function getTitle(): string
    {
        return 'PHP Coding Style Fixer';
    }
}