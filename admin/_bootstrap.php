<?php
require __DIR__ . '/../includes/bootstrap.php';

// the admin panel is Arabic-only, whatever language the visitor chose on the site
set_lang('ar');

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

function current_admin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function require_admin(): PDO
{
    if (!current_admin()) {
        header('Location: login.php');
        exit;
    }
    $pdo = db();
    if (!$pdo) {
        http_response_code(503);
        exit('قاعدة البيانات غير متاحة. تحقق من إعدادات includes/config.php');
    }
    return $pdo;
}

/** يتحقق من CSRF في كل طلب POST داخل لوحة التحكم. */
function require_post_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_valid($_POST['csrf'] ?? null)) {
        http_response_code(419);
        exit('انتهت صلاحية الجلسة. ارجع وحدّث الصفحة.');
    }
}

function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

function redirect_back(string $fallback): never
{
    $target = $_POST['return'] ?? $fallback;
    // only allow relative redirects inside the admin
    if (!preg_match('/^[a-z\-]+\.php(\?[^\s]*)?$/i', $target)) {
        $target = $fallback;
    }
    header('Location: ' . $target);
    exit;
}

function status_badge(string $status, array $labels): string
{
    [$label, $class] = $labels[$status] ?? [$status, 'secondary'];
    return '<span class="badge text-bg-' . $class . '">' . e($label) . '</span>';
}

function format_date(?string $date, bool $withTime = true): string
{
    if (!$date) {
        return '—';
    }
    return date($withTime ? 'Y-m-d H:i' : 'Y-m-d', strtotime($date));
}

const QUOTE_STATUSES = [
    'new'       => ['جديد', 'primary'],
    'contacted' => ['تم الاتصال', 'warning'],
    'converted' => ['مؤكَّد', 'success'],
    'cancelled' => ['ملغى', 'secondary'],
];

const MESSAGE_STATUSES = [
    'new'     => ['جديدة', 'primary'],
    'read'    => ['مقروءة', 'secondary'],
    'replied' => ['تم الرد', 'success'],
];
