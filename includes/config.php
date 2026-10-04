<?php
/**
 * إعدادات الموقع / Configuration du site.
 * Les identifiants de production vont dans includes/config.local.php (non versionné) :
 * voir includes/config.local.example.php.
 * Les textes traduisibles (horaires, ville, devise…) sont dans lang/ar.php et lang/fr.php.
 */

$config = [
    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'chtabt_db',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],

    // false en production : les erreurs PHP sont journalisées au lieu d'être affichées
    'debug' => false,

    'site' => [
        'name_ar'    => 'شطبتكم',
        'name_latin' => 'CHTABT.COM',
        'company'    => 'STE SHTABTKOUM SARL',
        'email'      => 'steshtabtkoumsarl@gmail.com',
        'phone'      => '+212 636-807446',
        // format international sans + ni espaces, pour les liens wa.me
        'whatsapp'   => '212636807446',
        // true pour afficher les prix de départ des services
        'show_prices' => false,
        'social' => [
            'facebook'  => '',
            'instagram' => '',
            'tiktok'    => '',
        ],
    ],

    // Langues disponibles ; la première est la langue par défaut
    'languages' => ['ar', 'fr'],
];

if (is_file(__DIR__ . '/config.local.php')) {
    $local = require __DIR__ . '/config.local.php';
    $config = array_replace_recursive($config, $local);
}

return $config;
