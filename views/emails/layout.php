<?php
/** @var string $content */
/** @var string $appName */
/** @var string $logoUrl */
/** @var string $appUrl */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?></title>
</head>
<body style="margin:0;padding:0;background:#f4f0ea;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#1f1a14;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f0ea;padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 32px rgba(31,26,20,0.08);">
                <tr>
                    <td style="background:linear-gradient(135deg,#1f1a14 0%,#3d3428 55%,#a67c00 100%);padding:28px 32px;text-align:center;">
                        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars($appName) ?>" width="64" height="64" style="border-radius:50%;border:3px solid rgba(255,255,255,0.25);object-fit:cover;">
                        <p style="margin:12px 0 0;font-size:22px;font-weight:700;color:#ffffff;letter-spacing:-0.02em;"><?= htmlspecialchars($appName) ?></p>
                        <p style="margin:4px 0 0;font-size:13px;color:rgba(255,255,255,0.75);">Handcrafted with care</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px;">
                        <?= $content ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px 32px 28px;border-top:1px solid #e5e0d8;text-align:center;">
                        <p style="margin:0 0 8px;font-size:12px;color:#6b6560;">&copy; <?= date('Y') ?> <?= htmlspecialchars($appName) ?>. All rights reserved.</p>
                        <p style="margin:0;font-size:12px;">
                            <a href="<?= htmlspecialchars($appUrl) ?>" style="color:#a67c00;text-decoration:none;">Visit our shop</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
