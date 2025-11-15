-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 07:21 PM
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
  `street` varchar(40) NOT NULL,
  `suburbtown` varchar(40) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skills` varchar(200) NOT NULL,
  `otherskills` text NOT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `jobref`, `Fname`, `Lname`, `street`, `suburbtown`, `state`, `postcode`, `email`, `phone`, `skills`, `otherskills`, `status`) VALUES
(1, 'SE24A', 'tung', 'luu', 'hht', 'hn', 'VIC', '1000', 't@gmail.com', '123456789', 'python_programming_language, data_science, cyber_security, software_development', 'test', 'New'),
(2, 'SE24A', 'duong', 'Nguyen', '4 Sponstreet', 'Melbourne', 'VIC', '1232', 'kenzienguyen22@gmail.com', '0983045486', 'python_programming_language, data_science, cyber_security, project_management, software_development, technical_writing', 'Cooking skills, coding', 'New'),
(3, 'SE24A', 'Anh', 'Nguyen', '4 Spoonstreet', 'Melbourne', 'VIC', '1345', 'i_love_web_development@swinburne.com', '0348139123', 'python_programming_language, data_science, cyber_security, project_management, software_development, technical_writing', 'Cooking', 'New'),
(4, 'ML24C', 'Hung', 'Nguyen', '1 Spoonstreet', 'Melbourne', 'NSW', '1234', 'hungphan@gmail.com', '021234543534', 'python_programming_language, data_science, cyber_security, project_management', '', 'New');

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `password`) VALUES
('duongnguyen', 'duongnguyen@gmail.com', '$2y$10$wDQ.lj9K7wf5q23p.rXRVuK0RWsmmhwSVX4TH8AVP69'),
('kenziedang', 'ilovewebdev@gmail.com', '$2y$10$KK4WUpYMMpysgsA59lWACOFs4DpxYfK8WuCpqFRufCr'),
('kenzieduongnguyen', 'kenzieduong@gmail.com', '$2y$10$jMYmmuy3RF0PT.uPXXxsju.Y6RvbdSPkUsYui6KKC3X'),
('kenzienguyen', 'mkenzienguyen@gmail.com', 'ilovewebdev');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
