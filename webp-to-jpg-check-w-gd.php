<?php
/**
 * Converts WebP images to JPG if the corresponding JPG files are missing.
 *
 * This function traverses a directory structure, looking for WebP images.
 * If a corresponding JPG is missing, it creates the JPG using the GD library.
 * Supports optional filtering by year and month and a dry-run mode.
 *
 * @param string $webpDir The base directory containing WebP images.
 * @param string $jpgDir The base directory where JPGs should be stored.
 * @param int|null $year Optional year to filter the directories.
 * @param int|null $month Optional month to filter the directories.
 * @param bool $dryRun If true, the function will only log actions without creating files.
 *
 * @return void
 *
 * chmod +x webp-to-jpg-check-w-gd.php and #!/usr/bin/env beginning scrip to execute without php prefix
 * 
 * dry run: php webp-to-jpg-check-w-gd.php --year=2022 --month=06 --dry-run
 * live run: php webp-to-jpg-check-w-gd.php --year=2022 --month=06
 * No Parameters: Process all directories without filtering by year/month: php webp-to-jpg-check-w-gd.php
 *
 * GD PHP library needed. Check: php -m | grep gd
 */
function convertWebpToJpgIfMissing($webpDir, $jpgDir, $year = null, $month = null, $dryRun = false)
{
    $missingJpgCount = 0;  // Initialize counter

    // Build the directory path based on year and month if provided
    if ($year && $month) {
        $webpDir = rtrim($webpDir, '/') . "/$year/$month";
        $jpgDir = rtrim($jpgDir, '/') . "/$year/$month";
    }

    // Ensure the webp directory exists
    if (!is_dir($webpDir)) {
        echo "Directory not found: $webpDir\n";
        return;
    }

    // Recursive directory iterator to find WebP files
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($webpDir));

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'webp') {
            $filename = $file->getFilename();
            $relativePath = str_replace($webpDir, '', $file->getPathname());
            
            if (preg_match('/\.jpg\.webp$/i', $filename)) {
                // Convert .jpg.webp -> .jpg
                $targetRelativePath = preg_replace('/\.webp$/i', '', $relativePath);
            } elseif (preg_match('/\.png\.webp$/i', $filename)) {
                // Convert .png.webp -> .png
                $targetRelativePath = preg_replace('/\.webp$/i', '', $relativePath);
            } else {
                // Skip other patterns
                continue;
            }

            $targetPath = $jpgDir . $targetRelativePath;

            // Check if either JPG or PNG file exists
            if (!file_exists($targetPath)) {
                if ($dryRun) {
                    echo "[Dry Run] No JPG/PNG found in '$jpgDir': " . basename($targetPath) . " will be created\n";
                    $missingJpgCount++;
                } else {
                    // Ensure the target directory exists
                    $targetDir = dirname($targetPath);
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    // Convert WebP to JPG or PNG using GD library
                    $webpImage = imagecreatefromwebp($file->getPathname());
                    if ($webpImage) {
                        if (preg_match('/\.jpg$/i', $targetPath)) {
                            imagejpeg($webpImage, $targetPath, 90); // Save as JPG with 90% quality
                        } elseif (preg_match('/\.png$/i', $targetPath)) {
                            imagepng($webpImage, $targetPath); // Save as PNG
                        }
                        imagedestroy($webpImage);
                        echo "Converted: " . $file->getPathname() . " -> " . $targetPath . "\n";
                    } else {
                        echo "Failed to convert: " . $file->getPathname() . "\n";
                    }
                }
            }
        }
    }

    if ($dryRun && $missingJpgCount > 0) {
        echo "\n[Dry Run] Total images to be converted: $missingJpgCount\n";
    }
}

// Define directories
$webpDir = '/var/www/autentical.com/public_html/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/sites/42';
$jpgDir = '/var/www/autentical.com/public_html/wp-content/uploads/sites/42';

// For local testing
// $webpDir = '/Users/user/code/autentical.com/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/sites/42';
// $jpgDir = '/Users/user/code/autentical.com/wp-content/uploads/sites/42';

// Get year and month from command-line arguments
$options = getopt('', ['year:', 'month:', 'dry-run']);
$year = $options['year'] ?? null;
$month = $options['month'] ?? null;
$dryRun = isset($options['dry-run']);

// Execute the function
convertWebpToJpgIfMissing($webpDir, $jpgDir, $year, $month, $dryRun);