<?php

declare(strict_types=1);

namespace Xililo\WhmApi\Exception;

final class ApiErrorException extends WhmException
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        string $message,
        private readonly array $payload = [],
    ) {
        parent::__construct($message);
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
