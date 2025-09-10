<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;
use App\Core\Response;
use App\Core\Security;
use App\Models\Lookup;

Security::preventCaching();

if (Auth::checkUser()) {
    Response::redirect('home.php');
}

$email = $_COOKIE['remember_email'] ?? '';
$password = '';
$genders = Lookup::genders();
$googleClientId = config('google.client_id', '');

ob_start();
?>
<div class="lc-auth-page">
    <div class="lc-auth-shell">
        <aside class="lc-auth-brand">
            <div class="lc-auth-brand-inner">
                <img src="<?= resource_url('images/hansi logo jpg.jpg') ?>" alt="Lagatama Craft" class="lc-auth-brand-logo">
                <h2>Lagatama Craft</h2>
                <p>Discover handcrafted pieces made with passion. Join our community and enjoy exclusive member offers.</p>
                <ul class="lc-auth-perks">
                    <li><i class="bi bi-gift"></i> Welcome offers for new members</li>
                    <li><i class="bi bi-truck"></i> Island-wide delivery</li>
                    <li><i class="bi bi-heart"></i> Curated artisan collections</li>
                </ul>
            </div>
        </aside>

        <div class="lc-auth-forms">
            <div class="lc-auth-card d-none" id="signUp_Box">
                <div class="lc-auth-header">
                    <h1>Create Account</h1>
                    <p>Join Lagatama Craft today</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="fname"><i class="bi bi-person"></i> First Name</label>
                        <input type="text" class="form-control" id="fname" placeholder="John">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="lname"><i class="bi bi-person"></i> Last Name</label>
                        <input type="text" class="form-control" id="lname" placeholder="Doe">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email"><i class="bi bi-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email" placeholder="you@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password"><i class="bi bi-lock"></i> Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Min. 5 characters">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="mobile"><i class="bi bi-phone"></i> Mobile</label>
                        <input type="text" class="form-control" id="mobile" placeholder="07XXXXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="gender"><i class="bi bi-gender-ambiguous"></i> Gender</label>
                        <select class="form-select" id="gender">
                            <?php foreach ($genders as $g): ?>
                                <option value="<?= (int) $g['id'] ?>"><?= htmlspecialchars($g['gender']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 d-grid">
                        <button class="lc-btn lc-btn-primary lc-btn-lg" type="button" onclick="signUp();">
                            <i class="bi bi-person-plus"></i> Sign Up
                        </button>
                    </div>

                    <div class="col-12">
                        <div class="lc-auth-divider"><span>or continue with</span></div>
                    </div>
                    <div class="col-12 d-grid" id="googleSignUpWrap">
                        <div id="googleSignUpBtn" class="lc-google-btn-host"></div>
                    </div>

                    <div class="col-12 text-center">
                        <button class="lc-auth-link-btn" type="button" onclick="changeView();">
                            Already have an account? <strong>Sign In</strong>
                        </button>
                    </div>
                </div>
            </div>

            <div class="lc-auth-card" id="signIn_Box">
                <div class="lc-auth-header">
                    <h1>Welcome Back</h1>
                    <p>Sign in to your account</p>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="email1"><i class="bi bi-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email1" value="<?= htmlspecialchars($email) ?>" placeholder="you@example.com">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="password1"><i class="bi bi-lock"></i> Password</label>
                        <div class="input-group lc-input-group">
                            <input type="password" class="form-control" id="password1" value="<?= htmlspecialchars($password) ?>" placeholder="Your password">
                            <button class="btn btn-outline-secondary" type="button" onclick="showPassword2();" id="sp" aria-label="Show password">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2" id="rememberme">
                        <label class="form-check-label" for="rememberme">Remember me</label>
                    </div>
                    <div class="col-6 text-end">
                        <a href="#" class="lc-auth-forgot" onclick="forgotPassword(); return false;">Forgot password?</a>
                    </div>

                    <div class="col-12 d-grid">
                        <button class="lc-btn lc-btn-primary lc-btn-lg" type="button" onclick="signIn();">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </button>
                    </div>

                    <div class="col-12">
                        <div class="lc-auth-divider"><span>or continue with</span></div>
                    </div>
                    <div class="col-12 d-grid" id="googleSignInWrap">
                        <div id="googleSignInBtn" class="lc-google-btn-host"></div>
                    </div>

                    <div class="col-12 text-center">
                        <button class="lc-auth-link-btn" type="button" onclick="changeView();">
                            New here? <strong>Create an account</strong>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="fpmodal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content lc-fp-modal">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title">Reset Password</h5>
                        <p class="text-muted small mb-0">Enter the OTP sent to your registered email</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="vcode">Verification Code (OTP)</label>
                            <input type="text" class="form-control text-center lc-otp-input" id="vcode" maxlength="6" placeholder="000000" inputmode="numeric">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <div class="input-group lc-input-group">
                                <input type="password" class="form-control" id="np">
                                <button class="btn btn-outline-secondary" type="button" onclick="showPassword3();" id="npb"><i class="bi bi-eye-slash-fill"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group lc-input-group">
                                <input type="password" class="form-control" id="rnp">
                                <button class="btn btn-outline-secondary" type="button" onclick="showPassword4();" id="rnpb"><i class="bi bi-eye-slash-fill"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="lc-btn lc-btn-primary" onclick="resetPassword();">Reset Password</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>window.GOOGLE_CLIENT_ID = <?= json_encode($googleClientId) ?>;</script>
<?php if ($googleClientId !== ''): ?>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title = 'Sign In | Lagatama Craft';
$pageScripts = ['assets/js/shop/auth.js'];
include base_path('views/layouts/customer.php');
