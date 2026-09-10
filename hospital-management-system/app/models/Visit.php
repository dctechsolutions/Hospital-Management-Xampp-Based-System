<?php
/**
 * Patient Visit Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class Visit {
    public static function getByPatientId(int $patientId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT v.*, d.name as doctor_name, u.full_name as created_by_name
            FROM patient_visits v
            LEFT JOIN doctors_lhvs d ON v.doctor_id = d.id
            LEFT JOIN users u ON v.created_by = u.id
            WHERE v.patient_id = ?
            ORDER BY v.visit_date DESC, v.id DESC
        ");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO patient_visits 
            (patient_id, visit_date, doctor_id, symptoms_notes, vitals_bp, vitals_temp, vitals_weight, consultation_fee, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            (int)$data['patient_id'],
            $data['visit_date'] ?? date('Y-m-d'),
            !empty($data['doctor_id']) ? (int)$data['doctor_id'] : null,
            $data['symptoms_notes'] ?? null,
            $data['vitals_bp'] ?? null,
            $data['vitals_temp'] ?? null,
            $data['vitals_weight'] ?? null,
            !empty($data['consultation_fee']) ? (float)$data['consultation_fee'] : 0.00,
            $data['created_by'] ?? null
        ]);
    }
}
