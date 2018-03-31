<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Helper\ExtensionHelper;

class DebugCodeProcessor extends AbstractProcessor
{
    public function process(string $file): void
    {
        if (ExtensionHelper::isPhp($file)) {
        } elseif (ExtensionHelper::isJs($file)) {
        }
    }

    public function getTitle(): string
    {
        return 'Debug Code finder';
    }

    public function supports(string $file): bool
    {
        return ExtensionHelper::isJs($file) || ExtensionHelper::isPhp($file) || ExtensionHelper::isTwig($file);
    }
}
