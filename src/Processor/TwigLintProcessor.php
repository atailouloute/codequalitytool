<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Helper\ExtensionHelper;

class TwigLintProcessor extends AbstractProcessor
{
    public function process(string $file): void
    {
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
