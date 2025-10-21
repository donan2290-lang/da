<?php
/**
 * WebP Image Helper
 * Usage: <img src="<?= webp_image('image.jpg') ?>" alt="..." />
 */

if (!function_exists('webp_image')) {
    function webp_image($imagePath, $fallback = true) {
        if (empty($imagePath)) {
            return '';
        }
        
        // Check if WebP version exists
        $pathInfo = pathinfo($imagePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Remove leading slash for file_exists check
        $webpCheck = ltrim($webpPath, '/');
        
        if (file_exists($webpCheck)) {
            return $webpPath;
        }
        
        return $imagePath; // Fallback to original
    }
}

if (!function_exists('picture_webp')) {
    function picture_webp($imagePath, $alt = '', $class = '', $width = '', $height = '') {
        if (empty($imagePath)) {
            return '';
        }
        
        $pathInfo = pathinfo($imagePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        $webpCheck = ltrim($webpPath, '/');
        
        $widthAttr = $width ? "width=\"{$width}\"" : '';
        $heightAttr = $height ? "height=\"{$height}\"" : '';
        $classAttr = $class ? "class=\"{$class}\"" : '';
        
        if (file_exists($webpCheck)) {
            // Use <picture> for WebP with fallback
            return "<picture>
    <source srcset=\"{$webpPath}\" type=\"image/webp\">
    <img src=\"{$imagePath}\" alt=\"{$alt}\" {$classAttr} {$widthAttr} {$heightAttr} loading=\"lazy\">
</picture>";
        }
        
        return "<img src=\"{$imagePath}\" alt=\"{$alt}\" {$classAttr} {$widthAttr} {$heightAttr} loading=\"lazy\">";
    }
}
?>