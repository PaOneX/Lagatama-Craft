<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Order
{
    public static function create(int $userId, string $orderId, float $amount, string $status = 'pending'): int
    {
        $date = (new \DateTime('now', new \DateTimeZone(config('app.timezone'))))->format('Y-m-d H:i:s');
        return Database::insert(
            'INSERT INTO `order_history` (`order_id`,`order_date`,`amount`,`user_id`,`status`) VALUES (?,?,?,?,?)',
            [$orderId, $date, $amount, $userId, $status]
        );
    }

    public static function addItem(int $orderId, int $stockId, int $qty): void
    {
        Database::insert(
            'INSERT INTO `order_items` (`oi_qty`,`order_history_oh_id`,`stock_stock_id`) VALUES (?,?,?)',
            [$qty, $orderId, $stockId]
        );
    }

    public static function findByOhId(int $ohId): ?array
    {
        return Database::fetchOne('SELECT * FROM `order_history` WHERE `oh_id` = ?', [$ohId]);
    }

    public static function findByPayhereOrderId(string $orderId): ?array
    {
        return Database::fetchOne('SELECT * FROM `order_history` WHERE `order_id` = ?', [$orderId]);
    }

    public static function markPaid(int $ohId, ?string $paymentId = null): void
    {
        Database::execute(
            'UPDATE `order_history` SET `status` = ?, `payhere_payment_id` = ? WHERE `oh_id` = ?',
            ['paid', $paymentId, $ohId]
        );
    }

    public static function markFailed(int $ohId): void
    {
        Database::execute('UPDATE `order_history` SET `status` = ? WHERE `oh_id` = ?', ['failed', $ohId]);
    }

    public static function getItems(int $ohId): array
    {
        return Database::fetchAll(
            'SELECT oi.*, s.price, p.name, p.path, p.description
             FROM `order_items` oi
             INNER JOIN `stock` s ON oi.stock_stock_id = s.stock_id
             INNER JOIN `product` p ON s.product_id = p.id
             WHERE oi.order_history_oh_id = ?',
            [$ohId]
        );
    }

    public static function getByUserId(int $userId): array
    {
        return Database::fetchAll(
            'SELECT * FROM `order_history` WHERE `user_id` = ? ORDER BY `oh_id` DESC',
            [$userId]
        );
    }

    public static function getPendingItems(int $ohId): array
    {
        return Database::fetchAll(
            'SELECT * FROM `order_items` WHERE `order_history_oh_id` = ?',
            [$ohId]
        );
    }
}
