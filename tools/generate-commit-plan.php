<?php

/**
 * Build commit-plan.json for backdated feature commits.
 *
 * Usage:
 *   php tools/generate-commit-plan.php
 *   php tools/generate-commit-plan.php --batch=2
 *
 * Batch 1 (default): Aug–Sep 2025 empty days.
 * Batch 2: other GitHub-empty days (Jul 17–31 + Oct 2025), excluding Aug–Sep.
 */

$root = dirname(__DIR__);
chdir($root);

const TIME_MORNING = '10:30:00';
const TIME_EVENING = '17:45:00';

$batch = 1;
foreach ($argv ?? [] as $arg) {
    if (str_starts_with($arg, '--batch=')) {
        $batch = (int) substr($arg, 8);
    }
}

function relativize(string $fullPath): string
{
    $root = str_replace('\\', '/', realpath(dirname(__DIR__)));
    $full = str_replace('\\', '/', realpath($fullPath) ?: $fullPath);
    if (str_starts_with($full, $root)) {
        return ltrim(substr($full, strlen($root)), '/');
    }
    return str_replace('\\', '/', $fullPath);
}

function isExcluded(string $path): bool
{
    $path = str_replace('\\', '/', $path);
    if (preg_match('/\.(md|env)$/i', $path)) {
        return true;
    }
    if ($path === '.env.example' || $path === 'README.md') {
        return true;
    }
    if ($path === 'tools/test_google.php') {
        return true;
    }
    // Duplicate of public/assets/
    if (str_starts_with($path, 'assets/')) {
        return true;
    }
    // Runtime rate-limit cache
    if (preg_match('#^storage/rate_limit/.+\.json$#', $path)) {
        return true;
    }
    return false;
}

function collectFiles(): array
{
    $output = [];
    exec('git status --porcelain', $lines, $code);
    if ($code !== 0) {
        fwrite(STDERR, "git status failed\n");
        exit(1);
    }

    foreach ($lines as $line) {
        $status = substr($line, 0, 2);
        $path   = trim(substr($line, 3), '"');
        $path   = str_replace('\\', '/', $path);

        if (isExcluded($path)) {
            continue;
        }

        if ($status === ' D' || $status === 'D ' || $status[0] === 'D') {
            $output[] = $path;
            continue;
        }

        if (is_dir($path)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (!$file->isFile()) {
                    continue;
                }
                $rel = relativize($file->getPathname());
                if (!isExcluded($rel)) {
                    $output[] = $rel;
                }
            }
        } elseif (file_exists($path)) {
            $output[] = $path;
        }
    }

    return array_values(array_unique($output));
}

function featureBucket(string $path): int
{
    $path = str_replace('\\', '/', $path);

    if (preg_match('#^(bootstrap\.php|composer\.json|config/|database/)#', $path)) {
        return 1;
    }
    if (str_starts_with($path, 'app/Core/')) {
        return 2;
    }
    if (str_starts_with($path, 'app/Models/')) {
        return 3;
    }
    if (str_starts_with($path, 'app/Services/')) {
        return 4;
    }
    if (preg_match('#^(public/init\.php|public/\.htaccess|\.htaccess|csrf\.php|public/csrf\.php|\.gitignore)$#', $path)) {
        return 5;
    }
    if (str_starts_with($path, 'public/api/auth/')) {
        return 6;
    }
    if (str_starts_with($path, 'public/api/cart/')) {
        return 7;
    }
    if (str_starts_with($path, 'public/api/checkout/') || $path === 'api/checkout/notify.php' || $path === 'api/checkout/status.php') {
        return 8;
    }
    if (str_starts_with($path, 'public/api/products/')) {
        return 9;
    }
    if (str_starts_with($path, 'public/api/user/')) {
        return 10;
    }
    if (str_starts_with($path, 'public/api/admin/') || str_starts_with($path, 'public/api/bootstrap.php')) {
        return 11;
    }
    if (preg_match('#^public/(home|cart|profile|orderHistory|invoice|aboutUs|singleProductView|index|router)\.php$#', $path)) {
        return 12;
    }
    if (str_starts_with($path, 'public/admin/')) {
        return 13;
    }
    if (str_starts_with($path, 'views/')) {
        return 14;
    }
    if (preg_match('#^public/assets/(js|css)/#', $path)) {
        return 15;
    }
    if (preg_match('#^(Exception|OAuth|PHPMailer|POP3|SMTP)\.php$#', $path) || str_starts_with($path, 'public/assets/vendor/phpmailer/')) {
        return 16;
    }
    if (preg_match('#^(public/)?[a-zA-Z]+Process\.php$#', $path) || preg_match('#^public/(admin|landing|save|search|remove|update|load|payment|shipping|productReg|brand|category|clr|size|userSearch|advSearch|addtoCart|buynow|checkout|changePassword|forgotPassword|resetPassword|signIn|signOut|signUp|googleSignIn|adminGoogleSignIn|adminSignIn|adminSignOut|profileImgUpload|landingOffer).*\.php$#', $path)) {
        return 17;
    }
    if (str_starts_with($path, 'resources/') || str_starts_with($path, 'tools/') || str_starts_with($path, 'storage/')) {
        return 18;
    }
    if (preg_match('#\.php$#', $path) && !str_starts_with($path, 'public/')) {
        return 17;
    }
    return 18;
}

