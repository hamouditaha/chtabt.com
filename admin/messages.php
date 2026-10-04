<?php
require __DIR__ . '/_bootstrap.php';
$pdo = require_admin();
require_post_csrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');
    if ($action === 'delete') {
        $pdo->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([$id]);
        flash('تم حذف الرسالة.');
        redirect_back('messages.php');
    }
    if (isset(MESSAGE_STATUSES[$action])) {
        $pdo->prepare('UPDATE contact_messages SET status = ? WHERE id = ?')->execute([$action, $id]);
        flash('تم تحديث حالة الرسالة.');
    }
    redirect_back('messages.php?id=' . $id);
}

$adminPage = 'messages';

// ---------- Detail view ----------
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM contact_messages WHERE id = ?');
    $stmt->execute([(int) $_GET['id']]);
    $m = $stmt->fetch();
    if (!$m) {
        http_response_code(404);
        exit('الرسالة غير موجودة.');
    }
    if ($m['status'] === 'new') {
        $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?")->execute([$m['id']]);
        $m['status'] = 'read';
    }
    $adminTitle = 'رسالة من ' . $m['name'];
    require __DIR__ . '/_header.php';
    $replySubject = 'رد: ' . ($m['subject'] ?: 'رسالتك إلى ' . $site['name_ar']);
    ?>
        <p><a href="messages.php" class="text-decoration-none"><i class="fa-solid fa-arrow-right"></i> العودة إلى القائمة</a></p>
        <section class="admin_card">
          <div class="admin_card_head"><h2><?= e($m['subject'] ?: 'استفسار عام') ?></h2><?= status_badge($m['status'], MESSAGE_STATUSES) ?></div>
          <dl class="admin_dl">
            <dt>الاسم</dt><dd><?= e($m['name']) ?></dd>
            <dt>البريد</dt><dd><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></dd>
            <dt>الهاتف</dt><dd dir="ltr" class="text-end"><?= $m['phone'] ? '<a href="tel:' . e($m['phone']) . '">' . e($m['phone']) . '</a>' : '—' ?></dd>
            <dt>التاريخ</dt><dd><?= format_date($m['created_at']) ?></dd>
            <dt>لغة العميل</dt><dd><?= ($m['lang'] ?? 'ar') === 'fr' ? 'الفرنسية (FR)' : 'العربية' ?></dd>
          </dl>
          <p class="admin_message"><?= nl2br(e($m['message'])) ?></p>
          <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="mailto:<?= e($m['email']) ?>?subject=<?= rawurlencode($replySubject) ?>"><i class="fa-solid fa-reply"></i> الرد بالبريد</a>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <input type="hidden" name="action" value="replied">
              <button class="btn btn-outline-success"><i class="fa-solid fa-check"></i> تم الرد</button>
            </form>
            <form method="post" data-confirm="حذف هذه الرسالة نهائياً؟">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <input type="hidden" name="action" value="delete">
              <button class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i> حذف</button>
            </form>
          </div>
        </section>
    <?php
    require __DIR__ . '/_footer.php';
    exit;
}

// ---------- List view ----------
$status = (string) ($_GET['status'] ?? '');
$params = [];
$whereSql = '';
if (isset(MESSAGE_STATUSES[$status])) {
    $whereSql = 'WHERE status = ?';
    $params[] = $status;
}
$stmt = $pdo->prepare("SELECT * FROM contact_messages $whereSql ORDER BY created_at DESC LIMIT 200");
$stmt->execute($params);
$messages = $stmt->fetchAll();

$adminTitle = 'الرسائل';
require __DIR__ . '/_header.php';
?>
        <div class="admin_filters">
          <div class="btn-group flex-wrap" role="group" aria-label="تصفية حسب الحالة">
            <a href="messages.php" class="btn btn-sm <?= $status === '' ? 'btn-dark' : 'btn-outline-dark' ?>">الكل</a>
            <?php foreach (MESSAGE_STATUSES as $value => [$label]): ?>
              <a href="messages.php?status=<?= $value ?>" class="btn btn-sm <?= $status === $value ? 'btn-dark' : 'btn-outline-dark' ?>"><?= $label ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <section class="admin_card">
          <?php if (!$messages): ?>
            <p class="text-muted mb-0">لا توجد رسائل.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead><tr><th>المرسل</th><th>الموضوع</th><th>الرسالة</th><th>الحالة</th><th>التاريخ</th></tr></thead>
                <tbody>
                  <?php foreach ($messages as $m): ?>
                    <tr class="<?= $m['status'] === 'new' ? 'fw-bold' : '' ?>">
                      <td><a href="messages.php?id=<?= $m['id'] ?>"><?= e($m['name']) ?></a><br><small class="text-muted fw-normal"><?= e($m['email']) ?></small></td>
                      <td><?= e($m['subject']) ?></td>
                      <td class="text-muted fw-normal"><?= e(mb_substr($m['message'], 0, 70)) ?><?= mb_strlen($m['message']) > 70 ? '…' : '' ?></td>
                      <td><?= status_badge($m['status'], MESSAGE_STATUSES) ?></td>
                      <td class="small text-nowrap fw-normal"><?= format_date($m['created_at']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </section>
<?php require __DIR__ . '/_footer.php';
