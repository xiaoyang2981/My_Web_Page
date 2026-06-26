<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => '上传失败']);
    exit;
}

$name = $_FILES['file']['name'];
$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
    // try from mime
    $mime = $_FILES['file']['type'];
    $map = ['image/png'=>'png','image/jpeg'=>'jpg','image/jpg'=>'jpg'];
    $ext = isset($map[$mime]) ? $map[$mime] : 'png';
}
$allowed = ['png', 'jpg', 'jpeg'];
if (!in_array($ext, $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => '仅支持 png, jpg, jpeg', 'got' => $ext, 'name' => $name, 'mime' => $_FILES['file']['type']]);
    exit;
}

// 删除旧文件
$name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
$prefixes = [$name];
$allowed = ['png', 'jpg', 'jpeg'];
foreach ($prefixes as $prefix) {
    foreach ($allowed as $e) {
        $old = __DIR__ . '/../img/' . $prefix . '.' . $e;
        if (file_exists($old)) @unlink($old);
    }
}
$dest = __DIR__ . '/../img/' . $name . '.' . $ext;
if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
    echo json_encode(['success' => true, 'url' => '/img/' . $name . '.' . $ext]);
} else {
    http_response_code(500);
    echo json_encode(['error' => '保存文件失败']);
}
