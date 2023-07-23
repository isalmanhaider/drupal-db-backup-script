# Drupal Database Backup Script

This repository contains a PHP script that automates the process of creating backups of a Drupal database using Drush. The script presents the created backups in a web page, showing details such as the last modification time and file size, and provides an easy download option. The script also includes basic HTTP authentication for added security.

## Requirements

Before using this script, please ensure you have the following installed:

- PHP
- Drush
- A Drupal website

## Installation

To set up the script, follow these steps:

1. Clone this repository to your server.
```bash
git clone https://github.com/yourusername/drupal-db-backup-script.git
```
2. Open the `db_export.php` file and replace `'admin'` and `'password'` placeholders on lines 4 and 5 with your desired username and password.
```php
$username = 'admin';  // Change this to your desired username
$password = 'password';  // Change this to your desired password
```
3. Ensure the backup directory (`/var/www/html/backup`) exists and is writable. If it does not exist, create it. The web server user must have write permissions to this directory.

## Usage

To use the script, navigate to its URL (e.g., `http://yourwebsite.com/db_export.php`). Enter the username and password when prompted. You will be presented with a list of existing database backups, if any.

To create a new backup, click the "Create and Download DB Backup" button. The page will refresh, and the new backup will be added to the list.

To download a backup, click the "Download" link next to the backup you wish to download.

## Contributing

Contributions are welcome. Please create an issue to discuss the change you wish to make. After discussion, you can make changes and submit a pull request.

## License

This project is licensed under the terms of the MIT License. See the `LICENSE` file for details.

---

You can replace the `yourusername` placeholder in the `git clone` command with your actual GitHub username. Also, remember to modify the URL in the "Usage" section to match the actual URL where your script will be located.