# webp-to-jpg-conversion-w-gd
This script scans WordPress uploads to identify missing JPG images, which may occur after migration or maintenance. It counts the missing JPGs using the missingJpgCount counter, organizing them by year and month based on the directory structure. The script then recreates the missing JPG images using their corresponding WebP versions.
