<?php
$adminTitle = $adminTitle ?? 'لوحة التحكم';
$adminPage  = $adminPage ?? '';
$menu = [
    'dashboard'   => ['index.php', 'fa-gauge', 'الرئيسية'],
    'quotes'      => ['quotes.php', 'fa-calendar-check', 'طلبات الحجز'],
    'messages'    => ['messages.php', 'fa-inbox', 'الرسائل'],
    'subscribers' => ['subscribers.php', 'fa-users', 'المشتركون'],
    'services'    => ['services.php', 'fa-broom', 'الخدمات'],
];
$flash = flash();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="../assets/img/favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/admin.css">
    <title><?= e($adminTitle) ?> | <?= e($site['name_ar']) ?></title>
  </head>
  <body>
    <div class="admin_layout">
      <aside class="admin_sidebar">
        <a href="index.php" class="admin_brand"><img src="../assets/img/logo-white.png" alt="<?= e($site['name_latin']) ?>"></a>
        <nav>
          <?php foreach ($menu as $key => [$href, $icon, $label]): ?>
            <a href="<?= $href ?>" class="<?= $adminPage === $key ? 'active' : '' ?>"><i class="fa-solid <?= $icon ?>"></i> <?= $label ?></a>
          <?php endforeach; ?>
        </nav>
        <div class="admin_sidebar_footer">
          <a href="../index.php" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> عرض الموقع</a>
          <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج</a>
        </div>
      </aside>
      <main class="admin_main">
        <header class="admin_topbar">
          <h1><?= e($adminTitle) ?></h1>
          <span class="text-muted small"><i class="fa-solid fa-user"></i> <?= e(current_admin()['name'] ?? '') ?></span>
        </header>
        <?php if ($flash): ?>
          <div class="alert alert-success"><?= e($flash) ?></div>
        <?php endif; ?>
