<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Jacaranda Libraries' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= BASE_PATH ?>/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="<?= BASE_PATH ?>/assets/img/favicon.ico" type="image/x-icon">
    
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        body {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php 
    // Load system settings for global use
    if (!isset($system_name)) {
        require_once __DIR__ . '/../../models/SystemSettings.php';
        $systemSettings = new SystemSettings();
        // Try both system_name and library_name for backward compatibility
        $system_name = $systemSettings->getSetting('system_name') 
                    ?: $systemSettings->getSetting('library_name') 
                    ?: 'Jacaranda Libraries';
    }
    
    // Include navbar for all pages
    include '../app/views/shared/navbar.php'; 
    ?>