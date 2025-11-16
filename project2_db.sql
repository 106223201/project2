-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 06:12 PM
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
-- Database: `project2_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `jobref` varchar(5) NOT NULL,
  `Fname` varchar(20) NOT NULL,
  `Lname` varchar(20) NOT NULL,
  `dob` varchar(10) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `street` varchar(40) NOT NULL,
  `suburbtown` varchar(40) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skills` varchar(200) NOT NULL,
  `otherskills` text NOT NULL,
  `status` enum('New','Under Review','Interview Scheduled','Accepted','Rejected') DEFAULT 'New',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `jobref`, `Fname`, `Lname`, `dob`, `gender`, `street`, `suburbtown`, `state`, `postcode`, `email`, `phone`, `skills`, `otherskills`, `status`, `created_at`, `user_id`) VALUES
(4, 'ML24C', 'Hung', 'Nguyen', '', 'Male', '1 Spoonstreet', 'Melbourne', 'NSW', '1234', 'hungphan@gmail.com', '021234543534', 'python_programming_language, data_science, cyber_security, project_management', '', 'New', '2025-11-16 15:51:28', NULL),
(11, 'ML24C', 'Duong', 'Nguyen', '2000-10-10', '', 'ftown', 'fsuburb', 'VIC', '3000', 'kenzienguyen@gmail.com', '0212345435', 'python_programming_language, data_science, cyber_security', 'cooking', 'New', '2025-11-16 15:59:50', NULL),
(12, 'ML24C', 'Duong', 'Nguyen', '2000-10-10', '', 'ftown', 'fsuburb', 'VIC', '3000', 'kenzienguyen@gmail.com', '0212345435', 'python_programming_language, data_science, cyber_security', 'cooking', 'New', '2025-11-16 16:03:57', NULL),
(13, 'ML24C', 'Duong', 'Nguyen', '2000-10-10', '', 'ftown', 'fsuburb', 'VIC', '3000', 'kenzienguyen@gmail.com', '0212345435', 'python_programming_language, data_science, cyber_security', 'cooking', 'New', '2025-11-16 16:04:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `jobref` varchar(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `salary` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_responsibilities`
--

INSERT INTO `job_responsibilities` (`responsibility_id`, `job_id`, `responsibility_text`, `display_order`) VALUES
(1, 1, 'Collect, process, and analyze large datasets to identify trends and patterns', 1),
(2, 1, 'Create and maintain interactive dashboards and reports for stakeholders', 2),
(3, 1, 'Perform statistical analysis and develop predictive models', 3),
(4, 1, 'Collaborate with business teams to understand data requirements and KPIs', 4),
(5, 1, 'Present findings and recommendations to senior leadership', 5),
(6, 1, 'Ensure data quality and integrity across reporting systems', 6),
(7, 2, 'Design, develop, and maintain scalable web applications using modern frameworks', 1),
(8, 2, 'Collaborate with cross-functional teams to define, design, and ship new features', 2),
(9, 2, 'Write clean, maintainable, and efficient code following best practices', 3),
(10, 2, 'Participate in code reviews and provide constructive feedback to team members', 4),
(11, 2, 'Troubleshoot, debug, and resolve production issues in a timely manner', 5),
(12, 2, 'Optimize application performance and ensure high availability', 6),
(13, 3, 'Design, develop, and implement machine learning models and algorithms', 1),
(14, 3, 'Train, evaluate, and optimize ML models for production deployment', 2),
(15, 3, 'Research and apply state-of-the-art AI/ML techniques to business problems', 3),
(16, 3, 'Build and maintain scalable ML pipelines and infrastructure', 4),
(17, 3, 'Collaborate with data engineers, software developers, and product managers', 5),
(18, 3, 'Monitor model performance and implement improvements continuously', 6),
(19, 4, 'Design, implement, and manage cloud infrastructure on AWS, Azure, or GCP', 1),
(20, 4, 'Automate deployment processes using Infrastructure as Code (IaC) tools', 2),
(21, 4, 'Monitor and optimize cloud resource usage and cost management', 3),
(22, 4, 'Implement and maintain security, compliance, and disaster recovery procedures', 4),
(23, 4, 'Support development teams with cloud architecture guidance and best practices', 5),
(24, 4, 'Troubleshoot and resolve cloud infrastructure issues', 6);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `time_count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `time_count`) VALUES
(5, '127.0.0.1', 1763234443),
(6, '127.0.0.1', 1763234449),
(7, '127.0.0.1', 1763234452),
(0, '::1', 1763312601),
(0, '::1', 1763312609),
(0, '::1', 1763312636);

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `username`, `password`, `email`, `full_name`, `failed_attempts`, `locked_until`, `created_at`, `last_login`) VALUES
(1, 'admin', '$2y$10$bXJYZFB4BkoC3QqH9K8vAeHN9sn6rKXGN7vP8J0tM1hVx.6OP6nLi', 'admin@epass.com', 'System Administrator', 3, '2025-11-16 16:47:10', '2025-11-16 15:27:41', NULL),
(2, 'admin123', '$2y$10$Jwa71q5w/TayNH2z1ZHupuKbHtiqLXByZ7VRs3LCkW5kUojDAa9OW', 'admin@hr.com', 'admin yo', 0, NULL, '2025-11-16 15:38:15', '2025-11-16 16:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `manager_activity_log`
--

CREATE TABLE `manager_activity_log` (
  `log_id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `action_type` enum('login','logout','query','update','delete') NOT NULL,
  `action_details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager_activity_log`
--

INSERT INTO `manager_activity_log` (`log_id`, `manager_id`, `action_type`, `action_details`, `ip_address`, `created_at`) VALUES
(1, 2, 'login', 'Successful login', '::1', '2025-11-16 15:38:30'),
(2, 2, 'query', 'Searched by name: Nguyen Hung', NULL, '2025-11-16 15:51:32'),
(3, 2, 'query', 'Viewed EOIs for job: DA24B', NULL, '2025-11-16 15:51:36'),
(4, 2, 'query', 'Viewed EOIs for job: DA24B', NULL, '2025-11-16 15:52:35'),
(5, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:53:14'),
(6, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:53:17'),
(7, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:53:33'),
(8, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:53:35'),
(9, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:55:42'),
(10, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:57:26'),
(11, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:57:30'),
(12, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 15:57:33'),
(13, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 16:05:43'),
(14, 2, 'query', 'Searched by name: Nguyen Duong', NULL, '2025-11-16 16:05:56'),
(15, 2, 'query', 'Searched by name: DUong Nguyen', NULL, '2025-11-16 16:06:04'),
(16, 2, 'query', 'Viewed EOIs for job: ML24C', NULL, '2025-11-16 16:06:13'),
(17, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 16:06:29'),
(18, 2, 'logout', 'Logged out', '::1', '2025-11-16 16:11:54'),
(19, 2, 'login', 'Successful login', '::1', '2025-11-16 16:12:01'),
(20, 2, 'logout', 'Logged out', '::1', '2025-11-16 16:12:07'),
(21, 2, 'login', 'Successful login', '::1', '2025-11-16 16:15:23'),
(22, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 16:15:46'),
(23, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 16:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `password`) VALUES
('idk', 'idk@man.com', 'idkmanlol'),
('kenzie', 'kenzienguyen@gmail.com', 'kenzienguyen'),
('kenzienguyen', 'kenzienguyenz@gmail.com', 'kenzienguyen'),
('lmao', 'lmao@lmao.com', '$2y$10$K7Lh33mupMcd132ywXu/mOwI94ejoXTRl6P7CCk9MS6AEX3CJP1o6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Indexes for table `job_qualifications`
--
ALTER TABLE `job_qualifications`
  ADD PRIMARY KEY (`qualification_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `job_responsibilities`
--
ALTER TABLE `job_responsibilities`
  ADD PRIMARY KEY (`responsibility_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_locked` (`locked_until`);

--
-- Indexes for table `manager_activity_log`
--
ALTER TABLE `manager_activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_manager` (`manager_id`),
  ADD KEY `idx_action` (`action_type`),
  ADD KEY `idx_date` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_qualifications`
--
ALTER TABLE `job_qualifications`
  MODIFY `qualification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `job_responsibilities`
--
ALTER TABLE `job_responsibilities`
  MODIFY `responsibility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `manager_activity_log`
--
ALTER TABLE `manager_activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `job_qualifications`
--
ALTER TABLE `job_qualifications`
  ADD CONSTRAINT `job_qualifications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;

--
-- Constraints for table `job_responsibilities`
--
ALTER TABLE `job_responsibilities`
  ADD CONSTRAINT `job_responsibilities_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;

--
-- Constraints for table `manager_activity_log`
--
ALTER TABLE `manager_activity_log`
  ADD CONSTRAINT `manager_activity_log_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`manager_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
