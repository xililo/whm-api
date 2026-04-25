<?php

declare(strict_types=1);

namespace Xililo\WhmApi\Resources;

use InvalidArgumentException;
use Xililo\WhmApi\Concerns\InteractsWithClient;

final class HostingPlans
{
    use InteractsWithClient;

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function create(array $parameters): \Xililo\WhmApi\ApiResponse
    {
        $this->assertNamePresent($parameters);

        return $this->call('addpkg', $parameters);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function update(string $name, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('editpkg', [
            'name' => $name,
            ...$parameters,
        ]);
    }

    public function info(string $name): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('getpkginfo', ['pkg' => $name]);
    }

    public function delete(string $name): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('killpkg', ['pkg' => $name]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function list(array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('listpkgs', $parameters);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function match(string $query, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('matchpkgs', [
            'pkg' => $query,
            ...$parameters,
        ]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    private function assertNamePresent(array $parameters): void
    {
        $name = $parameters['name'] ?? null;

        if (! is_string($name) || trim($name) === '') {
            throw new InvalidArgumentException('The hosting plan name is required.');
        }
    }
}
