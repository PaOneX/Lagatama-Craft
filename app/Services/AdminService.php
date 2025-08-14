<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\Lookup;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;

class AdminService
{
    public function renderUsers(): string
    {
        $users = Database::fetchAll("SELECT * FROM `user` WHERE `user_type_id` = 2");
        ob_start();
        foreach ($users as $d) {
            include base_path('views/partials/admin-user-row.php');
        }
        return (string) ob_get_clean();
    }

    public function searchUsers(string $term): string
    {
        if ($term === '') {
            return $this->renderUsers();
        }

        $users = User::search($term);
        if (empty($users)) {
            return 'Incorrect First name or Last Name';
        }

        ob_start();
        foreach ($users as $d) {
            include base_path('views/partials/admin-user-row.php');
        }
        return (string) ob_get_clean();
    }

    public function updateUserStatus(int $userId, int $status): string
    {
        User::updateStatus($userId, $status);
        return 'success';
    }

    public function registerProduct(array $input, ?array $files): string
    {
        $pname = trim($input['pname'] ?? '');
        if ($pname === '' || strlen($pname) > 100) {
            return 'Please Enter Product Name';
        }

        $required = ['brand', 'cat', 'color', 'size', 'desc'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                return 'Please fill all required fields';
            }
        }

        $uploads = $this->normalizeUploadedFiles($files);
        if (empty($uploads)) {
            return 'Please select at least one image';
        }

        $existing = Database::fetchOne('SELECT id FROM `product` WHERE `name` = ?', [$pname]);
        if ($existing) {
            return 'Product Already Exist';
        }

        $paths = $this->saveProductImages($uploads);
        if (empty($paths)) {
            return 'Upload failed';
        }

        $productId = Product::create([
            'name' => $pname,
            'description' => $input['desc'],
            'path' => $paths[0],
            'brand_id' => (int) $input['brand'],
            'category_id' => (int) $input['cat'],
            'color_id' => (int) $input['color'],
            'size_id' => (int) $input['size'],
        ]);

        Product::addImages($productId, $paths);

