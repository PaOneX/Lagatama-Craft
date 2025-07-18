<?php

declare(strict_types=1);

namespace App\Core;

class Upload
{
    /** @param list<string> $allowedMimes */
    public static function validate(array $file, array $allowedMimes, int $maxBytes): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return 'Upload failed';
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0) {
            return 'Empty file';
        }

        if ($size > $maxBytes) {
            return 'File is too large';
        }

        $tmp = $file['tmp_name'] ?? '';
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            return 'Invalid upload';
        }

        $mime = self::mimeFromPath($tmp);
        if ($mime === null || !in_array($mime, $allowedMimes, true)) {
            return 'Invalid file type';
        }

        return null;
    }

    public static function mimeFromBuffer(string $binary): ?string
    {
        if (class_exists(\finfo::class)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($binary);
            if (is_string($mime) && $mime !== 'application/octet-stream') {
                return $mime;
            }
        }

        if (function_exists('getimagesizefromstring')) {
            $info = @getimagesizefromstring($binary);
            if (is_array($info) && !empty($info['mime'])) {
                return $info['mime'];
            }
        }

        return self::mimeFromMagicBytes($binary);
    }

    public static function mimeFromPath(string $path): ?string
    {
        if (class_exists(\finfo::class) && is_file($path)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($path);
            if (is_string($mime)) {
                return $mime;
            }
        }

        if (function_exists('getimagesize') && is_file($path)) {
            $info = @getimagesize($path);
            if (is_array($info) && !empty($info['mime'])) {
                return $info['mime'];
            }
        }

        $header = @file_get_contents($path, false, null, 0, 16);
        if ($header !== false) {
            return self::mimeFromMagicBytes($header);
        }

        return null;
    }

    private static function mimeFromMagicBytes(string $binary): ?string
    {
        if (str_starts_with($binary, "\xFF\xD8\xFF")) {
            return 'image/jpeg';
        }
        if (str_starts_with($binary, "\x89PNG\r\n\x1a\n")) {
            return 'image/png';
        }
        if (str_starts_with($binary, 'GIF87a') || str_starts_with($binary, 'GIF89a')) {
            return 'image/gif';
        }
        if (strlen($binary) >= 12 && str_starts_with($binary, 'RIFF') && substr($binary, 8, 4) === 'WEBP') {
            return 'image/webp';
        }

        return null;
    }

    /** @return list<string> */
    public static function imageMimes(): array
    {
        return ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    }

    /** @return list<string> */
    public static function videoMimes(): array
    {
        return ['video/mp4', 'video/webm', 'video/quicktime'];
    }
}
