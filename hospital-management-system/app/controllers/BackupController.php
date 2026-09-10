<?php
/**
 * Database Backup and Restore Controller
 * Yasmeen Maternity and Medical Center
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Auth.php';

class BackupController {
    public static function exportDatabase(): void {
        Auth::requireRole('administrator');
        $db = Database::getConnection();

        $tables = [
            'roles', 'users', 'doctors_lhvs', 'employees', 'patients',
            'patient_visits', 'attendance', 'medicines', 'medicine_batches',
            'purchases', 'purchase_items', 'pharmacy_sales',
            'pharmacy_sale_items', 'system_settings', 'audit_logs'
        ];

        $filename = 'yasmeen_hms_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backupDir = __DIR__ . '/../../storage/backups/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        }
        $filepath = $backupDir . $filename;

        $output = "-- ====================================================================\n";
        $output .= "-- YASMEEN MATERNITY AND MEDICAL CENTER (R-85647)\n";
        $output .= "-- Automated SQL Database Backup\n";
        $output .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Developed_By_DCtechsolutions\n";
        $output .= "-- ====================================================================\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $checkTable = $db->query("SHOW TABLES LIKE '{$table}'")->fetch();
            if (!$checkTable) continue;

            $output .= "-- Table structure for `{$table}`\n";
            $createTable = $db->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_NUM);
            $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $output .= $createTable[1] . ";\n\n";

            $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $output .= "-- Dumping data for `{$table}`\n";
                foreach ($rows as $row) {
                    $cols = array_keys($row);
                    $escapedCols = array_map(fn($c) => "`$c`", $cols);
                    $values = array_map(function($v) use ($db) {
                        if ($v === null) return 'NULL';
                        return $db->quote($v);
                    }, array_values($row));

                    $output .= "INSERT INTO `{$table}` (" . implode(", ", $escapedCols) . ") VALUES (" . implode(", ", $values) . ");\n";
                }
                $output .= "\n";
            }
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

        file_put_contents($filepath, $output);

        // Send file for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($output));
        echo $output;
        exit;
    }

    public static function restoreDatabase(): void {
        Auth::requireRole('administrator');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['backup_file'])) {
            $file = $_FILES['backup_file'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Upload error occurred. Please try again.'];
                header('Location: ' . BASE_URL . 'index.php?route=backup');
                exit;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'sql') {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid file format. Only .sql files are allowed.'];
                header('Location: ' . BASE_URL . 'index.php?route=backup');
                exit;
            }

            $sql = file_get_contents($file['tmp_name']);
            if (empty($sql)) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Uploaded SQL file is empty.'];
                header('Location: ' . BASE_URL . 'index.php?route=backup');
                exit;
            }

            try {
                $db = Database::getConnection();
                $db->exec("SET FOREIGN_KEY_CHECKS=0;");
                $db->exec($sql);
                $db->exec("SET FOREIGN_KEY_CHECKS=1;");

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Database successfully restored from backup file.'];
            } catch (Exception $e) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Database restore failed: ' . $e->getMessage()];
            }

            header('Location: ' . BASE_URL . 'index.php?route=backup');
            exit;
        }
    }
}
