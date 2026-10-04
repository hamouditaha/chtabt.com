<?php
/** Outils communs des points d’entrée api/ — réponses JSON dans la langue de la page. */
require __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function json_response(bool $ok, string $message, array $errors = [], int $status = 200): never
{
    http_response_code($status);
    echo json_encode(['ok' => $ok, 'message' => $message, 'errors' => (object) $errors], JSON_UNESCAPED_UNICODE);
    exit;
}

/** يرفض الطلبات غير POST، طلبات CSRF غير الصالحة، والروبوتات (حقل الفخ website). */
function api_guard(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_response(false, t('api.method'), [], 405);
    }
    if (!csrf_valid($_POST['csrf'] ?? null)) {
        json_response(false, t('api.csrf'), [], 419);
    }
    if (!empty($_POST['website'])) {
        // bot filled the honeypot: pretend success
        json_response(true, t('api.thanks'));
    }
}

/** حد بسيط: 5 إرسالات ناجحة كل 10 دقائق لكل جلسة. يُستدعى بعد نجاح التحقق. */
function rate_limit(int $max = 5, int $window = 600): void
{
    $now = time();
    $_SESSION['submissions'] = array_filter(
        $_SESSION['submissions'] ?? [],
        fn ($t) => $t > $now - $window
    );
    if (count($_SESSION['submissions']) >= $max) {
        json_response(false, t('api.rate'), [], 429);
    }
    $_SESSION['submissions'][] = $now;
}

function input(string $key, int $maxLength = 255): string
{
    $value = trim((string) ($_POST[$key] ?? ''));
    return mb_substr($value, 0, $maxLength);
}

function valid_phone(string $phone): bool
{
    return (bool) preg_match('/^[0-9+\s\-]{8,20}$/', $phone);
}

function require_db(): PDO
{
    $pdo = db();
    if (!$pdo) {
        global $site;
        json_response(false, t('api.unavailable', ['phone' => $site['phone']]), [], 503);
    }
    return $pdo;
}

function client_ip(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
}
