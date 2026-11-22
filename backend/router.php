<?php
declare(strict_types=1);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (str_starts_with($path, '/api/')) {
    require __DIR__ . '/index.php';
    return true;
}
$file = realpath(__DIR__ . '/../' . ltrim($path, '/'));
$root = realpath(__DIR__ . '/..');
if ($file && str_starts_with($file, $root . DIRECTORY_SEPARATOR) && is_file($file)) return false;
http_response_code(404);
echo 'Not found';
return true;
