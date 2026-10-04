<?php /* Attend : $service, $aos (optionnel) */ ?>
            <div class="col-lg-4 col-md-6">
              <article class="cs_card_style_1 position-relative" data-aos="<?= e($aos ?? 'fade-up') ?>">
                <a href="service.php?slug=<?= e($service['slug']) ?>" class="cs_card_thumbnail cs_radius_16 overflow-hidden" tabindex="-1" aria-hidden="true">
                  <img src="assets/img/<?= e($service['image']) ?>" alt="" loading="lazy" width="400" height="340">
                </a>
                <div class="cs_card_info cs_white_bg cs_radius_8 position-absolute">
                  <?php if ($site['show_prices'] && (float) $service['base_price'] > 0): ?>
                    <span class="cs_price_tag"><?= e(t('card.from')) ?> <?= e(number_format((float) $service['base_price'], 0)) ?> <?= e(t('site.currency')) ?></span>
                  <?php endif; ?>
                  <h3 class="cs_card_title cs_fs_20 cs_mb_12"><a href="service.php?slug=<?= e($service['slug']) ?>"><?= e(loc($service, 'name')) ?></a></h3>
                  <p class="mb-0"><?= e(loc($service, 'description')) ?></p>
                  <a href="service.php?slug=<?= e($service['slug']) ?>" class="cs_card_link" aria-label="<?= e(t('card.aria', ['name' => loc($service, 'name')])) ?>"><?= e(t('card.details')) ?> <i class="fa-solid <?= is_rtl() ? 'fa-arrow-left' : 'fa-arrow-right' ?>"></i></a>
                </div>
              </article>
            </div>
