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

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp);
        if ($mime === false || !in_array($mime, $allowedMimes, true)) {
            return 'Invalid file type';
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
