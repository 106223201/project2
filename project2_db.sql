-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 09:53 AM
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
  `user_id` int(11) DEFAULT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `user_id`, `jobref`, `Fname`, `Lname`, `dob`, `gender`, `street`, `suburbtown`, `state`, `postcode`, `email`, `phone`, `skills`, `otherskills`, `status`, `created_at`) VALUES
(4, NULL, 'ML24C', 'Hung', 'Nguyen', '', 'Male', '1 Spoonstreet', 'Melbourne', 'NSW', '1234', 'hungphan@gmail.com', '021234543534', 'python_programming_language, data_science, cyber_security, project_management', '', 'New', '2025-11-16 15:51:28'),
(11, NULL, 'ML24C', 'Duong', 'Nguyen', '2000-10-10', '', 'ftown', 'fsuburb', 'VIC', '3000', 'kenzienguyen@gmail.com', '0212345435', 'python_programming_language, data_science, cyber_security', 'cooking', 'New', '2025-11-16 15:59:50'),
(12, NULL, 'ML24C', 'Duong', 'Nguyen', '2000-10-10', '', 'ftown', 'fsuburb', 'VIC', '3000', 'kenzienguyen@gmail.com', '0212345435', 'python_programming_language, data_science, cyber_security', 'cooking', 'New', '2025-11-16 16:03:57'),
(13, NULL, 'ML24C', 'Duong', 'Nguyen', '2000-10-10', '', 'ftown', 'fsuburb', 'VIC', '3000', 'kenzienguyen@gmail.com', '0212345435', 'python_programming_language, data_science, cyber_security', 'cooking', 'New', '2025-11-16 16:04:05'),
(14, NULL, 'SE24A', 'fdsfasdf', 'fsafdasf', '2000-10-10', '', 'fsdfasdf', 'fdsafadsf', 'VIC', '4000', 'fdsafds@gmail.com', '12312312321', 'python_programming_language, data_science', '', 'New', '2025-11-16 17:24:25'),
(15, NULL, 'SE24A', 'Duong', 'Nguyen', '2000-10-25', '', '1 Spoonstreet', 'Melbourne', 'VIC', '1321', 'mkenzienguyen@gmail.com', '0983458302', 'python_programming_language, data_science', '', 'New', '2025-11-17 07:55:44'),
(16, 6, 'CE24D', 'Duong', 'Nguyen', '2000-10-10', '', '4 Spoonstreet', 'Melbourne', 'VIC', '4000', 'mkenzienguyen@gmail.com', '0983045486', 'python_programming_language, data_science', '', 'New', '2025-11-17 08:28:51');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `location` varchar(50) NOT NULL,
  `employment_type` varchar(50) DEFAULT 'Full-time',
  `salary_min` int(11) NOT NULL,
  `salary_max` int(11) NOT NULL,
  `reports_to` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `reference`, `title`, `location`, `employment_type`, `salary_min`, `salary_max`, `reports_to`, `description`, `created_at`, `updated_at`) VALUES
(1, 'DA24B', 'Data Analyst', 'Melbourne', 'Full-time', 85000, 115000, 'Head of Data Analytics', 'Join our data team to transform complex datasets into actionable insights that drive business decisions. You will work closely with stakeholders across the organization to understand their needs and deliver data-driven solutions.', '2025-11-15 09:26:28', '2025-11-15 09:26:28'),
(2, 'SE24A', 'Software Engineer', 'Sydney', 'Full-time', 120000, 160000, 'Engineering Manager', 'We are seeking a talented Software Engineer to join our growing team. You will design and develop scalable web applications, work with modern technologies, and contribute to building innovative solutions for our global customer base.', '2025-11-15 09:26:28', '2025-11-15 09:26:28'),
(3, 'ML24C', 'AI/ML Engineer', 'Melbourne', 'Full-time', 140000, 190000, 'Director of AI Research', 'We\'re looking for an experienced AI/ML Engineer to develop and deploy cutting-edge machine learning models. You will research, design, and implement AI solutions that enhance our products and create new capabilities for our users.', '2025-11-15 09:26:28', '2025-11-15 09:26:28'),
(4, 'CE24D', 'Cloud Engineer', 'Sydney', 'Full-time', 110000, 145000, 'Cloud Infrastructure Lead', 'As a Cloud Engineer, you will design, implement, and manage our cloud infrastructure to ensure high availability, security, and cost-efficiency. You will work with development teams to architect scalable cloud solutions that support our growing platform.', '2025-11-15 09:26:28', '2025-11-15 09:26:28');

