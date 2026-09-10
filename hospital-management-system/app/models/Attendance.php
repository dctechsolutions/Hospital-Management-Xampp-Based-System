<?php
/**
 * Employee Attendance Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class Attendance {
    public static function getFiltered(int $year, int $month = 0, int $day = 0): array {
        $db = Database::getConnection();
        $conditions = ["YEAR(a.attendance_date) = ?"];
        $params = [$year];

        if ($month > 0) {
            $conditions[] = "MONTH(a.attendance_date) = ?";
            $params[] = $month;
        }

        if ($day > 0) {
            $conditions[] = "DAY(a.attendance_date) = ?";
            $params[] = $day;
        }

        $whereClause = implode(" AND ", $conditions);

        $sql = "
            SELECT 
                a.id as attendance_id,
                a.attendance_date,
                a.time_in,
                a.time_out,
                a.remarks,
                e.id as employee_id,
                e.employee_code,
                e.full_name as employee_name,
                e.designation
            FROM attendance a
            INNER JOIN employees e ON a.employee_id = e.id
            WHERE {$whereClause}
            ORDER BY a.attendance_date DESC, e.full_name ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function record(array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO attendance (employee_id, attendance_date, time_in, time_out, remarks, recorded_by)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                time_in = VALUES(time_in),
                time_out = VALUES(time_out),
                remarks = VALUES(remarks),
                recorded_by = VALUES(recorded_by)
        ");

        $timeIn = !empty($data['time_in']) ? $data['time_in'] : null;
        $timeOut = !empty($data['time_out']) ? $data['time_out'] : null;

        return $stmt->execute([
            (int)$data['employee_id'],
            $data['attendance_date'] ?? date('Y-m-d'),
            $timeIn,
            $timeOut,
            trim($data['remarks'] ?? ''),
            $data['recorded_by'] ?? null
        ]);
    }

    public static function getTodayCount(): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM attendance WHERE attendance_date = CURDATE()");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
