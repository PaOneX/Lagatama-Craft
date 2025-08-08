<?php

declare(strict_types=1);

namespace App\Core;

class Http
{
    public static function canReachHttps(): bool
    {
        return extension_loaded('openssl') || extension_loaded('curl');
    }

    public static function get(string $url, int $timeoutSeconds = 10, bool $includeErrorStatuses = false): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => $timeoutSeconds,
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
            ]);
            $body = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($body === false) {
                return null;
            }

            if ($includeErrorStatuses || ($status >= 200 && $status < 300)) {
                return $body;
            }

            return null;
        }

        if (!extension_loaded('openssl')) {
            return null;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => $timeoutSeconds,
                'ignore_errors' => true,
                'header' => "Accept: application/json\r\n",
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            return null;
        }

        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
            $status = (int) $matches[1];
        }

        if ($includeErrorStatuses || $status === 0 || ($status >= 200 && $status < 300)) {
            return $body;
        }

        return null;
    }
}
