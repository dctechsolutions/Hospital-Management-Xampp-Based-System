<?php
/**
 * Medicine Purchase Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Batch.php';

class Purchase {
    public static function create(array $data, array $items): bool {
        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            $totalAmount = 0;
            foreach ($items as $item) {
                $totalAmount += ((float)$item['purchase_price'] * (int)$item['quantity']);
            }

            $invoiceNo = !empty($data['invoice_number']) ? trim($data['invoice_number']) : ('PUR-' . date('Ymd') . '-' . rand(100, 999));
            $purchaseDate = $data['purchase_date'] ?? date('Y-m-d');
            $supplier = trim($data['supplier_name'] ?? 'General Supplier');

            $stmt = $db->prepare("
                INSERT INTO purchases (invoice_number, purchase_date, supplier_name, total_amount, remarks, created_by)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $invoiceNo,
                $purchaseDate,
                $supplier,
                $totalAmount,
                trim($data['remarks'] ?? ''),
                $data['created_by'] ?? null
            ]);
            $purchaseId = (int)$db->lastInsertId();

            // Insert each purchase item and batch
            foreach ($items as $item) {
                $batchId = Batch::create([
                    'medicine_id' => (int)$item['medicine_id'],
                    'batch_number' => trim($item['batch_number']),
                    'expiry_date' => $item['expiry_date'],
                    'quantity' => (int)$item['quantity'],
                    'purchase_price' => (float)$item['purchase_price'],
                    'selling_price' => (float)$item['selling_price'],
                    'supplier_name' => $supplier,
                    'purchase_date' => $purchaseDate,
                    'remarks' => $item['remarks'] ?? ''
                ]);

                $subtotal = (float)$item['purchase_price'] * (int)$item['quantity'];

                $itemStmt = $db->prepare("
                    INSERT INTO purchase_items 
                    (purchase_id, medicine_id, batch_id, batch_number, expiry_date, quantity, purchase_price, selling_price, subtotal)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $itemStmt->execute([
                    $purchaseId,
                    (int)$item['medicine_id'],
                    $batchId,
                    trim($item['batch_number']),
                    $item['expiry_date'],
                    (int)$item['quantity'],
                    (float)$item['purchase_price'],
                    (float)$item['selling_price'],
                    $subtotal
                ]);
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Purchase Error: " . $e->getMessage(), 3, __DIR__ . '/../../storage/logs/app.log');
            return false;
        }
    }

    public static function getAll(): array {
        $db = Database::getConnection();
        $sql = "
            SELECT p.*, u.full_name as created_by_name, COUNT(pi.id) as total_items
            FROM purchases p
            LEFT JOIN users u ON p.created_by = u.id
            LEFT JOIN purchase_items pi ON p.id = pi.purchase_id
            GROUP BY p.id
            ORDER BY p.purchase_date DESC, p.id DESC
        ";
        return $db->query($sql)->fetchAll();
    }
}
