<?php

/**
 * Seed sample images and mock data for Lagatama Craft.
 *
 * Usage:
 *   php tools/seed.php          # seed only if product table is empty
 *   php tools/seed.php --fresh  # clear catalog/orders/users and re-seed
 */

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Database;
use App\Models\Product;

$fresh = in_array('--fresh', $argv ?? [], true);

$productImgDir = BASE_PATH . '/resources/productImg';
if (!is_dir($productImgDir)) {
    mkdir($productImgDir, 0755, true);
}

$imageSources = [
    'product-01.jpg' => 'homepg3.jpg',
    'product-02.jpg' => 'painting-mountain-lake-with-mountain-background.jpg',
    'product-03.jpg' => 'productManag.jpg',
    'product-04.jpg' => '90698.jpg',
    'product-05.jpg' => 'pexels-pixabay-206359.jpg',
    'product-06.jpg' => 'admin.jpg',
    'product-07.jpg' => 'userManage.jpg',
    'product-08.jpg' => 'stockManag.png',
];

foreach ($imageSources as $dest => $src) {
    $from = BASE_PATH . '/resources/' . $src;
    $to = $productImgDir . '/' . $dest;
    if (file_exists($from) && (!file_exists($to) || $fresh)) {
        copy($from, $to);
        echo "Image: {$dest}\n";
    }
}

// Optional profile image column used by profile upload
try {
    Database::execute(
        'ALTER TABLE `user` ADD COLUMN `img_path` VARCHAR(255) NULL AFTER `line_2`'
    );
    echo "Added user.img_path column\n";
} catch (Throwable) {
    // column already exists
}

$existingProducts = (int) (Database::fetchOne('SELECT COUNT(*) AS c FROM `product`')['c'] ?? 0);
if ($existingProducts > 0 && !$fresh) {
    echo "Database already has products. Run with --fresh to reset demo data.\n";
    exit(0);
}

if ($fresh) {
    Database::connection()->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach (['order_items', 'order_history', 'cart', 'stock', 'product', 'brand', 'category', 'color', 'size'] as $table) {
        Database::connection()->exec("TRUNCATE TABLE `{$table}`");
    }
    Database::execute('DELETE FROM `user` WHERE `user_type_id` = 2');
    Database::connection()->exec('SET FOREIGN_KEY_CHECKS = 1');
    echo "Cleared existing catalog and customer data.\n";
}

$adminHash = '$2y$12$KQUSJi/2dRyU45RY244qquhUR.xmV1EwQ2raOuekgih9yq6KtENty';
$customerHash = '$2y$12$vj60LdU44g6wDl9FavGdau39y4gN//yxOuvllnbuwK2frWzLNn6Zq';
$now = (new DateTime('now', new DateTimeZone(config('app.timezone'))))->format('Y-m-d H:i:s');

// Admin account
$admin = Database::fetchOne('SELECT id FROM `user` WHERE `email` = ?', ['admin@lagatama.com']);
if (!$admin) {
    Database::insert(
        'INSERT INTO `user` (`fname`,`lname`,`email`,`password`,`mobile`,`joined_date`,`gender_id`,`status`,`user_type_id`,`no`,`line_1`,`line_2`)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',
        ['Admin', 'User', 'admin@lagatama.com', $adminHash, '0771111111', $now, 1, 1, 1, '12', 'Main Street', 'Colombo']
    );
    echo "Admin: admin@lagatama.com / admin123\n";
} else {
    Database::execute('UPDATE `user` SET `password` = ?, `user_type_id` = 1, `status` = 1 WHERE `email` = ?', [$adminHash, 'admin@lagatama.com']);
    echo "Updated admin@lagatama.com / admin123\n";
}

$customers = [
    ['Kamal', 'Perera', 'kamal@example.com', '0772222222', 1, '45', 'Galle Road', 'Dehiwala'],
    ['Nimali', 'Fernando', 'nimali@example.com', '0773333333', 2, '8', 'Lake Drive', 'Kandy'],
    ['Ruwan', 'Silva', 'ruwan@example.com', '0774444444', 1, '22', 'Temple Road', 'Galle'],
    ['Ayesha', 'Jayawardena', 'ayesha@example.com', '0775555555', 2, '3', 'Hill Street', 'Negombo'],
];

