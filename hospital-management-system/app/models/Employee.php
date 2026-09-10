<?php
/**
 * Employee Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class Employee {
    public static function getAll(bool $activeOnly = false): array {
        $db = Database::getConnection();
        $sql = "SELECT * FROM employees " . ($activeOnly ? "WHERE status = 'active' " : "") . "ORDER BY full_name ASC";
        return $db->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO employees (employee_code, full_name, designation, contact, address, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['employee_code'],
            $data['full_name'],
            $data['designation'],
            $data['contact'] ?? null,
            $data['address'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE employees SET employee_code = ?, full_name = ?, designation = ?, contact = ?, address = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['employee_code'],
            $data['full_name'],
            $data['designation'],
            $data['contact'] ?? null,
            $data['address'] ?? null,
            $data['status'] ?? 'active',
            $id
        ]);
    }
}
