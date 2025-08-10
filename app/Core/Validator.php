<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function mobile(string $mobile): bool
    {
        return (bool) preg_match('/^07[0,2,4,5,6,7,8][0-9]{7}$/', $mobile);
    }

    public static function password(string $password, int $min = 5, int $max = 45): bool
    {
        $len = strlen($password);
        return $len >= $min && $len <= $max;
    }

    public static function name(string $name, int $max = 20): bool
    {
        return $name !== '' && strlen($name) <= $max;
    }
}
