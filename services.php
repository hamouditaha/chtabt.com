<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle       = t('services.title');
$pageDescription = t('services.description');
$activePage      = 'services';

require __DIR__ . '/includes/header.php';

$hero = t('services.hero');
require __DIR__ . '/includes/page-hero.php';
?>
      <!-- Services -->
      <section class="cs_service_section">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_blue_bg3 cs_mb_16" data-aos="zoom-in"><?= e(t('services.sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 cs_black_color cs_mb_16"><?= e(t('services.heading')) ?></h2>
            <p class="cs_primary_font mb-0"><?= e(t('services.text')) ?></p>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="row cs_gap_y_24 justify-content-center">
            <?php
            foreach (services() as $service) {
                $aos = 'fade-up';
                require __DIR__ . '/includes/service-card.php';
            }
            ?>
          </div>
        </div>
      </section>

      <!-- Promotion -->
      <section class="cs_promotion_section">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_cta_style_3 cs_radius_16 cs_bg_filed" data-src="assets/img/promo-img3.jpg">
            <div class="cs_cta_text">
              <h2 class="cs_cta_title cs_fs_56 cs_blue_dark cs_mb_16" data-aos="fade-down"><?= e(t('services.promo_title')) ?></h2>
              <p class="cs_primary_color cs_primary_font cs_mb_16"><?= e(t('services.promo_text')) ?></p>
            </div>
            <a href="booking.php" class="cs_btn_style_1 cs_accent_bg cs_primary_color cs_radius_32" data-aos="fade-up"><span><?= e(t('services.promo_button')) ?></span></a>
          </div>
        </div>
      </section>

      <!-- How it works -->
      <section class="cs_how_work_section">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_blue_bg3 cs_mb_16"><?= e(t('services.how_sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 cs_black_color cs_mb_16" data-aos="zoom-in"><?= e(t('services.how_title')) ?></h2>
            <p class="cs_primary_color cs_primary_font mb-0"><?= e(t('services.how_text')) ?></p>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="row cs_gap_y_24 align-items-center">
            <div class="col-lg-6 order-lg-2">
              <div class="cs_steps_thumbnails_wrapper">
                <?php foreach (['how-to-work-2.jpg', 'how-to-work-1.jpg', 'how-to-work-3.jpg', 'how-to-work.jpg'] as $i => $img): ?>
                  <div class="cs_step_thumbnail cs_radius_20"><img src="assets/img/<?= $img ?>" alt="<?= e(t('services.step_alts')[$i]) ?>" loading="lazy"></div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="cs_iconbox_wrapper_style_2">
                <?php foreach (t('services.steps') as $i => [$icon, $title, $text]): ?>
                  <div class="cs_iconbox_style_2<?= $i === 0 ? ' active' : '' ?>" role="button" tabindex="0">
                    <span class="cs_iconbox_icon cs_center"><i class="<?= $icon ?>"></i></span>
                    <div class="cs_iconbox_info">
                      <h3 class="cs_iconbox_title cs_fs_20 cs_mb_12"><?= ($i + 1) . '. ' . e($title) ?></h3>
                      <p class="cs_iconbox_subtitle mb-0"><?= e($text) ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>

<?php
$cta = t('services.cta');
require __DIR__ . '/includes/cta.php';
require __DIR__ . '/includes/footer.php';
