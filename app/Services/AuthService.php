<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;
use App\Core\Http;
use App\Core\Security;
use App\Core\Validator;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

class AuthService
{
    private MailService $mail;

    public function __construct()
    {
        $this->mail = new MailService();
    }

    public function signUp(array $input): string
    {
        $fname = trim($input['f'] ?? '');
        $lname = trim($input['l'] ?? '');
        $email = trim($input['e'] ?? '');
        $mobile = trim($input['m'] ?? '');
        $password = $input['p'] ?? '';
        $gender = (int) ($input['g'] ?? 1);

        if (!Validator::name($fname)) {
            return 'Please enter your First Name.';
        }
        if (!Validator::name($lname)) {
            return 'Please enter your Last Name.';
        }
        if (!Validator::email($email)) {
            return 'Your Email Address is Invalid.';
        }
        if (!Validator::password($password)) {
            return 'Password must contain 5 to 45 Characters.';
        }
        if (!Validator::mobile($mobile)) {
            return 'Your mobile number is invalid.';
        }

        if (User::findByEmailOrMobile($email, $mobile)) {
            return 'User with the same Email Address or same Mobile Number already exists.';
        }

        $date = (new \DateTime('now', new \DateTimeZone(config('app.timezone'))))->format('Y-m-d H:i:s');
        $userId = User::create([
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'mobile' => $mobile,
            'joined_date' => $date,
            'gender_id' => $gender,
            'status' => 1,
            'user_type_id' => 2,
        ]);

        $this->sendWelcomeEmail($email, $fname, $userId);

        return 'success';
    }

    public function signIn(string $email, string $password, bool $remember): string
    {
        if (!Security::rateLimit('sign_in')) {
            return 'Too many attempts. Please try again later.';
        }

        if ($email === '') {
            return 'Please Enter Your Email Address.';
        }
        if ($password === '') {
            return 'Please Enter Your Password.';
        }

        $user = User::findByEmail($email);
        if ($user === null || !$this->verifyPassword($password, $user['password'])) {
            return 'Invalid Username or Password.';
        }

        if ((int) $user['status'] !== 1) {
            return 'Account Deactivated.Please Login to Continue.';
        }

        if (!password_get_info($user['password'])['algo']) {
            User::updatePassword((int) $user['id'], password_hash($password, PASSWORD_DEFAULT));
            $user = User::findById((int) $user['id']);
        }

        Auth::loginUser($user);

        if ($remember) {
            setcookie('remember_email', $email, time() + (60 * 60 * 24 * 365), '/');
        } else {
            setcookie('remember_email', '', -1, '/');
        }

        return 'success';
    }

    public function googleSignIn(string $credential): string
    {
        if (!Security::rateLimit('google_sign_in')) {
            return 'Too many attempts. Please try again later.';
        }

        $clientId = config('google.client_id');
        if ($clientId === '') {
            return 'Google Sign-In is not configured.';
        }

        $verifyError = null;
        $payload = $this->verifyGoogleToken($credential, $clientId, $verifyError);
        if ($payload === null) {
            return $verifyError ?? 'Google authentication failed.';
        }

        $googleId = $payload['sub'] ?? '';
        $email = trim($payload['email'] ?? '');
        $fname = trim($payload['given_name'] ?? 'Google');
        $lname = trim($payload['family_name'] ?? 'User');

        if ($email === '' || $googleId === '') {
            return 'Google account email is required.';
        }

        $user = User::findByGoogleId($googleId) ?? User::findByEmail($email);
        $isNew = false;

        if ($user === null) {
            $isNew = true;
            $date = (new \DateTime('now', new \DateTimeZone(config('app.timezone'))))->format('Y-m-d H:i:s');
            $userId = User::create([
                'fname' => Validator::name($fname) ? $fname : 'Google',
                'lname' => Validator::name($lname) ? $lname : 'User',
                'email' => $email,
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'mobile' => $this->placeholderMobile($googleId),
                'joined_date' => $date,
                'gender_id' => 3,
                'status' => 1,
                'user_type_id' => 2,
            ]);
            User::linkGoogleId($userId, $googleId);
            $user = User::findById($userId);
            $this->sendWelcomeEmail($email, $user['fname'], $userId);
        } else {
            if (empty($user['google_id'])) {
                User::linkGoogleId((int) $user['id'], $googleId);
            }
            if ((int) $user['status'] !== 1) {
                return 'Account Deactivated.Please Login to Continue.';
            }
        }

        Auth::loginUser($user);
        return $isNew ? 'registered' : 'success';
    }

    public function adminSignIn(string $email, string $password): string
    {
        if (!Security::rateLimit('admin_sign_in')) {
            return 'Too many attempts. Please try again later.';
        }

        if ($email === '' || $password === '') {
            return 'Please Enter Your Email Address.';
        }

        $user = User::findByEmail($email);
        if ($user === null || !$this->verifyPassword($password, $user['password'])) {
            return 'Invalid Username OR Password';
        }

        if ((int) $user['user_type_id'] !== 1) {
            return "You Don't Have an Admin Account";
        }

        if ((int) $user['status'] !== 1) {
            return 'Account Deactivated.Please Login to Continue.';
        }

        if (!password_get_info($user['password'])['algo']) {
            User::updatePassword((int) $user['id'], password_hash($password, PASSWORD_DEFAULT));
            $user = User::findById((int) $user['id']);
        }

        Auth::loginAdmin($user);
        return 'Success';
    }

