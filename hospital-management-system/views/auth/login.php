<?php
/**
 * Login Screen
 * Yasmeen Maternity and Medical Center (R-85647)
 * Developed_By_DCtechsolutions
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Yasmeen Maternity and Medical Center</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #f1f5f9;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 32px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .login-logo {
            width: 64px;
            height: 64px;
            margin-bottom: 12px;
            object-fit: contain;
        }
        .login-title-main {
            font-size: 20px;
            font-weight: 800;
            color: #047857;
            letter-spacing: 0.5px;
        }
        .login-title-sub {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-top: 2px;
        }
        .login-ref {
            font-size: 12px;
            color: #0f766e;
            font-weight: 700;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <img src="<?php echo BASE_URL; ?>assets/images/yasmeen-logo.png" 
                     alt="Hospital Logo" 
                     class="login-logo"
                     onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/yasmeen-logo.svg';">
                <div class="login-title-main">YASMEEN</div>
                <div class="login-title-sub">MATERNITY AND MEDICAL CENTER</div>
                <div class="login-ref">Reference: R-85647</div>
            </div>

            <?php require __DIR__ . '/../partials/alert.php'; ?>

            <form action="<?php echo BASE_URL; ?>index.php?route=do_login" method="POST">
                <?php echo CSRF::field(); ?>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required autofocus>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px;">Sign In</button>
            </form>

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #64748b; text-align: center;">
                <p>Default accounts: <strong>admin</strong> | <strong>receptionist</strong> | <strong>pharmacist</strong></p>
                <p>Default password: <strong>password123</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
