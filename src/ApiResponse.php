<?php

declare(strict_types=1);

namespace Xililo\WhmApi;

final class ApiResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(private readonly array $payload)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        $metadata = $this->payload['metadata'] ?? [];

        return is_array($metadata) ? $metadata : [];
    }

    /**
     * @template TDefault
     *
     * @param TDefault $default
     *
     * @return ($key is null ? array<string, mixed> : mixed|TDefault)
     */
    public function data(?string $key = null, mixed $default = null): mixed
    {
        $data = $this->payload['data'] ?? [];
        $data = is_array($data) ? $data : [];

        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? $default;
    }

    public function successful(): bool
    {
        return (int) ($this->metadata()['result'] ?? 0) === 1;
    }

    public function command(): ?string
    {
        $command = $this->metadata()['command'] ?? null;

        return is_string($command) ? $command : null;
    }

    public function reason(): ?string
    {
        $reason = $this->metadata()['reason'] ?? null;

        return is_string($reason) ? $reason : null;
    }

    public function raw(): array
    {
        return $this->payload;
    }
}
