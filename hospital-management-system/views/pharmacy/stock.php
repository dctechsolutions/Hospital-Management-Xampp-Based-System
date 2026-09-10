<?php
/**
 * Pharmacy Stock Inventory View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */

$currentFilter = $_GET['filter'] ?? 'all';
$searchQuery = $_GET['search'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Stock - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/print.css" media="print">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header no-print">
            <div>
                <h1 class="page-title">Pharmacy Stock & Batch Inventory</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Real-time inventory levels, batches, and expiry management</p>
            </div>
            <div class="page-actions">
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_issue" class="btn btn-primary">+ Dispense / Issue Medicine</a>
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_purchase" class="btn btn-secondary">+ Purchase Stock</a>
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_patient_history" class="btn btn-secondary">Patient History</a>
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_medicines" class="btn btn-secondary">Medicine Master</a>
            </div>
        </div>

        <!-- Inventory Summary Badges -->
        <div class="no-print" style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
            <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=all" 
               class="btn <?php echo $currentFilter === 'all' ? 'btn-primary' : 'btn-secondary'; ?> btn-sm">
               All Batches (<?php echo count($batches); ?>)
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=low" 
               class="btn <?php echo $currentFilter === 'low' ? 'btn-primary' : 'btn-secondary'; ?> btn-sm">
               Low Stock (<?php echo $summary['low_stock']; ?>)
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=out" 
               class="btn <?php echo $currentFilter === 'out' ? 'btn-primary' : 'btn-secondary'; ?> btn-sm">
               Out of Stock (<?php echo $summary['out_of_stock']; ?>)
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=expired" 
               class="btn <?php echo $currentFilter === 'expired' ? 'btn-primary' : 'btn-secondary'; ?> btn-sm">
               Expired (<?php echo $summary['expired']; ?>)
            </a>
        </div>

        <!-- Search Bar -->
        <div class="card no-print" style="padding: 14px; margin-bottom: 16px;">
            <form action="<?php echo BASE_URL; ?>index.php" method="GET" style="display: flex; gap: 10px;">
                <input type="hidden" name="route" value="pharmacy_stock">
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($currentFilter); ?>">
                <input type="text" name="search" class="form-control" placeholder="Search by medicine name, batch number, or supplier..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if (!empty($searchQuery)): ?>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=<?php echo $currentFilter; ?>" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Stock Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="data-table report-table">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch No.</th>
                            <th>Expiry Date</th>
                            <th>Current Stock</th>
                            <?php if (Auth::hasRole('administrator')): ?>
                                <th>Cost Price</th>
                            <?php endif; ?>
                            <th>Selling Price</th>
                            <th>Supplier</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($batches)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 32px; color: #64748b;">
                                    No medicine batches found matching the selected filter.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($batches as $b): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($b['medicine_name']); ?></strong>
                                        <?php if (!empty($b['generic_name'])): ?>
                                            <span style="display: block; font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($b['generic_name']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><code><?php echo htmlspecialchars($b['batch_number']); ?></code></td>
                                    <td>
                                        <?php 
                                            $isExpired = strtotime($b['expiry_date']) < strtotime(date('Y-m-d'));
                                            echo $isExpired ? '<span style="color: #dc2626; font-weight: bold;">' . date('d-M-Y', strtotime($b['expiry_date'])) . ' (Expired)</span>' : date('d-M-Y', strtotime($b['expiry_date']));
                                        ?>
                                    </td>
                                    <td>
                                        <strong><?php echo $b['current_stock']; ?></strong> <?php echo htmlspecialchars($b['unit']); ?>
                                    </td>
                                    <?php if (Auth::hasRole('administrator')): ?>
                                        <td>Rs. <?php echo number_format((float)$b['purchase_price'], 2); ?></td>
                                    <?php endif; ?>
                                    <td><strong>Rs. <?php echo number_format((float)$b['selling_price'], 2); ?></strong></td>
                                    <td><?php echo htmlspecialchars($b['supplier_name'] ?: '-'); ?></td>
                                    <td>
                                        <?php if ($b['stock_status'] === 'Expired'): ?>
                                            <span class="badge badge-danger">Expired</span>
                                        <?php elseif ($b['stock_status'] === 'Out of Stock'): ?>
                                            <span class="badge badge-danger">Out of Stock</span>
                                        <?php elseif ($b['stock_status'] === 'Low Stock'): ?>
                                            <span class="badge badge-warning">Low Stock</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">In Stock</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