        return 'success';
    }

    /** @return list<array> */
    private function normalizeUploadedFiles(?array $files): array
    {
        if ($files === null || !isset($files['name'])) {
            return [];
        }

        if (!is_array($files['name'])) {
            return ($files['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK ? [$files] : [];
        }

        $normalized = [];
        foreach ($files['name'] as $i => $name) {
            if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || $name === '') {
                continue;
            }
            $normalized[] = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i] ?? '',
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i] ?? 0,
            ];
        }

        return $normalized;
    }

    /** @param list<array> $uploads @return list<string> */
    private function saveProductImages(array $uploads): array
    {
        $dir = BASE_PATH . '/resources/productImg';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $paths = [];
        $maxBytes = (int) config('security.upload_max_image_bytes', 5 * 1024 * 1024);
        foreach ($uploads as $file) {
            if (Upload::validate($file, Upload::imageMimes(), $maxBytes) !== null) {
                continue;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                $ext = 'jpg';
            }
            $path = 'resources/productImg/' . uniqid('img_', true) . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], BASE_PATH . '/' . $path)) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    public function updateStock(int $productId, float $price, int $qty): string
    {
        if ($productId <= 0) {
            return 'Please select select your Product .';
        }
        if ($qty <= 0) {
            return 'Quantity must be Positive Number';
        }
        if ($price < 0) {
            return 'Please enter the positive Value';
        }

        $existing = Database::fetchOne(
            'SELECT * FROM `stock` WHERE `product_id` = ? AND `price` = ?',
            [$productId, $price]
        );

        if ($existing) {
            Stock::update((int) $existing['stock_id'], $price, (int) $existing['qty'] + $qty);
            return 'success';
        }

        Stock::create($productId, $price, $qty);
        return 'New Stock Added Successfully';
    }

    public function toggleUserStatus(int $uid): string
    {
        if ($uid <= 0) {
            return 'Please Enter Your User ID';
        }

        $user = Database::fetchOne(
            'SELECT * FROM `user` WHERE `id` = ? AND `user_type_id` = 2',
            [$uid]
        );

        if (!$user) {
            return 'Invalid User Id';
        }

        $newStatus = (int) $user['status'] === 1 ? 0 : 1;
        User::updateStatus($uid, $newStatus);
        return $newStatus === 1 ? 'Active' : 'Deactive';
    }

    public function addLookup(string $type, string $name): string
    {
        if ($name === '') {
            return 'Please Enter Your ' . ucfirst($type);
        }

        $tableMap = [
            'brand' => ['brand', 'brand_name'],
            'category' => ['category', 'cat_name'],
            'color' => ['color', 'color_name'],
            'size' => ['size', 'size_name'],
        ];

        if (!isset($tableMap[$type])) {
            return 'Invalid lookup type';
        }

        [$table, $col] = $tableMap[$type];
        $existing = Database::fetchOne("SELECT * FROM `{$table}` WHERE `{$col}` = ?", [$name]);
        if ($existing) {
            return ucfirst($type) . ' name Already Exists.';
        }

        match ($type) {
            'brand' => Lookup::addBrand($name),
            'category' => Lookup::addCategory($name),
            'color' => Lookup::addColor($name),
            'size' => Lookup::addSize($name),
        };

        return 'Success';
    }

    public function chartTopProducts(): array
    {
        $rows = Database::fetchAll(
            'SELECT p.id, p.name, SUM(oi.oi_qty) AS total_sold
             FROM order_items oi
             INNER JOIN stock s ON oi.stock_stock_id = s.stock_id
             INNER JOIN product p ON s.product_id = p.id
             INNER JOIN order_history oh ON oi.order_history_oh_id = oh.oh_id
             WHERE oh.status = ?
             GROUP BY p.id, p.name
             ORDER BY total_sold DESC LIMIT 5',
            ['paid']
        );

        return [
            'labels' => array_column($rows, 'name'),
            'data' => array_map('intval', array_column($rows, 'total_sold')),
        ];
    }

    public function chartDailyIncome(): array
    {
        $rows = Database::fetchAll(
            "SELECT DATE(oh.order_date) AS order_date,
                    SUM(oi.oi_qty * s.price) AS daily_income
             FROM order_items oi
             INNER JOIN stock s ON oi.stock_stock_id = s.stock_id
             INNER JOIN order_history oh ON oi.order_history_oh_id = oh.oh_id
             WHERE oh.status = 'paid'
             GROUP BY DATE(oh.order_date)
             ORDER BY order_date ASC"
        );

        $total = Database::fetchOne(
            "SELECT SUM(amount) AS total_amount FROM order_history WHERE status = 'paid'"
        );

        return [
            'dates' => array_column($rows, 'order_date'),
            'incomes' => array_map('floatval', array_column($rows, 'daily_income')),
            'total_amount' => $total['total_amount'] ?? 0,
        ];
    }

    public function chartTopCategories(): array
    {
        $rows = Database::fetchAll(
            'SELECT c.cat_name, SUM(oi.oi_qty) AS total_sold
             FROM order_items oi
             INNER JOIN stock s ON oi.stock_stock_id = s.stock_id
             INNER JOIN product p ON s.product_id = p.id
             INNER JOIN category c ON p.category_id = c.cat_id
             INNER JOIN order_history oh ON oi.order_history_oh_id = oh.oh_id
             WHERE oh.status = ?
             GROUP BY c.cat_id, c.cat_name
             ORDER BY total_sold DESC LIMIT 5',
            ['paid']
        );

        return [
            'labels' => array_column($rows, 'cat_name'),
            'data' => array_map('intval', array_column($rows, 'total_sold')),
        ];
    }

    public function dashboardStats(): array
    {
        $users = Database::fetchOne('SELECT COUNT(*) AS cnt FROM `user` WHERE `user_type_id` = 2');
        $products = Database::fetchOne('SELECT COUNT(*) AS cnt FROM `product`');
        $stock = Database::fetchOne('SELECT COALESCE(SUM(qty), 0) AS cnt FROM `stock`');
        $revenue = Database::fetchOne("SELECT COALESCE(SUM(amount), 0) AS total FROM order_history WHERE status = 'paid'");

        return [
            'users' => (int) ($users['cnt'] ?? 0),
            'products' => (int) ($products['cnt'] ?? 0),
            'stock_units' => (int) ($stock['cnt'] ?? 0),
            'revenue' => (float) ($revenue['total'] ?? 0),
        ];
    }

    public function productReport(): array
    {
        return Database::fetchAll(
            'SELECT p.*, b.brand_name, c.color_name, cat.cat_name, s.size_name
             FROM `product` p
             INNER JOIN `brand` b ON p.brand_id = b.brand_id
             INNER JOIN `color` c ON p.color_id = c.color_id
             INNER JOIN `category` cat ON p.category_id = cat.cat_id
             INNER JOIN `size` s ON p.size_id = s.size_id
             ORDER BY p.id ASC'
        );
    }

    public function userReport(): array
    {
        return Database::fetchAll(
            'SELECT u.*, ut.type_name AS type
             FROM `user` u
             INNER JOIN `user_type` ut ON u.user_type_id = ut.id
             ORDER BY u.id ASC'
        );
    }

    public function stockReport(): array
    {
        return Database::fetchAll(
            'SELECT st.*, p.name
             FROM `stock` st
             INNER JOIN `product` p ON st.product_id = p.id
             ORDER BY st.stock_id ASC'
        );
    }

    public function salesReport(string $date): array
    {
        $rows = Database::fetchAll(
            "SELECT oh.order_date AS Order_Date, p.name AS Product_Name,
                    oi.oi_qty AS Quantity, s.price AS Price,
                    (oi.oi_qty * s.price) AS Total_Price
             FROM order_history oh
             INNER JOIN order_items oi ON oh.oh_id = oi.order_history_oh_id
             INNER JOIN stock s ON oi.stock_stock_id = s.stock_id
             INNER JOIN product p ON s.product_id = p.id
             WHERE DATE(oh.order_date) = ?
             ORDER BY oh.order_date DESC",
            [$date]
        );

        $netTotal = array_sum(array_column($rows, 'Total_Price'));

        return ['rows' => $rows, 'netTotal' => $netTotal];
    }

    public function updateProfile(int $userId, array $input): string
    {
        $fname = trim($input['f'] ?? '');
        $lname = trim($input['l'] ?? '');
        $email = trim($input['e'] ?? '');
        $mobile = trim($input['m'] ?? '');
        $no = trim($input['n'] ?? '');
        $line1 = trim($input['l1'] ?? '');
        $line2 = trim($input['l2'] ?? '');

        if (!Validator::name($fname) || !Validator::name($lname)) {
            return 'Please Enter Your First Name';
        }
        if (!Validator::email($email)) {
            return 'Your Email Address is Invalid';
        }
        if (!Validator::mobile($mobile)) {
            return 'Your mobile Number is Invalid';
        }
        if (strlen($no) > 10) {
            return 'Your Address No Should be less than 10 Characters';
        }
        if ($line1 === '' || strlen($line1) > 50 || $line2 === '' || strlen($line2) > 50) {
            return 'Please Enter Your Address';
        }

        User::updateProfile($userId, [
            'fname' => $fname, 'lname' => $lname, 'email' => $email,
            'mobile' => $mobile,
            'no' => $no, 'line_1' => $line1, 'line_2' => $line2,
        ]);

        $updated = User::findById($userId);
        if ($updated) {
            \App\Core\Auth::loginUser($updated);
        }

        return 'Update Successfully';
    }

    public function updateShipping(int $userId, string $no, string $line1, string $line2): string
    {
        User::updateAddress($userId, $no, $line1, $line2);
        $updated = User::findById($userId);
        if ($updated) {
            \App\Core\Auth::loginUser($updated);
        }
        return 'success';
    }
}