$customerIds = [];
foreach ($customers as [$fname, $lname, $email, $mobile, $gender, $no, $line1, $line2]) {
    $existing = Database::fetchOne('SELECT id FROM `user` WHERE `email` = ?', [$email]);
    if ($existing) {
        $customerIds[] = (int) $existing['id'];
        continue;
    }
    $customerIds[] = Database::insert(
        'INSERT INTO `user` (`fname`,`lname`,`email`,`password`,`mobile`,`joined_date`,`gender_id`,`status`,`user_type_id`,`no`,`line_1`,`line_2`)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',
        [$fname, $lname, $email, $customerHash, $mobile, $now, $gender, 1, 2, $no, $line1, $line2]
    );
}

$brands = ['Lagatama', 'Artisan LK', 'Ceylon Craft', 'Island Weave', 'Urban Style', 'Stride LK'];
$brandMap = [];
foreach ($brands as $name) {
    $brandMap[$name] = Database::insert('INSERT INTO `brand` (`brand_name`) VALUES (?)', [$name]);
}

$categories = ['Handbags', 'Baskets', 'Backpacks', 'Accessories', 'Wallets', 'Clothes', 'Shoes'];
$catMap = [];
foreach ($categories as $name) {
    $catMap[$name] = Database::insert('INSERT INTO `category` (`cat_name`) VALUES (?)', [$name]);
}

$colors = ['Natural', 'Brown', 'Black', 'Navy', 'Burgundy', 'Olive', 'White', 'Grey', 'Red'];
$colorMap = [];
foreach ($colors as $name) {
    $colorMap[$name] = Database::insert('INSERT INTO `color` (`color_name`) VALUES (?)', [$name]);
}

$sizes = [
    'Small', 'Medium', 'Large', 'One Size',
    'XS', 'S', 'M', 'L', 'XL', 'XXL',
    '38', '39', '40', '41', '42', '43', '44',
];
$sizeMap = [];
foreach ($sizes as $name) {
    $sizeMap[$name] = Database::insert('INSERT INTO `size` (`size_name`) VALUES (?)', [$name]);
}

/**
 * @param list<array{0: string, 1: float, 2: int}> $variants [sizeName, price, qty]
 * @return list<int> stock IDs created
 */
function seedProductVariants(
    string $name,
    string $description,
    string $image,
    array $gallery,
    int $brandId,
    int $categoryId,
    int $colorId,
    array $variants,
    array $sizeMap
): array {
    $imagePaths = array_map(static fn (string $f) => 'resources/productImg/' . $f, $gallery);
    $primaryPath = 'resources/productImg/' . $image;
    $stockIds = [];

    foreach ($variants as $i => [$sizeName, $price, $qty]) {
        if (!isset($sizeMap[$sizeName])) {
            throw new RuntimeException("Unknown size: {$sizeName}");
        }

        $productId = Database::insert(
            'INSERT INTO `product` (`name`,`description`,`path`,`brand_id`,`category_id`,`color_id`,`size_id`) VALUES (?,?,?,?,?,?,?)',
            [$name, $description, $primaryPath, $brandId, $categoryId, $colorId, $sizeMap[$sizeName]]
        );

        if ($i === 0) {
            try {
                Product::addImages($productId, $imagePaths);
            } catch (\Throwable) {
                // product_image table may not exist until migration
            }
        }

        $stockIds[] = Database::insert(
            'INSERT INTO `stock` (`price`,`qty`,`product_id`) VALUES (?,?,?)',
            [$price, $qty, $productId]
        );
    }

    return $stockIds;
}

