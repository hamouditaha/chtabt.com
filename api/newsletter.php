<?php
require __DIR__ . '/../includes/api.php';

api_guard();

$email = input('email', 150);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(false, t('api.errors.email'), ['email' => t('api.errors.email')], 422);
}

$pdo = require_db();
rate_limit();

// re-subscribing an existing (or previously unsubscribed) email simply reactivates it
$stmt = $pdo->prepare(
    'INSERT INTO newsletter_subscribers (email, lang) VALUES (?, ?)
     ON DUPLICATE KEY UPDATE is_active = TRUE, unsubscribed_at = NULL, lang = VALUES(lang)'
);
$stmt->execute([mb_strtolower($email), lang()]);

json_response(true, t('api.newsletter_ok'));
