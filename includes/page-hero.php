<?php /* Attend : $hero = [sous-titre, titre, description] */ ?>
      <section class="cs_hero_style_2 cs_blue_bg1 cs_radius_20 position-relative overflow-hidden">
        <div class="container p-0">
          <div class="cs_hero_content cs_center_column text-center position-relative z-1">
            <p class="cs_hero_subtitle cs_fs_14 cs_medium cs_white_color cs_primary_font cs_mb_16 cs_radius_32" data-aos="fade-down"><?= e($hero[0]) ?></p>
            <h1 class="cs_hero_title cs_fs_72 cs_white_color cs_mb_16" data-aos="fade-up"><?= e($hero[1]) ?></h1>
            <p class="cs_hero_desc cs_white_color cs_primary_font mb-0"><?= e($hero[2]) ?></p>
          </div>
        </div>
        <div class="cs_vectoe_shape1 position-absolute">
          <img src="assets/img/page-shape-1.svg" alt="">
        </div>
        <div class="cs_vectoe_shape2 position-absolute">
          <img src="assets/img/page-shape-2.svg" alt="">
        </div>
      </section>
