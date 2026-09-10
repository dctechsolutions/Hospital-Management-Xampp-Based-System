<?php
/**
 * Patient Details and Visits View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient <?php echo htmlspecialchars($patient['unique_id']); ?> - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/print.css" media="print">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header no-print">
            <div>
                <h1 class="page-title">Patient Profile & Visit History</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">
                    ID: <strong style="color: #047857;"><?php echo htmlspecialchars($patient['unique_id']); ?></strong> | Registered: <?php echo date('d-M-Y', strtotime($patient['registration_date'])); ?>
                </p>
            </div>
            <div class="page-actions">
                <button onclick="window.print();" class="btn btn-secondary">Print Patient File</button>
                <a href="<?php echo BASE_URL; ?>index.php?route=patients" class="btn btn-secondary">&larr; Back to Patients</a>
            </div>
        </div>

        <div class="printable-content">
            <!-- Common Report Header for Print -->
            <?php 
                $reportTitle = 'PATIENT MEDICAL RECORD & VISIT HISTORY';
                $reportSubTitle = 'Patient ID: ' . $patient['unique_id'];
                require __DIR__ . '/../partials/report_header.php'; 
            ?>

            <!-- Stable Patient Information -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 class="card-title">Patient Demographics</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block;">Full Name</span>
                        <strong style="font-size: 16px;"><?php echo htmlspecialchars($patient['name']); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block;">Relation</span>
                        <strong><?php echo htmlspecialchars($patient['relation_type'] . ' ' . $patient['relation_name']); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block;">Age & Sex</span>
                        <strong><?php echo htmlspecialchars($patient['age'] . ' Years / ' . $patient['sex']); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block;">Contact Number</span>
                        <strong><?php echo htmlspecialchars($patient['contact'] ?: 'None recorded'); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block;">Primary Doctor / LHV</span>
                        <strong><?php echo htmlspecialchars($patient['doctor_name'] ?: 'General'); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block;">Residential Address</span>
                        <span><?php echo htmlspecialchars($patient['address'] ?: 'Sundar / Lahore'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Visits Architecture: Add New Visit (Screen Only) -->
            <?php if (Auth::hasRole(['administrator', 'receptionist'])): ?>
                <div class="card no-print" style="background-color: #f8fafc; border: 1px solid #cbd5e1;">
                    <h3 class="card-title" style="color: #047857;">+ Record New Hospital Visit</h3>
                    <form action="<?php echo BASE_URL; ?>index.php?route=patient_visit_store" method="POST">
                        <?php echo CSRF::field(); ?>
                        <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">

                        <div class="form-grid" style="margin-bottom: 16px;">
                            <div class="form-group">
                                <label class="form-label" for="visit_date">Visit Date *</label>
                                <input type="date" id="visit_date" name="visit_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="doctor_id">Attending Doctor / LHV *</label>
                                <select id="doctor_id" name="doctor_id" class="form-control" required>
                                    <option value="">-- Select Doctor --</option>
                                    <?php foreach ($doctors as $d): ?>
                                        <option value="<?php echo $d['id']; ?>" <?php echo $patient['doctor_id'] == $d['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($d['name']); ?> (<?php echo htmlspecialchars($d['designation']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="vitals_bp">Blood Pressure</label>
                                <input type="text" id="vitals_bp" name="vitals_bp" class="form-control" placeholder="e.g. 120/80">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="vitals_weight">Weight (kg)</label>
                                <input type="text" id="vitals_weight" name="vitals_weight" class="form-control" placeholder="e.g. 62 kg">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="consultation_fee">Consultation Fee (Rs.)</label>
                                <input type="number" id="consultation_fee" name="consultation_fee" class="form-control" value="0" min="0">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" for="symptoms_notes">Clinical Notes / Diagnosis / Reason for Visit</label>
                            <textarea id="symptoms_notes" name="symptoms_notes" class="form-control" rows="2" placeholder="Enter clinical assessment notes, symptoms, or instructions"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Visit Record</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Chronological Visit History -->
            <div class="card">
                <h3 class="card-title">Hospital Visits Log (<?php echo count($visits); ?> Total Visits)</h3>
                <div class="table-responsive">
                    <table class="data-table report-table">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Visit Date</th>
                                <th>Doctor / LHV</th>
                                <th>Vitals</th>
                                <th>Clinical Notes / Diagnosis</th>
                                <th style="width: 100px;">Fee (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($visits)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b;">No visits recorded yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($visits as $v): ?>
                                    <tr>
                                        <td><strong><?php echo date('d-M-Y', strtotime($v['visit_date'])); ?></strong></td>
                                        <td><?php echo htmlspecialchars($v['doctor_name'] ?: 'General Consultation'); ?></td>
                                        <td>
                                            <?php 
                                                $vitals = [];
                                                if (!empty($v['vitals_bp'])) $vitals[] = 'BP: ' . htmlspecialchars($v['vitals_bp']);
                                                if (!empty($v['vitals_temp'])) $vitals[] = 'Temp: ' . htmlspecialchars($v['vitals_temp']);
                                                if (!empty($v['vitals_weight'])) $vitals[] = 'Wt: ' . htmlspecialchars($v['vitals_weight']);
                                                echo !empty($vitals) ? implode(' | ', $vitals) : '-';
                                            ?>
                                        </td>
                                        <td><?php echo nl2br(htmlspecialchars($v['symptoms_notes'] ?: '-')); ?></td>
                                        <td><?php echo number_format((float)$v['consultation_fee'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pharmacy Dispensing History for this Patient -->
            <?php if (!empty($medicineHistory)): ?>
                <div class="card">
                    <h3 class="card-title">Pharmacy Medicine Issue History</h3>
                    <div class="table-responsive">
                        <table class="data-table report-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Medicine Name</th>
                                    <th>Batch</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total (Rs.)</th>
                                    <th>Pharmacist</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medicineHistory as $mh): ?>
                                    <tr>
                                        <td><?php echo date('d-M-Y', strtotime($mh['sale_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($mh['invoice_no']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($mh['medicine_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($mh['batch_number'] ?: '-'); ?></td>
                                        <td><?php echo htmlspecialchars($mh['quantity'] . ' ' . $mh['unit']); ?></td>
                                        <td><?php echo number_format((float)$mh['unit_selling_price'], 2); ?></td>
                                        <td><strong><?php echo number_format((float)$mh['subtotal'], 2); ?></strong></td>
                                        <td><?php echo htmlspecialchars($mh['pharmacist_name'] ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Common Report Footer for Print -->
            <?php require __DIR__ . '/../partials/report_footer.php'; ?>
        </div>
    </main>
</body>
</html>
