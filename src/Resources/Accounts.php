<?php

declare(strict_types=1);

namespace Xililo\WhmApi\Resources;

use Xililo\WhmApi\Concerns\InteractsWithClient;

final class Accounts
{
    use InteractsWithClient;

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function summary(string $user, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('accountsummary', [
            'user' => $user,
            ...$parameters,
        ]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function summaryByDomain(string $domain, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('accountsummary', [
            'domain' => $domain,
            ...$parameters,
        ]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function list(array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('listaccts', $parameters);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function listUsers(array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('list_users', $parameters);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function create(string $username, string $domain, string $password, ?string $plan = null, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        $payload = [
            'username' => $username,
            'domain' => $domain,
            'password' => $password,
            ...$parameters,
        ];

        if ($plan !== null) {
            $payload['plan'] = $plan;
        }

        return $this->call('createacct', $payload);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function changePackage(string $user, string $package, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('changepackage', [
            'user' => $user,
            'pkg' => $package,
            ...$parameters,
        ]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function setPassword(string $user, string $password, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('passwd', [
            'user' => $user,
            'password' => $password,
            ...$parameters,
        ]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function suspend(string $user, ?string $reason = null, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        $payload = [
            'user' => $user,
            ...$parameters,
        ];

        if ($reason !== null && $reason !== '') {
            $payload['reason'] = $reason;
        }

        return $this->call('suspendacct', $payload);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function unsuspend(string $user, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('unsuspendacct', [
            'user' => $user,
            ...$parameters,
        ]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function remove(string $user, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('removeacct', [
            'user' => $user,
            ...$parameters,
        ]);
    }
}
