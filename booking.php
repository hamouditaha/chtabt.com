<?php
require __DIR__ . '/includes/bootstrap.php';
$options = t('booking.options');

$pageTitle       = t('booking.title');
$pageDescription = t('booking.description');
$activePage      = 'booking';

require __DIR__ . '/includes/header.php';

$hero = t('booking.hero');
require __DIR__ . '/includes/page-hero.php';

$req = '<span class="cs_required" title="' . e(t('ui.required')) . '">*</span>';
?>
      <section>
        <div class="cs_height_80 cs_height_lg_40"></div>
        <div class="container p-0">
          <div class="cs_contact_form_wrapper cs_booking_wrapper cs_radius_16">
            <form action="api/booking.php" method="post" class="cs_contact_form row cs_gap_y_24 js-ajax-form" novalidate>
              <?= csrf_field() ?>
              <input type="hidden" name="lang" value="<?= lang() ?>">
              <input type="text" name="website" class="cs_hp" tabindex="-1" autocomplete="off" aria-hidden="true">

              <div class="col-12"><h2 class="cs_fs_24 mb-0"><?= e(t('booking.step1')) ?></h2></div>
              <div class="col-12">
                <div class="cs_input_group">
                  <label for="service"><?= e(t('booking.service')) ?> <?= $req ?></label>
                  <select name="service" id="service" class="cs_form_field" required>
                    <option value=""><?= e(t('booking.service_ph')) ?></option>
                    <?php foreach (services() as $service): ?>
                      <option value="<?= e($service['slug']) ?>"><?= e(loc($service, 'name')) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <fieldset class="cs_input_group">
                  <legend class="cs_fs_16 cs_medium cs_primary_color cs_primary_font cs_mb_8"><?= e(t('booking.size')) ?> <?= $req ?></legend>
                  <div class="cs_radio_cards">
                    <?php foreach ($options['property_size'] as $value => $label): ?>
                      <input type="radio" name="property_size" id="size_<?= $value ?>" value="<?= $value ?>" required>
                      <label for="size_<?= $value ?>"><?= e($label) ?></label>
                    <?php endforeach; ?>
                  </div>
                </fieldset>
              </div>
              <div class="col-12">
                <fieldset class="cs_input_group">
                  <legend class="cs_fs_16 cs_medium cs_primary_color cs_primary_font cs_mb_8"><?= e(t('booking.frequency')) ?> <?= $req ?></legend>
                  <div class="cs_radio_cards">
                    <?php foreach ($options['frequency'] as $value => $label): ?>
                      <input type="radio" name="frequency" id="freq_<?= $value ?>" value="<?= $value ?>" required<?= $value === 'once' ? ' checked' : '' ?>>
                      <label for="freq_<?= $value ?>"><?= e($label) ?></label>
                    <?php endforeach; ?>
                  </div>
                </fieldset>
              </div>

              <div class="col-12"><h2 class="cs_fs_24 mb-0"><?= e(t('booking.step2')) ?></h2></div>
              <div class="col-md-6">
                <div class="cs_input_group">
                  <label for="preferred_date"><?= e(t('booking.date')) ?></label>
                  <input type="date" name="preferred_date" id="preferred_date" class="cs_form_field">
                </div>
              </div>
              <div class="col-md-6">
                <div class="cs_input_group">
                  <label for="preferred_time"><?= e(t('booking.time')) ?></label>
                  <select name="preferred_time" id="preferred_time" class="cs_form_field">
                    <option value=""><?= e(t('booking.time_any')) ?></option>
                    <?php foreach ($options['preferred_time'] as $value => $label): ?>
                      <option value="<?= $value ?>"><?= e($label) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="cs_input_group">
                  <label for="city"><?= e(t('booking.city')) ?> <?= $req ?></label>
                  <input type="text" name="city" id="city" class="cs_form_field" placeholder="<?= e(t('booking.city_ph')) ?>" autocomplete="address-level2" required maxlength="100">
                </div>
              </div>
              <div class="col-md-6">
                <div class="cs_input_group">
                  <label for="address"><?= e(t('booking.address')) ?></label>
                  <input type="text" name="address" id="address" class="cs_form_field" placeholder="<?= e(t('booking.address_ph')) ?>" autocomplete="street-address" maxlength="255">
                </div>
              </div>

              <div class="col-12"><h2 class="cs_fs_24 mb-0"><?= e(t('booking.step3')) ?></h2></div>
              <div class="col-md-6">
                <div class="cs_input_group">
                  <label for="name"><?= e(t('booking.name')) ?> <?= $req ?></label>
                  <input type="text" name="name" id="name" class="cs_form_field" autocomplete="name" required maxlength="100">
                </div>
              </div>
              <div class="col-md-6">
                <div class="cs_input_group">
                  <label for="phone"><?= e(t('booking.phone')) ?> <?= $req ?></label>
                  <input type="tel" name="phone" id="phone" class="cs_form_field" placeholder="06XXXXXXXX" autocomplete="tel" dir="ltr" required pattern="[0-9+\s\-]{8,20}" maxlength="20">
                </div>
              </div>
              <div class="col-12">
                <div class="cs_input_group">
                  <label for="email"><?= e(t('booking.email')) ?></label>
                  <input type="email" name="email" id="email" class="cs_form_field" placeholder="ex@mail.com" autocomplete="email" dir="ltr" maxlength="150">
                </div>
              </div>
              <div class="col-12">
                <div class="cs_input_group">
                  <label for="message"><?= e(t('booking.notes')) ?></label>
                  <textarea rows="4" name="message" id="message" class="cs_form_field" placeholder="<?= e(t('booking.notes_ph')) ?>" maxlength="3000"></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="cs_btn_style_1 cs_center cs_accent_bg cs_primary_color cs_radius_32"><span><?= e(t('booking.submit')) ?></span></button>
                <div class="cs_form_result" role="status" aria-live="polite"></div>
                <p class="cs_fs_14 mt-3 mb-0"><?= e(t('booking.prefer_wa')) ?> <a href="<?= e(whatsapp_link(t('booking.wa_msg'))) ?>" target="_blank" rel="noopener" class="cs_blue_dark cs_semibold"><?= e(t('booking.prefer_wa_link')) ?></a>.</p>
              </div>
            </form>
          </div>
        </div>
        <div class="cs_height_80 cs_height_lg_40"></div>
      </section>
<?php require __DIR__ . '/includes/footer.php';
