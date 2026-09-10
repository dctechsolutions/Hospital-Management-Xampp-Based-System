<?php
/**
 * Record Medicine Purchase & Stock Entry
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Stock Entry - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">Stock Purchase Entry</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Record new medicine batch purchases with distinct cost and selling prices</p>
            </div>
            <div class="page-actions">
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary">&larr; Back to Stock</a>
            </div>
        </div>

        <div class="card">
            <form action="<?php echo BASE_URL; ?>index.php?route=pharmacy_purchase_store" method="POST" id="purchaseForm">
                <?php echo CSRF::field(); ?>

                <div class="form-grid" style="margin-bottom: 24px;">
                    <div class="form-group">
                        <label class="form-label" for="invoice_number">Supplier Invoice / Ref No.</label>
                        <input type="text" id="invoice_number" name="invoice_number" class="form-control" placeholder="e.g. INV-9042">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="purchase_date">Purchase Date *</label>
                        <input type="date" id="purchase_date" name="purchase_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="supplier_name">Supplier / Distributor Name *</label>
                        <input type="text" id="supplier_name" name="supplier_name" class="form-control" placeholder="e.g. Allied Pharma Distributors" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="remarks">General Remarks</label>
                        <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Optional purchase notes">
                    </div>
                </div>

                <h3 class="card-title" style="margin-top: 10px;">Purchased Medicine Batch Details</h3>

                <div id="itemsContainer">
                    <div class="purchase-item-row card" style="background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 16px; margin-bottom: 12px;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Medicine *</label>
                                <select name="medicine_id[]" class="form-control" required>
                                    <option value="">-- Select Medicine --</option>
                                    <?php foreach ($medicines as $m): ?>
                                        <option value="<?php echo $m['id']; ?>">
                                            <?php echo htmlspecialchars($m['medicine_name']); ?> (<?php echo htmlspecialchars($m['unit']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Batch Number *</label>
                                <input type="text" name="batch_number[]" class="form-control" placeholder="e.g. BATCH-771" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Expiry Date *</label>
                                <input type="date" name="expiry_date[]" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Quantity Received *</label>
                                <input type="number" name="quantity[]" class="form-control" placeholder="e.g. 100" min="1" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Purchase / Cost Price (Rs.) *</label>
                                <input type="number" step="0.01" name="purchase_price[]" class="form-control" placeholder="e.g. 40.00" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Selling Price (Rs.) *</label>
                                <input type="number" step="0.01" name="selling_price[]" class="form-control" placeholder="e.g. 50.00" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Save Stock Purchase</button>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
