<?php
/**
 * Database Backup and Restore View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Backup & Restore - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">Database Backup & Recovery</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">
                    Ensure data safety with single-click SQL export and emergency database restoration
                </p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            <!-- Export Section -->
            <div class="card">
                <h3 class="card-title" style="color: #047857;">Export Database Backup</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 20px; line-height: 1.6;">
                    Generates a complete, standalone <code>.sql</code> dump of all hospital records, including patients, visits, employee attendance, medicine batches, purchases, and sales transactions.
                </p>

                <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 6px; padding: 12px; margin-bottom: 20px; font-size: 13px; color: #065f46;">
                    <strong>Tip:</strong> We recommend taking a routine backup at the end of each operational day or before any major system maintenance.
                </div>

                <a href="<?php echo BASE_URL; ?>index.php?route=backup_export" class="btn btn-primary" style="padding: 10px 24px;">
                    Download SQL Backup File (.sql)
                </a>
            </div>

            <!-- Restore Section -->
            <div class="card">
                <h3 class="card-title" style="color: #dc2626;">Restore from SQL Backup</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 16px; line-height: 1.6;">
                    Upload a previously exported <code>.sql</code> backup file to restore database tables and data.
                </p>

                <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; margin-bottom: 20px; font-size: 13px; color: #991b1b;">
                    <strong>Warning:</strong> Restoring will overwrite existing records with the data inside the backup file. Ensure you have backed up current data before proceeding.
                </div>

                <form action="<?php echo BASE_URL; ?>index.php?route=backup_restore" method="POST" enctype="multipart/form-data" onsubmit="return confirm('WARNING: Are you sure you want to restore the database? This action will overwrite existing records with the selected backup file.');">
                    <?php echo CSRF::field(); ?>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="backup_file">Select .sql Backup File</label>
                        <input type="file" id="backup_file" name="backup_file" accept=".sql" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-danger" style="padding: 10px 24px;">
                        Confirm & Restore Database
                    </button>
                </form>
            </div>
        </div>

        <div class="card" style="margin-top: 20px; background-color: #f8fafc;">
            <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Database Health & Architecture</h4>
            <div style="font-size: 13px; color: #64748b; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 12px;">
                <div>
                    <strong>Engine:</strong> MySQL / MariaDB (InnoDB)
                </div>
                <div>
                    <strong>Charset:</strong> utf8mb4 / Unicode
                </div>
                <div>
                    <strong>Database:</strong> yasmeen_hms
                </div>
                <div>
                    <strong>Branding:</strong> Developed_By_DCtechsolutions
                </div>
            </div>
        </div>
    </main>
</body>
</html>
