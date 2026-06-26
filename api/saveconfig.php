<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../admin/config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['password']) || $input['password'] !== ADMIN_PASSWORD) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$file = __DIR__ . '/../config.json';
$current = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$current['user'] = isset($input['user']) ? $input['user'] : ($current['user'] ?? 'admin');
$current['pass'] = isset($input['pass']) ? $input['pass'] : ($current['pass'] ?? 'admin123');

file_put_contents($file, json_encode($current, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo json_encode(['success' => true]);
