-- ====================================================================
-- YASMEEN MATERNITY AND MEDICAL CENTER (R-85647)
-- Automated SQL Database Backup
-- Generated on: 2026-09-10 04:34:30
-- Developed_By_DCtechsolutions
-- ====================================================================

SET FOREIGN_KEY_CHECKS=0;

-- Table structure for `roles`
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `roles`
INSERT INTO `roles` (`id`, `role_name`, `description`, `created_at`) VALUES ('1', 'administrator', 'Complete system access, user management, financial reports, settings, and backups', '2026-09-10 04:29:08');
INSERT INTO `roles` (`id`, `role_name`, `description`, `created_at`) VALUES ('2', 'receptionist', 'Patient registration, search, visits, and employee attendance recording', '2026-09-10 04:29:08');
INSERT INTO `roles` (`id`, `role_name`, `description`, `created_at`) VALUES ('3', 'pharmacist', 'Pharmacy inventory, purchase entries, dispensing/sales, and patient medicine history', '2026-09-10 04:29:08');

-- Table structure for `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('administrator','receptionist','pharmacist') NOT NULL,
  `contact` varchar(30) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `users`
INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `role`, `contact`, `status`, `last_login`, `created_at`, `updated_at`) VALUES ('1', 'admin', '$2y$10$.OY7M0ijqTUWV5ttaJ2PAeSz7ycZn9lLhwjTleOJFUPSkoTumDpda', 'Dr. Yasmeen Administrator', 'administrator', '03074246239', 'active', '2026-09-10 04:32:21', '2026-09-10 04:29:08', '2026-09-10 04:32:21');
INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `role`, `contact`, `status`, `last_login`, `created_at`, `updated_at`) VALUES ('2', 'receptionist', '$2y$10$.OY7M0ijqTUWV5ttaJ2PAeSz7ycZn9lLhwjTleOJFUPSkoTumDpda', 'Fatima Reception Desk', 'receptionist', '03001234567', 'active', NULL, '2026-09-10 04:29:08', '2026-09-10 04:32:01');
INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `role`, `contact`, `status`, `last_login`, `created_at`, `updated_at`) VALUES ('3', 'pharmacist', '$2y$10$.OY7M0ijqTUWV5ttaJ2PAeSz7ycZn9lLhwjTleOJFUPSkoTumDpda', 'Tariq Pharmacist', 'pharmacist', '03219876543', 'active', NULL, '2026-09-10 04:29:08', '2026-09-10 04:32:01');

-- Table structure for `doctors_lhvs`
DROP TABLE IF EXISTS `doctors_lhvs`;
CREATE TABLE `doctors_lhvs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL DEFAULT 'Medical Officer',
  `contact` varchar(30) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_doctors_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `doctors_lhvs`
