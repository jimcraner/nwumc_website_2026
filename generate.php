<?php
declare(strict_types=1);

$root = __DIR__;
$workingDir = $root . '/working';
$publicDir = $root . '/public';
$htmlsDir = $workingDir . '/htmls';
$headerFile = $workingDir . '/header.fragment';
$footerFile = $workingDir . '/footer.fragment';

if (!is_dir($publicDir)) {
    fwrite(STDERR, "Missing public directory at {$publicDir}\n");
    exit(1);
}

foreach ([$headerFile, $footerFile] as $fragmentPath) {
    if (!is_file($fragmentPath)) {
        fwrite(STDERR, "Missing required fragment: {$fragmentPath}\n");
        exit(1);
    }
}

$header = file_get_contents($headerFile);
$footer = file_get_contents($footerFile);

$keepDirs = ['css', 'js', 'images', 'files'];

/**
 * Prefix asset paths (css/js/images/files) so nested pages resolve correctly.
 */
function adjustAssetPaths(string $html, string $prefix, array $assetDirs): string
{
    $dirsPattern = implode('|', array_map('preg_quote', $assetDirs));
    return preg_replace_callback(
        '/((?:href|src)\s*=\s*["\'])(?:' . $dirsPattern . ')\\//i',
        function (array $matches) use ($prefix) {
            // Reattach the matched directory after adding the prefix.
            $afterEquals = substr($matches[0], strlen($matches[1]));
            return $matches[1] . $prefix . $afterEquals;
        },
        $html
    );
}

/**
 * Recursively remove a path (file or directory).
 */
function removePath(string $path): void
{
    if (is_dir($path)) {
        $items = array_diff(scandir($path) ?: [], ['.', '..']);
        foreach ($items as $item) {
            removePath($path . '/' . $item);
        }
        rmdir($path);
        return;
    }

    if (file_exists($path)) {
        unlink($path);
    }
}

// Clear out generated content while preserving frozen asset directories.
$publicItems = array_diff(scandir($publicDir) ?: [], ['.', '..']);
foreach ($publicItems as $item) {
    if (in_array($item, $keepDirs, true)) {
        continue;
    }
    removePath($publicDir . '/' . $item);
}

// Ensure kept directories exist for consistent output structure.
foreach ($keepDirs as $dir) {
    $target = $publicDir . '/' . $dir;
    if (!is_dir($target)) {
        mkdir($target, 0777, true);
    }
}

$fragmentFiles = glob($htmlsDir . '/*.fragment') ?: [];
if (empty($fragmentFiles)) {
    fwrite(STDERR, "No content fragments found in {$htmlsDir}\n");
    exit(1);
}

foreach ($fragmentFiles as $fragmentPath) {
    $content = file_get_contents($fragmentPath);
    $name = basename($fragmentPath, '.fragment');

    if ($name === 'index') {
        $outputDir = $publicDir;
        $depth = 0;
    } else {
        $relativePath = str_replace('.', '/', $name);
        $outputDir = $publicDir . '/' . $relativePath;
        $depth = substr_count($relativePath, '/') + 1;
    }

    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    $prefix = $depth > 0 ? str_repeat('../', $depth) : '';
    $pageHeader = adjustAssetPaths($header, $prefix, $keepDirs);
    $pageFooter = adjustAssetPaths($footer, $prefix, $keepDirs);

    $outputFile = $outputDir . '/index.html';
    file_put_contents($outputFile, $pageHeader . "\n" . $content . "\n" . $pageFooter);
}

echo "Pages generated successfully.\n";
