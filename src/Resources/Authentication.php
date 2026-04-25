<?php

declare(strict_types=1);

namespace Xililo\WhmApi\Resources;

use Xililo\WhmApi\Concerns\InteractsWithClient;

final class Authentication
{
    use InteractsWithClient;

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function createUserSession(string $user, string $service = 'cpaneld', array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('create_user_session', [
            'user' => $user,
            'service' => $service,
            ...$parameters,
        ]);
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function getLoginUrl(string $provider, string $urlAfterLogin, array $parameters = []): \Xililo\WhmApi\ApiResponse
    {
        return $this->call('get_login_url', [
            'provider' => $provider,
            'url_after_login' => $urlAfterLogin,
            ...$parameters,
        ]);
    }
}
