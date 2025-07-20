<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Product
{
    public static function baseQuery(): string
    {
        return 'SELECT s.*, p.*, s.stock_id, s.price AS stock_price, s.qty AS stock_qty
                FROM `stock` s
                INNER JOIN `product` p ON s.product_id = p.id';
    }

    public static function countAll(): int
    {
        $row = Database::fetchOne('SELECT COUNT(*) AS cnt FROM `stock`');
        return (int) ($row['cnt'] ?? 0);
    }

    public static function featured(int $limit = 3): array
    {
        return Database::fetchAll(
            self::baseQuery() . ' WHERE s.qty > 0 ORDER BY s.stock_id DESC LIMIT ?',
            [$limit]
        );
    }

    public static function paginate(int $offset, int $limit): array
    {
        return Database::fetchAll(
            self::baseQuery() . ' ORDER BY s.stock_id ASC LIMIT ? OFFSET ?',
            [$limit, $offset]
        );
    }

    public static function searchByName(string $name, int $offset, int $limit): array
    {
        $like = '%' . $name . '%';
        return Database::fetchAll(
            self::baseQuery() . ' WHERE p.name LIKE ? ORDER BY s.stock_id ASC LIMIT ? OFFSET ?',
            [$like, $limit, $offset]
        );
    }

    public static function countSearchByName(string $name): int
    {
        $like = '%' . $name . '%';
        $row = Database::fetchOne(
            'SELECT COUNT(*) AS cnt FROM `stock` s INNER JOIN `product` p ON s.product_id = p.id WHERE p.name LIKE ?',
            [$like]
        );
        return (int) ($row['cnt'] ?? 0);
    }

    public static function advancedSearch(array $filters, int $offset, int $limit): array
    {
        [$where, $params] = self::buildAdvancedWhere($filters);
        return Database::fetchAll(
            self::baseQuery() . $where . ' ORDER BY s.stock_id ASC LIMIT ? OFFSET ?',
            array_merge($params, [$limit, $offset])
        );
    }

    public static function countAdvancedSearch(array $filters): int
    {
        [$where, $params] = self::buildAdvancedWhere($filters);
        $row = Database::fetchOne(
            'SELECT COUNT(*) AS cnt FROM `stock` s INNER JOIN `product` p ON s.product_id = p.id' . $where,
            $params
        );
        return (int) ($row['cnt'] ?? 0);
    }

    public static function findByStockId(int $stockId): ?array
    {
        return Database::fetchOne(
            self::baseQuery() . ' WHERE s.stock_id = ?',
            [$stockId]
        );
    }

    public static function findDetailByStockId(int $stockId): ?array
    {
        return Database::fetchOne(
            'SELECT s.stock_id, s.price, s.qty, s.product_id,
                    p.id, p.name, p.description, p.path,
                    p.brand_id, p.category_id, p.color_id, p.size_id,
                    b.brand_name, c.cat_name, cl.color_name, sz.size_name
             FROM `stock` s
             INNER JOIN `product` p ON s.product_id = p.id
             INNER JOIN `brand` b ON p.brand_id = b.brand_id
             INNER JOIN `category` c ON p.category_id = c.cat_id
             INNER JOIN `color` cl ON p.color_id = cl.color_id
             INNER JOIN `size` sz ON p.size_id = sz.size_id
             WHERE s.stock_id = ?',
            [$stockId]
        );
    }

    /** Same product in other colors (matched by name, brand, category, size). */
    public static function findColorVariantsByStockId(int $stockId): array
    {
        $current = self::findDetailByStockId($stockId);
        if ($current === null) {
            return [];
        }

        return Database::fetchAll(
            'SELECT s.stock_id, s.qty, cl.color_id, cl.color_name, p.path
             FROM `product` p
             INNER JOIN `stock` s ON s.product_id = p.id
             INNER JOIN `color` cl ON p.color_id = cl.color_id
             WHERE p.name = ? AND p.brand_id = ? AND p.category_id = ? AND p.size_id = ?
             ORDER BY cl.color_name',
            [$current['name'], $current['brand_id'], $current['category_id'], $current['size_id']]
        );
    }

    /** Paid order quantity for a product line (name + brand + category). */
    public static function soldCountForLine(string $name, int $brandId, int $categoryId): int
    {
        $row = Database::fetchOne(
            'SELECT COALESCE(SUM(oi.oi_qty), 0) AS cnt
             FROM `order_items` oi
             INNER JOIN `order_history` oh ON oi.order_history_oh_id = oh.oh_id
             INNER JOIN `stock` s ON oi.stock_stock_id = s.stock_id
             INNER JOIN `product` p ON s.product_id = p.id
             WHERE p.name = ? AND p.brand_id = ? AND p.category_id = ? AND oh.status = ?',
            [$name, $brandId, $categoryId, 'paid']
        );

        return (int) ($row['cnt'] ?? 0);
    }

    /** @return array{min: float, max: float} */
    public static function linePriceRange(string $name, int $brandId, int $categoryId): array
    {
        $row = Database::fetchOne(
            'SELECT MIN(s.price) AS min_price, MAX(s.price) AS max_price
             FROM `product` p
             INNER JOIN `stock` s ON s.product_id = p.id
             WHERE p.name = ? AND p.brand_id = ? AND p.category_id = ?',
            [$name, $brandId, $categoryId]
        );

        return [
            'min' => (float) ($row['min_price'] ?? 0),
            'max' => (float) ($row['max_price'] ?? 0),
        ];
    }

    /** Same product in other sizes (matched by name, brand, category, color). */
    public static function findSizeVariantsByStockId(int $stockId): array
    {
        $current = self::findDetailByStockId($stockId);
        if ($current === null) {
            return [];
        }

        return Database::fetchAll(
            'SELECT s.stock_id, s.price, s.qty, sz.size_id, sz.size_name
             FROM `product` p
             INNER JOIN `stock` s ON s.product_id = p.id
             INNER JOIN `size` sz ON p.size_id = sz.size_id
             WHERE p.name = ? AND p.brand_id = ? AND p.category_id = ? AND p.color_id = ?
             ORDER BY FIELD(sz.size_name,
                \'XS\', \'S\', \'Small\', \'M\', \'Medium\', \'L\', \'Large\', \'XL\', \'XXL\', \'2XL\', \'3XL\',
                \'38\', \'39\', \'40\', \'41\', \'42\', \'43\', \'44\', \'One Size\'
             ), sz.size_name',
            [$current['name'], $current['brand_id'], $current['category_id'], $current['color_id']]
        );
    }

    /** @return list<string> */
    public static function imagesForProduct(int $productId, string $fallbackPath): array
    {
        try {
            $rows = Database::fetchAll(
                'SELECT `path` FROM `product_image`
                 WHERE `product_id` = ?
                 ORDER BY `is_primary` DESC, `sort_order` ASC, `id` ASC',
                [$productId]
            );
            if (!empty($rows)) {
                return array_column($rows, 'path');
            }
        } catch (\Throwable) {
            // Table may not exist until migration is applied
        }

        return $fallbackPath !== '' ? [$fallbackPath] : [];
    }

    /** @param list<string> $paths */
    public static function addImages(int $productId, array $paths): void
    {
        foreach ($paths as $i => $path) {
            Database::insert(
                'INSERT INTO `product_image` (`product_id`, `path`, `sort_order`, `is_primary`) VALUES (?,?,?,?)',
                [$productId, $path, $i, $i === 0 ? 1 : 0]
            );
        }
    }

    public static function create(array $data): int
    {
        return Database::insert(
            'INSERT INTO `product` (`name`,`description`,`path`,`brand_id`,`category_id`,`color_id`,`size_id`) VALUES (?,?,?,?,?,?,?)',
            [$data['name'], $data['description'], $data['path'], $data['brand_id'],
             $data['category_id'], $data['color_id'], $data['size_id']]
        );
    }

    private static function buildAdvancedWhere(array $filters): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['color_id'])) {
            $conditions[] = 'p.color_id = ?';
            $params[] = $filters['color_id'];
        }
        if (!empty($filters['category_id'])) {
            $conditions[] = 'p.category_id = ?';
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['brand_id'])) {
            $conditions[] = 'p.brand_id = ?';
            $params[] = $filters['brand_id'];
        }
        if (!empty($filters['size_id'])) {
            $conditions[] = 'p.size_id = ?';
            $params[] = $filters['size_id'];
        }
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $conditions[] = 's.price >= ?';
            $params[] = $filters['min_price'];
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $conditions[] = 's.price <= ?';
            $params[] = $filters['max_price'];
        }

        $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
        return [$where, $params];
    }
}
