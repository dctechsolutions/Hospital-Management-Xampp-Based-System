<?php
/**
 * Pharmacy Patient Medicine History & Report
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Medicine History - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/print.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header no-print">
            <div>
                <h1 class="page-title">Patient Medicine History</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">
                    Track all medicines dispensed to a specific patient and frequency analysis
                </p>
            </div>
            <div class="page-actions">
                <?php if ($selectedPatient): ?>
                    <button onclick="window.print();" class="btn btn-primary">Print Patient History (A4)</button>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>index.php?route=pharmacy_stock" class="btn btn-secondary">&larr; Back to Stock</a>
            </div>
        </div>

        <!-- Search / Select Patient Form -->
        <div class="card no-print" style="padding: 16px; margin-bottom: 20px;">
            <form action="<?php echo BASE_URL; ?>index.php" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <input type="hidden" name="route" value="pharmacy_patient_history">

                <div class="form-group" style="flex-grow: 1; min-width: 260px;">
                    <label class="form-label" for="patient_select">Select Patient from List</label>
                    <select id="patient_select" name="patient_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Choose Patient --</option>
                        <?php foreach ($allPatients as $ap): ?>
                            <option value="<?php echo $ap['id']; ?>" <?php echo ($selectedPatient && $selectedPatient['id'] == $ap['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ap['unique_id']); ?> - <?php echo htmlspecialchars($ap['name']); ?> (<?php echo htmlspecialchars($ap['relation_type'] . ' ' . $ap['relation_name']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="flex-grow: 1; min-width: 240px;">
                    <label class="form-label" for="search_input">Or Search by ID / Name / Contact</label>
                    <input type="text" id="search_input" name="search" class="form-control" placeholder="e.g. P-2026-000001, Fatima..." value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <?php if ($selectedPatient): ?>
            <div class="report-paper">
                <!-- Reusable Common Hospital Header -->
                <?php 
                    $reportTitle = 'PATIENT PHARMACY & MEDICINE HISTORY REPORT';
                    $reportSubTitle = 'Patient: ' . $selectedPatient['name'] . ' (' . $selectedPatient['unique_id'] . ')';
                    require __DIR__ . '/../partials/report_header.php'; 
                ?>

                <!-- Patient Snapshot Card -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; font-size: 13px;">
                        <div>
                            <span style="color: #64748b;">Patient Unique ID:</span>
                            <strong style="color: #047857;"><?php echo htmlspecialchars($selectedPatient['unique_id']); ?></strong>
                        </div>
                        <div>
                            <span style="color: #64748b;">Patient Name:</span>
                            <strong><?php echo htmlspecialchars($selectedPatient['name']); ?></strong>
                        </div>
                        <div>
                            <span style="color: #64748b;">Relation:</span>
                            <strong><?php echo htmlspecialchars($selectedPatient['relation_type'] . ' ' . $selectedPatient['relation_name']); ?></strong>
                        </div>
                        <div>
                            <span style="color: #64748b;">Age & Sex:</span>
                            <strong><?php echo htmlspecialchars($selectedPatient['age'] . 'y / ' . $selectedPatient['sex']); ?></strong>
                        </div>
                        <div>
                            <span style="color: #64748b;">Contact:</span>
                            <strong><?php echo htmlspecialchars($selectedPatient['contact'] ?: 'None'); ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Answer to: "For this patient, how many times was a particular medicine given?" -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size: 14px; font-weight: 700; color: #047857; margin-bottom: 8px; text-transform: uppercase;">
                        Prescription Frequency Summary ("How many times given?")
                    </h4>
                    <table class="report-table data-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Medicine Name</th>
                                <th style="text-align: center;">Times Prescribed / Given</th>
                                <th style="text-align: center;">Total Units Issued</th>
                                <th>Last Issued Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($summary)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #64748b; padding: 16px;">
                                        No medicines have been dispensed to this patient yet.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($summary as $sm): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($sm['medicine_name']); ?></strong></td>
                                        <td style="text-align: center; font-weight: 700; color: #047857;">
                                            <?php echo $sm['times_prescribed']; ?> times
                                        </td>
                                        <td style="text-align: center;">
                                            <strong><?php echo $sm['total_units_given']; ?></strong> <?php echo htmlspecialchars($sm['unit']); ?>
                                        </td>
                                        <td><?php echo date('d-M-Y', strtotime($sm['last_issued_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Detailed Chronological Dispensing Log -->
                <div>
                    <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 8px; text-transform: uppercase;">
                        Complete Chronological Medicine Dispensing Log
                    </h4>
                    <table class="report-table data-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice No.</th>
                                <th>Medicine Name</th>
                                <th>Batch</th>
                                <th>Quantity</th>
                                <th>Unit Price (Rs.)</th>
                                <th>Total (Rs.)</th>
                                <th>Pharmacist</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($history)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #64748b; padding: 20px;">
                                        No transaction history found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $grandTotal = 0; foreach ($history as $h): $grandTotal += (float)$h['subtotal']; ?>
                                    <tr>
                                        <td><?php echo date('d-M-Y', strtotime($h['sale_date'])); ?></td>
                                        <td><code><?php echo htmlspecialchars($h['invoice_no']); ?></code></td>
                                        <td><strong><?php echo htmlspecialchars($h['medicine_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($h['batch_number'] ?: '-'); ?></td>
                                        <td><?php echo htmlspecialchars($h['quantity'] . ' ' . $h['unit']); ?></td>
                                        <td><?php echo number_format((float)$h['unit_selling_price'], 2); ?></td>
                                        <td><strong><?php echo number_format((float)$h['subtotal'], 2); ?></strong></td>
                                        <td><?php echo htmlspecialchars($h['pharmacist_name'] ?: 'Staff'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" style="text-align: right; font-weight: bold;">Grand Total Medicines Cost:</td>
                                        <td colspan="2"><strong style="color: #047857; font-size: 14px;">Rs. <?php echo number_format($grandTotal, 2); ?></strong></td>
                                    </tr>
                                </tfoot>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Reusable Common Hospital Footer -->
                <?php require __DIR__ . '/../partials/report_footer.php'; ?>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 48px; color: #64748b;">
                <p style="font-size: 16px;">Please select or search for a patient above to view their medicine history.</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
