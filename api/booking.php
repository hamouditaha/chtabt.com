<?php
require __DIR__ . '/../includes/api.php';

api_guard();

$options = t('booking.options');

$name          = input('name', 100);
$phone         = input('phone', 20);
$email         = input('email', 150);
$serviceSlug   = input('service', 100);
$propertySize  = input('property_size', 10);
$frequency     = input('frequency', 10);
$preferredDate = input('preferred_date', 10);
$preferredTime = input('preferred_time', 10);
$city          = input('city', 100);
$address       = input('address', 255);
$message       = input('message', 3000);

$service = service_by_slug($serviceSlug);

$errors = [];
if ($name === '') {
    $errors['name'] = t('api.errors.name');
}
if (!valid_phone($phone)) {
    $errors['phone'] = t('api.errors.phone');
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = t('api.errors.email');
}
if (!$service) {
    $errors['service'] = t('api.errors.service');
}
if (!isset($options['property_size'][$propertySize])) {
    $errors['property_size'] = t('api.errors.size');
}
if (!isset($options['frequency'][$frequency])) {
    $errors['frequency'] = t('api.errors.frequency');
}
if ($preferredTime !== '' && !isset($options['preferred_time'][$preferredTime])) {
    $preferredTime = '';
}
if ($preferredDate !== '') {
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $preferredDate);
    if (!$date || $date->format('Y-m-d') !== $preferredDate || $date < new DateTimeImmutable('today')) {
        $errors['preferred_date'] = t('api.errors.date');
    }
}
if ($city === '') {
    $errors['city'] = t('api.errors.city');
}
if ($errors) {
    json_response(false, t('api.fix'), $errors, 422);
}

$pdo = require_db();
rate_limit();

$stmt = $pdo->prepare(
    'INSERT INTO quotes (name, email, phone, service_type, property_size, frequency,
                         preferred_date, preferred_time, city, address, message, lang, ip_address)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->execute([
    $name,
    $email ?: null,
    $phone,
    $service['name_ar'], // the admin panel is in Arabic
    $propertySize,
    $frequency,
    $preferredDate ?: null,
    $preferredTime ?: null,
    $city,
    $address ?: null,
    $message ?: null,
    lang(),
    client_ip(),
]);

$reference = 'CH-' . str_pad((string) $pdo->lastInsertId(), 5, '0', STR_PAD_LEFT);

json_response(true, t('api.booking_ok', ['name' => $name, 'ref' => $reference]));
