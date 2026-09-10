-- ====================================================================
-- YASMEEN MATERNITY AND MEDICAL CENTER
-- Initial Database Seeds
-- Developed_By_DCtechsolutions
-- Default password for all initial demo accounts: password123
-- Bcrypt Hash: $2y$10$4y9pBfQ2aP941zSjPsqtceJ5g7l3tB8b3B.gQo1yQ9oQfN24Fm1.q
-- ====================================================================

USE `yasmeen_hms`;

-- 1. Seed Roles
INSERT INTO `roles` (`id`, `role_name`, `description`) VALUES
(1, 'administrator', 'Complete system access, user management, financial reports, settings, and backups'),
(2, 'receptionist', 'Patient registration, search, visits, and employee attendance recording'),
(3, 'pharmacist', 'Pharmacy inventory, purchase entries, dispensing/sales, and patient medicine history');

-- 2. Seed Default Users (Password: password123)
INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `role`, `contact`, `status`) VALUES
(1, 'admin', '$2y$10$.OY7M0ijqTUWV5ttaJ2PAeSz7ycZn9lLhwjTleOJFUPSkoTumDpda', 'Dr. Yasmeen Administrator', 'administrator', '03074246239', 'active'),
(2, 'receptionist', '$2y$10$.OY7M0ijqTUWV5ttaJ2PAeSz7ycZn9lLhwjTleOJFUPSkoTumDpda', 'Fatima Reception Desk', 'receptionist', '03001234567', 'active'),
(3, 'pharmacist', '$2y$10$.OY7M0ijqTUWV5ttaJ2PAeSz7ycZn9lLhwjTleOJFUPSkoTumDpda', 'Tariq Pharmacist', 'pharmacist', '03219876543', 'active');

-- 3. Seed Doctors / LHVs Master Data
INSERT INTO `doctors_lhvs` (`id`, `name`, `designation`, `contact`, `status`) VALUES
(1, 'Dr. Yasmeen Akhtar', 'Consultant Gynecologist & Obstetrician', '03074246239', 'active'),
(2, 'Dr. Muhammad Bilal', 'Senior Medical Officer', '03005551234', 'active'),
(3, 'LHV Nasreen Bibi', 'Lady Health Visitor (LHV)', '03124445566', 'active'),
(4, 'Dr. Ayesha Siddiqa', 'Pediatrician / Child Specialist', '03337778899', 'active');

-- 4. Seed Employees Master Data
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `designation`, `contact`, `address`, `status`) VALUES
(1, 'EMP-001', 'Fatima Zahra', 'Senior Receptionist', '03001234567', 'Sundar, Raiwind Road, Lahore', 'active'),
(2, 'EMP-002', 'Tariq Mehmood', 'Head Pharmacist', '03219876543', 'Chishtia Colony, Lahore', 'active'),
(3, 'EMP-003', 'Nasreen Bibi', 'Lady Health Visitor', '03124445566', 'Raiwind Road, Lahore', 'active'),
(4, 'EMP-004', 'Sajjad Ali', 'Lab Assistant', '03456667788', 'Sundar Industrial Estate, Lahore', 'active'),
(5, 'EMP-005', 'Samina Kausar', 'Staff Nurse (Maternity)', '03029998877', 'Manga Mandi, Lahore', 'active');

-- 5. Seed System Settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`) VALUES
('hospital_name', 'YASMEEN MATERNITY AND MEDICAL CENTER', 'Official Hospital Title'),
('hospital_ref', 'R-85647', 'Registration and Reference Number'),
('hospital_address', 'Chishtia Colony, Raiwind Road Sundar, Lahore', 'Hospital Postal Address'),
('hospital_contact', '03074246239', 'Hospital Official Contact Number'),
('developer_branding', 'Developed_By_DCtechsolutions', 'Developer Attribution'),
('low_stock_threshold', '15', 'Default minimum stock alert threshold');

-- 6. Seed Sample Medicines Master
INSERT INTO `medicines` (`id`, `medicine_name`, `generic_name`, `unit`, `min_stock_alert`, `status`) VALUES
(1, 'Augmentin 625mg', 'Amoxicillin + Clavulanic Acid', 'Tablets', 15, 'active'),
(2, 'Panadol 500mg', 'Paracetamol', 'Tablets', 20, 'active'),
(3, 'Cefix 400mg', 'Cefixime', 'Capsules', 10, 'active'),
(4, 'Gravibinan Injection', 'Hydroxyprogesterone Caproate', 'Vial', 10, 'active'),
(5, 'Folic Acid 5mg', 'Pteroylglutamic Acid', 'Tablets', 25, 'active'),
(6, 'Iberet Folic 500', 'Iron + Vitamin C + Folic Acid', 'Tablets', 15, 'active'),
(7, 'Syntocinon 5 IU', 'Oxytocin', 'Ampoule', 12, 'active'),
(8, 'Flagyl 400mg', 'Metronidazole', 'Tablets', 20, 'active'),
(9, 'Ponstan 500mg', 'Mefenamic Acid', 'Tablets', 20, 'active'),
(10, 'Calamox Suspension 156.25mg', 'Amoxicillin + Clavulanate Syrup', 'Bottle', 10, 'active');

-- 7. Seed Initial Medicine Batches (Stock)
INSERT INTO `medicine_batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `quantity_received`, `current_stock`, `purchase_price`, `selling_price`, `supplier_name`, `purchase_date`, `remarks`) VALUES
(1, 1, 'AUG-2026-A', '2027-08-30', 100, 85, 45.00, 60.00, 'Allied Pharma Distributors', '2026-08-01', 'Initial stock purchase'),
(2, 2, 'PAN-994', '2028-01-15', 500, 420, 2.50, 4.00, 'Lahore Central Medical Store', '2026-08-05', 'Bulk stock'),
(3, 3, 'CFX-101', '2027-11-20', 80, 65, 38.00, 52.00, 'BioCare Distribution', '2026-08-10', 'Quality verified'),
(4, 4, 'GRV-08', '2027-06-18', 40, 32, 180.00, 240.00, 'Maternity Care Suppliers', '2026-08-12', 'Maternity emergency stock'),
(5, 5, 'FLC-55', '2028-05-30', 300, 250, 1.20, 2.50, 'Allied Pharma Distributors', '2026-08-15', 'Antenatal care pack'),
(6, 6, 'IBR-88', '2027-10-10', 120, 95, 22.00, 32.00, 'Allied Pharma Distributors', '2026-08-15', 'Iron supplement'),
(7, 7, 'SYN-04', '2027-04-25', 50, 38, 65.00, 90.00, 'Labor Room Supply Co.', '2026-08-20', 'Labor room vital item');

