# PowerShell script to add hosts entries for multi-domain setup
# Run this script as Administrator

Write-Host "Adding hosts entries for Inventaris Multi-Domain setup..." -ForegroundColor Green

$hostsFile = "C:\Windows\System32\drivers\etc\hosts"
$userDomain = "127.0.0.1 user.inventaris.local"
$adminDomain = "127.0.0.1 admin.inventaris.local"

# Check if entries already exist
$hostsContent = Get-Content $hostsFile

if ($hostsContent -notcontains $userDomain) {
    Add-Content -Path $hostsFile -Value $userDomain
    Write-Host "Added: $userDomain" -ForegroundColor Yellow
} else {
    Write-Host "Entry already exists: $userDomain" -ForegroundColor Cyan
}

if ($hostsContent -notcontains $adminDomain) {
    Add-Content -Path $hostsFile -Value $adminDomain
    Write-Host "Added: $adminDomain" -ForegroundColor Yellow
} else {
    Write-Host "Entry already exists: $adminDomain" -ForegroundColor Cyan
}

Write-Host "`nHosts file updated successfully!" -ForegroundColor Green
Write-Host "`nDomains configured:" -ForegroundColor White
Write-Host "User Frontend: http://user.inventaris.local" -ForegroundColor Cyan
Write-Host "Admin Panel: http://admin.inventaris.local:8080" -ForegroundColor Cyan

pause
