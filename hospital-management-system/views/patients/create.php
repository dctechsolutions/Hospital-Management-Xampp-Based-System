<?php
/**
 * Patient Registration View
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Patient Registration - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>

    <main class="main-container">
        <?php require __DIR__ . '/../partials/alert.php'; ?>

        <div class="page-header">
            <div>
                <h1 class="page-title">Patient Registration</h1>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Register a new patient record</p>
            </div>
            <div class="page-actions">
                <a href="<?php echo BASE_URL; ?>index.php?route=patients" class="btn btn-secondary">&larr; Back to Patient List</a>
            </div>
        </div>

        <div class="card">
            <form action="<?php echo BASE_URL; ?>index.php?route=patient_store" method="POST" id="patientForm">
                <?php echo CSRF::field(); ?>

                <div class="form-grid" style="margin-bottom: 20px;">
                    <!-- Unique ID (Auto-Generated) -->
                    <div class="form-group">
                        <label class="form-label" for="unique_id">Unique Patient ID</label>
                        <input type="text" id="unique_id" name="unique_id" class="form-control" value="<?php echo htmlspecialchars($nextId); ?>" readonly>
                        <small style="color: #64748b; font-size: 11px;">Automatically generated sequential ID</small>
                    </div>

                    <!-- Registration Date -->
                    <div class="form-group">
                        <label class="form-label" for="registration_date">Registration Date</label>
                        <input type="date" id="registration_date" name="registration_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <!-- Patient Full Name -->
                    <div class="form-group">
                        <label class="form-label" for="name">Patient Name *</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter patient's full name" required autofocus>
                    </div>

                    <!-- Relation Type -->
                    <div class="form-group">
                        <label class="form-label" for="relation_type">Relation Type *</label>
                        <select id="relation_type" name="relation_type" class="form-control" required>
                            <option value="S/O">S/O (Son of)</option>
                            <option value="D/O">D/O (Daughter of)</option>
                            <option value="W/O">W/O (Wife of)</option>
                        </select>
                    </div>

                    <!-- Relation Name -->
                    <div class="form-group">
                        <label class="form-label" for="relation_name">Relation Name (Father/Husband) *</label>
                        <input type="text" id="relation_name" name="relation_name" class="form-control" placeholder="Enter father or husband name" required>
                    </div>

                    <!-- Age -->
                    <div class="form-group">
                        <label class="form-label" for="age">Age (Years) *</label>
                        <input type="number" id="age" name="age" class="form-control" min="0" max="130" placeholder="e.g. 28" required>
                    </div>

                    <!-- Sex -->
                    <div class="form-group">
                        <label class="form-label" for="sex">Sex *</label>
                        <select id="sex" name="sex" class="form-control" required>
                            <option value="Female" selected>Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>

                    <!-- Contact Number -->
                    <div class="form-group">
                        <label class="form-label" for="contact">Contact Number</label>
                        <input type="text" id="contact" name="contact" class="form-control" placeholder="e.g. 03001234567">
                    </div>

                    <!-- Attending Doctor / LHV -->
                    <div class="form-group">
                        <label class="form-label" for="doctor_id">Consultant Doctor / LHV</label>
                        <select id="doctor_id" name="doctor_id" class="form-control">
                            <option value="">-- Select Doctor / LHV --</option>
                            <?php foreach ($doctors as $doc): ?>
                                <option value="<?php echo $doc['id']; ?>">
                                    <?php echo htmlspecialchars($doc['name']); ?> (<?php echo htmlspecialchars($doc['designation']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="Village / Colony / Street address">
                </div>

                <!-- Initial Symptoms / Visit Notes -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" for="symptoms_notes">Initial Symptoms / Visit Reason</label>
                    <textarea id="symptoms_notes" name="symptoms_notes" class="form-control" rows="2" placeholder="e.g. Antenatal checkup, general fever, maternity consultation"></textarea>
                </div>

                <div style="display: flex; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <button type="submit" class="btn btn-primary" style="padding: 9px 24px;">Save Patient</button>
                    <button type="reset" class="btn btn-secondary" style="padding: 9px 20px;">Clear</button>
                    <a href="<?php echo BASE_URL; ?>index.php?route=patients" class="btn btn-secondary" style="margin-left: auto;">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
