-- ============================================================
-- DB Admin tomonidan bajarilishi kerak bo'lgan SQL skriptlar
-- vip_user ALTER/CREATE imtiyozlari yo'q, shuning uchun qo'lda bajariladi
-- ============================================================

-- 1. units jadvali yaratish
CREATE TABLE IF NOT EXISTS `units` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `slug`       VARCHAR(255)    NOT NULL UNIQUE,
    `name`       JSON            NOT NULL,
    `status`     TINYINT         NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP       NULL,
    `updated_at` TIMESTAMP       NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. units jadvaliga boshlang'ich ma'lumotlar
INSERT IGNORE INTO `units` (`slug`, `name`, `status`, `created_at`, `updated_at`) VALUES
('dona',    '{"uz":"Dona","en":"Piece","tr":"Adet"}',       1, NOW(), NOW()),
('porsiya', '{"uz":"Porsiya","en":"Portion","tr":"Porsiyon"}', 1, NOW(), NOW()),
('kg',      '{"uz":"Kg","en":"Kg","tr":"Kg"}',              1, NOW(), NOW()),
('gramm',   '{"uz":"Gramm","en":"Gram","tr":"Gram"}',       1, NOW(), NOW()),
('litr',    '{"uz":"Litr","en":"Litre","tr":"Litre"}',      1, NOW(), NOW());

-- 3. products jadvaliga original_price ustuni qo'shish
ALTER TABLE `products`
    ADD COLUMN `original_price` DECIMAL(12,2) NULL AFTER `price`;

-- 4. products jadvaliga status ustuni qo'shish
ALTER TABLE `products`
    ADD COLUMN `status` TINYINT NOT NULL DEFAULT 1 AFTER `is_available`;

-- 5. products jadvaliga unit_id ustuni qo'shish
ALTER TABLE `products`
    ADD COLUMN `unit_id` BIGINT UNSIGNED NULL AFTER `unit`,
    ADD CONSTRAINT `fk_products_unit_id`
        FOREIGN KEY (`unit_id`) REFERENCES `units`(`id`) ON DELETE SET NULL;

-- 6. Mavjud products dagi unit (string) ni unit_id ga ko'chirish
UPDATE `products` p
INNER JOIN `units` u ON p.`unit` = u.`slug`
SET p.`unit_id` = u.`id`
WHERE p.`unit_id` IS NULL;

-- 7. migrations jadvalini yangilash (artisan migrate:status uchun)
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
('2026_04_22_072401_add_original_price_to_products_table', 99),
('2026_04_22_120000_add_status_to_products_table',         99),
('2026_05_04_100000_create_units_table',                   99),
('2026_05_04_100001_add_unit_id_to_products_table',        99);
