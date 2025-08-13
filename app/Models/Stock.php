<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Stock
{
    public static function findById(int $stockId): ?array
    {
        return Database::fetchOne('SELECT * FROM `stock` WHERE `stock_id` = ?', [$stockId]);
    }

    public static function findWithProduct(int $stockId): ?array
    {
        return Database::fetchOne(
            'SELECT s.*, p.name, p.path FROM `stock` s
             INNER JOIN `product` p ON s.product_id = p.id
             WHERE s.stock_id = ?',
            [$stockId]
        );
    }

    public static function create(int $productId, float $price, int $qty): int
    {
        return Database::insert(
            'INSERT INTO `stock` (`price`,`qty`,`product_id`) VALUES (?,?,?)',
            [$price, $qty, $productId]
        );
    }

    public static function update(int $stockId, float $price, int $qty): void
    {
        Database::execute(
            'UPDATE `stock` SET `price`=?, `qty`=? WHERE `stock_id`=?',
            [$price, $qty, $stockId]
        );
    }

    public static function decrement(int $stockId, int $qty): bool
    {
        return Database::execute(
            'UPDATE `stock` SET `qty` = `qty` - ? WHERE `stock_id` = ? AND `qty` >= ?',
            [$qty, $stockId, $qty]
        ) > 0;
    }
}
