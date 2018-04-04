<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;
use AT\CodeQualityTool\Helper\ExtensionHelper;
use Symfony\Component\Process\Process;

class TwigLintProcessor extends AbstractProcessor
{
    private const BIN = __DIR__ . '/../../bin/twig-lint';

    public function process(string $file): void
    {
        $process = new Process(sprintf('%s lint --format=csv %s', self::BIN, $file));
        $process->run();

        if (!$process->isSuccessful()) {
            list(, $lineNo, $errorMessage) = explode(',', $process->getOutput());

            throw new ProcessorException(sprintf(
                'There is a problem in twig file "%s", line %d, Error message : %s',
                $file,
                $lineNo,
                $errorMessage
            ));
        }
    }

    public function supports(string $file): bool
    {
        return ExtensionHelper::isTwig($file);
    }

    public function getTitle(): string
    {
        return 'Twig Lint';
    }
}