-- 8. Seed Initial Patients
INSERT INTO `patients` (`id`, `unique_id`, `registration_date`, `name`, `relation_type`, `relation_name`, `age`, `sex`, `contact`, `address`, `doctor_id`, `created_by`) VALUES
(1, 'P-2026-000001', '2026-09-09', 'Zainab Bibi', 'W/O', 'Muhammad Rashid', 26, 'Female', '03014455667', 'Chishtia Colony, Sundar', 1, 1),
(2, 'P-2026-000002', '2026-09-09', 'Maryam Kausar', 'D/O', 'Abdul Ghaffar', 19, 'Female', '03228899001', 'Raiwind Road, Lahore', 1, 1),
(3, 'P-2026-000003', '2026-09-07', 'Muhammad Usman', 'S/O', 'Tariq Mehmood', 34, 'Male', '03441122334', 'Sundar Estate', 2, 1);

-- 9. Seed Initial Patient Visits
INSERT INTO `patient_visits` (`patient_id`, `visit_date`, `doctor_id`, `symptoms_notes`, `vitals_bp`, `vitals_temp`, `vitals_weight`, `consultation_fee`, `created_by`) VALUES
(1, '2026-09-09', 1, 'Routine antenatal checkup (2nd trimester). Fetal heart sounds normal.', '115/75', '98.4 F', '64 kg', 500.00, 1),
(2, '2026-09-09', 1, 'Severe morning sickness and dehydration. Prescribed vitamins & rest.', '100/70', '98.6 F', '52 kg', 500.00, 1),
(3, '2026-09-07', 2, 'Seasonal fever, sore throat and dry cough.', '125/85', '101.2 F', '74 kg', 300.00, 1);

-- 10. Seed Initial Employee Attendance
INSERT INTO `attendance` (`employee_id`, `attendance_date`, `time_in`, `time_out`, `remarks`, `recorded_by`) VALUES
(1, '2026-09-09', '08:00:00', '16:00:00', 'Present on morning shift', 1),
(2, '2026-09-09', '08:30:00', '17:00:00', 'Present in Pharmacy', 1),
(3, '2026-09-09', '08:15:00', '16:30:00', 'Present in Maternity OPD', 1),
(4, '2026-09-09', '08:00:00', '16:00:00', 'Present in Lab', 1),
(5, '2026-09-09', '08:00:00', '16:00:00', 'Present in Labor Ward', 1);

-- 11. Seed Initial Pharmacy Sales
INSERT INTO `pharmacy_sales` (`id`, `invoice_no`, `patient_id`, `sale_date`, `total_amount`, `pharmacist_id`, `remarks`) VALUES
(1, 'INV-20260901-001', 1, '2026-09-09', 407.00, 3, 'Antenatal prescription'),
(2, 'INV-20260901-002', 2, '2026-09-09', 140.00, 3, 'OPD consultation medicine');

INSERT INTO `pharmacy_sale_items` (`sale_id`, `medicine_id`, `batch_id`, `quantity`, `unit_purchase_price`, `unit_selling_price`, `subtotal`, `gross_profit`) VALUES
(1, 1, 1, 5, 45.00, 60.00, 300.00, 75.00),
(1, 5, 5, 30, 1.20, 2.50, 75.00, 39.00),
(1, 6, 6, 1, 22.00, 32.00, 32.00, 10.00),
(2, 2, 2, 20, 2.50, 4.00, 80.00, 30.00),
(2, 1, 1, 1, 45.00, 60.00, 60.00, 15.00);
