<?php
/**
 * Medicine Master Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';

class Medicine {
    public static function getAll(string $search = '', bool $activeOnly = false): array {
        $db = Database::getConnection();
        $conditions = [];
        $params = [];

        if ($activeOnly) {
            $conditions[] = "m.status = 'active'";
        }

        if (!empty($search)) {
            $conditions[] = "(m.medicine_name LIKE ? OR m.generic_name LIKE ?)";
            $term = '%' . $search . '%';
            $params[] = $term;
            $params[] = $term;
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "
            SELECT 
                m.*,
                COALESCE(SUM(CASE WHEN b.expiry_date >= CURDATE() THEN b.current_stock ELSE 0 END), 0) as total_active_stock,
                COALESCE(SUM(CASE WHEN b.expiry_date < CURDATE() THEN b.current_stock ELSE 0 END), 0) as total_expired_stock,
                COALESCE(SUM(b.current_stock), 0) as total_stock
            FROM medicines m
            LEFT JOIN medicine_batches b ON m.id = b.medicine_id
            {$whereClause}
            GROUP BY m.id
            ORDER BY m.medicine_name ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT 
                m.*,
                COALESCE(SUM(CASE WHEN b.expiry_date >= CURDATE() THEN b.current_stock ELSE 0 END), 0) as total_active_stock
            FROM medicines m
            LEFT JOIN medicine_batches b ON m.id = b.medicine_id
            WHERE m.id = ?
            GROUP BY m.id
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO medicines (medicine_name, generic_name, unit, min_stock_alert, status)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            trim($data['medicine_name']),
            trim($data['generic_name'] ?? ''),
            trim($data['unit'] ?? 'Tablets'),
            !empty($data['min_stock_alert']) ? (int)$data['min_stock_alert'] : 15,
            $data['status'] ?? 'active'
        ]);
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE medicines SET 
                medicine_name = ?, 
                generic_name = ?, 
                unit = ?, 
                min_stock_alert = ?, 
                status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            trim($data['medicine_name']),
            trim($data['generic_name'] ?? ''),
            trim($data['unit'] ?? 'Tablets'),
            (int)$data['min_stock_alert'],
            $data['status'] ?? 'active',
            $id
        ]);
    }

    public static function getStockSummary(): array {
        $db = Database::getConnection();
        $all = self::getAll();
        $lowStock = 0;
        $outOfStock = 0;
        $expiredCount = 0;

        foreach ($all as $med) {
            $stock = (int)$med['total_active_stock'];
            $minAlert = (int)$med['min_stock_alert'];
            if ($stock <= 0) {
                $outOfStock++;
            } elseif ($stock <= $minAlert) {
                $lowStock++;
            }
            if ((int)$med['total_expired_stock'] > 0) {
                $expiredCount++;
            }
        }

        return [
            'total_medicines' => count($all),
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'expired' => $expiredCount
        ];
    }
}
