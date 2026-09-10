<?php
/**
 * Medicine Master Management View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Master - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">Medicine Master Records</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Manage standard medicine formulations, units, and reorder alerts</p>
            </div>
            <div class="page-actions">
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary">&larr; Back to Stock</a>
            </div>
        </div>

        <div class="card" style="margin-bottom: 24px;">
            <h3 class="card-title">+ Add New Medicine to Master</h3>
            <form action="<?php echo BASE_URL; ?>index.php?route=pharmacy_medicine_store" method="POST">
                <?php echo CSRF::field(); ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="med_name">Medicine Name (Brand) *</label>
                        <input type="text" id="med_name" name="medicine_name" class="form-control" placeholder="e.g. Augmentin 625mg" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="med_gen">Generic / Chemical Name</label>
                        <input type="text" id="med_gen" name="generic_name" class="form-control" placeholder="e.g. Amoxicillin + Clavulanic Acid">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="med_unit">Unit of Measure *</label>
                        <select id="med_unit" name="unit" class="form-control" required>
                            <option value="Tablets">Tablets</option>
                            <option value="Capsules">Capsules</option>
                            <option value="Syrup / Bottle">Syrup / Bottle</option>
                            <option value="Injection / Vial">Injection / Vial</option>
                            <option value="Ampoule">Ampoule</option>
                            <option value="Drops">Drops</option>
                            <option value="Strip">Strip</option>
                            <option value="Tube / Ointment">Tube / Ointment</option>
                            <option value="Sachet">Sachet</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="med_alert">Low Stock Alert Level</label>
                        <input type="number" id="med_alert" name="min_stock_alert" class="form-control" value="15" min="1">
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">Save to Medicine Master</button>
                </div>
            </form>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Registered Medicines (<?php echo count($medicines); ?>)</h3>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Generic Name</th>
                            <th>Unit</th>
                            <th>Active Stock</th>
                            <th>Alert Threshold</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicines as $m): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($m['medicine_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($m['generic_name'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($m['unit']); ?></td>
                                <td>
                                    <?php 
                                        $stock = (int)$m['total_active_stock'];
                                        $alert = (int)$m['min_stock_alert'];
                                        if ($stock <= 0) {
                                            echo '<span style="color: #dc2626; font-weight: bold;">0 (Out of stock)</span>';
                                        } elseif ($stock <= $alert) {
                                            echo '<span style="color: #d97706; font-weight: bold;">' . $stock . ' (Low)</span>';
                                        } else {
                                            echo '<span style="color: #16a34a; font-weight: bold;">' . $stock . '</span>';
                                        }
                                    ?>
                                </td>
                                <td><?php echo $m['min_stock_alert']; ?> units</td>
                                <td>
                                    <span class="badge badge-success"><?php echo ucfirst($m['status']); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
