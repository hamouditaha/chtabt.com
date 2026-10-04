-- =============================================
-- Base de données شطبتكم (CHTABT.COM) — basée sur BD.sql avec les ajouts du site :
--   * services : slug, textes arabes + français, sort_order
--   * quotes : email facultatif, date/créneau/ville/adresse souhaités, langue du client
--   * aucun compte admin avec mot de passe par défaut : voir database/create-admin.php
--
-- Import : créez d'abord la base (utf8mb4_unicode_ci), sélectionnez-la dans phpMyAdmin,
-- puis importez ce fichier. routines.sql (procédures/triggers/vues) est facultatif.
-- =============================================

-- 1. المستخدمون
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    user_type ENUM('customer', 'cleaner', 'admin') DEFAULT 'customer',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. الملفات الشخصية
CREATE TABLE profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    avatar VARCHAR(255),
    bio TEXT,
    date_of_birth DATE,
    gender ENUM('male', 'female'),
    language ENUM('ar', 'fr', 'en') DEFAULT 'ar',
    notifications_enabled BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. الخدمات
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name_ar VARCHAR(255) NOT NULL,
    name_fr VARCHAR(255) NOT NULL,
    description_ar TEXT,
    description_fr TEXT,
    long_description_ar TEXT,
    long_description_fr TEXT,
    base_price DECIMAL(8,2) NOT NULL DEFAULT 0,
    duration_minutes INT DEFAULT 60,
    image VARCHAR(255),
    features_ar JSON,
    features_fr JSON,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. الحجوزات المؤكدة
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_number VARCHAR(20) UNIQUE,
    customer_id INT,
    service_id INT,
    cleaner_id INT,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    duration_hours INT DEFAULT 2,
    total_price DECIMAL(8,2) NOT NULL,
    address TEXT NOT NULL,
    special_instructions TEXT,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    rating TINYINT,
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (cleaner_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. طلبات الحجز / عروض الأسعار (من نموذج booking.php)
CREATE TABLE quotes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(20) NOT NULL,
    service_type VARCHAR(100),
    property_size ENUM('small', 'medium', 'large', 'xlarge'),
    frequency ENUM('once', 'weekly', 'biweekly', 'monthly'),
    preferred_date DATE NULL,
    preferred_time ENUM('morning', 'afternoon') NULL,
    city VARCHAR(100),
    address VARCHAR(255),
    message TEXT,
    status ENUM('new', 'contacted', 'converted', 'cancelled') DEFAULT 'new',
    admin_notes TEXT,
    lang CHAR(2) DEFAULT 'ar',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. المنظفون
CREATE TABLE cleaners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    experience_years INT,
    specialization JSON,
    hourly_rate DECIMAL(6,2),
    availability JSON,
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    is_verified BOOLEAN DEFAULT FALSE,
    verification_documents JSON,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. المدفوعات
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    transaction_id VARCHAR(100) UNIQUE,
    amount DECIMAL(8,2) NOT NULL,
    payment_method ENUM('credit_card', 'debit_card', 'bank_transfer', 'cash'),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_date TIMESTAMP NULL,
    refund_amount DECIMAL(8,2) DEFAULT 0.00,
    refund_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. المدونة
CREATE TABLE blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title_ar VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content_ar LONGTEXT,
    content_en LONGTEXT,
    excerpt_ar TEXT,
    excerpt_en TEXT,
    featured_image VARCHAR(255),
    author_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. رسائل الاتصال (من نموذج contact.php)
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    lang CHAR(2) DEFAULT 'ar',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. المشتركون في النشرة البريدية
CREATE TABLE newsletter_subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) UNIQUE NOT NULL,
    name VARCHAR(100),
    lang CHAR(2) DEFAULT 'ar',
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. الإعدادات
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- البيانات الأولية
-- =============================================

