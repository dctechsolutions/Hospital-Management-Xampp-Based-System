<?php
/**
 * Sequential Patient Unique ID Generator
 * Format: P-YYYY-000001
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class UniqueIdGenerator {
    public static function nextPatientId(): string {
        $db = Database::getConnection();
        $currentYear = date('Y');
        $prefix = "P-{$currentYear}-";

        // Query highest patient ID for this year
        $stmt = $db->prepare("SELECT unique_id FROM patients WHERE unique_id LIKE ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$prefix . '%']);
        $lastId = $stmt->fetchColumn();

        if ($lastId) {
            // Extract the sequence number
            $parts = explode('-', $lastId);
            $seq = isset($parts[2]) ? (int)$parts[2] : 0;
            $nextSeq = $seq + 1;
        } else {
            $nextSeq = 1;
        }

        return sprintf("P-%s-%06d", $currentYear, $nextSeq);
    }
}
