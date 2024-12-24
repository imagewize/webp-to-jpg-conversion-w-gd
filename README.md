# webp-to-jpg-conversion-w-gd

This script scans WordPress uploads to identify missing JPG images, which may occur after migration or maintenance. It counts the missing JPGs using the missingJpgCount counter, organizing them by year and month based on the directory structure. The script then recreates the missing JPG images using their corresponding WebP versions.

## Requirements

- PHP with GD library enabled. Check with: `php -m | grep gd`

## Usage

Make the script executable is optional. To do so first 

add `#!/usr/bin/env` to the beginning of the script to execute file without php prefix. Then do the following to make the file executable on the system:
```sh
chmod +x web-to-jpg-check-w-ym.php
```

Run the script:

- Dry run (only log actions without creating files):
```sh
php web-to-jpg-check-w-ym.php --year=xxxx --month=xx --dry-run
```

- Live run (create missing JPG files):
```sh
php web-to-jpg-check-w-ym.php --year=xxxx --month=xx
```

- Process all directories without filtering by year/month:
```sh
php web-to-jpg-check-w-ym.php
```

## Parameters

- `--year`: Optional year to filter the directories.
- `--month`: Optional month to filter the directories.
- `--dry-run`: If true, the function will only log actions without creating files.

## Example

To convert WebP images to JPG for June 2022 in dry-run mode:
```sh
php web-to-jpg-check-w-ym.php --year=2022 --month=06 --dry-run
```

To convert WebP images to JPG for June 2022 and actually create the files:
```sh
php web-to-jpg-check-w-ym.php --year=2022 --month=06
```

To process all directories without filtering by year/month:
```sh
php web-to-jpg-check-w-ym.php
```
