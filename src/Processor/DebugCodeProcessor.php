<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;
use AT\CodeQualityTool\Helper\ExtensionHelper;
use Symfony\Component\Process\Process;

class DebugCodeProcessor extends AbstractProcessor
{
    private const NOT_ALLOWED_KEYWORDS = [
        'php' => [
            'dump',
            'var_dump',
            'die',
            'exit',
            'eval',
        ],
        'js' => [
            'console.log',
            'console.info',
            'console.error',
        ],
        'twig' => [
            'dump',
        ],
    ];

    public function process(string $file): void
    {
        $process = new Process(sprintf('git diff --no-color -U0 HEAD^1 %s | grep \'^[+][^+-]\'', $file));
        $process->run();

        if ($process->isSuccessful()) {
            preg_match_all($this->generateRegex($file), $process->getOutput(), $matches);

            if (!empty($matches['keyword'])) {
                $one = 1 === count($matches['keyword']);
                throw new ProcessorException(sprintf(
                    'The keyword%s "%s" in file "%s" %s not allowed',
                    $one ? '': 's',
                    implode($matches['keyword'], ','),
                    $file,
                    $one ? 'is' : 'are'
                ));
            }
        }
    }

    private function generateRegex(string $file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        return sprintf('/[^a-zA-Z](?P<keyword>%s)\(/ix', implode(self::NOT_ALLOWED_KEYWORDS[$extension], '|'));
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