-- --------------------------------------------------------

--
-- Table structure for table `job_qualifications`
--

CREATE TABLE `job_qualifications` (
  `qualification_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `qualification_text` text NOT NULL,
  `is_essential` tinyint(1) DEFAULT 1,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_qualifications`
--

INSERT INTO `job_qualifications` (`qualification_id`, `job_id`, `qualification_text`, `is_essential`, `display_order`) VALUES
(1, 1, 'Bachelor\'s degree in Statistics, Mathematics, Economics, or related field', 1, 1),
(2, 1, '2+ years of experience in data analysis or business intelligence', 1, 2),
(3, 1, 'Advanced proficiency in SQL for data extraction and manipulation', 1, 3),
(4, 1, 'Experience with data visualization tools (Tableau, Power BI, or Looker)', 1, 4),
(5, 1, 'Strong knowledge of Python or R for statistical analysis', 1, 5),
(6, 1, 'Understanding of statistical methods and A/B testing', 1, 6),
(7, 1, 'Excellent analytical and problem-solving skills', 1, 7),
(8, 1, 'Strong communication skills with ability to explain complex data to non-technical audiences', 1, 8),
(9, 1, 'Master\'s degree in Data Science, Statistics, or related field', 0, 9),
(10, 1, 'Experience with big data technologies (Hadoop, Spark)', 0, 10),
(11, 1, 'Knowledge of machine learning algorithms and frameworks', 0, 11),
(12, 1, 'Experience with cloud data platforms (Snowflake, BigQuery, Redshift)', 0, 12),
(13, 1, 'Familiarity with ETL processes and data warehousing concepts', 0, 13),
(14, 1, 'Experience in financial services or e-commerce industry', 0, 14),
(15, 2, 'Bachelor\'s degree in Computer Science, Software Engineering, or related field', 1, 1),
(16, 2, '3+ years of professional experience in software development', 1, 2),
(17, 2, 'Strong proficiency in JavaScript and one backend language (Python, Java, or Go)', 1, 3),
(18, 2, 'Experience with React.js or Vue.js for frontend development', 1, 4),
(19, 2, 'Solid understanding of RESTful APIs and microservices architecture', 1, 5),
(20, 2, 'Experience with version control systems, particularly Git', 1, 6),
(21, 2, 'Knowledge of SQL databases (PostgreSQL, MySQL)', 1, 7),
(22, 2, 'Strong problem-solving and analytical skills', 1, 8),
(23, 2, 'Excellent written and verbal communication skills', 1, 9),
(24, 2, 'Master\'s degree in Computer Science or related field', 0, 10),
(25, 2, 'Experience with cloud platforms (AWS, Azure, or GCP)', 0, 11),
(26, 2, 'Knowledge of containerization technologies (Docker, Kubernetes)', 0, 12),
(27, 2, 'Experience with CI/CD pipelines and DevOps practices', 0, 13),
(28, 2, 'Familiarity with NoSQL databases (MongoDB, Redis)', 0, 14),
(29, 2, 'Contributions to open-source projects', 0, 15),
(30, 2, 'Experience with agile development methodologies', 0, 16),
(31, 3, 'Master\'s degree in Computer Science, Machine Learning, AI, or related field', 1, 1),
(32, 3, '4+ years of hands-on experience in machine learning engineering', 1, 2),
(33, 3, 'Expert-level programming skills in Python', 1, 3),
(34, 3, 'Deep knowledge of ML frameworks (TensorFlow, PyTorch, or Keras)', 1, 4),
(35, 3, 'Experience with deep learning architectures (CNNs, RNNs, Transformers)', 1, 5),
(36, 3, 'Strong understanding of NLP, computer vision, or recommendation systems', 1, 6),
(37, 3, 'Experience deploying ML models to production environments', 1, 7),
(38, 3, 'Solid foundation in statistics, probability, and linear algebra', 1, 8),
(39, 3, 'PhD in Machine Learning, AI, or related field', 0, 9),
(40, 3, 'Published research papers in top-tier conferences (NeurIPS, ICML, CVPR)', 0, 10),
(41, 3, 'Experience with MLOps tools and practices (MLflow, Kubeflow)', 0, 11),
(42, 3, 'Knowledge of reinforcement learning and generative AI', 0, 12),
(43, 3, 'Experience with distributed training and model optimization', 0, 13),
(44, 3, 'Familiarity with LLMs and prompt engineering', 0, 14),
(45, 3, 'Experience with edge AI and model quantization', 0, 15),
(46, 4, 'Bachelor\'s degree in Computer Science, Information Technology, or related field', 1, 1),
(47, 4, '3+ years of experience with cloud platforms (AWS, Azure, or GCP)', 1, 2),
(48, 4, 'Proficiency in scripting languages (Python, Bash, or PowerShell)', 1, 3),
(49, 4, 'Strong experience with Infrastructure as Code tools (Terraform or CloudFormation)', 1, 4),
(50, 4, 'Experience with containerization technologies (Docker, Kubernetes)', 1, 5),
(51, 4, 'Knowledge of CI/CD pipelines and automation tools (Jenkins, GitLab CI)', 1, 6),
(52, 4, 'Strong understanding of networking, security, and system administration', 1, 7),
(53, 4, 'Experience with monitoring and logging tools (CloudWatch, Prometheus, ELK)', 1, 8),
(54, 4, 'Cloud certifications (AWS Solutions Architect, Azure Administrator, GCP Professional)', 0, 9),
(55, 4, 'Experience with multiple cloud providers', 0, 10),
(56, 4, 'Knowledge of serverless architectures and services (Lambda, Cloud Functions)', 0, 11),
(57, 4, 'Experience with configuration management tools (Ansible, Chef, Puppet)', 0, 12),
(58, 4, 'Familiarity with service mesh technologies (Istio, Linkerd)', 0, 13),
(59, 4, 'Understanding of FinOps principles and cost optimization strategies', 0, 14),
(60, 4, 'Experience with database management in cloud environments', 0, 15);

-- --------------------------------------------------------

--
-- Table structure for table `job_responsibilities`
--

CREATE TABLE `job_responsibilities` (
  `responsibility_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `responsibility_text` text NOT NULL,
  `display_order` int(11) NOT NULL
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
(7, '127.0.0.1', 1763234452);

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
(23, 2, 'query', 'Viewed all EOIs', NULL, '2025-11-16 16:28:09'),
(24, 2, 'logout', 'Logged out', '::1', '2025-11-17 08:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'idk', 'idk@man.com', 'idkmanlol'),
(2, 'kenzie', 'kenzienguyen@gmail.com', 'kenzienguyen'),
(3, 'kenzienguyen', 'kenzienguyenz@gmail.com', 'kenzienguyen'),
(4, 'lmao', 'lmao@lmao.com', '$2y$10$K7Lh33mupMcd132ywXu/mOwI94ejoXTRl6P7CCk9MS6AEX3CJP1o6'),
(5, 'lmaojk', 'lmaojk@gmail.com', '$2y$10$ZPBgtcRq5AJHf.jrQ29cyO7R401j9cfUw6DN9QxrUmwqt8pRB8ltK'),
(6, 'mkenzie', 'mkenzienguyen@gmail.com', '$2y$10$JbsFWA2esOKIzLgWbjGdDOSH8C4LVIL9Pdw2Wo/EOsXYXVVB.Quca');

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
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
