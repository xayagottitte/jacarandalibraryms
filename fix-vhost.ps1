# Fix Apache VirtualHost Configuration
# This script adds a default localhost VirtualHost to restore normal XAMPP behavior

Write-Host "Fixing Apache VirtualHost Configuration..." -ForegroundColor Cyan

# Backup the current config
$vhostFile = "C:\xampp\apache\conf\extra\httpd-vhosts.conf"
$backupFile = "C:\xampp\apache\conf\extra\httpd-vhosts.conf.backup"

if (Test-Path $vhostFile) {
    Copy-Item $vhostFile $backupFile -Force
    Write-Host "✓ Backup created: $backupFile" -ForegroundColor Green
}

# Read current content
$content = Get-Content $vhostFile -Raw

# Check if localhost VirtualHost already exists
if ($content -match 'ServerName localhost') {
    Write-Host "✓ localhost VirtualHost already exists" -ForegroundColor Yellow
} else {
    # Find the position before the chimzy.local VirtualHost
    $newVHost = @"


# Default VirtualHost for localhost - allows normal XAMPP access
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "C:/xampp/htdocs"
    <Directory "C:/xampp/htdocs">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "logs/localhost-error.log"
    CustomLog "logs/localhost-access.log" common
</VirtualHost>

"@

    # Insert the new VirtualHost before the chimzy.local one
    $content = $content -replace '(<VirtualHost \*:80>\s+ServerName chimzy\.local)', "$newVHost`$1"
    
    # Save the updated content
    Set-Content -Path $vhostFile -Value $content -Force
    Write-Host "✓ Added localhost VirtualHost configuration" -ForegroundColor Green
}

Write-Host "`n✓ Configuration updated successfully!" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Cyan
Write-Host "1. Restart Apache in XAMPP Control Panel"
Write-Host "2. Access: http://localhost/jacarandalibraryms/login"
Write-Host "`nIf there are issues, restore backup from: $backupFile" -ForegroundColor Yellow
