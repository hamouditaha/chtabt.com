<?php /* Attend : $cta = [mis en avant, titre, texte, bouton] */ ?>
      <section class="cs_cta_style_1 cs_blue_bg1 cs_radius_16 cs_center_column text-center position-relative overflow-hidden">
        <div class="container p-0">
          <div class="cs_cta_text position-relative z-2">
            <h2 class="cs_cta_title cs_fs_56 cs_white_color cs_mb_16" data-aos="fade-down"><span class="cs_blue_light"><?= e($cta[0]) ?></span> <?= e($cta[1]) ?></h2>
            <p class="cs_cta_subtitle cs_white_color cs_primary_font cs_mb_24"><?= e($cta[2]) ?></p>
            <a href="booking.php" class="cs_btn_style_1 cs_accent_bg cs_primary_color cs_radius_32" data-aos="fade-up"><span><?= e($cta[3]) ?></span></a>
          </div>
          <div class="cs_cta_thumbnail_1 position-absolute">
            <img src="assets/img/cta-img1.png" alt="" loading="lazy">
          </div>
          <div class="cs_cta_thumbnail_2 position-absolute">
            <img src="assets/img/cta-img2.png" alt="" loading="lazy">
          </div>
          <div class="cs_vector_shape1 position-absolute">
            <img src="assets/img/vector-2.svg" alt="">
          </div>
          <div class="cs_vector_shape2 position-absolute">
            <img src="assets/img/vector-3.svg" alt="">
          </div>
        </div>
      </section>
