<?php

return [
    'session_idle_minutes' => (int) ($_ENV['SESSION_IDLE_MINUTES'] ?? 30),
    'login_max_attempts' => (int) ($_ENV['LOGIN_MAX_ATTEMPTS'] ?? 5),
    'login_lockout_minutes' => (int) ($_ENV['LOGIN_LOCKOUT_MINUTES'] ?? 15),
    'upload_max_image_bytes' => (int) ($_ENV['UPLOAD_MAX_IMAGE_BYTES'] ?? 5 * 1024 * 1024),
    'upload_max_video_bytes' => (int) ($_ENV['UPLOAD_MAX_VIDEO_BYTES'] ?? 50 * 1024 * 1024),
];
