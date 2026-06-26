<?php
$credFile = __DIR__ . '/../config.json';
$step = isset($_GET['step']) ? $_GET['step'] : '';

if ($step === 'check') {
    header('Content-Type: application/json');
    echo json_encode(['setup' => !file_exists($credFile)]);
    exit;
}

if ($step === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $user = isset($_POST['user']) ? trim($_POST['user']) : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
    if (!$user || !$pass) {
        http_response_code(400);
        echo json_encode(['error' => '用户名和密码不能为空']);
        exit;
    }
    file_put_contents($credFile, json_encode(['user' => $user, 'pass' => $pass], JSON_UNESCAPED_UNICODE));
    echo json_encode(['success' => true]);
    exit;
}
