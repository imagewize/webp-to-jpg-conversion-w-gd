# WebP to JPG conversion with GD

This script scans WordPress uploads to identify missing JPG images, which may occur after migration or maintenance. It counts the missing JPGs using the missingJpgCount counter, organizing them by year and month based on the directory structure. The script then recreates the missing JPG images using their corresponding WebP versions.

## Requirements

- PHP with GD library enabled. Check with: `php -m | grep gd`

## Configuration

The script is typically run from the root of your WordPress project. The paths `$webpDir` and `$jpgDir` in the script can be adjusted to match your server's directory structure and the location of your WebP and JPG files.

Default paths are set to:
```php
$webpDir = '/var/www/autentical.com/public_html/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/sites/42';
$jpgDir = '/var/www/autentical.com/public_html/wp-content/uploads/sites/42';
```

## Usage

Make the script executable is optional. To do so first 

add `#!/usr/bin/env` to the beginning of the script to execute file without php prefix. Then do the following to make the file executable on the system:
```sh
chmod +x webp-to-jpg-check-w-gd.php
```

Run the script:

- Dry run (only log actions without creating files):
```sh
php webp-to-jpg-check-w-gd.php --year=xxxx --month=xx --dry-run
```

- Live run (create missing JPG files):
```sh
php webp-to-jpg-check-w-gd.php --year=xxxx --month=xx
```

- Process all directories without filtering by year/month:
```sh
php webp-to-jpg-check-w-gd.php
```

## Parameters

- `--year`: Optional year to filter the directories.
- `--month`: Optional month to filter the directories.
- `--dry-run`: If true, the function will only log actions without creating files.

## Example

To convert WebP images to JPG for June 2022 in dry-run mode:
```sh
php webp-to-jpg-check-w-gd.php --year=2022 --month=06 --dry-run
```

To convert WebP images to JPG for June 2022 and actually create the files:
```sh
php webp-to-jpg-check-w-gd.php --year=2022 --month=06
```

To process all directories without filtering by year/month:
```sh
php webp-to-jpg-check-w-gd.php
```
