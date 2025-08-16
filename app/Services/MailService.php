<?php

declare(strict_types=1);

namespace App\Services;

class MailService
{
    public function __construct()
    {
        if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
            $mailerDir = BASE_PATH . '/public/assets/vendor/phpmailer';
            require_once $mailerDir . '/Exception.php';
            require_once $mailerDir . '/SMTP.php';
            require_once $mailerDir . '/PHPMailer.php';
        }
    }

    public function send(string $to, string $subject, string $htmlBody, string $altBody = ''): void
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = config('mail.host');
        $mail->SMTPAuth = true;
        $mail->Username = config('mail.username');
        $mail->Password = config('mail.password');
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = config('mail.port');
        $mail->setFrom(config('mail.from'), config('mail.from_name'));
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $altBody !== '' ? $altBody : strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));
        $mail->send();
    }

    public function sendPasswordOtp(string $email, string $fname, string $otp, int $expiresMinutes = 15): void
    {
        $html = $this->render('otp-reset', [
            'fname' => $fname,
            'otp' => $otp,
            'expiresMinutes' => $expiresMinutes,
            'appUrl' => config('app.url'),
            'appName' => config('app.name'),
            'logoUrl' => config('app.url') . resource_url('images/hansi logo jpg.jpg'),
        ]);

        $this->send(
            $email,
            config('app.name') . ' — Password Reset Code',
            $html,
            "Your Lagatama Craft password reset code is {$otp}. It expires in {$expiresMinutes} minutes."
        );
    }

    /** @param list<array<string, mixed>> $cartItems */
    /** @param list<array<string, mixed>> $featuredProducts */
    public function sendWelcome(
        string $email,
        string $fname,
        array $cartItems,
        array $featuredProducts,
        array $promotions
    ): void {
        $html = $this->render('welcome', [
            'fname' => $fname,
            'cartItems' => $cartItems,
            'featuredProducts' => $featuredProducts,
            'promotions' => $promotions,
            'appUrl' => config('app.url'),
            'appName' => config('app.name'),
            'logoUrl' => config('app.url') . resource_url('images/hansi logo jpg.jpg'),
            'shopUrl' => config('app.url') . web_base() . '/home.php',
        ]);

        $this->send(
            $email,
            'Welcome to ' . config('app.name') . ' — Your offers inside',
            $html,
            "Welcome to Lagatama Craft, {$fname}! Visit our shop to explore handcrafted pieces and use your welcome offers."
        );
    }

    private function render(string $template, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require base_path('views/emails/' . $template . '.php');
        return (string) ob_get_clean();
    }
}
