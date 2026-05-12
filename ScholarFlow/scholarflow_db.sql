-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 09:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scholarflow_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `scholarship_id` int(10) UNSIGNED NOT NULL,
  `essay` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by` int(10) UNSIGNED DEFAULT NULL,
  `review_notes` text DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `scholarship_id`, `essay`, `status`, `reviewed_by`, `review_notes`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(10, 3, 7, 'sfdgsxdfsdfsdefsddefdsefsdefdesrfxsefxsderfxserfse', 'pending', NULL, NULL, NULL, '2026-05-12 03:33:24', NULL),
(19, 4, 2, 'sdfsdfsdsdffsdsfdsddsfdsfsdfdsfsdfsdfsdfsdfsdfsdfsfd', 'approved', 2, '', '2026-05-12 20:52:17', '2026-05-12 19:42:31', '2026-05-13 02:52:17');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `application_id` int(10) UNSIGNED NOT NULL,
  `doc_type` enum('transcript','coe_cor','good_moral','id_document','recommendation','other') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `application_id`, `doc_type`, `file_path`, `original_name`, `uploaded_at`) VALUES
(19, 10, 'transcript', 'documents/doc_6a028364953812.78018058.png', 'Axolotl_Bucket.png', '2026-05-12 03:33:24'),
(20, 10, 'id_document', 'documents/doc_6a028364e86cb4.22194261.png', 'Axolotl_Bucket.png', '2026-05-12 03:33:24'),
(39, 19, 'transcript', 'documents/doc_6a036687ab78a2.22622957.jpg', 'c102b153-e1a7-4835-ac99-5c2d04808d42 (1).jpg', '2026-05-12 19:42:31'),
(40, 19, 'coe_cor', 'documents/doc_6a036687c94a73.20085036.jpg', 'c102b153-e1a7-4835-ac99-5c2d04808d42 (1).jpg', '2026-05-12 19:42:31'),
(41, 19, 'good_moral', 'documents/doc_6a036687daf2e3.23287909.jpg', 'c102b153-e1a7-4835-ac99-5c2d04808d42 (1).jpg', '2026-05-12 19:42:31'),
(42, 19, 'id_document', 'documents/doc_6a036687eb2ed5.34393796.jpg', 'c102b153-e1a7-4835-ac99-5c2d04808d42 (1).jpg', '2026-05-12 19:42:31');

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `deadline` date NOT NULL,
  `slots` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'NULL = unlimited',
  `allows_multiple` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = exclusive, 1 = student may hold multiple',
  `status` enum('active','inactive','closed') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scholarships`
--

