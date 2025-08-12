<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Lookup
{
    public static function all(string $table, string $idCol, string $nameCol): array
    {
        $allowed = [
            'gender' => ['id', 'gender'],
            'brand' => ['brand_id', 'brand_name'],
            'category' => ['cat_id', 'cat_name'],
            'color' => ['color_id', 'color_name'],
            'size' => ['size_id', 'size_name'],
        ];

        if (!isset($allowed[$table])) {
            return [];
        }

        return Database::fetchAll("SELECT * FROM `{$table}` ORDER BY {$idCol} ASC");
    }

    public static function genders(): array
    {
        return Database::fetchAll('SELECT * FROM `gender`');
    }

    public static function brands(): array
    {
        return Database::fetchAll('SELECT * FROM `brand`');
    }

    public static function categories(): array
    {
        return Database::fetchAll('SELECT * FROM `category`');
    }

    public static function colors(): array
    {
        return Database::fetchAll('SELECT * FROM `color`');
    }

    public static function sizes(): array
    {
        return Database::fetchAll('SELECT * FROM `size`');
    }

    public static function addBrand(string $name): void
    {
        Database::insert('INSERT INTO `brand` (`brand_name`) VALUES (?)', [$name]);
    }

    public static function addCategory(string $name): void
    {
        Database::insert('INSERT INTO `category` (`cat_name`) VALUES (?)', [$name]);
    }

    public static function addColor(string $name): void
    {
        Database::insert('INSERT INTO `color` (`color_name`) VALUES (?)', [$name]);
    }

    public static function addSize(string $name): void
    {
        Database::insert('INSERT INTO `size` (`size_name`) VALUES (?)', [$name]);
    }
}
