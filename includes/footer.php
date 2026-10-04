<?php
$socialIcons = [
    'facebook'  => 'fa-facebook-f',
    'instagram' => 'fa-instagram',
    'tiktok'    => 'fa-tiktok',
];
?>
      <footer class="cs_footer_style_1 cs_primary_bg cs_radius_16 position-relative overflow-hidden">
        <div class="container p-0">
          <div class="cs_newsletter_style_1 cs_blue_bg1 cs_radius_16 position-relative z-1">
            <div class="cs_newsletter_text">
              <h2 class="cs_fs_24 cs_white_color cs_mb_16"><?= e(t('footer.newsletter_title', ['name' => site_name()])) ?></h2>
              <p class="cs_white_color cs_primary_font mb-0"><?= e(t('footer.newsletter_text')) ?></p>
            </div>
            <form action="api/newsletter.php" method="post" class="cs_newsletter_form position-relative js-ajax-form" novalidate>
              <?= csrf_field() ?>
              <input type="hidden" name="lang" value="<?= lang() ?>">
              <label for="newsletter_email" class="visually-hidden"><?= e(t('footer.email_label')) ?></label>
              <input type="email" id="newsletter_email" name="email" placeholder="<?= e(t('footer.email_ph')) ?>" autocomplete="email" required>
              <input type="text" name="website" class="cs_hp" tabindex="-1" autocomplete="off" aria-hidden="true">
              <button type="submit"><?= e(t('footer.subscribe')) ?></button>
              <div class="cs_form_result" role="status" aria-live="polite"></div>
            </form>
          </div>
          <div class="cs_footer_main cs_center_column position-relative z-1">
            <div class="cs_footer_logo cs_mb_40 cs_mb_lg_30">
              <img src="assets/img/logo-white.png" alt="<?= e($site['name_latin']) ?>" width="220" height="66">
            </div>
            <ul class="cs_footer_menu cs_fs_14 cs_gray_color2 cs_primary_font cs_mp_0">
              <li><a href="index.php"><?= e(t('nav.home')) ?></a></li>
              <li><a href="about.php"><?= e(t('nav.about')) ?></a></li>
              <li><a href="services.php"><?= e(t('nav.services')) ?></a></li>
              <li><a href="booking.php"><?= e(t('nav.booking')) ?></a></li>
              <li><a href="contact.php"><?= e(t('nav.contact')) ?></a></li>
            </ul>
            <ul class="cs_footer_contact cs_fs_14 cs_gray_color2 cs_primary_font cs_mp_0">
              <li><i class="fa-solid fa-phone"></i> <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $site['phone'])) ?>" dir="ltr"><?= e($site['phone']) ?></a></li>
              <li><i class="fa-solid fa-envelope"></i> <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></li>
              <li><i class="fa-solid fa-clock"></i> <?= e(t('site.hours')) ?></li>
            </ul>
          </div>
          <div class="cs_footer_bottom position-relative z-1">
            <div class="cs_bottom_content">
              <div class="cs_copyright_text cs_fs_14 cs_gray_color2 cs_primary_font">&copy; <span class="cs_getting_year"></span> <?= e($site['company']) ?>. <?= e(t('footer.rights')) ?></div>
              <div class="cs_social_btns_style_1">
                <?php foreach ($socialIcons as $key => $icon): ?>
                  <?php if (!empty($site['social'][$key])): ?>
                    <a href="<?= e($site['social'][$key]) ?>" target="_blank" rel="noopener" aria-label="<?= e(t('footer.social.' . $key)) ?>"><i class="fa-brands <?= $icon ?>"></i></a>
                  <?php endif; ?>
                <?php endforeach; ?>
                <a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener" aria-label="<?= e(t('ui.whatsapp')) ?>"><i class="fa-brands fa-whatsapp"></i></a>
              </div>
              <ul class="cs_footer_bottom_menu cs_fs_14 cs_gray_color2 cs_primary_font cs_mp_0">
                <li><a href="privacy.php"><?= e(t('footer.privacy')) ?></a></li>
                <li><a href="contact.php#faq"><?= e(t('footer.faq')) ?></a></li>
              </ul>
            </div>
          </div>
          <div class="cs_vector_shape1 position-absolute">
            <img src="assets/img/vector-4.svg" alt="">
          </div>
          <div class="cs_vector_shape2 position-absolute">
            <img src="assets/img/vector-5.svg" alt="">
          </div>
        </div>
      </footer>

      <a href="<?= e(whatsapp_link(t('ui.whatsapp_msg'))) ?>" class="cs_whatsapp_float" target="_blank" rel="noopener" aria-label="<?= e(t('ui.whatsapp_aria')) ?>">
        <i class="fa-brands fa-whatsapp"></i>
      </a>
      <button type="button" class="cs_scrollup" aria-label="<?= e(t('ui.back_to_top')) ?>"><span><i class="fa-solid fa-arrow-up"></i></span></button>
    </div>
    <script src="assets/js/aos.min.js"></script>
    <script src="assets/js/lenis.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/odometer.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/app.js"></script>
  </body>
</html>
