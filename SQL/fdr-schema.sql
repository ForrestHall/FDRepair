-- =====================================================
-- Find Diesel Repair - Table Schema (fdr_ prefix)
-- =====================================================
-- Run this in your database. Creates fdr_listings and fdr_zips.
-- =====================================================

CREATE TABLE IF NOT EXISTS fdr_listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `NAME` VARCHAR(255) NOT NULL,
    ADDRESS VARCHAR(500) NULL,
    CITY VARCHAR(100) NULL,
    `STATE` VARCHAR(50) NULL,
    ZIPCODE VARCHAR(20) NULL,
    PHONE VARCHAR(50) NULL,
    SITE VARCHAR(500) NULL COMMENT 'Website URL',
    `MAP` VARCHAR(500) NULL COMMENT 'Google Maps URL',
    RATE VARCHAR(20) NULL COMMENT 'Google rating or price indicator',
    MOBILE TINYINT(1) DEFAULT 0 COMMENT '1 = mobile service',
    VERIFIED TINYINT(1) DEFAULT 0,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    PLACE VARCHAR(255) NULL COMMENT 'Google Place ID if available',
    created_at DATETIME NULL,
    INDEX idx_zip (ZIPCODE),
    INDEX idx_city_state (CITY, `STATE`),
    INDEX idx_mobile (MOBILE),
    INDEX idx_verified (VERIFIED),
    INDEX idx_geo (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS fdr_zips (
    ZIP VARCHAR(10) NOT NULL PRIMARY KEY,
    LAT DECIMAL(10, 8) NOT NULL,
    LNG DECIMAL(11, 8) NOT NULL,
    CITY VARCHAR(100) NULL,
    `STATE` VARCHAR(10) NULL,
    INDEX idx_geo (LAT, LNG)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
