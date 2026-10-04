<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle  = t('home.title');
$activePage = 'home';

require __DIR__ . '/includes/header.php';
?>
      <!-- Hero -->
      <section class="cs_hero_modern cs_blue_bg1 cs_radius_20 position-relative overflow-hidden">
        <div class="cs_hero_glow cs_hero_glow_1" aria-hidden="true"></div>
        <div class="cs_hero_glow cs_hero_glow_2" aria-hidden="true"></div>
        <div class="cs_hero_bubbles" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
        <div class="container position-relative">
          <div class="cs_hero_grid">
            <div class="cs_hero_text">
              <p class="cs_hero_pill" data-aos="fade-down"><i class="fa-solid fa-wand-magic-sparkles"></i> <?= e(t('home.hero_sub')) ?></p>
              <h1 class="cs_hero_heading" data-aos="fade-up">
                <?= e(t('home.hero_title')) ?>
                <br><span class="cs_hero_mark"><?= e(t('home.hero_highlight')) ?></span>
              </h1>
              <p class="cs_hero_lead" data-aos="fade-up" data-aos-delay="100"><?= e(t('home.hero_desc')) ?></p>
              <div class="cs_hero_actions" data-aos="fade-up" data-aos-delay="200">
                <a href="booking.php" class="cs_btn_style_1 cs_radius_32 cs_accent_bg cs_primary_color"><span><?= e(t('home.hero_cta')) ?></span></a>
                <a href="<?= e(whatsapp_link(t('ui.whatsapp_msg'))) ?>" target="_blank" rel="noopener" class="cs_hero_ghost_btn"><i class="fa-brands fa-whatsapp"></i> <?= e(t('home.hero_whatsapp')) ?></a>
              </div>
              <div class="cs_hero_proof" data-aos="fade-up" data-aos-delay="300">
                <div class="cs_hero_avatars" aria-hidden="true">
                  <img src="assets/img/avatar-1.png" alt="">
                  <img src="assets/img/avatar-2.png" alt="">
                  <img src="assets/img/avatar-3.png" alt="">
                </div>
                <div>
                  <div class="cs_hero_stars" role="img" aria-label="<?= e(t('ui.rating', ['n' => '4.9'])) ?>">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <strong dir="ltr">4.9/5</strong>
                  </div>
                  <span><?= e(t('home.customers')) ?></span>
                </div>
              </div>
            </div>

            <div class="cs_hero_visual" data-aos="zoom-in">
              <div class="cs_hero_ring" aria-hidden="true"></div>
              <div class="cs_hero_photo">
                <img src="assets/img/hero-img1.jpg" alt="<?= e(t('home.hero_alt')) ?>" width="562" height="600">
              </div>
              <div class="cs_hero_float cs_hero_float_stat">
                <span class="cs_hero_float_icon"><i class="fa-solid fa-house-circle-check"></i></span>
                <div><strong dir="ltr">+1200</strong><span><?= e(t('home.card_homes')) ?></span></div>
              </div>
              <div class="cs_hero_float cs_hero_float_team">
                <span class="cs_hero_float_icon cs_hero_float_icon_accent"><i class="fa-solid fa-user-shield"></i></span>
                <div><strong><?= e(t('home.card_team')) ?></strong><span><?= e(t('home.card_team_sub')) ?></span></div>
              </div>
            </div>
          </div>

          <ul class="cs_hero_badges" data-aos="fade-up">
            <?php foreach (t('home.badges') as $i => $badge): ?>
              <li><i class="fa-solid <?= ['fa-shield-halved', 'fa-leaf', 'fa-rotate-left'][$i] ?>"></i> <?= e($badge) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </section>

      <!-- Promise -->
      <section class="cs_how_work_section">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_blue_bg3 cs_mb_16" data-aos="fade-down"><?= e(t('home.about_sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 cs_black_color cs_mb_16" data-aos="fade-up"><?= e(t('home.about_title')) ?></h2>
            <p class="cs_primary_color cs_primary_font mb-0"><?= e(t('home.about_text')) ?></p>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="row cs_gap_y_24 align-items-center">
            <div class="col-lg-6 order-lg-2">
              <div class="cs_steps_thumbnails_wrapper">
                <?php foreach (['how-to-work-2.jpg', 'how-to-work-1.jpg', 'how-to-work-3.jpg', 'how-to-work.jpg'] as $i => $img): ?>
                  <div class="cs_step_thumbnail cs_radius_20"><img src="assets/img/<?= $img ?>" alt="<?= e(t('home.step_alts')[$i]) ?>" loading="lazy"></div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="cs_iconbox_wrapper_style_2">
                <?php foreach (t('home.promises') as $i => [$icon, $title, $text]): ?>
                  <div class="cs_iconbox_style_2<?= $i === 0 ? ' active' : '' ?>" role="button" tabindex="0">
                    <span class="cs_iconbox_icon cs_center"><i class="<?= $icon ?>"></i></span>
                    <div class="cs_iconbox_info">
                      <h3 class="cs_iconbox_title cs_fs_20 cs_mb_12"><?= e($title) ?></h3>
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

      <!-- Services -->
      <section class="cs_service_section">
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_blue_bg3 cs_mb_16" data-aos="fade-down"><?= e(t('home.services_sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 cs_black_color cs_mb_16" data-aos="fade-up"><?= e(t('home.services_title')) ?></h2>
            <p class="cs_primary_color cs_primary_font mb-0"><?= e(t('home.services_text')) ?></p>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="row cs_gap_y_24 justify-content-center">
            <?php
            foreach (array_slice(services(), 0, 3) as $service) {
                $aos = 'fade-up';
                require __DIR__ . '/includes/service-card.php';
            }
            ?>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="cs_center">
            <a href="services.php" class="cs_btn_style_1 cs_primary_color cs_accent_bg cs_radius_32" data-aos="zoom-in"><span><?= e(t('home.services_all')) ?></span></a>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>

      <!-- Testimonials -->
      <section class="cs_testimonial_section cs_blue_bg3 cs_radius_16">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_white_bg cs_mb_16" data-aos="fade-down"><?= e(t('home.testi_sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 cs_black_color cs_mb_16" data-aos="fade-up"><?= e(t('home.testi_title')) ?></h2>
            <p class="cs_primary_color cs_primary_font mb-0"><?= e(t('home.testi_text')) ?></p>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="cs_slider cs_style_1 cs_slider_gap_24">
            <div class="cs_slider_container" data-autoplay="0" data-loop="0" data-speed="800"
              data-center="0" data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1"
              data-sm-slides="2" data-md-slides="3" data-lg-slides="3" data-add-slides="3">
              <div class="cs_slider_wrapper">
                <?php foreach (t('home.testimonials') as [$rating, $quote, $name, $avatar]): ?>
                  <div class="cs_slide">
                    <figure class="cs_testimonial_style_1 cs_white_bg cs_radius_16 mb-0">
                      <div class="cs_rating cs_mb_19" data-rating="<?= $rating ?>" role="img" aria-label="<?= e(t('ui.rating', ['n' => $rating])) ?>">
                        <div class="cs_rating_percentage"></div>
                      </div>
                      <blockquote><?= e($quote) ?></blockquote>
                      <figcaption class="cs_avatar_style_1">
                        <div class="cs_avatar_icon cs_center cs_radius_100">
                          <img src="assets/img/<?= e($avatar) ?>" alt="" loading="lazy">
                        </div>
                        <div class="cs_avatar_info">
                          <h3 class="cs_fs_18 mb-0"><?= e($name) ?></h3>
                        </div>
                      </figcaption>
                    </figure>
                  </div>
                <?php endforeach; ?>
              </div>
              <div class="cs_height_50 cs_height_lg_24"></div>
              <div class="cs_center">
                <div class="cs_pagination cs_style_1"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>

<?php
$cta = t('home.cta');
require __DIR__ . '/includes/cta.php';
require __DIR__ . '/includes/footer.php';
