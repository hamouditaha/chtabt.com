<?php
require __DIR__ . '/_bootstrap.php';
$pdo = require_admin();
require_post_csrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'unsubscribe') {
        $pdo->prepare('UPDATE newsletter_subscribers SET is_active = FALSE, unsubscribed_at = NOW() WHERE id = ?')->execute([$id]);
        flash('تم إلغاء الاشتراك.');
    }
    redirect_back('subscribers.php');
}

if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="subscribers-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF"); // BOM so Excel reads UTF-8
    fputcsv($out, ['email', 'subscribed_at']);
    foreach ($pdo->query('SELECT email, subscribed_at FROM newsletter_subscribers WHERE is_active = TRUE ORDER BY subscribed_at') as $row) {
        fputcsv($out, $row);
    }
    exit;
}

$subscribers = $pdo->query('SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC')->fetchAll();

$adminTitle = 'المشتركون في النشرة';
$adminPage  = 'subscribers';
require __DIR__ . '/_header.php';
?>
        <div class="admin_filters">
          <span class="text-muted"><?= count(array_filter($subscribers, fn ($s) => $s['is_active'])) ?> مشترك نشط</span>
          <a href="subscribers.php?export=csv" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-download"></i> تصدير CSV</a>
        </div>
        <section class="admin_card">
          <?php if (!$subscribers): ?>
            <p class="text-muted mb-0">لا يوجد مشتركون بعد.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead><tr><th>البريد الإلكتروني</th><th>تاريخ الاشتراك</th><th>الحالة</th><th></th></tr></thead>
                <tbody>
                  <?php foreach ($subscribers as $s): ?>
                    <tr>
                      <td dir="ltr" class="text-end"><?= e($s['email']) ?></td>
                      <td class="small"><?= format_date($s['subscribed_at']) ?></td>
                      <td><?= $s['is_active'] ? '<span class="badge text-bg-success">نشط</span>' : '<span class="badge text-bg-secondary">ملغى</span>' ?></td>
                      <td class="text-start">
                        <?php if ($s['is_active']): ?>
                          <form method="post" data-confirm="إلغاء اشتراك هذا البريد؟">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <input type="hidden" name="action" value="unsubscribe">
                            <button class="btn btn-sm btn-link text-danger p-0">إلغاء الاشتراك</button>
                          </form>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </section>
<?php require __DIR__ . '/_footer.php';
