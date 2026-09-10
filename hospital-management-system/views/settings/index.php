<?php
/**
 * System Settings & Hospital Profile View
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Settings & Profile - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="main-container">
    <div class="page-header no-print">
        <div>
            <h1 class="page-title">Hospital Profile & Settings</h1>
            <p style="color: #64748b; font-size: 13px; margin-top: 2px;">
                Configure official hospital credentials, contact info, low stock alerts, and system branding.
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?php echo BASE_URL; ?>index.php?route=backup" class="btn btn-secondary">
                Database Backup & Restore
            </a>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/alert.php'; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px;">
        <!-- Official Hospital Info Card -->
        <div class="card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <img src="<?php echo BASE_URL; ?>assets/images/yasmeen-logo.png" 
                     alt="Hospital Logo" 
                     style="width: 44px; height: 44px; object-fit: contain;"
                     onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/yasmeen-logo.svg';">
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #047857; margin: 0;">Official Institutional Profile</h3>
                    <span style="font-size: 12px; color: #64748b;">Yasmeen Maternity and Medical Center (R-85647)</span>
                </div>
            </div>

            <table class="data-table" style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 35%; font-weight: 600; color: #475569;">Hospital Name</td>
                        <td style="font-weight: 700; color: #0f172a;">
                            <?php echo htmlspecialchars($settings['hospital_name'] ?? 'YASMEEN MATERNITY AND MEDICAL CENTER'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: #475569;">Registration / Ref #</td>
                        <td>
                            <span class="badge" style="background: #ccfbf1; color: #0f766e; font-weight: 700; font-size: 12px;">
                                <?php echo htmlspecialchars($settings['hospital_ref'] ?? 'R-85647'); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: #475569;">Official Address</td>
                        <td>
                            <?php echo htmlspecialchars($settings['hospital_address'] ?? 'Chishtia Colony, Raiwind Road Sundar, Lahore'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: #475569;">Contact Number</td>
                        <td>
                            <strong><?php echo htmlspecialchars($settings['hospital_contact'] ?? '03074246239'); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: #475569;">Developer Attribution</td>
                        <td>
                            <span style="color: #047857; font-weight: 600;">
                                <?php echo htmlspecialchars($settings['developer_branding'] ?? 'Developed_By_DCtechsolutions'); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: #475569;">Low Stock Threshold</td>
                        <td>
                            <strong><?php echo htmlspecialchars($settings['low_stock_threshold'] ?? '15'); ?> Units</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- System User Accounts & Roles -->
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-top: 0; margin-bottom: 16px;">
                Authorized Staff Accounts
            </h3>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
                Role-based access accounts for administration, reception, and pharmacy dispensary.
            </p>

            <table class="data-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>admin</strong></td>
                        <td>Dr. Yasmeen Administrator</td>
                        <td><span class="badge badge-admin">Administrator</span></td>
                        <td><span class="badge badge-active">Active</span></td>
                    </tr>
                    <tr>
                        <td><strong>receptionist</strong></td>
                        <td>Fatima Reception Desk</td>
                        <td><span class="badge badge-reception">Receptionist</span></td>
                        <td><span class="badge badge-active">Active</span></td>
                    </tr>
                    <tr>
                        <td><strong>pharmacist</strong></td>
                        <td>Tariq Pharmacist</td>
                        <td><span class="badge badge-pharmacy">Pharmacist</span></td>
                        <td><span class="badge badge-active">Active</span></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; color: #64748b;">
                <strong>Security Notice:</strong> All hospital records are encrypted in MariaDB InnoDB database storage with automated SQL export capabilities.
            </div>
        </div>
    </div>
</div>
</body>
</html>
