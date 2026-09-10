<?php
/**
 * Dispense / Issue Medicine to Patient
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */

$allActiveBatches = Batch::getAll('', 'all');
$validBatches = array_filter($allActiveBatches, function($b) {
    return $b['current_stock'] > 0 && strtotime($b['expiry_date']) >= strtotime(date('Y-m-d'));
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispense Medicine - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">Dispense / Issue Medicine</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Issue medicines to registered patients with real-time stock deduction</p>
            </div>
            <div class="page-actions">
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary">&larr; Back to Stock</a>
            </div>
        </div>

        <div class="card">
            <form action="<?php echo BASE_URL; ?>index.php?route=pharmacy_issue_store" method="POST" id="issueForm">
                <?php echo CSRF::field(); ?>

                <div class="form-grid" style="margin-bottom: 24px;">
                    <!-- Patient Selection -->
                    <div class="form-group">
                        <label class="form-label" for="patient_id">Select Patient *</label>
                        <select id="patient_id" name="patient_id" class="form-control" required>
                            <option value="">-- Select Registered Patient --</option>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?php echo $p['id']; ?>">
                                    <?php echo htmlspecialchars($p['unique_id']); ?> - <?php echo htmlspecialchars($p['name']); ?> (<?php echo htmlspecialchars($p['relation_type'] . ' ' . $p['relation_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Dispensing Date -->
                    <div class="form-group">
                        <label class="form-label" for="sale_date">Issue Date *</label>
                        <input type="date" id="sale_date" name="sale_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <!-- Remarks / Prescription Note -->
                    <div class="form-group">
                        <label class="form-label" for="remarks">Prescription / Remarks</label>
                        <input type="text" id="remarks" name="remarks" class="form-control" placeholder="e.g. Antenatal prescription, OPD dosage">
                    </div>
                </div>

                <h3 class="card-title">Medicine Line Items</h3>
                <p style="font-size: 12px; color: #64748b; margin-top: -12px; margin-bottom: 16px;">
                    * Only non-expired batches with available positive stock are eligible for dispensing.
                </p>

                <div id="issueItemsContainer">
                    <div class="card" style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 16px; margin-bottom: 12px;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Available Medicine & Batch *</label>
                                <select name="batch_id[]" class="form-control" required onchange="handleBatchSelect(this)">
                                    <option value="">-- Choose Medicine & Batch --</option>
                                    <?php foreach ($validBatches as $vb): ?>
                                        <option value="<?php echo $vb['id']; ?>" 
                                                data-medicine-id="<?php echo $vb['medicine_id']; ?>"
                                                data-stock="<?php echo $vb['current_stock']; ?>"
                                                data-price="<?php echo $vb['selling_price']; ?>">
                                            <?php echo htmlspecialchars($vb['medicine_name']); ?> | Batch: <?php echo htmlspecialchars($vb['batch_number']); ?> | Stock: <?php echo $vb['current_stock']; ?> | Rs. <?php echo number_format($vb['selling_price'], 2); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="medicine_id[]" class="med-id-input">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Available Stock</label>
                                <input type="text" class="form-control available-stock-display" placeholder="Stock" readonly>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Issue Quantity *</label>
                                <input type="number" name="quantity[]" class="form-control issue-qty" min="1" placeholder="e.g. 10" required oninput="calculateSubtotal(this)">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Unit Selling Price (Rs.)</label>
                                <input type="number" step="0.01" name="unit_selling_price[]" class="form-control unit-price" placeholder="Price" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Confirm & Issue Medicine</button>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        function handleBatchSelect(select) {
            const row = select.closest('.card');
            const opt = select.options[select.selectedIndex];
            const stock = opt.getAttribute('data-stock') || '';
            const price = opt.getAttribute('data-price') || '';
            const medId = opt.getAttribute('data-medicine-id') || '';

            row.querySelector('.med-id-input').value = medId;
            row.querySelector('.available-stock-display').value = stock ? stock + ' units' : '';
            row.querySelector('.unit-price').value = price;
            
            const qtyInput = row.querySelector('.issue-qty');
            if (stock) {
                qtyInput.max = stock;
            }
        }

        function calculateSubtotal(qtyInput) {
            const row = qtyInput.closest('.card');
            const max = parseInt(qtyInput.max, 10);
            const val = parseInt(qtyInput.value, 10);
            if (max && val > max) {
                alert('Cannot issue more than available stock (' + max + ').');
                qtyInput.value = max;
            }
        }
    </script>
</body>
</html>