$catalog = [
    // Bags & accessories (single size each)
    [
        'name' => 'Handwoven Tote Bag',
        'description' => 'Eco-friendly tote with traditional weave pattern. Perfect for daily use.',
        'image' => 'product-01.jpg',
        'gallery' => ['product-01.jpg', 'product-02.jpg'],
        'brand' => 'Lagatama',
        'category' => 'Handbags',
        'color' => 'Natural',
        'variants' => [['Medium', 2450.00, 25]],
    ],
    [
        'name' => 'Leather Crossbody',
        'description' => 'Soft leather crossbody bag with adjustable strap and inner pocket.',
        'image' => 'product-02.jpg',
        'gallery' => ['product-02.jpg', 'product-03.jpg'],
        'brand' => 'Artisan LK',
        'category' => 'Handbags',
        'color' => 'Brown',
        'variants' => [['Medium', 4200.00, 18]],
    ],
    [
        'name' => 'Reed Market Basket',
        'description' => 'Locally sourced reed basket ideal for markets and home storage.',
        'image' => 'product-03.jpg',
        'gallery' => ['product-03.jpg', 'product-04.jpg'],
        'brand' => 'Ceylon Craft',
        'category' => 'Baskets',
        'color' => 'Natural',
        'variants' => [['Large', 1850.00, 30]],
    ],
    [
        'name' => 'Canvas Adventure Backpack',
        'description' => 'Durable canvas backpack with padded straps and laptop sleeve.',
        'image' => 'product-04.jpg',
        'gallery' => ['product-04.jpg', 'product-05.jpg', 'product-06.jpg'],
        'brand' => 'Lagatama',
        'category' => 'Backpacks',
        'color' => 'Navy',
        'variants' => [['Large', 5600.00, 15]],
    ],
    [
        'name' => 'Batik Clutch',
        'description' => 'Hand-painted batik clutch for evenings and special occasions.',
        'image' => 'product-05.jpg',
        'gallery' => ['product-05.jpg'],
        'brand' => 'Island Weave',
        'category' => 'Accessories',
        'color' => 'Burgundy',
        'variants' => [['One Size', 1650.00, 22]],
    ],
    [
        'name' => 'Palm Leaf Handbag',
        'description' => 'Lightweight palm leaf handbag with wooden clasp detail.',
        'image' => 'product-06.jpg',
        'gallery' => ['product-06.jpg', 'product-07.jpg'],
        'brand' => 'Ceylon Craft',
        'category' => 'Handbags',
        'color' => 'Natural',
        'variants' => [['Medium', 2950.00, 20]],
    ],
    [
        'name' => 'Travel Duffel Bag',
        'description' => 'Spacious duffel with reinforced handles and side pockets.',
        'image' => 'product-07.jpg',
        'gallery' => ['product-07.jpg', 'product-08.jpg'],
        'brand' => 'Artisan LK',
        'category' => 'Backpacks',
        'color' => 'Black',
        'variants' => [['Large', 6800.00, 12]],
    ],
    [
        'name' => 'Mini Coin Pouch',
        'description' => 'Compact woven coin pouch with zip closure.',
        'image' => 'product-08.jpg',
        'gallery' => ['product-08.jpg'],
        'brand' => 'Island Weave',
        'category' => 'Wallets',
        'color' => 'Olive',
        'variants' => [['One Size', 750.00, 40]],
    ],

    // Clothes — same item, multiple sizes
    [
        'name' => 'Cotton Crew T-Shirt',
        'description' => 'Breathable 100% cotton tee with a relaxed fit. Everyday essential.',
        'image' => 'product-01.jpg',
        'gallery' => ['product-01.jpg', 'product-02.jpg'],
        'brand' => 'Urban Style',
        'category' => 'Clothes',
        'color' => 'Black',
        'variants' => [
            ['S', 1890.00, 20],
            ['M', 1890.00, 28],
            ['L', 1890.00, 22],
            ['XL', 1990.00, 14],
        ],
    ],
    [
        'name' => 'Linen Summer Shirt',
        'description' => 'Lightweight linen shirt with button-down collar. Ideal for warm weather.',
        'image' => 'product-02.jpg',
        'gallery' => ['product-02.jpg', 'product-03.jpg'],
        'brand' => 'Urban Style',
        'category' => 'Clothes',
        'color' => 'Navy',
        'variants' => [
            ['S', 3490.00, 12],
            ['M', 3490.00, 18],
            ['L', 3490.00, 15],
            ['XL', 3590.00, 10],
        ],
    ],
    [
        'name' => 'Denim Jacket',
        'description' => 'Classic denim jacket with metal buttons and side pockets.',
        'image' => 'product-04.jpg',
        'gallery' => ['product-04.jpg', 'product-05.jpg'],
        'brand' => 'Urban Style',
        'category' => 'Clothes',
        'color' => 'Navy',
        'variants' => [
            ['M', 5290.00, 10],
            ['L', 5290.00, 14],
            ['XL', 5390.00, 8],
            ['XXL', 5490.00, 6],
        ],
    ],
    [
        'name' => 'Casual Chino Pants',
        'description' => 'Stretch chino trousers with a tapered leg. Smart-casual staple.',
        'image' => 'product-06.jpg',
        'gallery' => ['product-06.jpg', 'product-07.jpg'],
        'brand' => 'Urban Style',
        'category' => 'Clothes',
        'color' => 'Olive',
        'variants' => [
            ['S', 3990.00, 11],
            ['M', 3990.00, 16],
            ['L', 3990.00, 13],
            ['XL', 4090.00, 9],
        ],
    ],
    [
        'name' => 'Graphic Hoodie',
        'description' => 'Soft fleece hoodie with kangaroo pocket and ribbed cuffs.',
        'image' => 'product-05.jpg',
        'gallery' => ['product-05.jpg', 'product-08.jpg'],
        'brand' => 'Urban Style',
        'category' => 'Clothes',
        'color' => 'Grey',
        'variants' => [
            ['XS', 4590.00, 7],
            ['S', 4590.00, 12],
            ['M', 4590.00, 20],
            ['L', 4590.00, 16],
            ['XL', 4690.00, 11],
        ],
    ],

    // Shoes — same item, multiple EU sizes
    [
        'name' => 'Classic Leather Sneakers',
        'description' => 'Low-top leather sneakers with cushioned insole. Versatile everyday pair.',
        'image' => 'product-03.jpg',
        'gallery' => ['product-03.jpg', 'product-04.jpg'],
        'brand' => 'Stride LK',
        'category' => 'Shoes',
        'color' => 'White',
        'variants' => [
            ['38', 6490.00, 5],
            ['39', 6490.00, 8],
            ['40', 6490.00, 12],
            ['41', 6490.00, 14],
            ['42', 6490.00, 10],
        ],
    ],
    [
        'name' => 'Running Sports Shoes',
        'description' => 'Lightweight mesh running shoes with responsive midsole and grip sole.',
        'image' => 'product-07.jpg',
        'gallery' => ['product-07.jpg', 'product-08.jpg'],
        'brand' => 'Stride LK',
        'category' => 'Shoes',
        'color' => 'Black',
        'variants' => [
            ['39', 7890.00, 6],
            ['40', 7890.00, 10],
            ['41', 7890.00, 12],
            ['42', 7890.00, 11],
            ['43', 7890.00, 7],
        ],
    ],
    [
        'name' => 'Canvas Loafers',
        'description' => 'Slip-on canvas loafers with padded collar. Easy casual wear.',
        'image' => 'product-08.jpg',
        'gallery' => ['product-08.jpg', 'product-01.jpg'],
        'brand' => 'Stride LK',
        'category' => 'Shoes',
        'color' => 'Brown',
        'variants' => [
            ['38', 4990.00, 4],
            ['39', 4990.00, 7],
            ['40', 4990.00, 9],
            ['41', 4990.00, 10],
            ['42', 4990.00, 8],
            ['44', 5090.00, 3],
        ],
    ],
    [
        'name' => 'High-Top Street Sneakers',
        'description' => 'Bold high-top sneakers with contrast stitching and ankle support.',
        'image' => 'product-02.jpg',
        'gallery' => ['product-02.jpg', 'product-06.jpg'],
        'brand' => 'Stride LK',
        'category' => 'Shoes',
        'color' => 'Red',
        'variants' => [
            ['40', 8590.00, 5],
            ['41', 8590.00, 8],
            ['42', 8590.00, 9],
            ['43', 8590.00, 6],
            ['44', 8690.00, 4],
        ],
    ],
];

