<?php
    $username = 'admin';
    $password = 'Xc7!uV2@Jf9#Lz1&';

    if (!isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) ||
        $_SERVER['PHP_AUTH_USER'] !== $username ||
        $_SERVER['PHP_AUTH_PW'] !== $password) {
        header('WWW-Authenticate: Basic realm="Restricted area"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Unauthorized';
        exit;
    }

    $backupDir = "/var/www/html/backup";

    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $timestamp = date("Ymd_His");
        $command = "cd /var/www/html && HOME=/var/www/html /usr/bin/env php vendor/bin/drush sql:dump --result-file=backup/backup_${timestamp}.sql --gzip";

        shell_exec($command);

        chmod($backupDir . "/backup_${timestamp}.sql.gz", 0644);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $files = glob($backupDir . "/backup_*.sql.gz");
        rsort($files);
        echo "<table border='1'>";
        echo "<tr><th>File</th><th>Last Modified</th><th>Size (MB)</th><th>Action</th></tr>";

        foreach ($files as $file) {
            $modifiedTime = date("F d Y H:i:s", filemtime($file));
            $fileName = basename($file);
            $fileSize = round(filesize($file) / 1024 / 1024, 2);

            echo "<tr>";
            echo "<td>{$fileName}</td>";
            echo "<td>{$modifiedTime}</td>";
            echo "<td>{$fileSize}</td>";
            echo "<td><a href='/backup/{$fileName}' download>Download</a></td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "<br>";
        echo '<form method="post">';
        echo '<input type="submit" value="Create and Download DB Backup">';
        echo '</form>';
    }
?>
