<?php
declare(strict_types=1);
final class ApiLogger
{
    public static function error(string $message): void
    {
        $directory = __DIR__ . '/../logs';
        if (!is_dir($directory)) @mkdir($directory, 0775, true);
        @file_put_contents($directory . '/error.log', '[' . date('Y-m-d H:i:s') . '] ERROR ' . preg_replace('/[\r\n]+/', ' ', $message) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
