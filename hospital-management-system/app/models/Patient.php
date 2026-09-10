<?php
/**
 * Patient Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/UniqueIdGenerator.php';

class Patient {
    public static function getAll(string $search = '', int $limit = 50, int $offset = 0): array {
        $db = Database::getConnection();
        if (!empty($search)) {
            $term = '%' . $search . '%';
            $stmt = $db->prepare("
                SELECT p.*, d.name as doctor_name 
                FROM patients p
                LEFT JOIN doctors_lhvs d ON p.doctor_id = d.id
                WHERE p.unique_id LIKE ? OR p.name LIKE ? OR p.contact LIKE ? OR p.relation_name LIKE ?
                ORDER BY p.id DESC LIMIT ? OFFSET ?
            ");
            $stmt->bindValue(1, $term, PDO::PARAM_STR);
            $stmt->bindValue(2, $term, PDO::PARAM_STR);
            $stmt->bindValue(3, $term, PDO::PARAM_STR);
            $stmt->bindValue(4, $term, PDO::PARAM_STR);
            $stmt->bindValue(5, $limit, PDO::PARAM_INT);
            $stmt->bindValue(6, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        $stmt = $db->prepare("
            SELECT p.*, d.name as doctor_name 
            FROM patients p
            LEFT JOIN doctors_lhvs d ON p.doctor_id = d.id
            ORDER BY p.id DESC LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countAll(string $search = ''): int {
        $db = Database::getConnection();
        if (!empty($search)) {
            $term = '%' . $search . '%';
            $stmt = $db->prepare("
                SELECT COUNT(*) FROM patients 
                WHERE unique_id LIKE ? OR name LIKE ? OR contact LIKE ? OR relation_name LIKE ?
            ");
            $stmt->execute([$term, $term, $term, $term]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.*, d.name as doctor_name 
            FROM patients p
            LEFT JOIN doctors_lhvs d ON p.doctor_id = d.id
            WHERE p.id = ? LIMIT 1
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function findByUniqueId(string $uniqueId): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.*, d.name as doctor_name 
            FROM patients p
            LEFT JOIN doctors_lhvs d ON p.doctor_id = d.id
            WHERE p.unique_id = ? LIMIT 1
        ");
        $stmt->execute([$uniqueId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): string {
        $db = Database::getConnection();
        $uniqueId = UniqueIdGenerator::nextPatientId();
        $regDate = !empty($data['registration_date']) ? $data['registration_date'] : date('Y-m-d');

        $stmt = $db->prepare("
            INSERT INTO patients 
            (unique_id, registration_date, name, relation_type, relation_name, age, sex, contact, address, doctor_id, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $uniqueId,
            $regDate,
            trim($data['name']),
            $data['relation_type'],
            trim($data['relation_name']),
            (int)$data['age'],
            $data['sex'],
            trim($data['contact'] ?? ''),
            trim($data['address'] ?? ''),
            !empty($data['doctor_id']) ? (int)$data['doctor_id'] : null,
            $data['created_by'] ?? null
        ]);

        $patientId = (int)$db->lastInsertId();

        // Automatically create the initial visit
        $visitStmt = $db->prepare("
            INSERT INTO patient_visits (patient_id, visit_date, doctor_id, symptoms_notes, created_by)
            VALUES (?, ?, ?, ?, ?)
        ");
        $visitStmt->execute([
            $patientId,
            $regDate,
            !empty($data['doctor_id']) ? (int)$data['doctor_id'] : null,
            $data['symptoms_notes'] ?? 'Initial Registration Visit',
            $data['created_by'] ?? null
        ]);

        return $uniqueId;
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE patients SET 
            registration_date = ?, name = ?, relation_type = ?, relation_name = ?, 
            age = ?, sex = ?, contact = ?, address = ?, doctor_id = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['registration_date'],
            trim($data['name']),
            $data['relation_type'],
            trim($data['relation_name']),
            (int)$data['age'],
            $data['sex'],
            trim($data['contact'] ?? ''),
            trim($data['address'] ?? ''),
            !empty($data['doctor_id']) ? (int)$data['doctor_id'] : null,
            $id
        ]);
    }

    public static function getTodayCount(): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM patients WHERE registration_date = CURDATE()");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