-- Prix à 0 = non affichés. Modifiez-les dans l'admin puis activez show_prices dans includes/config.php
INSERT INTO services (slug, name_ar, name_fr, description_ar, description_fr, long_description_ar, long_description_fr, base_price, duration_minutes, image, features_ar, features_fr, sort_order) VALUES
('weekly-cleaning', 'تنظيف أسبوعي', 'Ménage hebdomadaire',
 'تنظيف دوري: مسح، تكنيس، والعناية بالأسطح.',
 'Entretien régulier : dépoussiérage, balayage, serpillière et soin des surfaces.',
 'حافظ على منزلك منعشاً طوال الأسبوع دون عناء. يزورك فريقنا في الموعد الذي يناسبك لتنظيف الغرف والأرضيات والأسطح والمطبخ والحمام، بحيث تعود كل يوم إلى بيت مرتب ونظيف.',
 'Gardez une maison fraîche toute la semaine sans effort. Notre équipe passe au créneau qui vous convient pour nettoyer les pièces, les sols, les surfaces, la cuisine et la salle de bain : vous rentrez chaque jour dans un intérieur propre et rangé.',
 0, 120, 'card-services-1.jpg',
 '["تنظيف الغرف وترتيبها","تكنيس ومسح الأرضيات","إزالة الغبار عن الأثاث والأسطح","تنظيف المطبخ الأساسي","تنظيف الحمام الأساسي"]',
 '["Nettoyage et rangement des pièces","Balayage et lavage des sols","Dépoussiérage des meubles et surfaces","Entretien de base de la cuisine","Entretien de base de la salle de bain"]',
 1),
('deep-cleaning', 'تنظيف عميق', 'Nettoyage en profondeur',
 'تنظيف مكثف للمطابخ، الحمامات، والأماكن المُهمَلة والواجهات الزجاجية.',
 'Nettoyage intensif des cuisines, salles de bain, recoins oubliés et vitres.',
 'تنظيف شامل يصل إلى الأماكن التي لا يصلها التنظيف اليومي: خلف الأجهزة، داخل الخزائن، الزوايا، الزجاج والواجهات. مثالي مرة كل بضعة أشهر أو قبل المناسبات.',
 'Un nettoyage complet qui va là où le ménage quotidien ne va pas : derrière les appareils, dans les placards, les recoins, les vitres et les façades vitrées. Idéal tous les quelques mois ou avant une fête.',
 0, 240, 'card-services-2.jpg',
 '["تنظيف مفصل لكل الغرف","التنظيف خلف الأجهزة والأثاث","تنظيف النوافذ والواجهات الزجاجية","تعقيم الحمامات","إزالة الدهون من المطبخ"]',
 '["Nettoyage détaillé de toutes les pièces","Nettoyage derrière les appareils et les meubles","Lavage des fenêtres et baies vitrées","Désinfection des salles de bain","Dégraissage de la cuisine"]',
 2),
('move-cleaning', 'تنظيف قبل وبعد الانتقال', 'Nettoyage avant / après déménagement',
 'بداية جديدة في بيت نظيف، أو تسليم بيتك القديم نظيفاً 100%.',
 'Emménagez dans un logement propre ou rendez l’ancien impeccable à 100 %.',
 'سواء كنت تنتقل إلى بيت جديد أو تسلّم بيتك القديم، نتكفل بتنظيفه بالكامل من الأعلى إلى الأسفل حتى يكون جاهزاً للسكن أو للتسليم دون أي ملاحظات.',
 'Que vous emménagiez ou que vous rendiez votre ancien logement, nous le nettoyons de fond en comble pour qu’il soit prêt à habiter ou à rendre, sans aucune remarque.',
 0, 300, 'card-services-3.jpg',
 '["تنظيف شامل للمنزل","تنظيف الخزائن والأدراج من الداخل","تعقيم المطبخ والحمامات","تنظيف النوافذ من الداخل والخارج","إزالة الأتربة العميقة"]',
 '["Nettoyage complet du logement","Intérieur des placards et tiroirs","Désinfection de la cuisine et des salles de bain","Fenêtres intérieur et extérieur","Élimination de la poussière incrustée"]',
 3),
