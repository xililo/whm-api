<?php

declare(strict_types=1);

namespace Xililo\WhmApi\Concerns;

use Xililo\WhmApi\ApiResponse;
use Xililo\WhmApi\WhmClient;

trait InteractsWithClient
{
    public function __construct(protected WhmClient $client)
    {
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    protected function call(string $function, array $parameters = []): ApiResponse
    {
        return $this->client->get($function, $parameters);
    }
}