$stockIds = [];
$productCount = 0;
foreach ($catalog as $item) {
    $created = seedProductVariants(
        $item['name'],
        $item['description'],
        $item['image'],
        $item['gallery'],
        $brandMap[$item['brand']],
        $catMap[$item['category']],
        $colorMap[$item['color']],
        $item['variants'],
        $sizeMap
    );
    $stockIds = array_merge($stockIds, $created);
    $productCount++;
}

// Sample paid orders for dashboard charts (spread over last 14 days)
$orderDays = [
    [-13, [[0, 2], [10, 1]]],
    [-12, [[8, 1], [14, 1]]],
    [-10, [[3, 1], [18, 2]]],
    [-8,  [[5, 1], [22, 1], [30, 2]]],
    [-6,  [[0, 1], [12, 1]]],
    [-4,  [[2, 2], [16, 1], [25, 1]]],
    [-2,  [[1, 1], [11, 1], [20, 1], [28, 1]]],
    [-1,  [[7, 2], [15, 1], [32, 1]]],
    [0,   [[4, 1], [19, 2], [35, 1]]],
];

$orderNum = 1;
foreach ($orderDays as [$dayOffset, $items]) {
    $customerId = $customerIds[array_rand($customerIds)];
    $orderDate = (new DateTime('now', new DateTimeZone(config('app.timezone'))))
        ->modify("{$dayOffset} days")
        ->format('Y-m-d H:i:s');

    $amount = 0.0;
    $lineItems = [];
    foreach ($items as [$stockIdx, $qty]) {
        if (!isset($stockIds[$stockIdx])) {
            continue;
        }
        $stock = Database::fetchOne('SELECT * FROM `stock` WHERE `stock_id` = ?', [$stockIds[$stockIdx]]);
        if ($stock === null) {
            continue;
        }
        $lineTotal = (float) $stock['price'] * $qty;
        $amount += $lineTotal;
        $lineItems[] = [$stockIds[$stockIdx], $qty];
    }
    if ($lineItems === []) {
        continue;
    }
    $amount += (float) config('app.delivery_fee', 500);

    $ohId = Database::insert(
        'INSERT INTO `order_history` (`order_id`,`order_date`,`amount`,`user_id`,`status`,`payhere_payment_id`) VALUES (?,?,?,?,?,?)',
        [sprintf('LC-%04d', $orderNum++), $orderDate, $amount, $customerId, 'paid', 'DEMO-PAY-' . uniqid()]
    );

    foreach ($lineItems as [$stockId, $qty]) {
        Database::insert(
            'INSERT INTO `order_items` (`oi_qty`,`order_history_oh_id`,`stock_stock_id`) VALUES (?,?,?)',
            [$qty, $ohId, $stockId]
        );
    }
}

// One pending order (t-shirt size M)
$pendingStock = $stockIds[10] ?? $stockIds[0];
$pendingRow = Database::fetchOne('SELECT price FROM `stock` WHERE `stock_id` = ?', [$pendingStock]);
$pendingAmount = (float) ($pendingRow['price'] ?? 1890) + 500;
Database::insert(
    'INSERT INTO `order_history` (`order_id`,`order_date`,`amount`,`user_id`,`status`) VALUES (?,?,?,?,?)',
    [sprintf('LC-%04d', $orderNum), $now, $pendingAmount, $customerIds[0], 'pending']
);

echo "\nSeed complete!\n";
echo "  Catalog items: {$productCount}\n";
echo "  Stock SKUs: " . count($stockIds) . " (includes clothes & shoes in multiple sizes)\n";
echo "  Customers: " . count($customers) . " (password: customer123)\n";
echo "  Admin: admin@lagatama.com / admin123\n";
echo "  Orders: " . ($orderNum) . " (including 1 pending)\n";
