<?php
require __DIR__ . '/_bootstrap.php';
$pdo = require_admin();
require_post_csrf();
$options = t('booking.options');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'delete') {
        $pdo->prepare('DELETE FROM quotes WHERE id = ?')->execute([$id]);
        flash('تم حذف الطلب.');
        redirect_back('quotes.php');
    }
    $status = (string) ($_POST['status'] ?? '');
    if (isset(QUOTE_STATUSES[$status])) {
        $pdo->prepare('UPDATE quotes SET status = ?, admin_notes = ? WHERE id = ?')
            ->execute([$status, trim((string) ($_POST['admin_notes'] ?? '')) ?: null, $id]);
        flash('تم تحديث الطلب.');
    }
    redirect_back('quotes.php?id=' . $id);
}

/** رقم هاتف مغربي محلي (06...) إلى الصيغة الدولية لواتساب. */
function wa_number(string $phone): string
{
    $digits = preg_replace('/\D/', '', $phone);
    if (str_starts_with($digits, '00')) {
        $digits = substr($digits, 2);
    } elseif (str_starts_with($digits, '0')) {
        $digits = '212' . substr($digits, 1);
    }
    return $digits;
}

$adminPage = 'quotes';

// ---------- Detail view ----------
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM quotes WHERE id = ?');
    $stmt->execute([(int) $_GET['id']]);
    $q = $stmt->fetch();
    if (!$q) {
        http_response_code(404);
        exit('الطلب غير موجود.');
    }
    $ref = 'CH-' . str_pad((string) $q['id'], 5, '0', STR_PAD_LEFT);
    $adminTitle = 'طلب ' . $ref;
    require __DIR__ . '/_header.php';

    $rows = [
        'الاسم'          => e($q['name']),
        'الهاتف'         => '<a href="tel:' . e($q['phone']) . '" dir="ltr">' . e($q['phone']) . '</a>',
        'البريد'         => $q['email'] ? '<a href="mailto:' . e($q['email']) . '">' . e($q['email']) . '</a>' : '—',
        'الخدمة'         => e($q['service_type']),
        'حجم المكان'     => e($options['property_size'][$q['property_size']] ?? '—'),
        'التكرار'        => e($options['frequency'][$q['frequency']] ?? '—'),
        'التاريخ المفضل' => format_date($q['preferred_date'], false),
        'الفترة'         => e($options['preferred_time'][$q['preferred_time']] ?? 'أي وقت'),
        'المدينة'        => e($q['city']),
        'العنوان'        => e($q['address'] ?: '—'),
        'تاريخ الطلب'    => format_date($q['created_at']),
        'لغة العميل'     => ($q['lang'] ?? 'ar') === 'fr' ? 'الفرنسية (FR)' : 'العربية',
    ];
    $waText = ($q['lang'] ?? 'ar') === 'fr'
        ? "Bonjour {$q['name']}, ici l'équipe {$site['name_latin']} au sujet de votre demande n° {$ref}."
        : "السلام عليكم {$q['name']}، معكم فريق {$site['name_ar']} بخصوص طلبكم رقم {$ref} ({$q['service_type']}).";
    ?>
        <p><a href="quotes.php" class="text-decoration-none"><i class="fa-solid fa-arrow-right"></i> العودة إلى القائمة</a></p>
        <div class="row g-4">
          <div class="col-lg-7">
            <section class="admin_card">
              <div class="admin_card_head"><h2>تفاصيل الطلب</h2><?= status_badge($q['status'], QUOTE_STATUSES) ?></div>
              <dl class="admin_dl">
                <?php foreach ($rows as $label => $value): ?>
                  <dt><?= $label ?></dt><dd><?= $value ?></dd>
                <?php endforeach; ?>
              </dl>
              <?php if ($q['message']): ?>
                <h3 class="h6 mt-4">ملاحظات العميل</h3>
                <p class="admin_message"><?= nl2br(e($q['message'])) ?></p>
              <?php endif; ?>
              <a href="https://wa.me/<?= e(wa_number($q['phone'])) ?>?text=<?= rawurlencode($waText) ?>" target="_blank" rel="noopener" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i> مراسلة العميل على واتساب</a>
            </section>
          </div>
          <div class="col-lg-5">
            <section class="admin_card">
              <div class="admin_card_head"><h2>المتابعة</h2></div>
              <form method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $q['id'] ?>">
                <div class="mb-3">
                  <label class="form-label" for="status">الحالة</label>
                  <select name="status" id="status" class="form-select">
                    <?php foreach (QUOTE_STATUSES as $value => [$label]): ?>
                      <option value="<?= $value ?>"<?= $q['status'] === $value ? ' selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label" for="admin_notes">ملاحظات داخلية</label>
                  <textarea name="admin_notes" id="admin_notes" rows="5" class="form-control" placeholder="السعر المتفق عليه، اسم المنظف، الموعد النهائي..."><?= e($q['admin_notes']) ?></textarea>
                </div>
                <button class="btn btn-primary w-100">حفظ</button>
              </form>
              <form method="post" class="mt-3" data-confirm="حذف هذا الطلب نهائياً؟">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $q['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <button class="btn btn-outline-danger btn-sm w-100">حذف الطلب</button>
              </form>
            </section>
          </div>
        </div>
    <?php
    require __DIR__ . '/_footer.php';
    exit;
}

