<?php
$credFile = __DIR__ . '/../config.json';
if (file_exists($credFile)) {
    $cred = json_decode(file_get_contents($credFile), true);
    define('ADMIN_USER', $cred['user'] ?? 'admin');
    define('ADMIN_PASSWORD', $cred['pass'] ?? 'admin123');
} else {
    define('ADMIN_USER', 'admin');
    define('ADMIN_PASSWORD', 'admin123');
}
define('DATA_DIR', __DIR__ . '/../data/');
