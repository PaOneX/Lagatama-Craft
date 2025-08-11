<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Cart
{
    public static function getByUserId(int $userId): array
    {
        return Database::fetchAll(
            'SELECT c.*, s.price, s.stock_id, p.name, p.path, col.color_name, sz.size_name
             FROM `cart` c
             INNER JOIN `stock` s ON c.stock_stock_id = s.stock_id
             INNER JOIN `product` p ON s.product_id = p.id
             INNER JOIN `color` col ON p.color_id = col.color_id
             INNER JOIN `size` sz ON p.size_id = sz.size_id
             WHERE c.user_id = ?',
            [$userId]
        );
    }

    public static function findLine(int $userId, int $stockId): ?array
    {
        return Database::fetchOne(
            'SELECT * FROM `cart` WHERE `user_id` = ? AND `stock_stock_id` = ?',
            [$userId, $stockId]
        );
    }

    public static function add(int $userId, int $stockId, int $qty): void
    {
        Database::insert(
            'INSERT INTO `cart` (`cart_qty`,`user_id`,`stock_stock_id`) VALUES (?,?,?)',
            [$qty, $userId, $stockId]
        );
    }

    public static function updateQty(int $cartId, int $qty): void
    {
        Database::execute('UPDATE `cart` SET `cart_qty` = ? WHERE `cart_id` = ?', [$qty, $cartId]);
    }

    public static function remove(int $cartId, int $userId): void
    {
        Database::execute('DELETE FROM `cart` WHERE `cart_id` = ? AND `user_id` = ?', [$cartId, $userId]);
    }

    public static function clear(int $userId): void
    {
        Database::execute('DELETE FROM `cart` WHERE `user_id` = ?', [$userId]);
    }

    public static function findById(int $cartId, int $userId): ?array
    {
        return Database::fetchOne(
            'SELECT * FROM `cart` WHERE `cart_id` = ? AND `user_id` = ?',
            [$cartId, $userId]
        );
    }
}
