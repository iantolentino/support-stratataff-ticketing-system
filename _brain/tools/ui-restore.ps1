# Reverts a UI file to its most recent backup (or a specific one you name).
# Run from the site root.
#
# Usage:
#   powershell -File _brain\tools\ui-restore.ps1 -Path "wp-content\themes\your-theme\style.css"
#     -> restores the LATEST backup for that file
#
#   powershell -File _brain\tools\ui-restore.ps1 -Path "wp-content\themes\your-theme\style.css" -BackupFile "20260714-093000__style.css"
#     -> restores a SPECIFIC backup by filename

param(
    [Parameter(Mandatory = $true)]
    [string]$Path,

    [string]$BackupFile
)

$root     = Resolve-Path "$PSScriptRoot\..\.."
$fullPath = Join-Path $root $Path
$relative = $Path.TrimStart('\')
$backupDir = Join-Path "$PSScriptRoot\..\ui_backups" (Split-Path $relative -Parent)

if (-not (Test-Path $backupDir)) {
    Write-Error "No backups found for: $Path"
    exit 1
}

if ($BackupFile) {
    $chosen = Join-Path $backupDir $BackupFile
    if (-not (Test-Path $chosen)) {
        Write-Error "Backup not found: $chosen"
        exit 1
    }
} else {
    $fileName = Split-Path $relative -Leaf
    $chosen = Get-ChildItem $backupDir -Filter "*__$fileName" |
        Sort-Object Name -Descending |
        Select-Object -First 1 -ExpandProperty FullName
    if (-not $chosen) {
        Write-Error "No backups found for: $Path"
        exit 1
    }
}

Copy-Item -Path $chosen -Destination $fullPath -Force
Write-Host "Restored: $Path"
Write-Host "    from: $chosen"
