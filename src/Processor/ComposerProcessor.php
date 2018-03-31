<?php

namespace AT\CodeQualityTool\Processor;

use AT\CodeQualityTool\Exception\ProcessorException;

class ComposerProcessor extends AbstractProcessor
{
    private $composerJsonUpdated = false;
    private $composerLockUpdated = false;

    public function process(string $file): void
    {
        if ('composer.json' === $file) {
            $this->composerJsonUpdated = true;
        }

        if ('composer.lock' === $file) {
            $this->composerLockUpdated = true;
        }
    }

    public function after(): void
    {
        if ($this->composerJsonUpdated && !$this->composerLockUpdated) {
            throw new ProcessorException('Composer.lock must be commited if composer.json is modified');
        }
    }

    public function supports(string $file): bool
    {
        return preg_match('/composer.(json|lock)$/', $file);
    }

    public function getTitle(): string
    {
        return 'Checking composer';
    }
}