<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;

class PaymentService
{
    public function __construct(
        private CheckoutService $checkout = new CheckoutService()
    ) {
    }

    public function initiateFromCart(int $userId): array
    {
        $pending = $this->checkout->createPendingFromCart($userId);
        return $this->buildPayload($userId, $pending);
    }

    public function initiateBuyNow(int $userId, int $stockId, int $qty): array
    {
        $pending = $this->checkout->createPendingBuyNow($userId, $stockId, $qty);
        return $this->buildPayload($userId, $pending);
    }

    public function verifyNotify(array $post): bool
    {
        $merchantId = config('payhere.merchant_id');
        $secret = config('payhere.merchant_secret');

        if ($secret === '') {
            return false;
        }

        $orderId = $post['order_id'] ?? '';
        $amount = $post['payhere_amount'] ?? '';
        $currency = $post['payhere_currency'] ?? '';
        $statusCode = $post['status_code'] ?? '';
        $md5sig = $post['md5sig'] ?? '';

        $localHash = strtoupper(md5(
            $merchantId .
            $orderId .
            $amount .
            $currency .
            $statusCode .
            strtoupper(md5($secret))
        ));

        if (!hash_equals($localHash, $md5sig)) {
            return false;
        }

        if ((int) $statusCode !== 2) {
            return false;
        }

        return $this->checkout->finalizePayment($orderId, $post['payment_id'] ?? null);
    }

    private function buildPayload(int $userId, array $pending): array
    {
        $user = Auth::user() ?? \App\Models\User::findById($userId);
        if ($user === null) {
            throw new \RuntimeException('User not found');
        }

        $merchantId = config('payhere.merchant_id');
        $secret = config('payhere.merchant_secret');
        $currency = config('payhere.currency', 'LKR');
        $amount = $pending['amount'];

        $hash = strtoupper(md5(
            $merchantId .
            $pending['order_id'] .
            $amount .
            $currency .
            strtoupper(md5($secret))
        ));

        $baseUrl = config('app.url');

        return [
            'sandbox' => config('payhere.sandbox', true),
            'merchant_id' => $merchantId,
            'first_name' => $user['fname'],
            'last_name' => $user['lname'],
            'email' => $user['email'],
            'phone' => $user['mobile'],
            'address' => ($user['no'] ?? '') . ',' . ($user['line_1'] ?? ''),
            'city' => $user['line_2'] ?? '',
            'country' => 'Sri Lanka',
            'order_id' => $pending['order_id'],
            'oh_id' => $pending['oh_id'],
            'items' => $pending['items'],
            'currency' => $currency,
            'amount' => $amount,
            'hash' => $hash,
            'return_url' => $baseUrl . '/invoice.php?orderId=' . $pending['oh_id'],
            'cancel_url' => $baseUrl . '/cart.php',
            'notify_url' => $baseUrl . '/api/checkout/notify.php',
        ];
    }
}
