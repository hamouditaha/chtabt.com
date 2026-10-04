<?php
require __DIR__ . '/_bootstrap.php';
$pdo = require_admin();
require_post_csrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int) ($_POST['id'] ?? 0);
    $text  = fn (string $key) => trim((string) ($_POST[$key] ?? ''));
    $lines = fn (string $key) => json_encode(
        array_values(array_filter(array_map('trim', explode("\n", $text($key))))),
        JSON_UNESCAPED_UNICODE
    );
    $stmt = $pdo->prepare(
        'UPDATE services SET name_ar = ?, name_fr = ?, description_ar = ?, description_fr = ?,
                long_description_ar = ?, long_description_fr = ?, base_price = ?, duration_minutes = ?,
                features_ar = ?, features_fr = ?, sort_order = ?, status = ? WHERE id = ?'
    );
    $stmt->execute([
        mb_substr($text('name_ar'), 0, 255),
        mb_substr($text('name_fr'), 0, 255),
        $text('description_ar'),
        $text('description_fr'),
        $text('long_description_ar'),
        $text('long_description_fr'),
        max(0, (float) $text('base_price')),
        max(0, (int) $text('duration_minutes')),
        $lines('features_ar'),
        $lines('features_fr'),
        (int) $text('sort_order'),
        $text('status') === 'inactive' ? 'inactive' : 'active',
        $id,
    ]);
    flash('تم حفظ الخدمة.');
    redirect_back('services.php?id=' . $id);
}

$adminPage = 'services';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([(int) $_GET['id']]);
    $s = $stmt->fetch();
    if (!$s) {
        http_response_code(404);
        exit('الخدمة غير موجودة.');
    }
    $features = fn (string $col) => implode("\n", json_decode($s[$col] ?? '[]', true) ?: []);
    $columns = [
        'ar' => ['العربية', 'rtl', 'اسم الخدمة', 'وصف قصير (يظهر في البطاقة)', 'الوصف الكامل (صفحة التفاصيل)', 'ماذا تشمل الخدمة؟ (سطر لكل عنصر)'],
        'fr' => ['Français', 'ltr', 'Nom du service', 'Description courte (carte)', 'Description complète (page détail)', 'Ce qui est inclus (une ligne par élément)'],
    ];
    $adminTitle = 'تعديل: ' . $s['name_ar'];
    require __DIR__ . '/_header.php';
    ?>
        <p><a href="services.php" class="text-decoration-none"><i class="fa-solid fa-arrow-right"></i> العودة إلى القائمة</a></p>
        <section class="admin_card">
          <form method="post" class="row g-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $s['id'] ?>">
            <?php foreach ($columns as $code => [$title, $dir, $nameLabel, $descLabel, $longLabel, $featLabel]): ?>
              <div class="col-lg-6" dir="<?= $dir ?>" lang="<?= $code ?>">
                <h2 class="h6 text-muted mb-3"><?= $title ?></h2>
                <div class="mb-3">
                  <label class="form-label" for="name_<?= $code ?>"><?= $nameLabel ?></label>
                  <input class="form-control" id="name_<?= $code ?>" name="name_<?= $code ?>" value="<?= e($s['name_' . $code]) ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label" for="description_<?= $code ?>"><?= $descLabel ?></label>
                  <input class="form-control" id="description_<?= $code ?>" name="description_<?= $code ?>" value="<?= e($s['description_' . $code]) ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label" for="long_description_<?= $code ?>"><?= $longLabel ?></label>
                  <textarea class="form-control" id="long_description_<?= $code ?>" name="long_description_<?= $code ?>" rows="4"><?= e($s['long_description_' . $code]) ?></textarea>
                </div>
                <div>
                  <label class="form-label" for="features_<?= $code ?>"><?= $featLabel ?></label>
                  <textarea class="form-control" id="features_<?= $code ?>" name="features_<?= $code ?>" rows="6"><?= e($features('features_' . $code)) ?></textarea>
                </div>
              </div>
            <?php endforeach; ?>
            <div class="col-md-3">
              <label class="form-label" for="status">الحالة</label>
              <select class="form-select" id="status" name="status">
                <option value="active"<?= $s['status'] === 'active' ? ' selected' : '' ?>>معروضة في الموقع</option>
                <option value="inactive"<?= $s['status'] === 'inactive' ? ' selected' : '' ?>>مخفية</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="base_price">السعر الابتدائي (<?= e(t('site.currency')) ?>)</label>
              <input type="number" step="1" min="0" class="form-control" id="base_price" name="base_price" value="<?= e((string) (float) $s['base_price']) ?>">
              <div class="form-text">يظهر فقط إذا كان show_prices مفعّلاً.</div>
            </div>
            <div class="col-md-3">
              <label class="form-label" for="duration_minutes">المدة التقريبية (دقائق)</label>
              <input type="number" step="15" min="0" class="form-control" id="duration_minutes" name="duration_minutes" value="<?= (int) $s['duration_minutes'] ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label" for="sort_order">الترتيب</label>
              <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= (int) $s['sort_order'] ?>">
            </div>
            <div class="col-12">
              <button class="btn btn-primary">حفظ التعديلات</button>
              <a href="../service.php?slug=<?= e($s['slug']) ?>&amp;lang=ar" target="_blank" class="btn btn-link">معاينة (ع)</a>
              <a href="../service.php?slug=<?= e($s['slug']) ?>&amp;lang=fr" target="_blank" class="btn btn-link">Aperçu (FR)</a>
            </div>
          </form>
        </section>
    <?php
    require __DIR__ . '/_footer.php';
    exit;
}

$services = $pdo->query('SELECT * FROM services ORDER BY sort_order, id')->fetchAll();

$adminTitle = 'الخدمات';
require __DIR__ . '/_header.php';
?>
        <section class="admin_card">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead><tr><th></th><th>الخدمة</th><th>السعر</th><th>المدة</th><th>الحالة</th><th></th></tr></thead>
              <tbody>
                <?php foreach ($services as $s): ?>
                  <tr>
                    <td style="width: 72px"><img src="../assets/img/<?= e($s['image']) ?>" alt="" class="admin_thumb"></td>
                    <td><strong><?= e($s['name_ar']) ?></strong> <small class="text-muted" dir="ltr">— <?= e($s['name_fr']) ?></small><br><small class="text-muted"><?= e($s['description_ar']) ?></small></td>
                    <td class="text-nowrap"><?= (float) $s['base_price'] > 0 ? e(number_format((float) $s['base_price'], 0)) . ' ' . e(t('site.currency')) : '—' ?></td>
                    <td class="text-nowrap"><?= e(duration_label((int) $s['duration_minutes'])) ?></td>
                    <td><?= $s['status'] === 'active' ? '<span class="badge text-bg-success">معروضة</span>' : '<span class="badge text-bg-secondary">مخفية</span>' ?></td>
                    <td class="text-start"><a href="services.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">تعديل</a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </section>
<?php require __DIR__ . '/_footer.php';
