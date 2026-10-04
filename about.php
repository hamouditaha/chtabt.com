<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle       = t('about.title');
$pageDescription = t('about.description');
$activePage      = 'about';

require __DIR__ . '/includes/header.php';

$hero = t('about.hero');
require __DIR__ . '/includes/page-hero.php';
?>
      <!-- About -->
      <section class="cs_about_style_2">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_about_content position-relative">
            <div class="cs_section_heading_style_1 cs_mb_40 cs_mb_lg_24">
              <p class="cs_section_subtitle cs_blue_bg3 cs_mb_16" data-aos="fade-down"><?= e(t('about.offer_sub')) ?></p>
              <h2 class="cs_section_title cs_fs_36 cs_mb_16" data-aos="fade-up"><?= e(t('about.offer_title')) ?></h2>
              <p class="mb-0"><?= e(t('about.offer_text')) ?></p>
            </div>
            <div class="cs_about_thumbnail cs_radius_16 cs_mb_24" data-aos="zoom-in">
              <img src="assets/img/about-img1.jpg" alt="<?= e(t('about.offer_alt')) ?>" loading="lazy">
            </div>
            <div class="cs_funfact_wrapper_style_1">
              <?php foreach (t('about.stats') as [$number, $suffix, $label]): ?>
                <div class="cs_funfact_style_1">
                  <div class="cs_funfact_number cs_fs_36 cs_semibold cs_blue_dark cs_primary_font cs_mb_6" dir="ltr">
                    <span class="odometer" data-count-to="<?= $number ?>"></span><?= $suffix ?>
                  </div>
                  <h3 class="cs_funfact_title cs_fs_18 cs_secondary_color cs_normal mb-0"><?= e($label) ?></h3>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>

      <!-- Identity -->
      <section class="cs_benefit_section cs_blue_bg3 cs_radius_16">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_white_bg cs_mb_16"><?= e(t('about.identity_sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 mb-0" data-aos="fade-up"><?= e(t('about.identity_title')) ?></h2>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="row cs_gap_y_24">
            <div class="col-lg-4 order-lg-2">
              <div class="cs_benefit_thumbnail cs_radius_16 w-100 h-100" data-aos="zoom-in">
                <img src="assets/img/benefit-img1.jpg" alt="<?= e(t('about.benefit_alt')) ?>" class="w-100 h-100" loading="lazy">
              </div>
            </div>
            <div class="col-lg-4 order-lg-1">
              <div class="cs_card_style_2 cs_white_bg cs_radius_16">
                <div class="cs_top_content">
                  <h3 class="cs_fs_20 cs_mb_16"><?= e(t('about.org_title')) ?></h3>
                  <p class="mb-0"><?= e(t('about.org_text')) ?></p>
                </div>
                <div class="cs_bottom_content">
                  <ul class="cs_list_style_1 cs_mp_0">
                    <li>
                      <p class="cs_mb_8"><?= e(t('about.legal_name')) ?></p>
                      <h4 class="cs_fs_16 cs_semibold mb-0"><?= e($site['company']) ?></h4>
                    </li>
                    <li>
                      <p class="cs_mb_8"><?= e(t('about.hq')) ?></p>
                      <h4 class="cs_fs_16 cs_semibold mb-0"><?= e(t('site.city')) ?></h4>
                    </li>
                    <li>
                      <p class="cs_mb_8"><?= e(t('about.hours')) ?></p>
                      <h4 class="cs_fs_16 cs_semibold mb-0"><?= e(t('site.hours')) ?></h4>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-4 order-lg-3">
              <div class="cs_card_style_3 cs_white_bg cs_radius_16 cs_mb_24">
                <div class="cs_top_content">
                  <h3 class="cs_fs_20 mb-0"><?= e(t('about.vision_title')) ?></h3>
                </div>
                <div class="cs_bottom_content"><?= e(t('about.vision_text')) ?></div>
              </div>
              <div class="cs_card_style_3 cs_white_bg cs_radius_16">
                <div class="cs_top_content">
                  <h3 class="cs_fs_20 mb-0"><?= e(t('about.mission_title')) ?></h3>
                </div>
                <div class="cs_bottom_content"><?= e(t('about.mission_text')) ?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>

      <!-- Values -->
      <section>
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_blue_bg3 cs_mb_16"><?= e(t('about.values_sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 mb-0" data-aos="fade-up"><?= e(t('about.values_title')) ?></h2>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="row cs_gap_y_24">
            <?php foreach (t('about.values') as $i => [$icon, $title, $text]): ?>
              <div class="col-md-4">
                <div class="cs_value_card cs_gray_bg1 cs_radius_16 h-100" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                  <span class="cs_value_icon"><i class="<?= $icon ?>"></i></span>
                  <h3 class="cs_fs_20 cs_mb_12"><?= e($title) ?></h3>
                  <p class="mb-0"><?= e($text) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>

      <!-- CTA -->
      <section class="cs_cta_style_2 cs_blue_bg3 cs_radius_16 position-relative overflow-hidden">
        <div class="container p-0">
          <div class="row cs_gap_y_24">
            <div class="col-lg-6 order-lg-2">
              <div class="cs_cta_thumbnail">
                <img src="assets/img/about-img2.jpg" alt="<?= e(t('about.cta_alt')) ?>" loading="lazy">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="cs_cta_text position-relative z-1">
                <div class="cs_mb_24">
                  <h2 class="cs_fs_56 cs_mb_24" data-aos="fade-down"><?= e(t('about.cta_title')) ?></h2>
                  <p class="mb-0"><?= e(t('about.cta_text')) ?></p>
                </div>
                <a href="booking.php" class="cs_btn_style_1 cs_white_bg cs_primary_color cs_radius_32" data-aos="fade-up"><span><?= e(t('about.cta_button')) ?></span></a>
              </div>
            </div>
          </div>
        </div>
        <div class="cs_vector_shape position-absolute">
          <img src="assets/img/cta-shape.svg" alt="">
        </div>
      </section>
<?php require __DIR__ . '/includes/footer.php';
