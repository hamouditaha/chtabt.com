# شطبتكم — CHTABT.COM

Site vitrine + réservation en ligne pour STE SHTABTKOUM SARL (nettoyage à domicile, Maroc).
PHP 8.1+ / MySQL (ou MariaDB), sans framework. Interface en arabe (RTL).

## Structure

```
chtabt/
├── index.php, about.php, services.php, service.php?slug=…, booking.php, contact.php, privacy.php
├── api/            booking.php, contact.php, newsletter.php  (JSON, appelés en AJAX par assets/js/app.js)
├── admin/          tableau de bord : demandes de réservation, messages, abonnés (export CSV), services
├── includes/       config.php, bootstrap.php (PDO, CSRF, helpers), header/footer partagés
├── database/       schema.sql, create-admin.php
└── assets/         CSS/JS/images du template + custom.css, rtl.css, app.js
```

## Installation (XAMPP)

1. Copier le dossier `chtabt` dans `C:\xampp\htdocs\`.
2. Importer `database/schema.sql` dans phpMyAdmin (crée la base `chtabt_db`).
3. Si vos identifiants MySQL diffèrent de `root` sans mot de passe, créer `includes/config.local.php` :
   ```php
   <?php
   return ['db' => ['user' => 'root', 'pass' => 'VOTRE_MOT_DE_PASSE', 'port' => 3306]];
   ```
4. Créer le compte administrateur :
   ```
   C:\xampp\php\php.exe database\create-admin.php admin@exemple.com "MotDePasseSolide" "Nom"
   ```
5. Ouvrir http://localhost/chtabt/ et l'admin sur http://localhost/chtabt/admin/

Sans XAMPP/Apache : `php -S localhost:8000` dans le dossier `chtabt`.

Si la base est indisponible, les pages publiques restent affichées (services lus depuis
`includes/services-data.php`) et les formulaires renvoient vers WhatsApp / téléphone.

## À compléter avant la mise en ligne

Dans `includes/config.php` :
- `phone` et `whatsapp` : **numéros provisoires** (`+212 600-000000`) à remplacer ;
- `social` : liens Facebook / Instagram / TikTok (les icônes n'apparaissent que si renseignés) ;
- `show_prices` : passer à `true` après avoir saisi les prix dans l'admin → Services.

Les chiffres (1200+ foyers, 98 %, 4.9/5, 5 ans) et les témoignages viennent de l'ancienne version :
vérifiez qu'ils sont exacts.

## Sécurité

Requêtes préparées PDO, jeton CSRF sur tous les formulaires, champ piège anti-robots,
limite de 5 envois / 10 min par session, mots de passe `password_hash`, limite de tentatives
de connexion admin, `.htaccess` qui bloque `includes/` et `database/`.
