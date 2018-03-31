<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Helper\ExtensionHelper;

abstract class AbstractPhpProcessor extends AbstractProcessor
{
    public function supports(string $file): bool
    {
        return ExtensionHelper::isPhp($file);
    }
}