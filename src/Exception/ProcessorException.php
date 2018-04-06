<?php

namespace AT\CodeQualityTool\Exception;

use Throwable;

class ProcessorException extends \Exception
{
    private $warning;

    public function __construct(string $message = "", bool $warning = false, int $code = 0, Throwable $previous = null)
    {
        $this->warning = $warning;

        parent::__construct($message, $code, $previous);
    }

    public function isWarning(): bool
    {
        return $this->warning;
    }
}
