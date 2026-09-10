<?php
/**
 * Pharmacy Sale / Medicine Issue Model
 * Developed_By_DCtechsolutions
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Batch.php';

class PharmacySale {
    public static function createSale(array $saleData, array $items): array {
        $db = Database::getConnection();
        try {
            $db->beginTransaction();

            // First validate stock and expiry for all items
            foreach ($items as $item) {
                $batch = Batch::findById((int)$item['batch_id']);
                if (!$batch) {
                    throw new Exception("Batch record not found for selected medicine.");
                }
                if ($batch['current_stock'] < (int)$item['quantity']) {
                    throw new Exception("Insufficient stock for {$batch['medicine_name']}. Available: {$batch['current_stock']}.");
                }
                if (strtotime($batch['expiry_date']) < strtotime(date('Y-m-d'))) {
                    throw new Exception("Cannot issue expired medicine: {$batch['medicine_name']}.");
                }
            }

            $totalAmount = 0;
            $saleItemsToInsert = [];

            foreach ($items as $item) {
                $batch = Batch::findById((int)$item['batch_id']);
                $qty = (int)$item['quantity'];
                $unitPurchase = (float)$batch['purchase_price'];
                $unitSelling = !empty($item['unit_selling_price']) ? (float)$item['unit_selling_price'] : (float)$batch['selling_price'];
                $subtotal = $unitSelling * $qty;
                $profit = ($unitSelling - $unitPurchase) * $qty;

                $totalAmount += $subtotal;

                $saleItemsToInsert[] = [
                    'medicine_id' => (int)$item['medicine_id'],
                    'batch_id' => (int)$item['batch_id'],
                    'quantity' => $qty,
                    'unit_purchase_price' => $unitPurchase,
                    'unit_selling_price' => $unitSelling,
                    'subtotal' => $subtotal,
                    'gross_profit' => $profit
                ];
            }

            $invoiceNo = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $saleDate = $saleData['sale_date'] ?? date('Y-m-d');
            $patientId = !empty($saleData['patient_id']) ? (int)$saleData['patient_id'] : null;

            $stmt = $db->prepare("
                INSERT INTO pharmacy_sales (invoice_no, patient_id, sale_date, total_amount, pharmacist_id, remarks)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $invoiceNo,
                $patientId,
                $saleDate,
                $totalAmount,
                $saleData['pharmacist_id'] ?? null,
                trim($saleData['remarks'] ?? '')
            ]);
            $saleId = (int)$db->lastInsertId();

            $itemStmt = $db->prepare("
                INSERT INTO pharmacy_sale_items 
                (sale_id, medicine_id, batch_id, quantity, unit_purchase_price, unit_selling_price, subtotal, gross_profit)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($saleItemsToInsert as $si) {
                // Deduct stock
                $success = Batch::reduceStock($si['batch_id'], $si['quantity']);
                if (!$success) {
                    throw new Exception("Stock deduction failed. Please verify stock availability.");
                }

                $itemStmt->execute([
                    $saleId,
                    $si['medicine_id'],
                    $si['batch_id'],
                    $si['quantity'],
                    $si['unit_purchase_price'],
                    $si['unit_selling_price'],
                    $si['subtotal'],
                    $si['gross_profit']
                ]);
            }

            $db->commit();
            return ['success' => true, 'sale_id' => $saleId, 'invoice_no' => $invoiceNo];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Patient Medicine History Query
     * Returns all historical medicine issues for a specific patient.
     * Answers: "For this patient, how many times was a particular medicine given?"
     */
    public static function getPatientMedicineHistory(?int $patientId, ?int $medicineId = null, ?string $startDate = null, ?string $endDate = null): array {
        $db = Database::getConnection();
        $conditions = [];
        $params = [];

        if ($patientId) {
            $conditions[] = "s.patient_id = ?";
            $params[] = $patientId;
        }

        if ($medicineId) {
            $conditions[] = "psi.medicine_id = ?";
            $params[] = $medicineId;
        }

        if (!empty($startDate)) {
            $conditions[] = "s.sale_date >= ?";
            $params[] = $startDate;
        }

        if (!empty($endDate)) {
            $conditions[] = "s.sale_date <= ?";
            $params[] = $endDate;
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "
            SELECT 
                s.id as sale_id,
                s.invoice_no,
                s.sale_date,
                p.id as patient_id,
                p.unique_id as patient_unique_id,
                p.name as patient_name,
                p.contact as patient_contact,
                m.id as medicine_id,
                m.medicine_name,
                m.unit,
                b.batch_number,
                psi.quantity,
                psi.unit_selling_price,
                psi.subtotal,
                u.full_name as pharmacist_name
            FROM pharmacy_sale_items psi
            INNER JOIN pharmacy_sales s ON psi.sale_id = s.id
            LEFT JOIN patients p ON s.patient_id = p.id
            INNER JOIN medicines m ON psi.medicine_id = m.id
            LEFT JOIN medicine_batches b ON psi.batch_id = b.id
            LEFT JOIN users u ON s.pharmacist_id = u.id
            {$whereClause}
            ORDER BY s.sale_date DESC, s.id DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count how many times each medicine has been issued to a patient
     */
    public static function getPatientMedicineSummary(int $patientId): array {
        $db = Database::getConnection();
        $sql = "
            SELECT 
                m.id as medicine_id,
                m.medicine_name,
                m.unit,
                COUNT(psi.id) as times_prescribed,
                SUM(psi.quantity) as total_units_given,
                MAX(s.sale_date) as last_issued_date
            FROM pharmacy_sale_items psi
            INNER JOIN pharmacy_sales s ON psi.sale_id = s.id
            INNER JOIN medicines m ON psi.medicine_id = m.id
            WHERE s.patient_id = ?
            GROUP BY m.id, m.medicine_name, m.unit
            ORDER BY times_prescribed DESC, last_issued_date DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$patientId]);
        return $stmt->fetchAll();
    }

    /**
     * Financial / Profit Report (Admin only)
     */
    public static function getFinancialReport(string $startDate = '', string $endDate = ''): array {
        $db = Database::getConnection();
        $conditions = [];
        $params = [];

        if (!empty($startDate)) {
            $conditions[] = "s.sale_date >= ?";
            $params[] = $startDate;
        }
        if (!empty($endDate)) {
            $conditions[] = "s.sale_date <= ?";
            $params[] = $endDate;
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        $sql = "
            SELECT 
                m.id as medicine_id,
                m.medicine_name,
                m.unit,
                SUM(psi.quantity) as total_quantity,
                SUM(psi.quantity) as quantity_sold,
                AVG(psi.unit_purchase_price) as avg_purchase_price,
                AVG(psi.unit_purchase_price) as cost_price,
                AVG(psi.unit_selling_price) as avg_selling_price,
                AVG(psi.unit_selling_price) as sale_price,
                SUM(psi.quantity * psi.unit_purchase_price) as total_purchase_cost,
                SUM(psi.quantity * psi.unit_purchase_price) as total_cost,
                SUM(psi.subtotal) as total_sales,
                SUM(psi.gross_profit) as total_gross_profit,
                SUM(psi.gross_profit) as gross_profit
            FROM pharmacy_sale_items psi
            INNER JOIN pharmacy_sales s ON psi.sale_id = s.id
            INNER JOIN medicines m ON psi.medicine_id = m.id
            {$whereClause}
            GROUP BY m.id, m.medicine_name, m.unit
            ORDER BY total_sales DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        $totalSales = 0;
        $totalCost = 0;
        $totalProfit = 0;
        foreach ($items as $it) {
            $totalSales += (float)($it['total_sales'] ?? 0);
            $totalCost += (float)($it['total_purchase_cost'] ?? 0);
            $totalProfit += (float)($it['total_gross_profit'] ?? 0);
        }

        return [
            'summary' => [
                'total_sales' => $totalSales,
                'total_cost' => $totalCost,
                'total_profit' => $totalProfit
            ],
            'items' => $items
        ];
    }

    public static function getTodaySalesTotal(): float {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COALESCE(SUM(total_amount), 0) FROM pharmacy_sales WHERE sale_date = CURDATE()");
        $stmt->execute();
        return (float)$stmt->fetchColumn();
    }

    public static function getTodayTransactionsCount(): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM pharmacy_sales WHERE sale_date = CURDATE()");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
