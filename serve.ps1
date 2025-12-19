# Lagatama Craft — local dev server (document root = public/)
$Host = if ($env:LAGATAMA_HOST) { $env:LAGATAMA_HOST } else { "127.0.0.1" }
$Port = if ($env:LAGATAMA_PORT) { $env:LAGATAMA_PORT } else { "8888" }
$Root = Join-Path $PSScriptRoot "public"

Write-Host "Lagatama Craft: http://${Host}:${Port}/" -ForegroundColor Green
Write-Host "Document root: $Root" -ForegroundColor DarkGray
Set-Location $PSScriptRoot
php -S "${Host}:${Port}" -t public public/router.php
