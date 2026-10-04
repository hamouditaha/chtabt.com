<?php
require __DIR__ . '/_bootstrap.php';
$pdo = require_admin();

$count = fn (string $sql) => (int) $pdo->query($sql)->fetchColumn();

$cards = [
    ['طلبات حجز جديدة', $count("SELECT COUNT(*) FROM quotes WHERE status = 'new'"), 'fa-calendar-check', 'quotes.php?status=new', 'primary'],
    ['رسائل غير مقروءة', $count("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'"), 'fa-inbox', 'messages.php?status=new', 'warning'],
    ['حجوزات مؤكدة هذا الشهر', $count("SELECT COUNT(*) FROM quotes WHERE status = 'converted' AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')"), 'fa-circle-check', 'quotes.php?status=converted', 'success'],
    ['مشتركون في النشرة', $count('SELECT COUNT(*) FROM newsletter_subscribers WHERE is_active = TRUE'), 'fa-users', 'subscribers.php', 'info'],
];

$latestQuotes   = $pdo->query('SELECT * FROM quotes ORDER BY created_at DESC LIMIT 6')->fetchAll();
$latestMessages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 6')->fetchAll();

$adminTitle = 'الرئيسية';
$adminPage  = 'dashboard';
require __DIR__ . '/_header.php';
?>
        <div class="row g-3 mb-4">
          <?php foreach ($cards as [$label, $value, $icon, $href, $color]): ?>
            <div class="col-sm-6 col-xl-3">
              <a href="<?= $href ?>" class="admin_stat admin_stat_<?= $color ?>">
                <i class="fa-solid <?= $icon ?>"></i>
                <div>
                  <strong><?= $value ?></strong>
                  <span><?= e($label) ?></span>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="row g-4">
          <div class="col-xl-7">
            <section class="admin_card">
              <div class="admin_card_head">
                <h2>أحدث طلبات الحجز</h2>
                <a href="quotes.php">عرض الكل</a>
              </div>
              <?php if (!$latestQuotes): ?>
                <p class="text-muted mb-0">لا توجد طلبات بعد.</p>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>العميل</th><th>الخدمة</th><th>المدينة</th><th>الحالة</th><th>التاريخ</th></tr></thead>
                    <tbody>
                      <?php foreach ($latestQuotes as $q): ?>
                        <tr>
                          <td><a href="quotes.php?id=<?= $q['id'] ?>"><?= e($q['name']) ?></a><br><small class="text-muted" dir="ltr"><?= e($q['phone']) ?></small></td>
                          <td><?= e($q['service_type']) ?></td>
                          <td><?= e($q['city']) ?></td>
                          <td><?= status_badge($q['status'], QUOTE_STATUSES) ?></td>
                          <td class="small text-nowrap"><?= format_date($q['created_at']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </section>
          </div>
          <div class="col-xl-5">
            <section class="admin_card">
              <div class="admin_card_head">
                <h2>أحدث الرسائل</h2>
                <a href="messages.php">عرض الكل</a>
              </div>
              <?php if (!$latestMessages): ?>
                <p class="text-muted mb-0">لا توجد رسائل بعد.</p>
              <?php else: ?>
                <ul class="admin_list">
                  <?php foreach ($latestMessages as $m): ?>
                    <li>
                      <a href="messages.php?id=<?= $m['id'] ?>">
                        <strong><?= e($m['name']) ?></strong> <?= status_badge($m['status'], MESSAGE_STATUSES) ?>
                        <span class="d-block text-muted small text-truncate"><?= e($m['subject']) ?> — <?= e(mb_substr($m['message'], 0, 80)) ?></span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </section>
          </div>
        </div>
<?php require __DIR__ . '/_footer.php';
