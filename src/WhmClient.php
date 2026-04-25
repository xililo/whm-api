<?php

declare(strict_types=1);

namespace Xililo\WhmApi;

use Xililo\WhmApi\Exception\ApiErrorException;
use Xililo\WhmApi\Exception\AuthenticationException;
use Xililo\WhmApi\Exception\RequestException;

final class WhmClient
{
    public function __construct(private readonly Config $config)
    {
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     */
    public function get(string $function, array $parameters = []): ApiResponse
    {
        $query = http_build_query(
            ['api.version' => 1, ...$this->normalizeParameters($parameters)],
            '',
            '&',
            PHP_QUERY_RFC3986,
        );

        $url = sprintf('%s/%s?%s', $this->config->baseUrl(), ltrim($function, '/'), $query);

        $ch = curl_init($url);

        if ($ch === false) {
            throw new RequestException('Unable to initialize cURL.');
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                $this->config->authorizationHeader(),
            ],
            CURLOPT_TIMEOUT => $this->config->timeout,
            CURLOPT_CONNECTTIMEOUT => $this->config->timeout,
            CURLOPT_SSL_VERIFYPEER => $this->config->verifyTls,
            CURLOPT_SSL_VERIFYHOST => $this->config->verifyTls ? 2 : 0,
        ]);

        $rawBody = curl_exec($ch);

        if ($rawBody === false) {
            $message = curl_error($ch);
            curl_close($ch);

            throw new RequestException($message !== '' ? $message : 'The WHM request failed.');
        }

        $httpCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        $payload = json_decode($rawBody, true);

        if (! is_array($payload)) {
            throw new RequestException('WHM returned a non-JSON response.');
        }

        if ($httpCode === 401 || $httpCode === 403) {
            throw new AuthenticationException(
                $this->extractErrorMessage($payload, 'Authentication with WHM failed.')
            );
        }

        if ($httpCode >= 400) {
            throw new RequestException(
                $this->extractErrorMessage($payload, sprintf('WHM request failed with HTTP %d.', $httpCode))
            );
        }

        $response = new ApiResponse($payload);

        if (! $response->successful()) {
            throw new ApiErrorException(
                $response->reason() ?? 'The WHM API reported a failure.',
                $payload,
            );
        }

        return $response;
    }

    /**
     * @param array<string, scalar|array|null> $parameters
     * @return array<string, scalar|array>
     */
    private function normalizeParameters(array $parameters): array
    {
        $normalized = [];

        foreach ($parameters as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                $normalized[$key] = $value ? 1 : 0;
                continue;
            }

            $normalized[$key] = $value;
        }

        return $normalized;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function extractErrorMessage(array $payload, string $fallback): string
    {
        $metadata = $payload['metadata'] ?? null;

        if (is_array($metadata) && isset($metadata['reason']) && is_string($metadata['reason'])) {
            return $metadata['reason'];
        }

        $error = $payload['error'] ?? null;

        if (is_string($error) && $error !== '') {
            return $error;
        }

        $errors = $payload['errors'] ?? null;

        if (is_array($errors) && isset($errors[0]) && is_string($errors[0])) {
            return $errors[0];
        }

        return $fallback;
    }
}
