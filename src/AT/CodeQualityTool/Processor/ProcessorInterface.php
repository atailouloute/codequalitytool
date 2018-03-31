<?php

namespace AT\CodeQualityTool\Processor;

interface ProcessorInterface
{
    public function process(string $file): void;
    public function before(): void;
    public function after(): void;
    public function supports(string $file): bool;
    public function getTitle(): string;
}