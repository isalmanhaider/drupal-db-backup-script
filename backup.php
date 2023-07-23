<?php

// Define username and password
$Username = 'admin';
$Password = 'password';

// Password protection
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || ($_SERVER['PHP_AUTH_USER'] != $Username) || ($_SERVER['PHP_AUTH_PW'] != $Password)) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Secure Area"');
    exit("Access Denied");
}

// Directory to save the backups
$backupDir = "/var/www/html/backup";
$docRoot = "/var/www/html";

// Export and download db if form submitted
if (isset($_POST['create_backup'])) {
    $timestamp = date('Ymd_His');
    $backupFile = $backupDir . '/backup_' . $timestamp . '.sql';

    // Update this with your drush path
    $drushPath = "/var/www/html/vendor/bin/drush";

    // Build command
    $command = "cd /var/www/html && $drushPath sql:dump > $backupFile";
    shell_exec($command);

    // Force download the backup file
    if (file_exists($backupFile)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backupFile));
        readfile($backupFile);
        exit;
    }
}

// Get the list of backup files
$backupFiles = array_diff(scandir($backupDir), array('.', '..'));
$backupFiles = array_reverse($backupFiles);  // reverse to show the latest files first
?>

<!DOCTYPE html>
<html>
<head>
    <title>Drupal DB Backup</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="my-4">Drupal DB Backup</h1>
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Backup Name</th>
            <th scope="col">Last Modified</th>
            <th scope="col">File Size (MB)</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($backupFiles as $filename) {
            $filePath = $backupDir . '/' . $filename;
            $lastModified = date('F d Y H:i:s', filemtime($filePath));
            $fileSize = round(filesize($filePath) / 1024 / 1024, 2);

            echo '<tr>';
            echo '<td>' . $filename . '</td>';
            echo '<td>' . $lastModified . '</td>';
            echo '<td>' . $fileSize . '</td>';
            echo '<td><a href="' . str_replace($docRoot, '', $filePath) . '" class="btn btn-primary btn-sm">Download</a></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
    <form method="post">
        <input type="submit" name="create_backup" value="Create and Download DB Backup" class="btn btn-success btn-lg">
    </form>
</div>
</body>
</html>