INSERT INTO `doctors_lhvs` (`id`, `name`, `designation`, `contact`, `status`, `created_at`, `updated_at`) VALUES ('1', 'Dr. Yasmeen Akhtar', 'Consultant Gynecologist & Obstetrician', '03074246239', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `doctors_lhvs` (`id`, `name`, `designation`, `contact`, `status`, `created_at`, `updated_at`) VALUES ('2', 'Dr. Muhammad Bilal', 'Senior Medical Officer', '03005551234', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `doctors_lhvs` (`id`, `name`, `designation`, `contact`, `status`, `created_at`, `updated_at`) VALUES ('3', 'LHV Nasreen Bibi', 'Lady Health Visitor (LHV)', '03124445566', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `doctors_lhvs` (`id`, `name`, `designation`, `contact`, `status`, `created_at`, `updated_at`) VALUES ('4', 'Dr. Ayesha Siddiqa', 'Pediatrician / Child Specialist', '03337778899', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');

-- Table structure for `employees`
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(30) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `contact` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_code` (`employee_code`),
  KEY `idx_employees_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `employees`
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `designation`, `contact`, `address`, `status`, `created_at`, `updated_at`) VALUES ('1', 'EMP-001', 'Fatima Zahra', 'Senior Receptionist', '03001234567', 'Sundar, Raiwind Road, Lahore', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `designation`, `contact`, `address`, `status`, `created_at`, `updated_at`) VALUES ('2', 'EMP-002', 'Tariq Mehmood', 'Head Pharmacist', '03219876543', 'Chishtia Colony, Lahore', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `designation`, `contact`, `address`, `status`, `created_at`, `updated_at`) VALUES ('3', 'EMP-003', 'Nasreen Bibi', 'Lady Health Visitor', '03124445566', 'Raiwind Road, Lahore', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `designation`, `contact`, `address`, `status`, `created_at`, `updated_at`) VALUES ('4', 'EMP-004', 'Sajjad Ali', 'Lab Assistant', '03456667788', 'Sundar Industrial Estate, Lahore', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `designation`, `contact`, `address`, `status`, `created_at`, `updated_at`) VALUES ('5', 'EMP-005', 'Samina Kausar', 'Staff Nurse (Maternity)', '03029998877', 'Manga Mandi, Lahore', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');

-- Table structure for `patients`
DROP TABLE IF EXISTS `patients`;
CREATE TABLE `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(30) NOT NULL,
  `registration_date` date NOT NULL,
  `name` varchar(100) NOT NULL,
  `relation_type` enum('S/O','D/O','W/O') NOT NULL,
  `relation_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `contact` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id` (`unique_id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_patients_unique_id` (`unique_id`),
  KEY `idx_patients_name` (`name`),
  KEY `idx_patients_contact` (`contact`),
  KEY `idx_patients_date` (`registration_date`),
  CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors_lhvs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patients_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `patients`
INSERT INTO `patients` (`id`, `unique_id`, `registration_date`, `name`, `relation_type`, `relation_name`, `age`, `sex`, `contact`, `address`, `doctor_id`, `created_by`, `created_at`, `updated_at`) VALUES ('1', 'P-2026-000001', '2026-09-10', 'Zainab Bibi', 'W/O', 'Muhammad Rashid', '26', 'Female', '03014455667', 'Chishtia Colony, Sundar', '1', '1', '2026-09-10 04:29:40', '2026-09-10 04:29:40');
INSERT INTO `patients` (`id`, `unique_id`, `registration_date`, `name`, `relation_type`, `relation_name`, `age`, `sex`, `contact`, `address`, `doctor_id`, `created_by`, `created_at`, `updated_at`) VALUES ('2', 'P-2026-000002', '2026-09-10', 'Maryam Kausar', 'D/O', 'Abdul Ghaffar', '19', 'Female', '03228899001', 'Raiwind Road, Lahore', '1', '1', '2026-09-10 04:29:40', '2026-09-10 04:29:40');
INSERT INTO `patients` (`id`, `unique_id`, `registration_date`, `name`, `relation_type`, `relation_name`, `age`, `sex`, `contact`, `address`, `doctor_id`, `created_by`, `created_at`, `updated_at`) VALUES ('3', 'P-2026-000003', '2026-09-08', 'Muhammad Usman', 'S/O', 'Tariq Mehmood', '34', 'Male', '03441122334', 'Sundar Estate', '2', '1', '2026-09-10 04:29:40', '2026-09-10 04:29:40');
INSERT INTO `patients` (`id`, `unique_id`, `registration_date`, `name`, `relation_type`, `relation_name`, `age`, `sex`, `contact`, `address`, `doctor_id`, `created_by`, `created_at`, `updated_at`) VALUES ('4', 'P-2026-000004', '2026-09-10', 'Shaista Parveen', 'W/O', 'Muhammad Akram', '28', 'Female', '03009988776', 'Sundar Village, Lahore', '1', '1', '2026-09-10 04:34:13', '2026-09-10 04:34:13');

-- Table structure for `patient_visits`
DROP TABLE IF EXISTS `patient_visits`;
CREATE TABLE `patient_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `symptoms_notes` text DEFAULT NULL,
  `vitals_bp` varchar(20) DEFAULT NULL,
  `vitals_temp` varchar(20) DEFAULT NULL,
  `vitals_weight` varchar(20) DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT 0.00,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `created_by` (`created_by`),
  KEY `idx_visits_patient` (`patient_id`),
  KEY `idx_visits_date` (`visit_date`),
  CONSTRAINT `patient_visits_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_visits_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors_lhvs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_visits_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `patient_visits`
INSERT INTO `patient_visits` (`id`, `patient_id`, `visit_date`, `doctor_id`, `symptoms_notes`, `vitals_bp`, `vitals_temp`, `vitals_weight`, `consultation_fee`, `created_by`, `created_at`) VALUES ('1', '1', '2026-09-10', '1', 'Routine antenatal checkup (2nd trimester). Fetal heart sounds normal.', '115/75', '98.4 F', '64 kg', '500.00', '1', '2026-09-10 04:29:40');
INSERT INTO `patient_visits` (`id`, `patient_id`, `visit_date`, `doctor_id`, `symptoms_notes`, `vitals_bp`, `vitals_temp`, `vitals_weight`, `consultation_fee`, `created_by`, `created_at`) VALUES ('2', '2', '2026-09-10', '1', 'Severe morning sickness and dehydration. Prescribed vitamins & rest.', '100/70', '98.6 F', '52 kg', '500.00', '1', '2026-09-10 04:29:40');
INSERT INTO `patient_visits` (`id`, `patient_id`, `visit_date`, `doctor_id`, `symptoms_notes`, `vitals_bp`, `vitals_temp`, `vitals_weight`, `consultation_fee`, `created_by`, `created_at`) VALUES ('3', '3', '2026-09-08', '2', 'Seasonal fever, sore throat and dry cough.', '125/85', '101.2 F', '74 kg', '300.00', '1', '2026-09-10 04:29:40');
INSERT INTO `patient_visits` (`id`, `patient_id`, `visit_date`, `doctor_id`, `symptoms_notes`, `vitals_bp`, `vitals_temp`, `vitals_weight`, `consultation_fee`, `created_by`, `created_at`) VALUES ('4', '4', '2026-09-10', '1', 'First trimester pregnancy booking', NULL, NULL, NULL, '0.00', '1', '2026-09-10 04:34:13');

-- Table structure for `attendance`
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_emp_date` (`employee_id`,`attendance_date`),
  KEY `recorded_by` (`recorded_by`),
  KEY `idx_attendance_date` (`attendance_date`),
  KEY `idx_attendance_employee` (`employee_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `attendance`
INSERT INTO `attendance` (`id`, `employee_id`, `attendance_date`, `time_in`, `time_out`, `remarks`, `recorded_by`, `created_at`, `updated_at`) VALUES ('1', '1', '2026-09-10', '08:00:00', '16:00:00', 'Present on Reception duty', '1', '2026-09-10 04:29:40', '2026-09-10 04:34:19');
INSERT INTO `attendance` (`id`, `employee_id`, `attendance_date`, `time_in`, `time_out`, `remarks`, `recorded_by`, `created_at`, `updated_at`) VALUES ('2', '2', '2026-09-10', '08:30:00', '17:00:00', 'Present in Pharmacy', '1', '2026-09-10 04:29:40', '2026-09-10 04:29:40');
INSERT INTO `attendance` (`id`, `employee_id`, `attendance_date`, `time_in`, `time_out`, `remarks`, `recorded_by`, `created_at`, `updated_at`) VALUES ('3', '3', '2026-09-10', '08:15:00', '16:30:00', 'Present in Maternity OPD', '1', '2026-09-10 04:29:40', '2026-09-10 04:29:40');
INSERT INTO `attendance` (`id`, `employee_id`, `attendance_date`, `time_in`, `time_out`, `remarks`, `recorded_by`, `created_at`, `updated_at`) VALUES ('4', '4', '2026-09-10', '08:00:00', '16:00:00', 'Present in Lab', '1', '2026-09-10 04:29:40', '2026-09-10 04:29:40');
INSERT INTO `attendance` (`id`, `employee_id`, `attendance_date`, `time_in`, `time_out`, `remarks`, `recorded_by`, `created_at`, `updated_at`) VALUES ('5', '5', '2026-09-10', '08:00:00', '16:00:00', 'Present in Labor Ward', '1', '2026-09-10 04:29:40', '2026-09-10 04:29:40');

-- Table structure for `medicines`
DROP TABLE IF EXISTS `medicines`;
CREATE TABLE `medicines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medicine_name` varchar(150) NOT NULL,
  `generic_name` varchar(150) DEFAULT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'Tablets',
  `min_stock_alert` int(11) NOT NULL DEFAULT 15,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_medicines_name` (`medicine_name`),
  KEY `idx_medicines_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `medicines`
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('1', 'Augmentin 625mg', 'Amoxicillin + Clavulanic Acid', 'Tablets', '15', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('2', 'Panadol 500mg', 'Paracetamol', 'Tablets', '20', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('3', 'Cefix 400mg', 'Cefixime', 'Capsules', '10', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('4', 'Gravibinan Injection', 'Hydroxyprogesterone Caproate', 'Vial', '10', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('5', 'Folic Acid 5mg', 'Pteroylglutamic Acid', 'Tablets', '25', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('6', 'Iberet Folic 500', 'Iron + Vitamin C + Folic Acid', 'Tablets', '15', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('7', 'Syntocinon 5 IU', 'Oxytocin', 'Ampoule', '12', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('8', 'Flagyl 400mg', 'Metronidazole', 'Tablets', '20', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('9', 'Ponstan 500mg', 'Mefenamic Acid', 'Tablets', '20', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`, `created_at`, `updated_at`) VALUES ('10', 'Calamox Suspension 156.25mg', 'Amoxicillin + Clavulanate Syrup', 'Bottle', '10', 'active', '2026-09-10 04:29:08', '2026-09-10 04:29:08');

-- Table structure for `medicine_batches`
DROP TABLE IF EXISTS `medicine_batches`;
CREATE TABLE `medicine_batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medicine_id` int(11) NOT NULL,
  `batch_number` varchar(60) NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity_received` int(11) NOT NULL DEFAULT 0,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `supplier_name` varchar(100) DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_batches_medicine` (`medicine_id`),
  KEY `idx_batches_expiry` (`expiry_date`),
  KEY `idx_batches_stock` (`current_stock`),
  CONSTRAINT `medicine_batches_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `medicine_batches`
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('1', '1', 'AUG-2026-A', '2027-08-30', '100', '79', '45.00', '60.00', 'Allied Pharma Distributors', '2026-08-01', 'Initial stock purchase', '2026-09-10 04:29:08', '2026-09-10 04:34:25');
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('2', '2', 'PAN-994', '2028-01-15', '500', '420', '2.50', '4.00', 'Lahore Central Medical Store', '2026-08-05', 'Bulk stock', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('3', '3', 'CFX-101', '2027-11-20', '80', '65', '38.00', '52.00', 'BioCare Distribution', '2026-08-10', 'Quality verified', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('4', '4', 'GRV-08', '2027-06-18', '40', '32', '180.00', '240.00', 'Maternity Care Suppliers', '2026-08-12', 'Maternity emergency stock', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('5', '5', 'FLC-55', '2028-05-30', '300', '250', '1.20', '2.50', 'Allied Pharma Distributors', '2026-08-15', 'Antenatal care pack', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('6', '6', 'IBR-88', '2027-10-10', '120', '95', '22.00', '32.00', 'Allied Pharma Distributors', '2026-08-15', 'Iron supplement', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('7', '7', 'SYN-04', '2027-04-25', '50', '38', '65.00', '90.00', 'Labor Room Supply Co.', '2026-08-20', 'Labor room vital item', '2026-09-10 04:29:08', '2026-09-10 04:29:08');
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`, `created_at`, `updated_at`) VALUES ('8', '1', 'AUG-SEP-99', '2028-06-30', '50', '50', '48.00', '65.00', 'Allied Pharma', '2026-09-10', '', '2026-09-10 04:34:23', '2026-09-10 04:34:23');

-- Table structure for `purchases`
DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(60) NOT NULL,
  `purchase_date` date NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remarks` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `idx_purchases_date` (`purchase_date`),
  CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `purchases`
INSERT INTO `purchases` (`id`, `invoice_number`, `purchase_date`, `supplier_name`, `total_amount`, `remarks`, `created_by`, `created_at`) VALUES ('1', 'PUR-2026-9901', '2026-09-10', 'Allied Pharma', '2400.00', '', '1', '2026-09-10 04:34:23');

-- Table structure for `purchase_items`
DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE `purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `batch_number` varchar(60) NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `medicine_id` (`medicine_id`),
  KEY `batch_id` (`batch_id`),
  CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_ibfk_3` FOREIGN KEY (`batch_id`) REFERENCES `medicine_batches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `purchase_items`
INSERT INTO `purchase_items` (`id`, `purchase_id`, `medicine_id`, `batch_id`, `batch_number`, `expiry_date`, `quantity`, `purchase_price`, `selling_price`, `subtotal`) VALUES ('1', '1', '1', '8', 'AUG-SEP-99', '2028-06-30', '50', '48.00', '65.00', '2400.00');

-- Table structure for `pharmacy_sales`
DROP TABLE IF EXISTS `pharmacy_sales`;
CREATE TABLE `pharmacy_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(60) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `sale_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `pharmacist_id` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_no` (`invoice_no`),
  KEY `pharmacist_id` (`pharmacist_id`),
  KEY `idx_sales_patient` (`patient_id`),
  KEY `idx_sales_date` (`sale_date`),
  CONSTRAINT `pharmacy_sales_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pharmacy_sales_ibfk_2` FOREIGN KEY (`pharmacist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `pharmacy_sales`
INSERT INTO `pharmacy_sales` (`id`, `invoice_no`, `patient_id`, `sale_date`, `total_amount`, `pharmacist_id`, `remarks`, `created_at`) VALUES ('1', 'INV-20260901-001', '1', '2026-09-10', '420.00', '3', 'Antenatal prescription', '2026-09-10 04:29:40');
INSERT INTO `pharmacy_sales` (`id`, `invoice_no`, `patient_id`, `sale_date`, `total_amount`, `pharmacist_id`, `remarks`, `created_at`) VALUES ('2', 'INV-20260901-002', '2', '2026-09-10', '180.00', '3', 'OPD consultation medicine', '2026-09-10 04:29:40');
INSERT INTO `pharmacy_sales` (`id`, `invoice_no`, `patient_id`, `sale_date`, `total_amount`, `pharmacist_id`, `remarks`, `created_at`) VALUES ('3', 'INV-20260910-9CBF6', '4', '2026-09-10', '360.00', '1', 'Prescribed antenatal care', '2026-09-10 04:34:25');

-- Table structure for `pharmacy_sale_items`
DROP TABLE IF EXISTS `pharmacy_sale_items`;
CREATE TABLE `pharmacy_sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gross_profit` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `idx_sale_items_medicine` (`medicine_id`),
  KEY `idx_sale_items_batch` (`batch_id`),
  CONSTRAINT `pharmacy_sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `pharmacy_sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pharmacy_sale_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pharmacy_sale_items_ibfk_3` FOREIGN KEY (`batch_id`) REFERENCES `medicine_batches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `pharmacy_sale_items`
INSERT INTO `pharmacy_sale_items` (`id`, `sale_id`, `medicine_id`, `batch_id`, `quantity`, `unit_purchase_price`, `unit_selling_price`, `subtotal`, `gross_profit`) VALUES ('1', '1', '1', '1', '5', '45.00', '60.00', '300.00', '75.00');
INSERT INTO `pharmacy_sale_items` (`id`, `sale_id`, `medicine_id`, `batch_id`, `quantity`, `unit_purchase_price`, `unit_selling_price`, `subtotal`, `gross_profit`) VALUES ('2', '1', '5', '5', '30', '1.20', '2.50', '75.00', '39.00');
INSERT INTO `pharmacy_sale_items` (`id`, `sale_id`, `medicine_id`, `batch_id`, `quantity`, `unit_purchase_price`, `unit_selling_price`, `subtotal`, `gross_profit`) VALUES ('3', '1', '6', '6', '1', '22.00', '32.00', '32.00', '10.00');
INSERT INTO `pharmacy_sale_items` (`id`, `sale_id`, `medicine_id`, `batch_id`, `quantity`, `unit_purchase_price`, `unit_selling_price`, `subtotal`, `gross_profit`) VALUES ('4', '2', '2', '2', '20', '2.50', '4.00', '80.00', '30.00');
INSERT INTO `pharmacy_sale_items` (`id`, `sale_id`, `medicine_id`, `batch_id`, `quantity`, `unit_purchase_price`, `unit_selling_price`, `subtotal`, `gross_profit`) VALUES ('5', '2', '1', '1', '1', '45.00', '60.00', '60.00', '15.00');
INSERT INTO `pharmacy_sale_items` (`id`, `sale_id`, `medicine_id`, `batch_id`, `quantity`, `unit_purchase_price`, `unit_selling_price`, `subtotal`, `gross_profit`) VALUES ('6', '3', '1', '1', '6', '45.00', '60.00', '360.00', '90.00');

-- Table structure for `system_settings`
DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(60) NOT NULL,
  `setting_value` text NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for `system_settings`
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('1', 'hospital_name', 'YASMEEN MATERNITY AND MEDICAL CENTER', 'Official Hospital Title', '2026-09-10 04:29:08');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('2', 'hospital_ref', 'R-85647', 'Registration and Reference Number', '2026-09-10 04:29:08');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('3', 'hospital_address', 'Chishtia Colony, Raiwind Road Sundar, Lahore', 'Hospital Postal Address', '2026-09-10 04:29:08');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('4', 'hospital_contact', '03074246239', 'Hospital Official Contact Number', '2026-09-10 04:29:08');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('5', 'developer_branding', 'Developed_By_DCtechsolutions', 'Developer Attribution', '2026-09-10 04:29:08');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('6', 'low_stock_threshold', '15', 'Default minimum stock alert threshold', '2026-09-10 04:29:08');

-- Table structure for `audit_logs`
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `record_id` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_audit_module` (`module`),
  KEY `idx_audit_created` (`created_at`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
