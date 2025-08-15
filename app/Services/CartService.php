<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\Stock;

class CartService
{
    public function addItem(int $userId, int $stockId, int $qty): string
    {
        if ($qty < 1) {
            return 'Please Enter Your Quantity';
        }

        $stock = Stock::findById($stockId);
        if ($stock === null) {
            return 'Your Stock is not Found!';
        }

        $existing = Cart::findLine($userId, $stockId);
        if ($existing !== null) {
            $newQty = $qty + (int) $existing['cart_qty'];
            if ((int) $stock['qty'] < $newQty) {
                return 'Stock Quantity has been Exceeded';
            }
            Cart::updateQty((int) $existing['cart_id'], $newQty);
            return 'success';
        }

        if ((int) $stock['qty'] < $qty) {
            return 'Stock Quantity has been Exceeded';
        }

        Cart::add($userId, $stockId, $qty);
        return 'Cart Item Added Successfully';
    }

    public function setQty(int $userId, int $cartId, int $newQty): string
    {
        $line = Cart::findById($cartId, $userId);
        if ($line === null) {
            return 'Cart Item Not Found';
        }

        if ($newQty < 1) {
            return 'Quantity cannot be less than 1';
        }

        $stock = Stock::findById((int) $line['stock_stock_id']);
        if ($stock === null || (int) $stock['qty'] < $newQty) {
            return 'Your Product Quantity Exceeded!';
        }

        Cart::updateQty($cartId, $newQty);
        return 'Success';
    }

    public function remove(int $userId, int $cartId): string
    {
        Cart::remove($cartId, $userId);
        return 'success';
    }

    public function renderCart(int $userId): string
    {
        $items = Cart::getByUserId($userId);
        $deliveryFee = (int) config('app.delivery_fee', 500);

        ob_start();
        if (empty($items)) {
            include base_path('views/partials/cart-empty.php');
        } else {
            $netTotal = 0;
            $num = count($items);
            include base_path('views/partials/cart-items.php');
        }
        return (string) ob_get_clean();
    }
}
