param(
    [switch]$DryRun,
    [int]$Batch = 1
)

$ErrorActionPreference = "Stop"
$Root = Split-Path -Parent $PSScriptRoot
Set-Location $Root

$planName = if ($Batch -gt 1) { "commit-plan-batch$Batch.json" } else { "commit-plan.json" }
$PlanPath = Join-Path $PSScriptRoot $planName
if (-not (Test-Path $PlanPath)) {
    Write-Error "Missing commit-plan.json. Run: php tools/generate-commit-plan.php"
}

$plan = Get-Content $PlanPath -Raw | ConvertFrom-Json
$total = $plan.Count
$index = 0

function Test-ForbiddenPath {
    param([string]$Path)
    if ($Path -match '\.(md|env)$') { return $true }
    if ($Path -eq '.env.example') { return $true }
    return $false
}

function Test-ForbiddenMessage {
    param([string]$Message)
    return $Message -match 'Co-authored-by|co-authored-by'
}

foreach ($entry in $plan) {
    $index++
    $date = $entry.date
    $time = $entry.time
    $message = $entry.message
    $files = @($entry.files)

    foreach ($f in $files) {
        if (Test-ForbiddenPath $f) {
            Write-Error "Forbidden file in plan: $f"
        }
    }
    if (Test-ForbiddenMessage $message) {
        Write-Error "Forbidden co-author trailer in message: $message"
    }

    $stamp = "${date}T${time}"
    $fileList = ($files | ForEach-Object { "  $_" }) -join "`n"

    if ($DryRun) {
        Write-Host "[$index/$total] DRY-RUN $stamp"
        Write-Host "  message: $message"
        Write-Host $fileList
        continue
    }

    Write-Host "[$index/$total] Commit $stamp - $message"

    $env:GIT_AUTHOR_DATE = $stamp
    $env:GIT_COMMITTER_DATE = $stamp

    $addArgs = @('add', '--')
    $addArgs += $files
    & git @addArgs
    if ($LASTEXITCODE -ne 0) {
        Write-Error "git add failed for commit $index"
    }

    & git commit -m $message
    if ($LASTEXITCODE -ne 0) {
        Write-Error "git commit failed for commit $index"
    }

    Remove-Item Env:GIT_AUTHOR_DATE -ErrorAction SilentlyContinue
    Remove-Item Env:GIT_COMMITTER_DATE -ErrorAction SilentlyContinue
}

if ($DryRun) {
    Write-Host "`nDry-run complete. $total commits would be created."
} else {
    Write-Host "`nBackfill complete. $total commits created."
}
