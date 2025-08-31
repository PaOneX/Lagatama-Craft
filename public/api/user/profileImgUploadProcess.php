<?php

require_once dirname(__DIR__, 2) . '/init.php';

use App\Core\Auth;
use App\Core\Database;
use App\Core\Response;
use App\Core\Upload;

$user = Auth::requireUser();
Auth::verifyCsrfOrFail();

if (empty($_FILES['i'])) {
    Response::text('empty');
}

$error = Upload::validate($_FILES['i'], Upload::imageMimes(), (int) config('security.upload_max_image_bytes', 5 * 1024 * 1024));
if ($error !== null) {
    Response::text($error, 400);
}

$path = 'resources/profileImg/' . uniqid() . '.png';
$dir = BASE_PATH . '/resources/profileImg';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

if (!move_uploaded_file($_FILES['i']['tmp_name'], BASE_PATH . '/' . $path)) {
    Response::text('Upload failed', 400);
}

Database::execute('UPDATE `user` SET `img_path` = ? WHERE `id` = ?', [$path, $user['id']]);
Response::text($path . 'success');
