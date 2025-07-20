<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class User
{
    public static function findByEmail(string $email): ?array
    {
        return Database::fetchOne('SELECT * FROM `user` WHERE `email` = ?', [$email]);
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne('SELECT * FROM `user` WHERE `id` = ?', [$id]);
    }

    public static function findByEmailOrMobile(string $email, string $mobile): ?array
    {
        return Database::fetchOne(
            'SELECT * FROM `user` WHERE `email` = ? OR `mobile` = ?',
            [$email, $mobile]
        );
    }

    public static function create(array $data): int
    {
        return Database::insert(
            'INSERT INTO `user` (`fname`,`lname`,`email`,`password`,`mobile`,`joined_date`,`gender_id`,`status`,`user_type_id`)
             VALUES (?,?,?,?,?,?,?,?,?)',
            [
                $data['fname'], $data['lname'], $data['email'], $data['password'],
                $data['mobile'], $data['joined_date'], $data['gender_id'],
                $data['status'] ?? 1, $data['user_type_id'] ?? 2,
            ]
        );
    }

    public static function updatePassword(int $id, string $password): void
    {
        Database::execute('UPDATE `user` SET `password` = ? WHERE `id` = ?', [$password, $id]);
    }

    public static function updateVerificationCode(string $email, string $code, ?string $expiresAt = null): void
    {
        Database::execute(
            'UPDATE `user` SET `verification_code` = ?, `verification_expires` = ? WHERE `email` = ?',
            [$code, $expiresAt, $email]
        );
    }

    public static function resetPassword(string $email, string $code, string $password): bool
    {
        return Database::execute(
            'UPDATE `user` SET `password` = ?, `verification_code` = NULL, `verification_expires` = NULL
             WHERE `email` = ? AND `verification_code` = ?
             AND (`verification_expires` IS NULL OR `verification_expires` >= NOW())',
            [$password, $email, $code]
        ) > 0;
    }

    public static function findByGoogleId(string $googleId): ?array
    {
        return Database::fetchOne('SELECT * FROM `user` WHERE `google_id` = ?', [$googleId]);
    }

    public static function linkGoogleId(int $userId, string $googleId): void
    {
        Database::execute('UPDATE `user` SET `google_id` = ? WHERE `id` = ?', [$googleId, $userId]);
    }

    public static function updateProfileImage(int $id, string $path): void
    {
        Database::execute('UPDATE `user` SET `img_path` = ? WHERE `id` = ?', [$path, $id]);
    }

    public static function updateProfile(int $id, array $data): void
    {
        Database::execute(
            'UPDATE `user` SET `fname`=?, `lname`=?, `email`=?, `mobile`=?, `no`=?, `line_1`=?, `line_2`=? WHERE `id`=?',
            [$data['fname'], $data['lname'], $data['email'], $data['mobile'],
             $data['no'], $data['line_1'], $data['line_2'], $id]
        );
    }

    public static function updateAddress(int $id, string $no, string $line1, string $line2): void
    {
        Database::execute(
            'UPDATE `user` SET `no`=?, `line_1`=?, `line_2`=? WHERE `id`=?',
            [$no, $line1, $line2, $id]
        );
    }

    public static function updateStatus(int $id, int $status): void
    {
        Database::execute('UPDATE `user` SET `status` = ? WHERE `id` = ?', [$status, $id]);
    }

    public static function search(string $term): array
    {
        $like = '%' . $term . '%';
        return Database::fetchAll(
            'SELECT * FROM `user` WHERE `fname` LIKE ? OR `lname` LIKE ? OR `email` LIKE ?',
            [$like, $like, $like]
        );
    }

    public static function all(int $offset, int $limit): array
    {
        return Database::fetchAll(
            'SELECT * FROM `user` ORDER BY `id` ASC LIMIT ? OFFSET ?',
            [$limit, $offset]
        );
    }

    public static function count(): int
    {
        $row = Database::fetchOne('SELECT COUNT(*) AS cnt FROM `user`');
        return (int) ($row['cnt'] ?? 0);
    }
}
