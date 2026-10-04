<?php
/**
 * إنشاء حساب مسؤول أو تغيير كلمة مروره (سطر الأوامر فقط):
 *   php database/create-admin.php admin@example.com "كلمة-مرور-قوية" "الاسم"
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require __DIR__ . '/../includes/bootstrap.php';

[$script, $email, $password, $name] = $argv + [null, null, null, 'مدير الموقع'];

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$password) {
    fwrite(STDERR, "Usage: php database/create-admin.php <email> <password> [name]\n");
    exit(1);
}
if (strlen($password) < 8) {
    fwrite(STDERR, "Password must be at least 8 characters.\n");
    exit(1);
}

$pdo = db();
if (!$pdo) {
    fwrite(STDERR, "Cannot connect to the database. Check includes/config.php (or config.local.php).\n");
    exit(1);
}

$stmt = $pdo->prepare(
    "INSERT INTO users (name, email, password, user_type, status) VALUES (?, ?, ?, 'admin', 'active')
     ON DUPLICATE KEY UPDATE password = VALUES(password), user_type = 'admin', status = 'active'"
);
$stmt->execute([$name, strtolower($email), password_hash($password, PASSWORD_DEFAULT)]);

echo "Admin account ready: {$email}\n";
