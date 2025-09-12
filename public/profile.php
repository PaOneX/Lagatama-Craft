<?php

require_once __DIR__ . '/init.php';

use App\Core\Auth;
use App\Core\Database;

if (!Auth::checkUser()) {
    header('Location: index.php');
    exit;
}

$user = Auth::user();
$d = Database::fetchOne('SELECT * FROM `user` WHERE `id` = ?', [$user['id']]);

ob_start();
include base_path('views/partials/header.php');
include base_path('views/partials/navbar.php');
?>
<div class="lc-page">
    <div class="lc-page-header">
        <h1 class="lc-page-title">My Profile</h1>
        <p class="lc-page-subtitle">Manage your account details and shipping address</p>
    </div>

    <div class="lc-profile-grid">
        <div class="lc-card lc-profile-avatar">
            <img src="<?= htmlspecialchars(!empty($d['img_path']) ? '/' . ltrim($d['img_path'], '/') : resource_url('profileImg/profileImg.png')) ?>"
                 alt="Profile">
            <label class="form-label">Profile photo</label>
            <input type="file" class="form-control mb-3" id="imgUploader" accept="image/*">
            <button type="button" class="lc-btn lc-btn-outline w-100" onclick="uploadImg();">Upload Photo</button>
        </div>

        <div class="lc-card">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="fname">First Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($d['fname']) ?>" id="fname">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="lname">Last Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($d['lname']) ?>" id="lname">
                </div>
                <div class="col-12">
                    <label class="form-label" for="email">Email</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($d['email']) ?>" id="email" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="mobile">Mobile</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($d['mobile']) ?>" id="mobile">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pw">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" value="********" id="pw" disabled>
                        <button type="button" class="btn btn-outline-secondary" onclick="chnagepw();">Change</button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="fpModal4" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Current Password</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="opw">
                                <button class="btn btn-outline-secondary" onclick="showpw5();" id="spb5"><i class="bi bi-eye-slash"></i></button>
                            </div>
                            <label class="form-label">New Password</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="newpw1">
                                <button class="btn btn-outline-secondary" onclick="showpw3();" id="spb3"><i class="bi bi-eye-slash"></i></button>
                            </div>
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="renewpw1">
                                <button class="btn btn-outline-secondary" onclick="showpw4()" id="spb4"><i class="bi bi-eye-slash"></i></button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="lc-btn lc-btn-primary" onclick="resetPassword2();">Update Password</button>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3"><i class="bi bi-geo-alt me-2"></i>Shipping Address</h5>
            <div class="row g-3" id="ship">
                <div class="col-3">
                    <label class="form-label" for="no">No</label>
                    <input type="text" class="form-control" id="no" value="<?= htmlspecialchars($d['no'] ?? '') ?>">
                </div>
                <div class="col-9">
                    <label class="form-label" for="line1">Address Line 1</label>
                    <input type="text" class="form-control" id="line1" value="<?= htmlspecialchars($d['line_1'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label" for="line2">Address Line 2</label>
                    <input type="text" class="form-control" id="line2" value="<?= htmlspecialchars($d['line_2'] ?? '') ?>">
                </div>
            </div>

            <button type="button" class="lc-btn lc-btn-primary w-100 mt-4" onclick="updateData();">Save Changes</button>
        </div>
    </div>
</div>
<?php include base_path('views/partials/footer.php'); ?>
<?php
$content = ob_get_clean();
$pageScripts = ['assets/js/shop/auth.js', 'assets/js/admin/management.js'];
include base_path('views/layouts/customer.php');
