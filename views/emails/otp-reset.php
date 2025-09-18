<?php
ob_start();
?>
<p style="margin:0 0 8px;font-size:15px;color:#6b6560;">Hi <?= htmlspecialchars($fname) ?>,</p>
<h1 style="margin:0 0 16px;font-size:24px;font-weight:700;color:#1f1a14;letter-spacing:-0.02em;">Reset your password</h1>
<p style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#3d3428;">
    We received a request to reset the password for your <?= htmlspecialchars($appName) ?> account.
    Enter the verification code below on the reset password screen.
</p>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:24px;">
    <tr>
        <td align="center" style="background:#faf8f5;border:2px dashed #d4c4a0;border-radius:12px;padding:24px;">
            <p style="margin:0 0 8px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#6b6560;">Your OTP Code</p>
            <p style="margin:0;font-size:36px;font-weight:700;letter-spacing:0.25em;color:#a67c00;"><?= htmlspecialchars($otp) ?></p>
        </td>
    </tr>
</table>
<p style="margin:0 0 8px;font-size:14px;color:#6b6560;">
    This code expires in <strong style="color:#1f1a14;"><?= (int) $expiresMinutes ?> minutes</strong>.
</p>
<p style="margin:0;font-size:14px;color:#6b6560;line-height:1.6;">
    If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.
</p>
<?php
$content = ob_get_clean();
$appName = $appName ?? 'Lagatama Craft';
require __DIR__ . '/layout.php';