('eco-cleaning', 'تنظيف صديق للبيئة', 'Nettoyage écologique',
 'منتجات غير سامة وآمنة للأطفال والحيوانات الأليفة.',
 'Des produits non toxiques, sans danger pour les enfants et les animaux.',
 'خدمة مثالية للمنازل التي بها أطفال أو حيوانات أليفة أو أشخاص يعانون من الحساسية. نستعمل منتجات لطيفة وقابلة للتحلل، فعالة ضد الأوساخ وآمنة على صحة عائلتك.',
 'Le service idéal pour les foyers avec enfants, animaux ou personnes allergiques. Nous utilisons des produits doux et biodégradables, efficaces contre la saleté et sans risque pour la santé de votre famille.',
 0, 120, 'card-services-4.jpg',
 '["منتجات صديقة للبيئة","آمن للأطفال والحيوانات الأليفة","مناشف مايكروفايبر لتقليل مسببات الحساسية","منتجات قابلة للتحلل"]',
 '["Produits respectueux de l’environnement","Sans danger pour enfants et animaux","Chiffons microfibre contre les allergènes","Produits biodégradables"]',
 4),
('post-construction', 'تنظيف ما بعد البناء', 'Nettoyage après travaux',
 'إزالة الغبار ومخلفات الأشغال بعد البناء أو الإصلاح.',
 'Élimination de la poussière et des résidus après construction ou rénovation.',
 'بعد أشغال البناء أو الإصلاح يبقى الغبار في كل مكان. فريقنا مجهز لإزالة بقايا الإسمنت والطلاء والغبار الدقيق حتى تستلم مساحتك جاهزة للاستعمال.',
 'Après des travaux, la poussière est partout. Notre équipe est équipée pour retirer les traces de ciment, de peinture et la poussière fine, afin que vous récupériez un espace prêt à l’emploi.',
 0, 360, 'card-services-5.jpg',
 '["إزالة غبار البناء","إزالة بقع الطلاء والإسمنت","تنظيف النوافذ والإطارات","تنظيف الأرضيات وتلميعها","التخلص من المخلفات الخفيفة"]',
 '["Élimination de la poussière de chantier","Retrait des taches de peinture et de ciment","Nettoyage des fenêtres et encadrements","Lavage et lustrage des sols","Évacuation des petits déchets"]',
 5),
('appliance-cleaning', 'تنظيف الأجهزة والتفاصيل', 'Électroménager et finitions',
 'تنظيف داخلي للثلاجة والفرن والأجهزة المنزلية.',
 'Nettoyage intérieur du réfrigérateur, du four et des appareils ménagers.',
 'نهتم بالتفاصيل التي تصنع الفرق: الثلاجة من الداخل، الفرن، الميكروويف، الغسالة وباقي الأجهزة، لتعمل بكفاءة وتبقى نظيفة وخالية من الروائح.',
 'Nous soignons les détails qui font la différence : intérieur du réfrigérateur, four, micro-ondes, lave-linge et autres appareils, pour qu’ils restent propres, efficaces et sans odeurs.',
 0, 180, 'card-services-6.jpg',
 '["تنظيف الثلاجة من الداخل","تنظيف الفرن","تنظيف الميكروويف","تنظيف الغسالة","إزالة الروائح"]',
 '["Intérieur du réfrigérateur","Four","Micro-ondes","Lave-linge","Élimination des odeurs"]',
 6);

INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name_ar', 'شطبتكم', 'string', 'اسم الموقع بالعربية'),
('site_name_fr', 'CHTABT.COM', 'string', 'Nom du site en français'),
('site_email', 'steshtabtkoumsarl@gmail.com', 'string', 'البريد الإلكتروني للموقع'),
('site_address', 'المغرب', 'string', 'عنوان الموقع'),
('booking_hours_start', '08:00:00', 'string', 'ساعة بدء الحجوزات'),
('booking_hours_end', '18:00:00', 'string', 'ساعة انتهاء الحجوزات'),
('max_booking_days', '30', 'number', 'الحد الأقصى للأيام المسموح بالحجز مقدماً'),
('cancellation_hours', '24', 'number', 'عدد الساعات المسموح بها للإلغاء بدون رسوم');

-- فهارس
CREATE INDEX idx_users_type ON users(user_type);
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_bookings_customer_id ON bookings(customer_id);
CREATE INDEX idx_bookings_cleaner_id ON bookings(cleaner_id);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_bookings_date ON bookings(booking_date);
CREATE INDEX idx_services_status ON services(status);
CREATE INDEX idx_quotes_status ON quotes(status);
CREATE INDEX idx_quotes_created_at ON quotes(created_at);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_blog_status ON blog_posts(status);
CREATE INDEX idx_contact_status ON contact_messages(status);
CREATE INDEX idx_contact_created_at ON contact_messages(created_at);

