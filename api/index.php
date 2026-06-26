<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../admin/config.php';

$type = isset($_GET['type']) ? preg_replace('/[^a-z]/', '', $_GET['type']) : '';
$allowed = ['friends', 'furls', 'oc', 'projects', 'about', 'homepage', 'settings', 'account'];
if (!in_array($type, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid type']);
    exit;
}

$file = DATA_DIR . $type . '.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($type === 'account') {
        $configFile = __DIR__ . '/../config.json';
        echo file_exists($configFile) ? file_get_contents($configFile) : '{"user":"admin","pass":"admin123"}';
        exit;
    }
    if (!file_exists($file)) file_put_contents($file, '[]');
    echo file_get_contents($file);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type === 'account') {
        http_response_code(400);
        echo json_encode(['error' => 'Use saveconfig.php']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['password']) || $input['password'] !== ADMIN_PASSWORD || (isset($input['user']) && $input['user'] !== ADMIN_USER)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    if (!isset($input['data'])) {
        echo json_encode(['success' => true, 'auth' => true]);
        exit;
    }
    file_put_contents($file, json_encode($input['data'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
