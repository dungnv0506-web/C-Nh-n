<?php
declare(strict_types=1);
// includes/data.php
// Định nghĩa các hàm đọc/ghi JSON. Bọc bằng if (!function_exists) để tránh redeclare nếu file bị include nhiều lần.

if (!function_exists('data_path')) {
    function data_path(string $file): string {
        return __DIR__ . '/../data/' . $file;
    }
}

if (!function_exists('read_json')) {
    function read_json(string $file, array $default = []): array {
        $path = data_path($file);
        if (!file_exists($path)) return $default;
        $json = @file_get_contents($path);
        if ($json === false) return $default;
        $data = json_decode($json, true);
        return is_array($data) ? $data : $default;
    }
}

if (!function_exists('write_json')) {
    function write_json(string $file, array $data): void {
        $path = data_path($file);
        $dir = dirname($path);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
}
?>