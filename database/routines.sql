-- =============================================
-- Routines SQL optionnelles (procédures, triggers, vues) issues de BD.sql.
-- Le site n'en a pas besoin. Certains hébergeurs mutualisés refusent
-- CREATE PROCEDURE / TRIGGER : dans ce cas, n'importez pas ce fichier.
-- À importer APRÈS schema.sql.
-- =============================================
-- الإجراءات المخزنة (Stored Procedures)
-- =============================================

-- إجراء لتحديث تقييم المنظف
DELIMITER //
CREATE PROCEDURE UpdateCleanerRating(IN p_cleaner_id INT)
BEGIN
    UPDATE cleaners 
    SET rating = (
        SELECT AVG(rating) 
        FROM bookings 
        WHERE cleaner_id = p_cleaner_id AND rating IS NOT NULL
    ),
    total_reviews = (
        SELECT COUNT(*) 
        FROM bookings 
        WHERE cleaner_id = p_cleaner_id AND rating IS NOT NULL
    )
    WHERE user_id = p_cleaner_id;
END//
DELIMITER ;

-- إجراء لتوليد رقم حجز فريد
DELIMITER //
CREATE PROCEDURE GenerateBookingNumber(OUT booking_number VARCHAR(20))
BEGIN
    DECLARE new_number VARCHAR(20);
    DECLARE counter INT DEFAULT 1;
    
    WHILE counter > 0 DO
        SET new_number = CONCAT('CH', DATE_FORMAT(NOW(), '%y%m%d'), LPAD(FLOOR(RAND() * 10000), 4, '0'));
        
        SELECT COUNT(*) INTO counter 
        FROM bookings 
        WHERE booking_number = new_number;
    END WHILE;
    
    SET booking_number = new_number;
END//
DELIMITER ;

-- إجراء للحصول على إحصائيات الموقع
DELIMITER //
CREATE PROCEDURE GetSiteStatistics(OUT total_customers INT, OUT total_cleaners INT, OUT total_bookings INT, OUT total_revenue DECIMAL(10,2))
BEGIN
    SELECT COUNT(*) INTO total_customers FROM users WHERE user_type = 'customer' AND status = 'active';
    SELECT COUNT(*) INTO total_cleaners FROM users WHERE user_type = 'cleaner' AND status = 'active';
    SELECT COUNT(*) INTO total_bookings FROM bookings WHERE status = 'completed';
    SELECT COALESCE(SUM(total_price), 0) INTO total_revenue FROM bookings WHERE payment_status = 'paid';
END//
DELIMITER ;

-- =============================================
-- الدوال (Functions)
-- =============================================

-- دالة للتحقق من توفر المنظف
DELIMITER //
CREATE FUNCTION IsCleanerAvailable(p_cleaner_id INT, p_booking_date DATE, p_booking_time TIME, p_duration_hours INT) RETURNS BOOLEAN
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE conflict_count INT;
    
    SELECT COUNT(*) INTO conflict_count
    FROM bookings
    WHERE cleaner_id = p_cleaner_id
    AND booking_date = p_booking_date
    AND status IN ('confirmed', 'in_progress')
    AND (
        (booking_time BETWEEN p_booking_time AND ADDTIME(p_booking_time, SEC_TO_TIME(p_duration_hours * 3600 - 1)))
        OR
        (ADDTIME(booking_time, SEC_TO_TIME(duration_hours * 3600 - 1)) BETWEEN p_booking_time AND ADDTIME(p_booking_time, SEC_TO_TIME(p_duration_hours * 3600 - 1)))
    );
    
    RETURN conflict_count = 0;
END//
DELIMITER ;

-- =============================================
-- المحفزات (Triggers)
-- =============================================

-- محفز لتوليد رقم الحجز تلقائيًا
DELIMITER //
CREATE TRIGGER before_booking_insert
BEFORE INSERT ON bookings
FOR EACH ROW
BEGIN
    IF NEW.booking_number IS NULL THEN
        CALL GenerateBookingNumber(@new_booking_number);
        SET NEW.booking_number = @new_booking_number;
    END IF;
END//
DELIMITER ;

-- محفز لتحديث تقييم المنظف بعد إدخال تقييم جديد
DELIMITER //
CREATE TRIGGER after_booking_rating_update
AFTER UPDATE ON bookings
FOR EACH ROW
BEGIN
    IF NEW.rating IS NOT NULL AND OLD.rating IS NULL AND NEW.cleaner_id IS NOT NULL THEN
        CALL UpdateCleanerRating(NEW.cleaner_id);
    END IF;
END//
DELIMITER ;

-- محفز لإضافة مستخدم جديد إلى جدول الملفات الشخصية
DELIMITER //
CREATE TRIGGER after_user_insert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO profiles (user_id) VALUES (NEW.id);
END//
DELIMITER ;

-- =============================================
-- العروض (Views)
-- =============================================

-- عرض للحجوزات النشطة
CREATE VIEW active_bookings_view AS
SELECT 
    b.booking_number,
    b.booking_date,
    b.booking_time,
    b.duration_hours,
    b.total_price,
    b.status,
    b.payment_status,
    c.name AS customer_name,
    c.email AS customer_email,
    c.phone AS customer_phone,
    s.name_ar AS service_name_ar,
    s.name_fr AS service_name_fr,
    cl.name AS cleaner_name
FROM bookings b
JOIN users c ON b.customer_id = c.id
JOIN services s ON b.service_id = s.id
LEFT JOIN users cl ON b.cleaner_id = cl.id
WHERE b.status IN ('pending', 'confirmed', 'in_progress')
ORDER BY b.booking_date, b.booking_time;

-- عرض للإحصائيات الشهرية
CREATE VIEW monthly_stats_view AS
SELECT 
    YEAR(booking_date) AS year,
    MONTH(booking_date) AS month,
    COUNT(*) AS total_bookings,
    SUM(total_price) AS total_revenue,
    AVG(rating) AS avg_rating
FROM bookings
WHERE status = 'completed'
GROUP BY YEAR(booking_date), MONTH(booking_date)
ORDER BY year DESC, month DESC;

-- عرض للمنظفين المتاحين
CREATE VIEW available_cleaners_view AS
SELECT 
    u.id,
    u.name,
    u.phone,
    c.experience_years,
    c.rating,
    c.total_reviews,
    c.hourly_rate,
    c.is_verified
FROM users u
JOIN cleaners c ON u.id = c.user_id
WHERE u.status = 'active'
AND c.is_verified = TRUE
ORDER BY c.rating DESC, c.total_reviews DESC;

