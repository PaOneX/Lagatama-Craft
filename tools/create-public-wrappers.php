<?php

/**
 * Create thin wrappers in public/ for legacy *Process.php URLs.
 * Run: php tools/create-public-wrappers.php
 */

$root = dirname(__DIR__);
$public = $root . '/public';

$wrappers = [
    'signInProcess.php' => 'api/auth/signInProcess.php',
    'signUpProcess.php' => 'api/auth/signUpProcess.php',
    'signOutProcess.php' => 'api/auth/signOutProcess.php',
    'forgotPasswordProcess.php' => 'api/auth/forgotPasswordProcess.php',
    'resetPasswordProcess.php' => 'api/auth/resetPasswordProcess.php',
    'changePasswordProcess.php' => 'api/auth/changePasswordProcess.php',
    'adminSignInProcess.php' => 'api/auth/adminSignInProcess.php',
    'adminSignOutProcess.php' => 'api/auth/adminSignOutProcess.php',
    'addtoCartProcess.php' => 'api/cart/addtoCartProcess.php',
    'loadCartProcess.php' => 'api/cart/loadCartProcess.php',
    'updateCartQtyProcess.php' => 'api/cart/updateCartQtyProcess.php',
    'removeCartProcess.php' => 'api/cart/removeCartProcess.php',
    'paymentProcess.php' => 'api/checkout/paymentProcess.php',
    'checkoutProcess.php' => 'api/checkout/checkoutProcess.php',
    'buynowProcess.php' => 'api/checkout/buynowProcess.php',
    'loadProductProcess.php' => 'api/products/loadProductProcess.php',
    'searchProductProcess.php' => 'api/products/searchProductProcess.php',
    'advSearchProductProcess.php' => 'api/products/advSearchProductProcess.php',
    'shippingAddressProcess.php' => 'api/user/shippingAddressProcess.php',
    'updateDataProcess.php' => 'api/user/updateDataProcess.php',
    'profileImgUploadProcess.php' => 'api/user/profileImgUploadProcess.php',
    'loadUserProcess.php' => 'api/admin/loadUserProcess.php',
    'userSearchProcess.php' => 'api/admin/userSearchProcess.php',
    'updateUserStatusProcess.php' => 'api/admin/updateUserStatusProcess.php',
    'brandRegisterProcess.php' => 'api/admin/brandRegisterProcess.php',
    'categoryRegisterProcess.php' => 'api/admin/categoryRegisterProcess.php',
    'clrRegisterProcess.php' => 'api/admin/clrRegisterProcess.php',
    'sizeRegisterProcess.php' => 'api/admin/sizeRegisterProcess.php',
    'productRegProcess.php' => 'api/admin/productRegProcess.php',
    'updateStockProcess.php' => 'api/admin/updateStockProcess.php',
    'loadChartProcess.php' => 'api/admin/loadChartProcess.php',
    'loadChartProcess2.php' => 'api/admin/loadChartProcess2.php',
    'loadChartProcess3.php' => 'api/admin/loadChartProcess3.php',
    'saveLandingHeroProcess.php' => 'api/admin/saveLandingHeroProcess.php',
    'landingOfferSaveProcess.php' => 'api/admin/landingOfferSaveProcess.php',
    'landingOfferDeleteProcess.php' => 'api/admin/landingOfferDeleteProcess.php',
    'landingOfferToggleProcess.php' => 'api/admin/landingOfferToggleProcess.php',
    'googleSignInProcess.php' => 'api/auth/googleSignInProcess.php',
    'adminGoogleSignInProcess.php' => 'api/auth/adminGoogleSignInProcess.php',
];

// Create resources junction for web access (Windows)
$publicResources = dirname(__DIR__) . '/public/resources';
$rootResources = dirname(__DIR__) . '/resources';
if (!file_exists($publicResources) && is_dir($rootResources) && PHP_OS_FAMILY === 'Windows') {
    exec('cmd /c mklink /J ' . escapeshellarg($publicResources) . ' ' . escapeshellarg($rootResources));
}

foreach ($wrappers as $name => $target) {
    $path = $public . '/' . $name;
    $content = "<?php\n\nrequire __DIR__ . '/{$target}';\n";
    file_put_contents($path, $content);
    echo "Created public/{$name}\n";
}

echo "Done. " . count($wrappers) . " wrappers created.\n";
