<?php
require __DIR__ . '/includes/bootstrap.php';

$pageTitle       = t('contact.title');
$pageDescription = t('contact.description');
$activePage      = 'contact';

require __DIR__ . '/includes/header.php';

$hero = t('contact.hero');
require __DIR__ . '/includes/page-hero.php';
?>
      <section class="cs_contact_section">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="row cs_gap_y_24">
            <div class="col-lg-6 order-lg-2">
              <div class="cs_contact_form_wrapper cs_radius_16">
                <h2 class="cs_fs_24 cs_mb_24"><?= e(t('contact.form_title')) ?></h2>
                <form action="api/contact.php" method="post" class="cs_contact_form row cs_gap_y_24 js-ajax-form" novalidate>
                  <?= csrf_field() ?>
                  <input type="hidden" name="lang" value="<?= lang() ?>">
                  <input type="text" name="website" class="cs_hp" tabindex="-1" autocomplete="off" aria-hidden="true">
                  <div class="col-md-6">
                    <div class="cs_input_group">
                      <label for="name"><?= e(t('contact.name')) ?> <span class="cs_required" title="<?= e(t('ui.required')) ?>">*</span></label>
                      <input type="text" name="name" id="name" class="cs_form_field" placeholder="<?= e(t('contact.name_ph')) ?>" autocomplete="name" required maxlength="100">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="cs_input_group">
                      <label for="phone"><?= e(t('contact.phone')) ?></label>
                      <input type="tel" name="phone" id="phone" class="cs_form_field" placeholder="06XXXXXXXX" autocomplete="tel" dir="ltr" pattern="[0-9+\s\-]{8,20}" maxlength="20">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="cs_input_group">
                      <label for="email"><?= e(t('contact.email')) ?> <span class="cs_required" title="<?= e(t('ui.required')) ?>">*</span></label>
                      <input type="email" name="email" id="email" class="cs_form_field" placeholder="ex@mail.com" autocomplete="email" dir="ltr" required maxlength="150">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="cs_input_group">
                      <label for="subject"><?= e(t('contact.subject')) ?></label>
                      <select name="subject" id="subject" class="cs_form_field">
                        <option value=""><?= e(t('contact.subject_general')) ?></option>
                        <?php foreach (services() as $service): ?>
                          <option value="<?= e(loc($service, 'name')) ?>"><?= e(loc($service, 'name')) ?></option>
                        <?php endforeach; ?>
                        <option value="<?= e(t('contact.subject_partner')) ?>"><?= e(t('contact.subject_partner')) ?></option>
                        <option value="<?= e(t('contact.subject_complaint')) ?>"><?= e(t('contact.subject_complaint')) ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="cs_input_group">
                      <label for="message"><?= e(t('contact.message')) ?> <span class="cs_required" title="<?= e(t('ui.required')) ?>">*</span></label>
                      <textarea rows="5" name="message" id="message" class="cs_form_field" placeholder="<?= e(t('contact.message_ph')) ?>" required maxlength="3000"></textarea>
                    </div>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="cs_btn_style_1 cs_center cs_accent_bg cs_primary_color cs_radius_32"><span><?= e(t('contact.send')) ?></span></button>
                    <div class="cs_form_result" role="status" aria-live="polite"></div>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="cs_contact_info_wrapper cs_blue_bg3 cs_radius_16">
                <div class="cs_contact_info">
                  <h2 class="cs_contact_title cs_fs_36 cs_mb_24"><?= e(t('contact.info_title')) ?></h2>
                  <p class="cs_fs_18 cs_primary_color cs_primary_font mb-0"><?= e(t('contact.info_text')) ?></p>
                </div>
                <ul class="cs_contact_info_list list-unstyled">
                  <li>
                    <span class="cs_contact_icon"><i class="fa-brands fa-whatsapp"></i></span>
                    <div>
                      <h3 class="cs_fs_18 cs_mb_8"><?= e(t('ui.whatsapp')) ?></h3>
                      <a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener" class="cs_primary_color cs_primary_font" dir="ltr"><?= e($site['phone']) ?></a>
                    </div>
                  </li>
                  <li>
                    <span class="cs_contact_icon"><i class="fa-solid fa-phone"></i></span>
                    <div>
                      <h3 class="cs_fs_18 cs_mb_8"><?= e(t('contact.phone_label')) ?></h3>
                      <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $site['phone'])) ?>" class="cs_primary_color cs_primary_font" dir="ltr"><?= e($site['phone']) ?></a>
                    </div>
                  </li>
                  <li>
                    <span class="cs_contact_icon"><i class="fa-solid fa-envelope"></i></span>
                    <div>
                      <h3 class="cs_fs_18 cs_mb_8"><?= e(t('contact.email_label')) ?></h3>
                      <a href="mailto:<?= e($site['email']) ?>" class="cs_primary_color cs_primary_font"><?= e($site['email']) ?></a>
                    </div>
                  </li>
                  <li>
                    <span class="cs_contact_icon"><i class="fa-solid fa-clock"></i></span>
                    <div>
                      <h3 class="cs_fs_18 cs_mb_8"><?= e(t('contact.hours_label')) ?></h3>
                      <p class="cs_primary_color cs_primary_font mb-0"><?= e(t('site.hours')) ?></p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ -->
      <section class="cs_faq_section" id="faq">
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_section_heading_style_1 cs_center_column text-center">
            <p class="cs_section_subtitle cs_blue_bg3 cs_mb_16"><?= e(t('contact.faq_sub')) ?></p>
            <h2 class="cs_section_title cs_fs_36 cs_black_color cs_mb_16" data-aos="zoom-in"><?= e(t('contact.faq_title')) ?></h2>
            <p class="cs_primary_color cs_primary_font mb-0"><?= e(t('contact.faq_text')) ?></p>
          </div>
          <div class="cs_height_50 cs_height_lg_24"></div>
          <div class="cs_accordian_wraper_style_1">
            <?php foreach (t('contact.faqs') as $i => [$question, $answer]): ?>
              <div class="cs_accordian cs_style_1 cs_gray_bg1 cs_radius_12<?= $i === 0 ? ' active' : '' ?>">
                <div class="cs_accordian_head cs_fs_20 cs_semibold cs_primary_color cs_primary_font" role="button" tabindex="0">
                  <?= e($question) ?>
                  <span class="cs_accordian_toggle"></span>
                </div>
                <div class="cs_accordian_body cs_primary_font"><?= e($answer) ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>
<?php require __DIR__ . '/includes/footer.php';
