<div class="lc-topbar">
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <?php if (\App\Core\Auth::checkUser()): $data = \App\Core\Auth::user(); ?>
                    <i class="bi bi-person-circle"></i>
                    Hi, <strong><?= htmlspecialchars($data['fname']) ?></strong>
                    <span class="lc-topbar-divider">|</span>
                    <span class="signout" onclick="signout();">Sign out</span>
                <?php else: ?>
                    <a href="index.php"><i class="bi bi-person-circle"></i> Sign in or Register</a>
                <?php endif; ?>
            </div>
            <div class="lc-theme-toggle">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="themeSwitch" onchange="themeChange();">
                    <label class="form-check-label" for="themeSwitch">Dark mode</label>
                </div>
            </div>
        </div>
    </div>
</div>