// ---------- List view ----------
$status = (string) ($_GET['status'] ?? '');
$search = trim((string) ($_GET['q'] ?? ''));
$page   = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;

$where  = [];
$params = [];
if (isset(QUOTE_STATUSES[$status])) {
    $where[]  = 'status = ?';
    $params[] = $status;
}
if ($search !== '') {
    $where[] = '(name LIKE ? OR phone LIKE ? OR email LIKE ? OR city LIKE ?)';
    array_push($params, ...array_fill(0, 4, '%' . $search . '%'));
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $pdo->prepare("SELECT COUNT(*) FROM quotes $whereSql");
$stmt->execute($params);
$total = (int) $stmt->fetchColumn();
$pages = max(1, (int) ceil($total / $perPage));

$stmt = $pdo->prepare("SELECT * FROM quotes $whereSql ORDER BY created_at DESC LIMIT $perPage OFFSET " . (($page - 1) * $perPage));
$stmt->execute($params);
$quotes = $stmt->fetchAll();

$adminTitle = 'طلبات الحجز';
require __DIR__ . '/_header.php';
?>
        <form class="admin_filters" method="get">
          <div class="btn-group flex-wrap" role="group" aria-label="تصفية حسب الحالة">
            <a href="quotes.php" class="btn btn-sm <?= $status === '' ? 'btn-dark' : 'btn-outline-dark' ?>">الكل</a>
            <?php foreach (QUOTE_STATUSES as $value => [$label]): ?>
              <a href="quotes.php?status=<?= $value ?>" class="btn btn-sm <?= $status === $value ? 'btn-dark' : 'btn-outline-dark' ?>"><?= $label ?></a>
            <?php endforeach; ?>
          </div>
          <input type="hidden" name="status" value="<?= e($status) ?>">
          <input type="search" name="q" value="<?= e($search) ?>" class="form-control form-control-sm" placeholder="بحث بالاسم، الهاتف، المدينة...">
        </form>

        <section class="admin_card">
          <?php if (!$quotes): ?>
            <p class="text-muted mb-0">لا توجد نتائج.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr><th>#</th><th>العميل</th><th>الخدمة</th><th>المدينة</th><th>الموعد المفضل</th><th>الحالة</th><th>تاريخ الطلب</th></tr>
                </thead>
                <tbody>
                  <?php foreach ($quotes as $q): ?>
                    <tr class="<?= $q['status'] === 'new' ? 'fw-bold' : '' ?>">
                      <td><a href="quotes.php?id=<?= $q['id'] ?>">CH-<?= str_pad((string) $q['id'], 5, '0', STR_PAD_LEFT) ?></a></td>
                      <td><?= e($q['name']) ?><br><small class="text-muted fw-normal" dir="ltr"><?= e($q['phone']) ?></small></td>
                      <td><?= e($q['service_type']) ?></td>
                      <td><?= e($q['city']) ?></td>
                      <td class="text-nowrap"><?= format_date($q['preferred_date'], false) ?></td>
                      <td><?= status_badge($q['status'], QUOTE_STATUSES) ?></td>
                      <td class="small text-nowrap fw-normal"><?= format_date($q['created_at']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </section>

        <?php if ($pages > 1): ?>
          <nav class="mt-3" aria-label="الصفحات">
            <ul class="pagination pagination-sm">
              <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item<?= $i === $page ? ' active' : '' ?>">
                  <a class="page-link" href="?<?= e(http_build_query(['status' => $status, 'q' => $search, 'page' => $i])) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
<?php require __DIR__ . '/_footer.php';
