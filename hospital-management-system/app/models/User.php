<?php
/**
 * User Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class User {
    public static function findByUsername(string $username): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, username, full_name, role, contact, status, last_login, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function getAll(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id, username, full_name, role, contact, status, last_login, created_at FROM users ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public static function create(array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO users (username, password_hash, full_name, role, contact, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['full_name'],
            $data['role'],
            $data['contact'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        if (!empty($data['password'])) {
            $stmt = $db->prepare("UPDATE users SET full_name = ?, role = ?, contact = ?, status = ?, password_hash = ? WHERE id = ?");
            return $stmt->execute([
                $data['full_name'],
                $data['role'],
                $data['contact'] ?? null,
                $data['status'] ?? 'active',
                password_hash($data['password'], PASSWORD_BCRYPT),
                $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE users SET full_name = ?, role = ?, contact = ?, status = ? WHERE id = ?");
            return $stmt->execute([
                $data['full_name'],
                $data['role'],
                $data['contact'] ?? null,
                $data['status'] ?? 'active',
                $id
            ]);
        }
    }

    public static function recordLogin(int $id): void {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }
}
