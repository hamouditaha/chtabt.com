<?php
/**
 * Bilinguisme arabe / français.
 * La langue vient de ?lang=xx (mémorisée dans un cookie), sinon du cookie,
 * sinon de l'en-tête Accept-Language, sinon la langue par défaut (arabe).
 */

function detect_lang(array $available): string
{
    // POST: the forms send the page language so API replies match it
    $requested = $_GET['lang'] ?? $_POST['lang'] ?? null;
    if (is_string($requested) && in_array($requested, $available, true)) {
        if (!headers_sent()) {
            setcookie('lang', $requested, [
                'expires'  => time() + 365 * 86400,
                'path'     => '/',
                'samesite' => 'Lax',
                'secure'   => is_https(),
            ]);
        }
        return $requested;
    }
    $cookie = $_COOKIE['lang'] ?? null;
    if (is_string($cookie) && in_array($cookie, $available, true)) {
        return $cookie;
    }
    $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''));
    foreach ($available as $code) {
        if (str_starts_with($accept, $code)) {
            return $code;
        }
    }
    return $available[0];
}

function set_lang(string $code): void
{
    $GLOBALS['lang'] = $code;
    $GLOBALS['translations'] = require __DIR__ . '/../lang/' . $code . '.php';
}

function lang(): string
{
    return $GLOBALS['lang'];
}

function is_rtl(): bool
{
    return lang() === 'ar';
}

/**
 * Traduction : t('home.hero_title'), t('api.contact_ok', ['name' => 'Sara']).
 * Une clé peut contenir un tableau (listes de FAQ, témoignages…).
 */
function t(string $key, array $vars = []): mixed
{
    $value = $GLOBALS['translations'];
    foreach (explode('.', $key) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $key;
        }
        $value = $value[$part];
    }
    if (is_string($value) && $vars) {
        $value = strtr($value, array_combine(
            array_map(fn ($k) => ':' . $k, array_keys($vars)),
            array_values($vars)
        ));
    }
    return $value;
}

/** Nom du site dans la langue courante. */
function site_name(): string
{
    global $site;
    return lang() === 'ar' ? $site['name_ar'] : $site['name_latin'];
}

/** Champ traduit d'un service : loc($service, 'name') → name_fr ou name_ar. */
function loc(array $row, string $field): mixed
{
    $value = $row[$field . '_' . lang()] ?? null;
    return ($value === null || $value === '' || $value === []) ? ($row[$field . '_ar'] ?? '') : $value;
}

/** URL relative de la page courante dans une autre langue (garde les autres paramètres). */
function lang_url(string $code): string
{
    $query = $_GET;
    $query['lang'] = $code;
    return '?' . http_build_query($query);
}