    public function adminGoogleSignIn(string $credential): string
    {
        if (!Security::rateLimit('admin_google_sign_in')) {
            return 'Too many attempts. Please try again later.';
        }

        $clientId = config('google.client_id');
        if ($clientId === '') {
            return 'Google Sign-In is not configured.';
        }

        $verifyError = null;
        $payload = $this->verifyGoogleToken($credential, $clientId, $verifyError);
        if ($payload === null) {
            return $verifyError ?? 'Google authentication failed.';
        }

        $googleId = $payload['sub'] ?? '';
        $email = trim($payload['email'] ?? '');

        if ($email === '' || $googleId === '') {
            return 'Google account email is required.';
        }

        $user = User::findByGoogleId($googleId) ?? User::findByEmail($email);
        if ($user === null) {
            return "You Don't Have an Admin Account";
        }

        if ((int) $user['user_type_id'] !== 1) {
            return "You Don't Have an Admin Account";
        }

        if ((int) $user['status'] !== 1) {
            return 'Account Deactivated.Please Login to Continue.';
        }

        if (empty($user['google_id'])) {
            User::linkGoogleId((int) $user['id'], $googleId);
            $user = User::findById((int) $user['id']);
        }

        Auth::loginAdmin($user);
        return 'Success';
    }

    public function forgotPassword(string $email): string
    {
        if (!Security::rateLimit('forgot_password')) {
            return 'Too many attempts. Please try again later.';
        }

        if ($email === '') {
            return 'Please enter your Email Address in Email Field.';
        }

        if (!Validator::email($email)) {
            return 'Invalid Email Address.';
        }

        $user = User::findByEmail($email);
        if ($user === null) {
            return 'Invalid Email Address.';
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = (new \DateTime('now', new \DateTimeZone(config('app.timezone'))))
            ->modify('+15 minutes')
            ->format('Y-m-d H:i:s');

        User::updateVerificationCode($email, $otp, $expiresAt);

        try {
            $this->mail->sendPasswordOtp($email, $user['fname'], $otp);
            return 'Success';
        } catch (\Throwable) {
            return 'Verification code sending failed. Please check your email settings.';
        }
    }

    public function resetPassword(string $email, string $newPw, string $retypePw, string $code): string
    {
        if ($newPw !== $retypePw) {
            return 'Password does not match.';
        }
        if (!Validator::password($newPw)) {
            return 'Password must contain 5 to 45 Characters.';
        }

        if (!User::resetPassword($email, $code, password_hash($newPw, PASSWORD_DEFAULT))) {
            return 'Invalid Email Address or Verification Code';
        }

        return 'success';
    }

    public function changePassword(int $userId, string $current, string $newPw, string $retype): string
    {
        $user = User::findById($userId);
        if ($user === null) {
            return 'error';
        }

        $stored = $user['password'];
        $valid = password_get_info($stored)['algo']
            ? password_verify($current, $stored)
            : hash_equals($stored, $current);

        if (!$valid) {
            return 'Incorrect old password';
        }
        if ($newPw !== $retype) {
            return 'Password does not match.';
        }
        if (!Validator::password($newPw)) {
            return 'Password must contain 5 to 45 Characters.';
        }

        User::updatePassword($userId, password_hash($newPw, PASSWORD_DEFAULT));
        return 'success';
    }

    private function sendWelcomeEmail(string $email, string $fname, int $userId): void
    {
        try {
            $cartItems = Cart::getByUserId($userId);
            $featured = Product::featured(3);
            $promotions = config('promotions', []);
            $this->mail->sendWelcome($email, $fname, $cartItems, $featured, $promotions);
        } catch (\Throwable) {
            // Registration should succeed even if welcome email fails
        }
    }

    /** @return array<string, string>|null */
    private function verifyGoogleToken(string $credential, string $clientId, ?string &$error = null): ?array
    {
        if ($credential === '') {
            $error = 'Google sign-in was cancelled or did not return a token.';
            return null;
        }

        if (!Http::canReachHttps()) {
            $error = 'Google Sign-In requires the PHP openssl extension (or curl). Enable extension=openssl in php.ini and restart the PHP server.';
            return null;
        }

        $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($credential);
        $response = Http::get($url, 10, true);
        if ($response === null) {
            $error = 'Could not verify Google token. Check that this server can reach https://oauth2.googleapis.com.';
            return null;
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            $error = 'Google authentication failed.';
            return null;
        }

        if (isset($data['error_description'])) {
            $error = 'Google authentication failed.';
            return null;
        }

        if (($data['aud'] ?? '') !== $clientId) {
            $error = 'Google client ID mismatch. Confirm GOOGLE_CLIENT_ID in .env matches your OAuth client and authorized JavaScript origin.';
            return null;
        }

        if (($data['email_verified'] ?? 'false') !== 'true' && ($data['email_verified'] ?? false) !== true) {
            $error = 'Google account email is not verified.';
            return null;
        }

        return $data;
    }

    private function placeholderMobile(string $googleId): string
    {
        return str_pad(substr(preg_replace('/\D/', '', $googleId), -9), 10, '0', STR_PAD_LEFT);
    }

    private function verifyPassword(string $plain, string $stored): bool
    {
        if (password_get_info($stored)['algo']) {
            return password_verify($plain, $stored);
        }
        return hash_equals($stored, $plain);
    }
}
