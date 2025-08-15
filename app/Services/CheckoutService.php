<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Stock;

class CheckoutService
{
    public function createPendingFromCart(int $userId): array
    {
        $items = Cart::getByUserId($userId);
        if (empty($items)) {
            throw new \RuntimeException('Cart is empty');
        }

        return $this->createPendingOrder($userId, $items, true);
    }

    public function createPendingBuyNow(int $userId, int $stockId, int $qty): array
    {
        $stock = Stock::findWithProduct($stockId);
        if ($stock === null) {
            throw new \RuntimeException('Product not found');
        }
        if ((int) $stock['qty'] < $qty) {
            throw new \RuntimeException('Product has out of stock');
        }

        $items = [[
            'stock_stock_id' => $stockId,
            'cart_qty' => $qty,
            'price' => $stock['price'],
            'name' => $stock['name'],
        ]];

        return $this->createPendingOrder($userId, $items, false);
    }

    private function createPendingOrder(int $userId, array $items, bool $fromCart): array
    {
        $subtotal = 0;
        $names = [];

        foreach ($items as $item) {
            $stock = Stock::findById((int) $item['stock_stock_id']);
            if ($stock === null || (int) $stock['qty'] < (int) $item['cart_qty']) {
                throw new \RuntimeException('Product has out of stock');
            }
            $subtotal += (float) $stock['price'] * (int) $item['cart_qty'];
            $product = Stock::findWithProduct((int) $item['stock_stock_id']);
            $names[] = $product['name'] ?? 'Item';
        }

        $delivery = (int) config('app.delivery_fee', 500);
        $total = $subtotal + $delivery;
        $payhereOrderId = (string) random_int(100000, 999999);

        $ohId = Database::transaction(function () use ($userId, $payhereOrderId, $total, $items) {
            $ohId = Order::create($userId, $payhereOrderId, $total, 'pending');
            foreach ($items as $item) {
                Order::addItem($ohId, (int) $item['stock_stock_id'], (int) $item['cart_qty']);
            }
            return $ohId;
        });

        return [
            'oh_id' => $ohId,
            'order_id' => $payhereOrderId,
            'amount' => number_format($total, 2, '.', ''),
            'items' => implode(',', $names),
            'from_cart' => $fromCart,
        ];
    }

    public function finalizePayment(string $payhereOrderId, ?string $paymentId = null): bool
    {
        $order = Order::findByPayhereOrderId($payhereOrderId);
        if ($order === null || $order['status'] === 'paid') {
            return false;
        }

        return (bool) Database::transaction(function () use ($order, $paymentId) {
            $items = Order::getPendingItems((int) $order['oh_id']);

            foreach ($items as $item) {
                if (!Stock::decrement((int) $item['stock_stock_id'], (int) $item['oi_qty'])) {
                    throw new \RuntimeException('Insufficient stock');
                }
            }

            Order::markPaid((int) $order['oh_id'], $paymentId);
            Cart::clear((int) $order['user_id']);
            return true;
        });
    }

    public function getOrderForInvoice(int $ohId, int $userId): ?array
    {
        $order = Order::findByOhId($ohId);
        if ($order === null || (int) $order['user_id'] !== $userId) {
            return null;
        }
        if ($order['status'] !== 'paid') {
            return null;
        }
        $order['items'] = Order::getItems($ohId);
        return $order;
    }

    public function getOrderStatus(int $ohId, int $userId): ?string
    {
        $order = Order::findByOhId($ohId);
        if ($order === null || (int) $order['user_id'] !== $userId) {
            return null;
        }
        return $order['status'];
    }
}
