<?php
/**
 * Reports Center View
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
    <title>Reports Center - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">A4 Printable Reports Center</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">
                    Standardized formal hospital documents with uniform header, footer, and developer watermark
                </p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
            <!-- Attendance Report -->
            <div class="card">
                <h3 class="card-title" style="color: #047857;">Staff Attendance Report</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                    A4 printable staff duty record with Day, Month, and Year filter headers, dynamic serial numbers, and signoff footers.
                </p>
                <div style="display: flex; gap: 8px;">
                    <a href="<?php echo BASE_URL; ?>index.php?route=attendance_report" target="_blank" class="btn btn-primary btn-sm">
                        Print Monthly Attendance &rarr;
                    </a>
                    <a href="<?php echo BASE_URL; ?>index.php?route=attendance" class="btn btn-secondary btn-sm">
                        Filter & View
                    </a>
                </div>
            </div>

            <!-- Patient Medicine History Report -->
            <div class="card">
                <h3 class="card-title" style="color: #0f766e;">Patient Medicine History Report</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                    Detailed medication tracking showing how many times a medicine was given to a specific patient, quantities, and dates.
                </p>
                <div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_patient_history" class="btn btn-primary btn-sm">
                        Select Patient & Print &rarr;
                    </a>
                </div>
            </div>

            <!-- Pharmacy Stock Report -->
            <div class="card">
                <h3 class="card-title" style="color: #1e293b;">Pharmacy Stock & Expiry Report</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                    Inventory audit showing batch numbers, expiration dates, stock levels, and replenishment status.
                </p>
                <div>
                    <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary btn-sm">
                        View Stock & Print &rarr;
                    </a>
                </div>
            </div>

            <?php if ($userRole === 'administrator'): ?>
                <!-- Pharmacy Financial Report -->
                <div class="card">
                    <h3 class="card-title" style="color: #0284c7;">Financial & Gross Profit Report</h3>
                    <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                        Comprehensive executive breakdown: Cost price vs selling price, purchase total, sales revenue, and gross profit margins.
                    </p>
                    <div>
                        <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_financial" class="btn btn-primary btn-sm">
                            Generate Financial Report &rarr;
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="card" style="margin-top: 10px; background-color: #f8fafc;">
            <h4 style="font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 8px;">Standard Report Design Specifications</h4>
            <ul style="padding-left: 20px; font-size: 13px; color: #64748b; line-height: 1.6;">
                <li><strong>Common Header:</strong> Yasmeen Maternity and Medical Center, Registration R-85647, Hospital Logo, and Report Meta bar.</li>
                <li><strong>Common Footer:</strong> Hospital Address (Chishtia Colony, Raiwind Road Sundar, Lahore), Contact (03074246239), and <code>Developed_By_DCtechsolutions</code> signature.</li>
                <li><strong>Print Optimization:</strong> Clean A4 page margins, page-break safety, and high-contrast black/dark gray ink saving typography.</li>
            </ul>
        </div>
    </main>
</body>
</html>
