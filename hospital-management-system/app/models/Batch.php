<?php
/**
 * Medicine Batch / Stock Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class Batch {
    public static function getActiveBatchesByMedicine(int $medicineId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * FROM medicine_batches 
            WHERE medicine_id = ? AND current_stock > 0 AND expiry_date >= CURDATE()
            ORDER BY expiry_date ASC
        ");
        $stmt->execute([$medicineId]);
        return $stmt->fetchAll();
    }

    public static function getAll(string $search = '', string $filter = 'all'): array {
        $db = Database::getConnection();
        $conditions = [];
        $params = [];

        if (!empty($search)) {
            $conditions[] = "(m.medicine_name LIKE ? OR b.batch_number LIKE ? OR b.supplier_name LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        if ($filter === 'low') {
            $conditions[] = "b.current_stock > 0 AND b.current_stock <= m.min_stock_alert AND b.expiry_date >= CURDATE()";
        } elseif ($filter === 'out') {
            $conditions[] = "b.current_stock <= 0";
        } elseif ($filter === 'expired') {
            $conditions[] = "b.expiry_date < CURDATE()";
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "
            SELECT 
                b.*,
                m.medicine_name,
                m.generic_name,
                m.unit,
                m.min_stock_alert,
                CASE 
                    WHEN b.expiry_date < CURDATE() THEN 'Expired'
                    WHEN b.current_stock <= 0 THEN 'Out of Stock'
                    WHEN b.current_stock <= m.min_stock_alert THEN 'Low Stock'
                    ELSE 'In Stock'
                END as stock_status
            FROM medicine_batches b
            INNER JOIN medicines m ON b.medicine_id = m.id
            {$whereClause}
            ORDER BY b.id DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT b.*, m.medicine_name, m.unit
            FROM medicine_batches b
            INNER JOIN medicines m ON b.medicine_id = m.id
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO medicine_batches 
            (medicine_id, batch_number, expiry_date, quantity_received, current_stock, purchase_price, selling_price, supplier_name, purchase_date, remarks)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            (int)$data['medicine_id'],
            trim($data['batch_number']),
            $data['expiry_date'],
            (int)$data['quantity'],
            (int)$data['quantity'],
            (float)$data['purchase_price'],
            (float)$data['selling_price'],
            trim($data['supplier_name'] ?? ''),
            $data['purchase_date'] ?? date('Y-m-d'),
            trim($data['remarks'] ?? '')
        ]);
        return (int)$db->lastInsertId();
    }

    public static function reduceStock(int $batchId, int $quantity): bool {
        $db = Database::getConnection();
        // Prevent negative stock and expired batch
        $stmt = $db->prepare("
            UPDATE medicine_batches 
            SET current_stock = current_stock - ? 
            WHERE id = ? AND current_stock >= ? AND expiry_date >= CURDATE()
        ");
        $stmt->execute([$quantity, $batchId, $quantity]);
        return $stmt->rowCount() > 0;
    }
}
