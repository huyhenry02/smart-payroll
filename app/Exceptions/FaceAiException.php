<?php

namespace App\Exceptions;

use RuntimeException;

class FaceAiException extends RuntimeException
{
    protected array $detail = [];

    public function __construct(string $message, int $code = 0, array $detail = [])
    {
        parent::__construct($message, $code);
        $this->detail = $detail;
    }

    public function getDetail(): array
    {
        return $this->detail;
    }
}
