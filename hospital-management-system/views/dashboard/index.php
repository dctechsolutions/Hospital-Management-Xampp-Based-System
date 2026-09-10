<?php
/**
 * Dashboard View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
$userRole = Auth::role();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">Hospital Dashboard</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">
                    Welcome back, <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo ucfirst($userRole); ?>)
                </p>
            </div>
            <div class="page-actions">
                <?php if ($userRole === 'administrator' || $userRole === 'receptionist'): ?>
                    <a href="<?php echo BASE_URL; ?>index.php?route=patient_create" class="btn btn-primary">+ Register New Patient</a>
                <?php endif; ?>
                <?php if ($userRole === 'pharmacist'): ?>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_issue" class="btn btn-primary">+ Dispense / Issue Medicine</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($userRole === 'receptionist'): ?>
            <!-- Receptionist Dashboard: Clean, Action-Focused -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Today's Registered Patients</div>
                    <div style="font-size: 32px; font-weight: 800; color: #047857; margin-top: 4px;"><?php echo $todayPatients; ?></div>
                    <div style="margin-top: 12px;">
                        <a href="<?php echo BASE_URL; ?>index.php?route=patients" class="btn btn-secondary btn-sm">View Patient List</a>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Staff Attendance Recorded Today</div>
                    <div style="font-size: 32px; font-weight: 800; color: #0f766e; margin-top: 4px;"><?php echo $todayAttendance; ?></div>
                    <div style="margin-top: 12px;">
                        <a href="<?php echo BASE_URL; ?>index.php?route=attendance" class="btn btn-secondary btn-sm">Record Attendance</a>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Quick Actions</div>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">
                        <a href="<?php echo BASE_URL; ?>index.php?route=patient_create" class="btn btn-primary btn-sm">+ Register Patient</a>
                        <a href="<?php echo BASE_URL; ?>index.php?route=patients" class="btn btn-secondary btn-sm">Search Patient Records</a>
                    </div>
                </div>
            </div>

        <?php elseif ($userRole === 'pharmacist'): ?>
            <!-- Pharmacist Dashboard: Inventory & Dispensing -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Today's Transactions</div>
                    <div style="font-size: 28px; font-weight: 800; color: #047857; margin-top: 4px;"><?php echo $todayTransactions; ?></div>
                    <div style="font-size: 12px; color: #64748b;">Rs. <?php echo number_format($todaySales, 2); ?> total sales</div>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #b45309; font-weight: 600;">Low Stock Medicines</div>
                    <div style="font-size: 28px; font-weight: 800; color: #d97706; margin-top: 4px;"><?php echo $pharmacySummary['low_stock']; ?></div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=low" style="font-size: 12px; color: #d97706; font-weight: 600; text-decoration: none;">View low stock items &rarr;</a>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #b91c1c; font-weight: 600;">Out of Stock</div>
                    <div style="font-size: 28px; font-weight: 800; color: #dc2626; margin-top: 4px;"><?php echo $pharmacySummary['out_of_stock']; ?></div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=out" style="font-size: 12px; color: #dc2626; font-weight: 600; text-decoration: none;">View out of stock &rarr;</a>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Expired Medicines</div>
                    <div style="font-size: 28px; font-weight: 800; color: #475569; margin-top: 4px;"><?php echo $pharmacySummary['expired']; ?></div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock&filter=expired" style="font-size: 12px; color: #475569; font-weight: 600; text-decoration: none;">Inspect expired batches &rarr;</a>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">Pharmacy Actions</h3>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_issue" class="btn btn-primary">Dispense / Issue Medicine</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_purchase" class="btn btn-secondary">Record Medicine Purchase</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_patient_history" class="btn btn-secondary">Patient Medicine History</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_medicines" class="btn btn-secondary">Medicine Master List</a>
                </div>
            </div>

        <?php else: ?>
            <!-- Administrator Dashboard -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Today's Patients</div>
                    <div style="font-size: 28px; font-weight: 800; color: #047857; margin-top: 4px;"><?php echo $todayPatients; ?></div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=patients" style="font-size: 12px; color: #047857; font-weight: 600; text-decoration: none;">Manage Patients &rarr;</a>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Attendance Today</div>
                    <div style="font-size: 28px; font-weight: 800; color: #0f766e; margin-top: 4px;"><?php echo $todayAttendance; ?></div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=attendance" style="font-size: 12px; color: #0f766e; font-weight: 600; text-decoration: none;">View Attendance &rarr;</a>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Pharmacy Inventory</div>
                    <div style="font-size: 28px; font-weight: 800; color: #1e293b; margin-top: 4px;"><?php echo $pharmacySummary['total_medicines']; ?></div>
                    <span style="font-size: 12px; color: #64748b;"><?php echo $pharmacySummary['low_stock']; ?> low, <?php echo $pharmacySummary['out_of_stock']; ?> out</span>
                </div>

                <div class="card" style="margin-bottom: 0;">
                    <div style="font-size: 13px; color: #64748b; font-weight: 600;">Today's Pharmacy Sales</div>
                    <div style="font-size: 28px; font-weight: 800; color: #0284c7; margin-top: 4px;">Rs. <?php echo number_format($todaySales, 0); ?></div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_financial" style="font-size: 12px; color: #0284c7; font-weight: 600; text-decoration: none;">Financial Report &rarr;</a>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">Hospital Administrative Navigation</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <a href="<?php echo BASE_URL; ?>index.php?route=patients" class="btn btn-secondary" style="justify-content: flex-start;">Patient Registration & Visits</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=attendance" class="btn btn-secondary" style="justify-content: flex-start;">Employee Attendance</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary" style="justify-content: flex-start;">Pharmacy & Stock</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_financial" class="btn btn-secondary" style="justify-content: flex-start;">Financial & Profit Analysis</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=reports" class="btn btn-secondary" style="justify-content: flex-start;">A4 Printable Reports</a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=backup" class="btn btn-secondary" style="justify-content: flex-start;">Database Backup & Restore</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
