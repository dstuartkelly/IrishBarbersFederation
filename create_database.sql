-- Irish Barbers Federation: database schema
--
-- This file is the source of truth for the database structure. When the structure changes,
-- edit this file and run the matching ALTER TABLE on the live database.
--
-- Plesk (Blacknight) owns the database and its user, so they cannot be created from here.
-- To set up a database from scratch:
--   1. Plesk > Databases > Add Database (the name gets a prefix, e.g. barbersf_...).
--   2. Create a database user for it in the same dialog and note the password.
--   3. Copy db-config.template.php to db-config.php, fill in the DB_* values, and place it
--      above the web root on the server (submit-form.php looks there first).
--   4. In phpMyAdmin, select the new database, open the SQL tab, paste this file and run it.
--
-- Safe to run more than once: existing tables are left alone.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS registrations (
    id                INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    name              VARCHAR(255)     NOT NULL,
    email             VARCHAR(255)     NOT NULL,              -- stored lower-case by submit-form.php
    phone             VARCHAR(50)      NULL,
    experience        TINYINT UNSIGNED NULL,                  -- years, 0-100; NULL if not given
    ip_address        VARCHAR(45)      NULL,                  -- used for the per-IP rate limit
    user_agent        VARCHAR(255)     NULL,                  -- browser details, for compatibility research
    consent_register  TINYINT(1)       NOT NULL DEFAULT 0,    -- agreed to be stored on the IBF 2000 register
    consent_marketing TINYINT(1)       NOT NULL DEFAULT 0,    -- agreed to receive news and updates by email
    consent_at        DATETIME         NULL,                  -- when consent was given
    registration_date TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_email (email),                            -- duplicates are rejected atomically (error 1062)
    KEY idx_ip_date (ip_address, registration_date)           -- serves the rate-limit query
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
