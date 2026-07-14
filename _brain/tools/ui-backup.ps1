# Backs up a UI file (CSS/PHP template) before it is edited, so a failed layout change can be
# reverted instantly. Run from the site root.
#
# Usage:
#   powershell -File _brain\tools\ui-backup.ps1 -Path "wp-content\themes\your-theme\style.css"
#
# Backups are stored under _brain\ui_backups\<relative-path>\<timestamp>__<filename>
# Nothing is ever overwritten — every backup is timestamped, so you can restore any prior version.

param(
    [Parameter(Mandatory = $true)]
    [string]$Path
)

if (-not (Test-Path $Path)) {
    Write-Error "File not found: $Path"
    exit 1
}

$resolved = Resolve-Path $Path
$root     = Resolve-Path "$PSScriptRoot\..\.."
$relative = $resolved.Path.Substring($root.Path.Length).TrimStart('\')
$backupDir = Join-Path "$PSScriptRoot\..\ui_backups" (Split-Path $relative -Parent)

if (-not (Test-Path $backupDir)) {
    New-Item -ItemType Directory -Force -Path $backupDir | Out-Null
}

$timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
$fileName  = Split-Path $relative -Leaf
$backupPath = Join-Path $backupDir "$timestamp`__$fileName"

Copy-Item -Path $resolved -Destination $backupPath -Force
Write-Host "Backed up: $relative"
Write-Host "       to: _brain\ui_backups\$relative folder ($timestamp`__$fileName)"
