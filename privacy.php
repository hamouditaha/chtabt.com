<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle   = t('privacy.title');
$lightHeader = true;

require __DIR__ . '/includes/header.php';
?>
      <section>
        <div class="cs_height_150 cs_height_lg_100"></div>
        <div class="container p-0">
          <article class="cs_legal_content">
            <h1 class="cs_fs_36 cs_mb_16"><?= e(t('privacy.title')) ?></h1>
            <p><?= e(t('privacy.intro', ['company' => $site['company'], 'name' => site_name()])) ?></p>
            <?php foreach (t('privacy.sections') as [$heading, $text]): ?>
              <h2><?= e($heading) ?></h2>
              <p><?= e($text) ?></p>
            <?php endforeach; ?>
            <h2><?= e(t('privacy.contact_title')) ?></h2>
            <p class="mb-0"><?= e(t('privacy.contact_text')) ?> <a href="mailto:<?= e($site['email']) ?>" class="cs_blue_dark"><?= e($site['email']) ?></a>.</p>
          </article>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>
<?php require __DIR__ . '/includes/footer.php';
