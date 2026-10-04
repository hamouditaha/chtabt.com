<?php
require __DIR__ . '/../includes/api.php';

api_guard();

$name    = input('name', 100);
$email   = input('email', 150);
$phone   = input('phone', 20);
$subject = input('subject', 255);
$message = input('message', 3000);

$errors = [];
if ($name === '') {
    $errors['name'] = t('api.errors.name');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = t('api.errors.email');
}
if ($phone !== '' && !valid_phone($phone)) {
    $errors['phone'] = t('api.errors.phone');
}
if (mb_strlen($message) < 5) {
    $errors['message'] = t('api.errors.message');
}
if ($errors) {
    json_response(false, t('api.fix'), $errors, 422);
}

$pdo = require_db();
rate_limit();

$stmt = $pdo->prepare(
    'INSERT INTO contact_messages (name, email, phone, subject, message, lang, ip_address, user_agent)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->execute([
    $name,
    $email,
    $phone ?: null,
    $subject ?: t('contact.subject_general'),
    $message,
    lang(),
    client_ip(),
    mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
]);

json_response(true, t('api.contact_ok', ['name' => $name]));
