-- ═══════════════════════════════════════════════════════════
-- ScholarFlow Database Schema
-- MySQL 5.7+ / MariaDB 10.3+
-- ═══════════════════════════════════════════════════════════

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ── Create & select database ─────────────────────────────────
CREATE DATABASE IF NOT EXISTS `scholarflow_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `scholarflow_db`;

-- ── users ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(120)    NOT NULL,
  `email`      VARCHAR(180)    NOT NULL UNIQUE,
  `password`   VARCHAR(255)    NOT NULL,
  `role`       ENUM('student','reviewer','admin') NOT NULL DEFAULT 'student',
  `phone`      VARCHAR(30)     DEFAULT NULL,
  `address`    VARCHAR(255)    DEFAULT NULL,
  `school`     VARCHAR(150)    DEFAULT NULL,
  `course`     VARCHAR(120)    DEFAULT NULL,
  `gpa`        VARCHAR(20)     DEFAULT NULL,
  `year_level` VARCHAR(30)     DEFAULT NULL,
  `avatar`     VARCHAR(255)    DEFAULT NULL,
  `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_role`  (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── scholarships ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `scholarships` (
  `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(200)    NOT NULL,
  `description`      TEXT            NOT NULL,
  `requirements`     TEXT            DEFAULT NULL,
  `amount`           DECIMAL(12,2)   NOT NULL,
  `deadline`         DATE            NOT NULL,
  `slots`            SMALLINT UNSIGNED DEFAULT NULL COMMENT 'NULL = unlimited',
  `allows_multiple`  TINYINT(1)      NOT NULL DEFAULT 0
                     COMMENT '0 = exclusive, 1 = student may hold multiple',
  `status`           ENUM('active','inactive','closed') NOT NULL DEFAULT 'active',
  `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_status`   (`status`),
  INDEX `idx_deadline` (`deadline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── applications ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `applications` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `user_id`         INT UNSIGNED    NOT NULL,
  `scholarship_id`  INT UNSIGNED    NOT NULL,
  `essay`           TEXT            NOT NULL,
  `status`          ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by`     INT UNSIGNED    DEFAULT NULL,
  `review_notes`    TEXT            DEFAULT NULL,
  `reviewed_at`     DATETIME        DEFAULT NULL,
  `created_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_scholarship` (`user_id`, `scholarship_id`),
  INDEX `idx_status`         (`status`),
  INDEX `idx_user_id`        (`user_id`),
  INDEX `idx_scholarship_id` (`scholarship_id`),
  CONSTRAINT `fk_app_user`
    FOREIGN KEY (`user_id`)        REFERENCES `users`(`id`)         ON DELETE CASCADE,
  CONSTRAINT `fk_app_scholarship`
    FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_app_reviewer`
    FOREIGN KEY (`reviewed_by`)    REFERENCES `users`(`id`)         ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── documents ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `documents` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `application_id`  INT UNSIGNED  NOT NULL,
  `doc_type`        ENUM('transcript','id_document','recommendation','other') NOT NULL,
  `file_path`       VARCHAR(255)  NOT NULL,
  `original_name`   VARCHAR(255)  NOT NULL,
  `uploaded_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_application_id` (`application_id`),
  CONSTRAINT `fk_doc_application`
    FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ═══════════════════════════════════════════════════════════
-- SEED DATA
-- ═══════════════════════════════════════════════════════════

-- Admin account  (password: Admin@1234)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('System Admin', 'admin@scholarflow.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Reviewer account  (password: Reviewer@1234)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Jane Reviewer', 'reviewer@scholarflow.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'reviewer');

-- Demo student  (password: Student@1234)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `school`, `course`, `gpa`, `year_level`) VALUES
('Juan dela Cruz', 'student@scholarflow.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'student', '+63 912 345 6789',
 'Cebu Institute of Technology — University',
 'BS Computer Science', '1.50', '3rd Year');

-- Scholarships
INSERT INTO `scholarships`
  (`name`, `description`, `requirements`, `amount`, `deadline`, `allows_multiple`, `slots`, `status`)
VALUES
(
  'DOST-SEI Merit Scholarship',
  'The Department of Science and Technology Science Education Institute (DOST-SEI) Merit Scholarship supports academically outstanding students pursuing science and technology courses. Awardees receive full financial support including tuition, stipend, and book allowance.',
  'Must be a Filipino citizen. Incoming college freshmen or current students in priority S&T courses. Minimum GPA of 1.75 (90%) in high school or previous semester. Must pass the DOST-SEI qualifying exam.',
  25000.00,
  DATE_ADD(CURDATE(), INTERVAL 60 DAY),
  0,  -- exclusive
  50,
  'active'
),
(
  'SM Foundation College Scholarship',
  'The SM Foundation College Scholarship Program provides financial assistance to deserving but financially challenged students. This scholarship covers tuition fees, monthly stipend, and textbook allowance throughout their college education.',
  'Filipino citizen. Must be a full-time student. Family income must not exceed ₱200,000 per year. Must maintain a minimum GWA of 2.00. Active participation in community service.',
  18000.00,
  DATE_ADD(CURDATE(), INTERVAL 45 DAY),
  1,  -- allows multiple
  100,
  'active'
),
(
  'Gokongwei Brothers Foundation Scholarship',
  'The GBF Scholarship supports academically excellent students from low-income families enrolled in engineering, business, and science courses at accredited Philippine universities.',
  'Must be enrolled in an accredited 4-year course. Minimum GWA of 1.75. Annual family income not exceeding ₱300,000. Must be in 1st to 3rd year of college.',
  20000.00,
  DATE_ADD(CURDATE(), INTERVAL 30 DAY),
  0,  -- exclusive
  30,
  'active'
),
(
  'Ayala Foundation Scholarship',
  'The Ayala Foundation Scholarship Program aims to develop the next generation of leaders by supporting exceptional students with academic excellence and demonstrated leadership potential.',
  'Outstanding academic record (minimum GWA 1.5). Demonstrated leadership in school or community organizations. Financial need. Must be taking business, social sciences, or humanities courses.',
  15000.00,
  DATE_ADD(CURDATE(), INTERVAL 90 DAY),
  1,  -- allows multiple
  NULL,
  'active'
),
(
  'Metrobank Foundation Excellence Award',
  'Recognizes and supports exceptional students who demonstrate academic excellence, strong values, and commitment to service. Open to all courses in accredited Philippine universities.',
  'Must be a 3rd year college student. Must have a minimum GWA of 1.25 (95%). Must show proof of financial need. No failed subjects. Active in school organizations.',
  30000.00,
  DATE_ADD(CURDATE(), INTERVAL 15 DAY),
  1,  -- allows multiple
  25,
  'active'
),
(
  'DepEd Regional Scholarship Grant',
  'A regional government scholarship for public school graduates who demonstrate exceptional academic performance and financial need, prioritizing education and technology courses.',
  'Must be a graduate of a public high school. Enrolled in a state university or college. Minimum GWA of 1.75. Must be a permanent resident of the region.',
  10000.00,
  DATE_ADD(CURDATE(), INTERVAL 75 DAY),
  0,  -- exclusive
  200,
  'active'
);

-- ═══════════════════════════════════════════════════════════
-- NOTE: Default passwords are all "password" (Laravel hash)
-- For production, reset all passwords after setup!
-- Actual bcrypt hash for "Admin@1234" etc — change this in
-- create_admin.php script before going live.
-- ═══════════════════════════════════════════════════════════
