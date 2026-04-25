<?php

declare(strict_types=1);

namespace Xililo\WhmApi;

final class Config
{
    public function __construct(
        public readonly string $host,
        public readonly string $username,
        public readonly string $token,
        public readonly int $port = 2087,
        public readonly string $scheme = 'https',
        public readonly bool $verifyTls = true,
        public readonly int $timeout = 30,
    ) {
    }

    public function baseUrl(): string
    {
        return sprintf(
            '%s://%s:%d/json-api',
            rtrim($this->scheme, ':/'),
            rtrim($this->host, '/'),
            $this->port,
        );
    }

    public function authorizationHeader(): string
    {
        return sprintf('Authorization: whm %s:%s', $this->username, $this->token);
    }
}
