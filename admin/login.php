<?php
/**
 * Admin Login Portal
 * Hardened with:
 * - Anti-CSRF token validation
 * - Brute-force login rate limiting
 * - Session fixation prevention (session_regenerate_id)
 * - Timing-safe password verification
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

sendSecurityHeaders();

// Redirect if already authenticated
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Rate Limiting Check (Max 5 attempts per 5 minutes)
    if (!checkRateLimit('admin_login', 5, 300)) {
        $error = 'Rate Limit Triggered: Too many failed login attempts. Account locked for 5 minutes.';
    } else {
        // 2. CSRF Verification
        $csrf = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($csrf)) {
            $error = 'Security Token Mismatch: Invalid CSRF token. Please retry.';
        } else {
            $username = trim((string)($_POST['username'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            $authenticated = false;
            $pdo = getDBConnection();

            if ($pdo !== null) {
                try {
                    $stmt = $pdo->prepare("SELECT `id`, `username`, `password_hash` FROM `admin_users` WHERE `username` = :user LIMIT 1");
                    $stmt->execute([':user' => $username]);
                    $user = $stmt->fetch();

                    if ($user && password_verify($password, $user['password_hash'])) {
                        $authenticated = true;
                        // Update last login
                        $update = $pdo->prepare("UPDATE `admin_users` SET `last_login` = NOW() WHERE `id` = :id");
                        $update->execute([':id' => $user['id']]);
                    }
                } catch (PDOException $e) {
                    error_log('[ADMIN LOGIN QUERY ERROR] ' . $e->getMessage());
                }
            } else {
                // Fallback verification if database has not been initialized yet
                // Default: admin / Admin@Portfolio2026!
                $fallbackHash = '$2y$10$9kKMUTV95dNN/NvorKnOt.Y4mCbs4OVVCd5ox8k2uRpR5J80VCFc2';
                if ($username === 'admin' && password_verify($password, $fallbackHash)) {
                    $authenticated = true;
                }
            }

            if ($authenticated) {
                // Prevent Session Fixation attack
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user'] = $username;
                $_SESSION['login_time'] = time();

                // Rotate CSRF token
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header('Location: index.php');
                exit;
            } else {
                // Generic error message to prevent username enumeration
                $error = 'Authentication Failed: Invalid credentials or authorization level.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secured Admin Portal | Command Center</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: radial-gradient(circle at 50% 30%, rgba(0, 243, 255, 0.05) 0%, #080b11 75%);
        }
        .login-card {
            background: rgba(15, 20, 34, 0.85);
            border: 1px solid rgba(0, 243, 255, 0.25);
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.15), inset 0 0 15px rgba(0, 243, 255, 0.05);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem 2rem;
            backdrop-filter: blur(12px);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-badge {
            display: inline-block;
            background: rgba(0, 255, 157, 0.1);
            color: var(--neon-emerald);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-family: var(--font-mono);
            border: 1px solid rgba(0, 255, 157, 0.3);
            margin-bottom: 0.75rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-family: var(--font-mono);
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(8, 11, 17, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            color: var(--text-light);
            font-family: var(--font-mono);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
        }
        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, var(--neon-cyan), var(--neon-emerald));
            border: none;
            border-radius: 6px;
            color: #080b11;
            font-weight: 700;
            font-family: var(--font-mono);
            font-size: 1rem;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.5);
        }
        .alert-box {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            font-family: var(--font-mono);
        }
        .alert-danger {
            background: rgba(255, 71, 87, 0.15);
            border: 1px solid rgba(255, 71, 87, 0.4);
            color: #ff6b81;
        }
        .default-credentials-hint {
            margin-top: 1.5rem;
            padding: 0.85rem;
            background: rgba(0, 243, 255, 0.05);
            border: 1px dashed rgba(0, 243, 255, 0.25);
            border-radius: 6px;
            font-size: 0.8rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }
        .default-credentials-hint strong {
            color: var(--neon-cyan);
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <span class="login-badge">● RESTRICTED ACCESS AREA</span>
                <h1 style="font-size: 1.5rem; color: var(--text-light); margin-bottom: 0.25rem;">Admin Verification</h1>
                <p style="color: var(--text-muted); font-size: 0.85rem;">Authenticate to access threat reports and message dispatcher</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert-box alert-danger">
                    ⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">

                <div class="form-group">
                    <label for="username">> USER_IDENTITY</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="admin" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">> ENCRYPTED_KEY_PASS</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••••••••••" required>
                </div>

                <button type="submit" class="btn-submit">DECRYPT & ACCESS</button>
            </form>

            <div class="default-credentials-hint">
                <div>⚡ <strong>Initial Default Credentials:</strong></div>
                <div>User: <code>admin</code></div>
                <div>Pass: <code>Admin@Portfolio2026!</code></div>
            </div>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="../index.php" style="color: var(--text-muted); font-size: 0.85rem; font-family: var(--font-mono); text-decoration: none;">
                    ← Return to Public Portfolio
                </a>
            </div>
        </div>
    </div>
</body>
</html>
