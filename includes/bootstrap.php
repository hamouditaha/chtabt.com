<?php
declare(strict_types=1);

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
}

$config = require __DIR__ . '/config.php';
$site   = $config['site'];

ini_set('display_errors', $config['debug'] ? '1' : '0');
ini_set('log_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => is_https()]);
    session_start();
}

require __DIR__ . '/i18n.php';
set_lang(detect_lang($config['languages']));

/** اتصال PDO مشترك، أو null إذا تعذّر الاتصال (الموقع يبقى يعمل بالبيانات الثابتة). */
function db(): ?PDO
{
    static $pdo = false;
    if ($pdo !== false) {
        return $pdo;
    }
    global $config;
    $c = $config['db'];
    try {
        $pdo = new PDO(
            "mysql:host={$c['host']};port={$c['port']};dbname={$c['name']};charset={$c['charset']}",
            $c['user'],
            $c['pass'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        error_log('DB connection failed: ' . $e->getMessage());
        $pdo = null;
    }
    return $pdo;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_valid(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/** قائمة الخدمات: من قاعدة البيانات إن وُجدت، وإلا من الملف الثابت. */
function services(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $pdo = db();
    if ($pdo) {
        try {
            $rows = $pdo->query("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order, id")->fetchAll();
            foreach ($rows as &$row) {
                $row['features_ar'] = json_decode($row['features_ar'] ?? '[]', true) ?: [];
                $row['features_fr'] = json_decode($row['features_fr'] ?? '[]', true) ?: [];
            }
            unset($row);
            if ($rows) {
                return $cache = $rows;
            }
        } catch (PDOException $e) {
            error_log('services query failed: ' . $e->getMessage());
        }
    }
    return $cache = require __DIR__ . '/services-data.php';
}

function service_by_slug(string $slug): ?array
{
    foreach (services() as $service) {
        if ($service['slug'] === $slug) {
            return $service;
        }
    }
    return null;
}

/** Durée lisible : ar 120 => "ساعتان"، 240 => "4 ساعات" ; fr 120 => "2 h", 90 => "1,5 h". */
function duration_label(int $minutes): string
{
    $hours = $minutes / 60;
    if (lang() === 'fr') {
        return str_replace('.', ',', rtrim(rtrim(number_format($hours, 1, '.', ''), '0'), '.')) . ' h';
    }
    if ($hours == 1) {
        return 'ساعة واحدة';
    }
    if ($hours == 2) {
        return 'ساعتان';
    }
    if ($hours == (int) $hours && $hours >= 3 && $hours <= 10) {
        return (int) $hours . ' ساعات';
    }
    return rtrim(rtrim(number_format($hours, 1, '.', ''), '0'), '.') . ' ساعة';
}

function whatsapp_link(string $text = ''): string
{
    global $site;
    $url = 'https://wa.me/' . $site['whatsapp'];
    return $text === '' ? $url : $url . '?text=' . rawurlencode($text);
}
