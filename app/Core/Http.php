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
            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => $timeoutSeconds,
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
            ];

            $caBundle = self::caBundlePath();
            if ($caBundle !== null) {
                $options[CURLOPT_CAINFO] = $caBundle;
            }

            curl_setopt_array($ch, $options);
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

        $ssl = ['verify_peer' => true, 'verify_peer_name' => true];
        $caBundle = self::caBundlePath();
        if ($caBundle !== null) {
            $ssl['cafile'] = $caBundle;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => $timeoutSeconds,
                'ignore_errors' => true,
                'header' => "Accept: application/json\r\n",
            ],
            'ssl' => $ssl,
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

    public static function download(string $url, int $timeoutSeconds = 15): ?string
    {
        if (!self::canReachHttps()) {
            return null;
        }

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => $timeoutSeconds,
                CURLOPT_HTTPHEADER => ['Accept: image/*,*/*;q=0.8'],
            ];

            $caBundle = self::caBundlePath();
            if ($caBundle !== null) {
                $options[CURLOPT_CAINFO] = $caBundle;
            }

            curl_setopt_array($ch, $options);
            $body = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($body === false || $status < 200 || $status >= 300) {
                return null;
            }

            return $body;
        }

        if (!extension_loaded('openssl')) {
            return null;
        }

        $ssl = ['verify_peer' => true, 'verify_peer_name' => true];
        $caBundle = self::caBundlePath();
        if ($caBundle !== null) {
            $ssl['cafile'] = $caBundle;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => $timeoutSeconds,
                'ignore_errors' => true,
                'header' => "Accept: image/*,*/*;q=0.8\r\n",
            ],
            'ssl' => $ssl,
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            return null;
        }

        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
            $status = (int) $matches[1];
        }

        return ($status === 0 || ($status >= 200 && $status < 300)) ? $body : null;
    }

    public static function caBundlePath(): ?string
    {
        static $resolved = null;
        if ($resolved !== null) {
            return $resolved !== '' ? $resolved : null;
        }

        $candidates = [];

        $configured = trim((string) config('app.ssl_ca_file', ''));
        if ($configured !== '') {
            $candidates[] = $configured;
        }

        $candidates[] = BASE_PATH . '/storage/cacert.pem';
        $candidates[] = BASE_PATH . '/config/cacert.pem';

        $iniCa = trim((string) ini_get('curl.cainfo'));
        if ($iniCa !== '') {
            $candidates[] = $iniCa;
        }

        $iniOpenSsl = trim((string) ini_get('openssl.cafile'));
        if ($iniOpenSsl !== '') {
            $candidates[] = $iniOpenSsl;
        }

        foreach ($candidates as $path) {
            if ($path !== '' && is_file($path)) {
                $resolved = $path;
                return $path;
            }
        }

        $resolved = '';
        return null;
    }
}
