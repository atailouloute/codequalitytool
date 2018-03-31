<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;
use Symfony\Component\Process\Process;

class PhpMDProcessor extends AbstractPhpProcessor
{
    private const BIN = __DIR__ . '/../../../../bin/phpmd';
    private const RULES_SET_FILE = __DIR__ . '/../../resources/phpmd/rulesset.xml';

    public function process(string $file): void
    {
        $process = new Process(sprintf('%s %s text %s', self::BIN, $file, self::RULES_SET_FILE));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessorException($process->getErrorOutput());
        }
    }

    public function getTitle(): string
    {
        return 'PHP Mess Detector';
    }
}