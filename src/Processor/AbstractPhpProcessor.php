<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Helper\ExtensionHelper;

abstract class AbstractPhpProcessor extends AbstractProcessor
{
    private const FILES_TO_EXCLUDE = [
        '/App(Cache|Kernel).php$/',
        '/SymfonyRequirements.php$/',
        '/web\/app.php$/',
        '/web\/app_([a-z]+).php$/',
        '/web\/config.php$/',
    ];

    public function supports(string $file): bool
    {
        if (!ExtensionHelper::isPhp($file)) {
            return false;
        }

        foreach (self::FILES_TO_EXCLUDE as $filePattern) {
            if (preg_match($filePattern, $file)) {
                return false;
            }
        }

        return true;
    }
}
