<?php
require __DIR__ . '/_bootstrap.php';

if (current_admin()) {
    header('Location: index.php');
    exit;
}

$error = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    $attempts = array_filter($_SESSION['login_attempts'] ?? [], fn ($t) => $t > time() - 900);
    $_SESSION['login_attempts'] = $attempts;

    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $error = 'انتهت صلاحية الصفحة، حاول مرة أخرى.';
    } elseif (count($attempts) >= 5) {
        $error = 'محاولات كثيرة. انتظر 15 دقيقة ثم حاول مجدداً.';
    } elseif (!($pdo = db())) {
        $error = 'قاعدة البيانات غير متاحة. تحقق من الإعدادات.';
    } else {
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ? AND user_type = 'admin' AND status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            unset($_SESSION['login_attempts']);
            $_SESSION['admin'] = ['id' => (int) $user['id'], 'name' => $user['name'], 'email' => $user['email']];
            $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')->execute([$user['id']]);
            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')
                    ->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
            }
            header('Location: index.php');
            exit;
        }
        $_SESSION['login_attempts'][] = time();
        $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة.';
    }
}
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
    <link rel="stylesheet" href="assets/admin.css">
    <title>تسجيل الدخول | <?= e($site['name_ar']) ?></title>
  </head>
  <body class="admin_login_page">
    <main class="admin_login_card">
      <img src="../assets/img/logo.png" alt="<?= e($site['name_latin']) ?>" class="admin_login_logo">
      <h1 class="h4 mb-4">لوحة التحكم</h1>
      <?php if ($error): ?>
        <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label for="email" class="form-label">البريد الإلكتروني</label>
          <input type="email" id="email" name="email" class="form-control" value="<?= e($email) ?>" required autofocus dir="ltr" autocomplete="username">
        </div>
        <div class="mb-4">
          <label for="password" class="form-label">كلمة المرور</label>
          <input type="password" id="password" name="password" class="form-control" required dir="ltr" autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary w-100">دخول</button>
      </form>
    </main>
  </body>
</html>
