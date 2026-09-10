<?php
/**
 * Yasmeen Maternity and Medical Center
 * Registration / Reference: R-85647
 * Main Front Controller & Application Router
 * Developed_By_DCtechsolutions
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../storage/logs/app.log');

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/helpers/Auth.php';
require_once __DIR__ . '/../app/helpers/CSRF.php';
require_once __DIR__ . '/../app/helpers/UniqueIdGenerator.php';

require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Patient.php';
require_once __DIR__ . '/../app/models/Visit.php';
require_once __DIR__ . '/../app/models/Doctor.php';
require_once __DIR__ . '/../app/models/Employee.php';
require_once __DIR__ . '/../app/models/Attendance.php';
require_once __DIR__ . '/../app/models/Medicine.php';
require_once __DIR__ . '/../app/models/Batch.php';
require_once __DIR__ . '/../app/models/Purchase.php';
require_once __DIR__ . '/../app/models/PharmacySale.php';
require_once __DIR__ . '/../app/models/Setting.php';

Auth::init();

$route = $_GET['route'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// Flash message helper
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Authentication check
$publicRoutes = ['login', 'do_login'];
if (!in_array($route, $publicRoutes, true) && !Auth::check()) {
    header('Location: ' . BASE_URL . 'index.php?route=login');
    exit;
}

// -------------------------------------------------------------
// ROUTER
// -------------------------------------------------------------

switch ($route) {
    // ---------------- AUTHENTICATION ----------------
    case 'login':
        if (Auth::check()) {
            header('Location: ' . BASE_URL . 'index.php?route=dashboard');
            exit;
        }
        require __DIR__ . '/../views/auth/login.php';
        break;

    case 'do_login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = User::findByUsername($username);
            if ($user && password_verify($password, $user['password_hash'])) {
                Auth::login($user);
                User::recordLogin($user['id']);
                header('Location: ' . BASE_URL . 'index.php?route=dashboard');
                exit;
            } else {
                setFlash('danger', 'Invalid username or password.');
                header('Location: ' . BASE_URL . 'index.php?route=login');
                exit;
            }
        }
        break;

    case 'logout':
        Auth::logout();
        header('Location: ' . BASE_URL . 'index.php?route=login');
        exit;

    // ---------------- DASHBOARD ----------------
    case 'dashboard':
        $user = Auth::user();
        $todayPatients = Patient::getTodayCount();
        $todayAttendance = Attendance::getTodayCount();
        $pharmacySummary = Medicine::getStockSummary();
        $todaySales = PharmacySale::getTodaySalesTotal();
        $todayTransactions = PharmacySale::getTodayTransactionsCount();
        require __DIR__ . '/../views/dashboard/index.php';
        break;

    // ---------------- PATIENT REGISTRATION ----------------
    case 'patients':
        Auth::requireRole(['administrator', 'receptionist']);
        $search = trim($_GET['search'] ?? '');
        $patients = Patient::getAll($search);
        require __DIR__ . '/../views/patients/index.php';
        break;

    case 'patient_create':
        Auth::requireRole(['administrator', 'receptionist']);
        $doctors = Doctor::getAll(true);
        $nextId = UniqueIdGenerator::nextPatientId();
        require __DIR__ . '/../views/patients/create.php';
        break;

    case 'patient_store':
        Auth::requireRole(['administrator', 'receptionist']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['name']) || empty($_POST['relation_name']) || empty($_POST['age']) || empty($_POST['sex'])) {
                setFlash('danger', 'Please fill in all required patient fields.');
                header('Location: ' . BASE_URL . 'index.php?route=patient_create');
                exit;
            }

            $data = [
                'registration_date' => $_POST['registration_date'] ?? date('Y-m-d'),
                'name' => $_POST['name'],
                'relation_type' => $_POST['relation_type'],
                'relation_name' => $_POST['relation_name'],
                'age' => $_POST['age'],
                'sex' => $_POST['sex'],
                'contact' => $_POST['contact'] ?? '',
                'address' => $_POST['address'] ?? '',
                'doctor_id' => $_POST['doctor_id'] ?? null,
                'symptoms_notes' => $_POST['symptoms_notes'] ?? 'Initial Consultation',
                'created_by' => Auth::id()
            ];

            $uniqueId = Patient::create($data);
            setFlash('success', "Patient record saved successfully with ID: {$uniqueId}");
            header('Location: ' . BASE_URL . 'index.php?route=patients');
            exit;
        }
        break;

    case 'patient_view':
        Auth::requireRole(['administrator', 'receptionist', 'pharmacist']);
        $id = (int)($_GET['id'] ?? 0);
        $patient = Patient::findById($id);
        if (!$patient) {
            setFlash('danger', 'Patient not found.');
            header('Location: ' . BASE_URL . 'index.php?route=patients');
            exit;
        }
        $visits = Visit::getByPatientId($id);
        $medicineHistory = PharmacySale::getPatientMedicineHistory($id);
        $medicineSummary = PharmacySale::getPatientMedicineSummary($id);
        $doctors = Doctor::getAll(true);
        require __DIR__ . '/../views/patients/view.php';
        break;

    case 'patient_visit_store':
        Auth::requireRole(['administrator', 'receptionist']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = (int)$_POST['patient_id'];
            Visit::create([
                'patient_id' => $patientId,
                'visit_date' => $_POST['visit_date'] ?? date('Y-m-d'),
                'doctor_id' => $_POST['doctor_id'] ?? null,
                'symptoms_notes' => $_POST['symptoms_notes'] ?? null,
                'vitals_bp' => $_POST['vitals_bp'] ?? null,
                'vitals_temp' => $_POST['vitals_temp'] ?? null,
                'vitals_weight' => $_POST['vitals_weight'] ?? null,
                'consultation_fee' => $_POST['consultation_fee'] ?? 0,
                'created_by' => Auth::id()
            ]);
            setFlash('success', 'Visit recorded successfully.');
            header('Location: ' . BASE_URL . 'index.php?route=patient_view&id=' . $patientId);
            exit;
        }
        break;

    // ---------------- EMPLOYEE ATTENDANCE ----------------
    case 'attendance':
        Auth::requireRole(['administrator', 'receptionist']);
        $year = (int)($_GET['year'] ?? date('Y'));
        $month = (int)($_GET['month'] ?? date('m'));
        $day = (int)($_GET['day'] ?? date('d'));

        $records = Attendance::getFiltered($year, $month, $day);
        $employees = Employee::getAll(true);
        require __DIR__ . '/../views/attendance/index.php';
        break;

    case 'attendance_store':
        Auth::requireRole(['administrator', 'receptionist']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Attendance::record([
                'employee_id' => $_POST['employee_id'],
                'attendance_date' => $_POST['attendance_date'] ?? date('Y-m-d'),
                'time_in' => $_POST['time_in'] ?? null,
                'time_out' => $_POST['time_out'] ?? null,
                'remarks' => $_POST['remarks'] ?? '',
                'recorded_by' => Auth::id()
            ]);
            setFlash('success', 'Attendance record saved successfully.');
            header('Location: ' . BASE_URL . 'index.php?route=attendance&year=' . date('Y', strtotime($_POST['attendance_date'])) . '&month=' . date('m', strtotime($_POST['attendance_date'])) . '&day=' . date('d', strtotime($_POST['attendance_date'])));
            exit;
        }
        break;

    case 'attendance_report':
        Auth::requireRole(['administrator', 'receptionist']);
        $year = (int)($_GET['year'] ?? date('Y'));
        $month = (int)($_GET['month'] ?? date('m'));
        $day = (int)($_GET['day'] ?? 0);
        $records = Attendance::getFiltered($year, $month, $day);
        require __DIR__ . '/../views/reports/attendance_report.php';
        break;

    // ---------------- PHARMACY MANAGEMENT ----------------
    case 'pharmacy_medicines':
        Auth::requireRole(['administrator', 'pharmacist']);
        $medicines = Medicine::getAll();
        require __DIR__ . '/../views/pharmacy/medicines.php';
        break;

    case 'pharmacy_medicine_store':
        Auth::requireRole(['administrator', 'pharmacist']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Medicine::create([
                'medicine_name' => $_POST['medicine_name'],
                'generic_name' => $_POST['generic_name'] ?? '',
                'unit' => $_POST['unit'] ?? 'Tablets',
                'min_stock_alert' => $_POST['min_stock_alert'] ?? 15,
                'status' => 'active'
            ]);
            setFlash('success', 'Medicine added to master successfully.');
            header('Location: ' . BASE_URL . 'index.php?route=pharmacy_medicines');
            exit;
        }
        break;

    case 'pharmacy_stock':
        Auth::requireRole(['administrator', 'pharmacist']);
        $filter = $_GET['filter'] ?? 'all';
        $search = $_GET['search'] ?? '';
        $batches = Batch::getAll($search, $filter);
        $summary = Medicine::getStockSummary();
        require __DIR__ . '/../views/pharmacy/stock.php';
        break;

    case 'pharmacy_purchase':
        Auth::requireRole(['administrator', 'pharmacist']);
        $medicines = Medicine::getAll('', true);
        require __DIR__ . '/../views/pharmacy/purchase_create.php';
        break;

    case 'pharmacy_purchase_store':
        Auth::requireRole(['administrator', 'pharmacist']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [];
            $medicineIds = $_POST['medicine_id'] ?? [];
            $batchNumbers = $_POST['batch_number'] ?? [];
            $expiryDates = $_POST['expiry_date'] ?? [];
            $quantities = $_POST['quantity'] ?? [];
            $purchasePrices = $_POST['purchase_price'] ?? [];
            $sellingPrices = $_POST['selling_price'] ?? [];

            for ($i = 0; $i < count($medicineIds); $i++) {
                if (!empty($medicineIds[$i]) && !empty($quantities[$i])) {
                    $items[] = [
                        'medicine_id' => $medicineIds[$i],
                        'batch_number' => $batchNumbers[$i] ?? 'BATCH-' . rand(100, 999),
                        'expiry_date' => $expiryDates[$i],
                        'quantity' => $quantities[$i],
                        'purchase_price' => $purchasePrices[$i],
                        'selling_price' => $sellingPrices[$i]
                    ];
                }
            }

            if (empty($items)) {
                setFlash('danger', 'Please enter at least one medicine item.');
                header('Location: ' . BASE_URL . 'index.php?route=pharmacy_purchase');
                exit;
            }

            $success = Purchase::create([
                'invoice_number' => $_POST['invoice_number'] ?? '',
                'purchase_date' => $_POST['purchase_date'] ?? date('Y-m-d'),
                'supplier_name' => $_POST['supplier_name'] ?? 'General Supplier',
                'remarks' => $_POST['remarks'] ?? '',
                'created_by' => Auth::id()
            ], $items);

            if ($success) {
                setFlash('success', 'Medicine purchase and stock entry recorded successfully.');
                header('Location: ' . BASE_URL . 'index.php?route=pharmacy_stock');
                exit;
            } else {
                setFlash('danger', 'Failed to save purchase entry.');
                header('Location: ' . BASE_URL . 'index.php?route=pharmacy_purchase');
                exit;
            }
        }
        break;

    case 'pharmacy_issue':
        Auth::requireRole(['administrator', 'pharmacist']);
        $patients = Patient::getAll('', 200);
        $medicines = Medicine::getAll('', true);
        require __DIR__ . '/../views/pharmacy/issue.php';
        break;

    case 'pharmacy_issue_store':
        Auth::requireRole(['administrator', 'pharmacist']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = !empty($_POST['patient_id']) ? (int)$_POST['patient_id'] : null;
            $items = [];
            $medicineIds = $_POST['medicine_id'] ?? [];
            $batchIds = $_POST['batch_id'] ?? [];
            $quantities = $_POST['quantity'] ?? [];
            $sellingPrices = $_POST['unit_selling_price'] ?? [];

            for ($i = 0; $i < count($medicineIds); $i++) {
                if (!empty($medicineIds[$i]) && !empty($batchIds[$i]) && !empty($quantities[$i])) {
                    $items[] = [
                        'medicine_id' => $medicineIds[$i],
                        'batch_id' => $batchIds[$i],
                        'quantity' => $quantities[$i],
                        'unit_selling_price' => $sellingPrices[$i] ?? 0
                    ];
                }
            }

            if (empty($items)) {
                setFlash('danger', 'Please add at least one medicine item to issue.');
                header('Location: ' . BASE_URL . 'index.php?route=pharmacy_issue');
                exit;
            }

            $result = PharmacySale::createSale([
                'patient_id' => $patientId,
                'sale_date' => $_POST['sale_date'] ?? date('Y-m-d'),
                'pharmacist_id' => Auth::id(),
                'remarks' => $_POST['remarks'] ?? ''
            ], $items);

            if ($result['success']) {
                setFlash('success', "Medicine issued successfully. Invoice: {$result['invoice_no']}");
                header('Location: ' . BASE_URL . 'index.php?route=pharmacy_stock');
                exit;
            } else {
                setFlash('danger', $result['error'] ?? 'Failed to issue medicine.');
                header('Location: ' . BASE_URL . 'index.php?route=pharmacy_issue');
                exit;
            }
        }
        break;

    case 'pharmacy_patient_history':
        Auth::requireRole(['administrator', 'pharmacist']);
        $search = trim($_GET['search'] ?? '');
        $patientId = !empty($_GET['patient_id']) ? (int)$_GET['patient_id'] : null;
        $selectedPatient = null;
        $history = [];
        $summary = [];

        if ($patientId) {
            $selectedPatient = Patient::findById($patientId);
            $history = PharmacySale::getPatientMedicineHistory($patientId);
            $summary = PharmacySale::getPatientMedicineSummary($patientId);
        } elseif (!empty($search)) {
            $patient = Patient::findByUniqueId($search);
            if (!$patient) {
                $candidates = Patient::getAll($search, 1);
                if (!empty($candidates)) {
                    $patient = $candidates[0];
                }
            }
            if ($patient) {
                $selectedPatient = $patient;
                $history = PharmacySale::getPatientMedicineHistory($patient['id']);
                $summary = PharmacySale::getPatientMedicineSummary($patient['id']);
            }
        }

        $allPatients = Patient::getAll('', 100);
        require __DIR__ . '/../views/pharmacy/patient_history.php';
        break;

    case 'pharmacy_financial':
        Auth::requireRole(['administrator']);
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $reportData = PharmacySale::getFinancialReport($startDate, $endDate);
        require __DIR__ . '/../views/pharmacy/financial.php';
        break;

    // ---------------- REPORTS CENTER ----------------
    case 'reports':
        Auth::requireRole(['administrator', 'receptionist', 'pharmacist']);
        require __DIR__ . '/../views/reports/index.php';
        break;

    // ---------------- SETTINGS & BACKUP ----------------
    case 'settings':
        Auth::requireRole(['administrator']);
        $settings = Setting::getAll();
        require __DIR__ . '/../views/settings/index.php';
        break;

    case 'backup':
        Auth::requireRole(['administrator']);
        require __DIR__ . '/../views/settings/backup.php';
        break;

    case 'backup_export':
        Auth::requireRole(['administrator']);
        require_once __DIR__ . '/../app/controllers/BackupController.php';
        BackupController::exportDatabase();
        exit;

    case 'backup_restore':
        Auth::requireRole(['administrator']);
        require_once __DIR__ . '/../app/controllers/BackupController.php';
        BackupController::restoreDatabase();
        exit;

    default:
        header('Location: ' . BASE_URL . 'index.php?route=dashboard');
        exit;
}