function bucketMessage(int $bucket): string
{
    return match ($bucket) {
        1  => 'Add project foundation and configuration',
        2  => 'Add core application layer',
        3  => 'Add domain models',
        4  => 'Add application services',
        5  => 'Add public bootstrap and security helpers',
        6  => 'Wire authentication API endpoints',
        7  => 'Wire cart API endpoints',
        8  => 'Add checkout and PayHere integration',
        9  => 'Wire product search API endpoints',
        10 => 'Wire user profile API endpoints',
        11 => 'Wire admin management API endpoints',
        12 => 'Add customer storefront pages',
        13 => 'Add admin dashboard pages',
        14 => 'Add view templates and layouts',
        15 => 'Add frontend assets',
        16 => 'Move PHPMailer into vendor directory',
        17 => 'Add root compatibility wrappers',
        18 => 'Add resources and maintenance tools',
        default => 'Update project files',
    };
}

function sortByFeature(array $files): array
{
    usort($files, function (string $a, string $b): int {
        $ba = featureBucket($a);
        $bb = featureBucket($b);
        if ($ba !== $bb) {
            return $ba <=> $bb;
        }
        return strcmp($a, $b);
    });
    return $files;
}

function fetchGithubEmptyDays(string $from, string $to, ?string $excludeStart = null, ?string $excludeEnd = null): array
{
    $query = '{ viewer { contributionsCollection { contributionCalendar { weeks { contributionDays { date contributionCount } } } } } }';
    $cmd   = 'gh api graphql -f query=' . escapeshellarg($query) . ' 2>&1';
    $raw   = shell_exec($cmd);
    if (!$raw) {
        fwrite(STDERR, "Failed to fetch GitHub contribution calendar\n");
        exit(1);
    }
    $data = json_decode($raw, true);
    $days = [];
    foreach ($data['data']['viewer']['contributionsCollection']['contributionCalendar']['weeks'] ?? [] as $week) {
        foreach ($week['contributionDays'] ?? [] as $day) {
            if (($day['contributionCount'] ?? 1) !== 0) {
                continue;
            }
            $date = $day['date'];
            if ($date < $from || $date > $to) {
                continue;
            }
            if ($excludeStart && $excludeEnd && $date >= $excludeStart && $date <= $excludeEnd) {
                continue;
            }
            $days[] = $date;
        }
    }
    sort($days);
    return array_values(array_unique($days));
}

function scheduleDays(int $batch): array
{
    if ($batch === 2) {
        return fetchGithubEmptyDays('2025-07-17', '2025-12-31', '2025-08-01', '2025-09-30');
    }
    $days    = [];
    $current = new DateTimeImmutable('2025-08-01');
    $end     = new DateTimeImmutable('2025-09-30');
    while ($current <= $end) {
        $days[] = $current->format('Y-m-d');
        $current = $current->modify('+1 day');
    }
    return $days;
}

function splitDayFiles(array $dayFiles): array
{
    $count = count($dayFiles);
    if ($count === 0) {
        return [];
    }
    if ($count === 1) {
        return [[$dayFiles, TIME_MORNING]];
    }
    if ($count === 2) {
        return [
            [[$dayFiles[0]], TIME_MORNING],
            [[$dayFiles[1]], TIME_EVENING],
        ];
    }
    // 3 files: 2 morning, 1 evening
    return [
        [[$dayFiles[0], $dayFiles[1]], TIME_MORNING],
        [[$dayFiles[2]], TIME_EVENING],
    ];
}

function buildPlan(array $files, int $batch): array
{
    $files = sortByFeature($files);
    $days  = scheduleDays($batch);
    $plan  = [];
    $index = 0;
    $total = count($files);

    foreach ($days as $day) {
        if ($index >= $total) {
            break;
        }

        $remaining = $total - $index;
        $dayCount  = min(3, $remaining);
        // Prefer 2 files most days, 3 occasionally when backlog is large
        if ($dayCount === 3 && ($remaining < 10 || (int) date('j', strtotime($day)) % 4 !== 0)) {
            $dayCount = min(2, $remaining);
        }

        $dayFiles = array_slice($files, $index, $dayCount);
        $index += count($dayFiles);

        $bucket  = featureBucket($dayFiles[0]);
        $message = bucketMessage($bucket);

        foreach (splitDayFiles($dayFiles) as [$batch, $time]) {
            $plan[] = [
                'date'    => $day,
                'time'    => $time,
                'message' => $message,
                'files'   => $batch,
            ];
        }
    }

    if ($index < $total) {
        $leftover = array_slice($files, $index);
        fwrite(STDERR, sprintf(
            "Warning: %d files did not fit in schedule (first leftover: %s)\n",
            count($leftover),
            $leftover[0]
        ));
    }

    return $plan;
}

$files = collectFiles();
$plan  = buildPlan($files, $batch);

$outPath = $root . '/tools/commit-plan' . ($batch > 1 ? "-batch{$batch}" : '') . '.json';
file_put_contents($outPath, json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

$commitCount = count($plan);
$fileCount   = array_sum(array_map(fn ($c) => count($c['files']), $plan));
$dayCount    = count(array_unique(array_column($plan, 'date')));

echo "Generated {$outPath}\n";
echo "Batch: {$batch}\n";
echo "Commits: {$commitCount}\n";
echo "Files scheduled: {$fileCount} / " . count($files) . "\n";
echo "Days used: {$dayCount}\n";
