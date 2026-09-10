<?php
/**
 * Doctor / LHV Master Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class Doctor {
    public static function getAll(bool $activeOnly = false): array {
        $db = Database::getConnection();
        $sql = "SELECT * FROM doctors_lhvs " . ($activeOnly ? "WHERE status = 'active' " : "") . "ORDER BY name ASC";
        return $db->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM doctors_lhvs WHERE id = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO doctors_lhvs (name, designation, contact, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['designation'] ?? 'Medical Officer',
            $data['contact'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE doctors_lhvs SET name = ?, designation = ?, contact = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['designation'],
            $data['contact'] ?? null,
            $data['status'] ?? 'active',
            $id
        ]);
    }
}