INSERT INTO `scholarships` (`id`, `name`, `description`, `requirements`, `amount`, `deadline`, `slots`, `allows_multiple`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DOST-SEI Merit Scholarship', 'The Department of Science and Technology Science Education Institute (DOST-SEI) Merit Scholarship supports academically outstanding students pursuing science and technology courses. Awardees receive full financial support including tuition, stipend, and book allowance.', 'Must be a Filipino citizen. Incoming college freshmen or current students in priority S&T courses. Minimum GPA of 1.75 (90%) in high school or previous semester. Must pass the DOST-SEI qualifying exam.', 25000.00, '2026-07-08', 50, 0, 'active', '2026-05-09 23:16:07', NULL),
(2, 'SM Foundation College Scholarship', 'The SM Foundation College Scholarship Program provides financial assistance to deserving but financially challenged students. This scholarship covers tuition fees, monthly stipend, and textbook allowance throughout their college education.', 'Filipino citizen. Must be a full-time student. Family income must not exceed ₱200,000 per year. Must maintain a minimum GWA of 2.00. Active participation in community service.', 18000.00, '2026-06-23', 100, 1, 'active', '2026-05-09 23:16:07', NULL),
(3, 'Gokongwei Brothers Foundation Scholarship', 'The GBF Scholarship supports academically excellent students from low-income families enrolled in engineering, business, and science courses at accredited Philippine universities.', 'Must be enrolled in an accredited 4-year course. Minimum GWA of 1.75. Annual family income not exceeding ₱300,000. Must be in 1st to 3rd year of college.', 20000.00, '2026-06-08', 30, 0, 'active', '2026-05-09 23:16:07', NULL),
(4, 'Ayala Foundation Scholarship', 'The Ayala Foundation Scholarship Program aims to develop the next generation of leaders by supporting exceptional students with academic excellence and demonstrated leadership potential.', 'Outstanding academic record (minimum GWA 1.5). Demonstrated leadership in school or community organizations. Financial need. Must be taking business, social sciences, or humanities courses.', 15000.00, '2026-08-07', NULL, 1, 'active', '2026-05-09 23:16:07', NULL),
(5, 'Metrobank Foundation Excellence Award', 'Recognizes and supports exceptional students who demonstrate academic excellence, strong values, and commitment to service. Open to all courses in accredited Philippine universities.', 'Must be a 3rd year college student. Must have a minimum GWA of 1.25 (95%). Must show proof of financial need. No failed subjects. Active in school organizations.', 30000.00, '2026-05-24', 25, 1, 'active', '2026-05-09 23:16:07', NULL),
(6, 'DepEd Regional Scholarship Grant', 'A regional government scholarship for public school graduates who demonstrate exceptional academic performance and financial need, prioritizing education and technology courses.', 'Must be a graduate of a public high school. Enrolled in a state university or college. Minimum GWA of 1.75. Must be a permanent resident of the region.', 10000.00, '2026-07-23', 200, 0, 'active', '2026-05-09 23:16:07', NULL),
(7, 'Future Innovators STEM Scholarship 2026', 'This scholarship aims to support academically talented but financially disadvantaged Filipino students pursuing degrees in Science, Technology, Engineering, and Mathematics (STEM). The goal is to cultivate the next generation of researchers and innovators to drive national progress.', 'Incoming 1st-year college student for Academic Year 2026-2027.\r\nMust be enrolled in a STEM-related course (e.g., Engineering, Computer Science, Biology, Chemistry).\r\nMust have a general weighted average (GWA) of at least 85% or equivalent in Grades 11 and 12.', 50000.00, '2026-05-16', 30, 0, 'active', '2026-05-10 18:20:27', '2026-05-12 19:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','reviewer','admin') NOT NULL DEFAULT 'student',
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `school` varchar(150) DEFAULT NULL,
  `course` varchar(120) DEFAULT NULL,
  `gpa` varchar(20) DEFAULT NULL,
  `year_level` varchar(30) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `school`, `course`, `gpa`, `year_level`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'admin@scholarflow.com', '$2y$10$Am06hGblcASejPXFSNvQQedfnFqC1DREBeA8sFW8.NjWA.2eGLxJG', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-09 23:16:06', '2026-05-11 15:33:17'),
(2, 'Jane Reviewer', 'reviewer@scholarflow.com', '$2y$10$lgyUB5cyiBD5KbyMmGbbcOksRoRKs1RukePi1ykg6rnEjW26.AkMy', 'reviewer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-09 23:16:06', '2026-05-12 22:47:27'),
(3, 'Juan dela Cruz', 'student@scholarflow.com', '$2y$10$5En/q75zZHef5KM3jyIhhO.e5tbbbSk1tSVhaszmdNl8cJZj0Shba', 'student', '+63 912 345 6789', NULL, 'Cebu Institute of Technology — University', 'BS Computer Science', '1.50', '3rd Year', NULL, '2026-05-09 23:16:06', '2026-05-12 09:20:27'),
(4, 'Aerll Kian Villalon', 'aerllkianvillalon@gmail.com', '$2y$10$V3YwBkqw5b3FF/JZo.WNb.ftuFqlgvMb9AgkVhyC0GX4y37Y6YDBa', 'student', '09109466040', 'Taboc, Danao City, Cebu', 'Cebu Technological University - Danao Campus', 'BSIT', '1', '2nd Year', 'avatars/doc_6a026adaee0131.42212625.jpg', '2026-05-10 11:23:15', '2026-05-12 01:48:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_scholarship` (`user_id`,`scholarship_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_scholarship_id` (`scholarship_id`),
  ADD KEY `fk_app_reviewer` (`reviewed_by`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_deadline` (`deadline`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_app_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_app_scholarship` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_app_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_doc_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
