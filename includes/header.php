<?php
/**
 * Variables attendues de la page :
 *   $pageTitle, $pageDescription, $activePage (home|about|services|booking|contact)
 *   $lightHeader (bool) — en-tête blanc pour les pages sans bandeau bleu
 */
$pageTitle       = $pageTitle ?? t('meta.tagline');
$pageDescription = $pageDescription ?? t('meta.description');
$activePage      = $activePage ?? '';
$lightHeader     = $lightHeader ?? false;
$otherLang       = lang() === 'ar' ? 'fr' : 'ar';

$navItems = [
    'home'     => 'index.php',
    'about'    => 'about.php',
    'services' => 'services.php',
    'contact'  => 'contact.php',
];
?>
<!DOCTYPE html>
<html lang="<?= lang() ?>" dir="<?= is_rtl() ? 'rtl' : 'ltr' ?>">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="theme-color" content="#0B8A8A">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="<?= e(t('meta.locale')) ?>">
    <meta property="og:site_name" content="<?= e(site_name()) ?>">
    <meta property="og:title" content="<?= e($pageTitle . ' | ' . site_name()) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:image" content="assets/img/hero-img1.jpg">
    <?php foreach ($config['languages'] as $code): ?>
      <link rel="alternate" hreflang="<?= $code ?>" href="<?= e(lang_url($code)) ?>">
    <?php endforeach; ?>
    <link rel="icon" href="assets/img/favicon.png">
    <?php if (is_rtl()): ?>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@500;600;700;800&display=swap">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/aos.min.css">
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <link rel="stylesheet" href="assets/css/odometer.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if (is_rtl()): ?>
      <link rel="stylesheet" href="assets/css/rtl.css">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/custom.css">
    <title><?= e($pageTitle . ' | ' . site_name()) ?></title>
  </head>
  <body>
    <div class="cs_preloader cs_center">
      <div class="cs_preloader_in">
        <div class="cs_preloader_text cs_fs_72 cs_extrabold cs_primary_font" data-text="<?= e(site_name()) ?>"><?= e(site_name()) ?></div>
      </div>
    </div>
    <div class="cs_custom_content_wrapper">
      <header class="cs_site_header cs_style_1 cs_sticky_header cs_primary_font <?= $lightHeader ? 'cs_type_1 cs_white_bg cs_primary_color' : 'cs_white_color' ?>">
        <div class="cs_header_main">
          <div class="container p-0">
            <div class="cs_main_header_in">
              <div class="cs_main_header_left">
                <a href="index.php" aria-label="<?= e(t('nav.home_link', ['name' => site_name()])) ?>" class="cs_site_brand">
                  <img src="assets/img/logo.png" alt="<?= e($site['name_latin']) ?>" class="cs_site_logo" width="150" height="45">
                </a>
              </div>
              <div class="cs_main_header_center">
                <nav class="cs_nav" aria-label="<?= e(t('nav.menu')) ?>">
                  <div class="cs_nav_list_wrap">
                    <ul class="cs_nav_list cs_mp_0">
                      <?php foreach ($navItems as $key => $href): ?>
                        <li class="<?= $activePage === $key ? 'active' : '' ?>">
                          <a href="<?= $href ?>"<?= $activePage === $key ? ' aria-current="page"' : '' ?>><?= e(t('nav.' . $key)) ?></a>
                        </li>
                      <?php endforeach; ?>
                      <li class="d-xl-none"><a href="<?= e(lang_url($otherLang)) ?>" lang="<?= $otherLang ?>" hreflang="<?= $otherLang ?>"><i class="fa-solid fa-globe"></i> <?= e(t('nav.switch')) ?></a></li>
                      <li class="d-xl-none"><a href="booking.php"><?= e(t('nav.booking')) ?></a></li>
                    </ul>
                  </div>
                </nav>
              </div>
              <div class="cs_main_header_right">
                <a href="<?= e(lang_url($otherLang)) ?>" class="cs_lang_switch" lang="<?= $otherLang ?>" hreflang="<?= $otherLang ?>" title="<?= e(t('nav.switch')) ?>" aria-label="<?= e(t('nav.switch')) ?>"><?= e(t('nav.switch_short')) ?></a>
                <a href="booking.php" class="cs_btn_style_1 cs_radius_32 <?= $lightHeader ? 'cs_accent_bg cs_primary_color' : '' ?>"><span><?= e(t('nav.booking')) ?></span></a>
              </div>
            </div>
          </div>
        </div>
      </header>
