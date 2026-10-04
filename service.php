<?php
require __DIR__ . '/includes/bootstrap.php';

$service = service_by_slug((string) ($_GET['slug'] ?? ''));
if (!$service) {
    http_response_code(404);
}

$pageTitle       = $service ? loc($service, 'name') : t('service.not_found');
$pageDescription = $service ? loc($service, 'description') : null;
$activePage      = 'services';
$lightHeader     = true;

require __DIR__ . '/includes/header.php';
?>
      <section class="cs_service_section">
        <div class="cs_height_150 cs_height_lg_100"></div>
        <div class="container p-0">
          <?php if (!$service): ?>
            <div class="cs_section_heading_style_1 cs_center_column text-center">
              <h1 class="cs_section_title cs_fs_36 cs_mb_16"><?= e(t('service.not_found_title')) ?></h1>
              <p class="cs_mb_24"><?= e(t('service.not_found_text')) ?></p>
              <a href="services.php" class="cs_btn_style_1 cs_accent_bg cs_primary_color cs_radius_32"><span><?= e(t('service.all')) ?></span></a>
            </div>
          <?php else:
            $name     = loc($service, 'name');
            $minutes  = (int) $service['duration_minutes'];
            $features = loc($service, 'features');
            ?>
            <nav aria-label="<?= e(t('ui.breadcrumb')) ?>" class="cs_breadcrumb cs_mb_16">
              <a href="index.php"><?= e(t('nav.home')) ?></a> / <a href="services.php"><?= e(t('nav.services')) ?></a> / <span aria-current="page"><?= e($name) ?></span>
            </nav>
            <div class="cs_section_heading_style_1 cs_center_column text-center">
              <h1 class="cs_section_title cs_fs_36 cs_mb_16" data-aos="zoom-in"><?= e($name) ?></h1>
              <p class="cs_primary_color cs_primary_font mb-0"><?= e(loc($service, 'description')) ?></p>
            </div>
            <div class="cs_height_50 cs_height_lg_24"></div>
            <div class="cs_service_details_banner cs_radius_16" data-aos="fade-up">
              <img src="assets/img/<?= e($service['image']) ?>" alt="<?= e($name) ?>" class="d-block w-100 h-100">
            </div>
            <div class="cs_height_50 cs_height_lg_24"></div>
            <div class="cs_service_details">
              <div class="row cs_gap_y_24">
                <div class="col-lg-4 order-lg-2">
                  <aside class="cs_service_contact cs_radius_16">
                    <?php if ($site['show_prices'] && (float) $service['base_price'] > 0): ?>
                      <p class="cs_primary_font cs_mb_8"><?= e(t('service.from')) ?></p>
                      <div class="cs_service_price cs_fs_36 cs_semibold cs_blue_dark cs_primary_font mb-0"><?= e(number_format((float) $service['base_price'], 0)) ?> <small><?= e(t('site.currency')) ?></small></div>
                      <hr>
                    <?php endif; ?>
                    <?php if ($minutes > 0): ?>
                      <p class="cs_primary_font cs_mb_8"><?= e(t('service.duration')) ?></p>
                      <h2 class="cs_fs_18 cs_medium cs_mb_20"><?= e(t('service.duration_note', ['duration' => duration_label($minutes)])) ?></h2>
                    <?php endif; ?>
                    <p class="cs_primary_font cs_mb_8"><?= e(t('service.area')) ?></p>
                    <h2 class="cs_fs_18 cs_medium cs_mb_20"><?= e(t('service.area_text', ['city' => t('site.city')])) ?></h2>
                    <a href="booking.php?service=<?= e($service['slug']) ?>" class="cs_btn_style_1 cs_center cs_accent_bg cs_primary_color cs_radius_32 w-100"><span><?= e(t('service.book')) ?></span></a>
                    <a href="<?= e(whatsapp_link(t('service.whatsapp_msg', ['service' => $name]))) ?>" target="_blank" rel="noopener" class="cs_btn_style_1 cs_center cs_btn_whatsapp cs_radius_32 w-100"><span><i class="fa-brands fa-whatsapp"></i><?= e(t('service.whatsapp')) ?></span></a>
                  </aside>
                </div>
                <div class="col-lg-8">
                  <div class="cs_service_desc">
                    <h2><?= e(t('service.desc_title')) ?></h2>
                    <p><?= e(loc($service, 'long_description') ?: loc($service, 'description')) ?></p>
                    <?php if ($features): ?>
                      <h2><?= e(t('service.includes_title')) ?></h2>
                      <ul class="cs_list_style_1 cs_mp_0 cs_primary_font">
                        <?php foreach ($features as $feature): ?>
                          <li>
                            <span class="cs_blue_dark"><i class="fa-solid fa-circle-check"></i></span>
                            <span><?= e($feature) ?></span>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>

      <?php if ($service): ?>
        <!-- Other services -->
        <section class="cs_service_section cs_blue_bg3 cs_radius_16">
          <div class="cs_height_80 cs_height_lg_40"></div>
          <div class="container p-0">
            <div class="cs_section_heading_style_1 cs_center_column text-center">
              <p class="cs_section_subtitle cs_white_bg cs_mb_16"><?= e(t('service.others_sub')) ?></p>
              <h2 class="cs_section_title cs_fs_36 cs_black_color mb-0" data-aos="zoom-in"><?= e(t('service.others_title')) ?></h2>
            </div>
            <div class="cs_height_50 cs_height_lg_24"></div>
            <div class="row cs_gap_y_24 justify-content-center">
              <?php
              $current = $service['slug'];
              $others  = array_filter(services(), fn ($s) => $s['slug'] !== $current);
              foreach (array_slice($others, 0, 3) as $service) {
                  $aos = 'fade-up';
                  require __DIR__ . '/includes/service-card.php';
              }
              ?>
            </div>
          </div>
          <div class="cs_height_80 cs_height_lg_40"></div>
        </section>
      <?php endif; ?>
<?php require __DIR__ . '/includes/footer.php';
