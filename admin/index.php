<?php
/**
 * Admin Security Dashboard & Message Ingestion Center
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

sendSecurityHeaders();

// Authentication Gatekeeper
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$pdo = getDBConnection();
$feedbackMessage = '';
$feedbackType = 'info';

// Handle Actions (Delete, Mark as Read)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $msgId = $_POST['message_id'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';

    if (verifyCsrfToken($csrf)) {
        if ($action === 'delete' && !empty($msgId)) {
            if ($pdo !== null && is_numeric($msgId)) {
                try {
                    $delStmt = $pdo->prepare("DELETE FROM `contacts` WHERE `id` = :id");
                    $delStmt->execute([':id' => $msgId]);
                    $feedbackMessage = "Record #{$msgId} safely purged from database.";
                    $feedbackType = 'success';
                } catch (PDOException $e) {
                    $feedbackMessage = 'Failed to delete record: ' . $e->getMessage();
                    $feedbackType = 'danger';
                }
            } else {
                // Fallback delete in JSON file
                $file = __DIR__ . '/../data/messages.json';
                if (file_exists($file)) {
                    $data = json_decode(file_get_contents($file), true) ?: [];
                    $data = array_values(array_filter($data, fn($m) => (string)$m['id'] !== (string)$msgId));
                    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $feedbackMessage = "Record {$msgId} purged from fallback store.";
                    $feedbackType = 'success';
                }
            }
        } elseif ($action === 'mark_read' && !empty($msgId)) {
            if ($pdo !== null && is_numeric($msgId)) {
                try {
                    $readStmt = $pdo->prepare("UPDATE `contacts` SET `is_read` = 1 WHERE `id` = :id");
                    $readStmt->execute([':id' => $msgId]);
                    $feedbackMessage = "Record #{$msgId} marked as reviewed.";
                    $feedbackType = 'success';
                } catch (PDOException $e) {
                    $feedbackMessage = 'Database error: ' . $e->getMessage();
                    $feedbackType = 'danger';
                }
            }
        }
    } else {
        $feedbackMessage = 'CSRF validation failed for this action.';
        $feedbackType = 'danger';
    }
}

// Fetch Messages
$messages = [];
$totalMessages = 0;
$unreadCount = 0;
$secDisclosureCount = 0;

if ($pdo !== null) {
    try {
        $stmt = $pdo->query("SELECT * FROM `contacts` ORDER BY `created_at` DESC");
        $messages = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('[ADMIN FETCH ERROR] ' . $e->getMessage());
    }
}

// Check fallback storage if empty or no DB
if (empty($messages)) {
    $file = __DIR__ . '/../data/messages.json';
    if (file_exists($file)) {
        $messages = json_decode(file_get_contents($file), true) ?: [];
    }
}

foreach ($messages as $msg) {
    $totalMessages++;
    if (empty($msg['is_read'])) {
        $unreadCount++;
    }
    if (!empty($msg['is_security_disclosure'])) {
        $secDisclosureCount++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOC Dashboard | Portfolio Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav {
            background: rgba(15, 20, 34, 0.95);
            border-bottom: 1px solid rgba(0, 243, 255, 0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }
        .admin-nav-title {
            font-family: var(--font-mono);
            font-size: 1.1rem;
            color: var(--neon-cyan);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: rgba(15, 20, 34, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--neon-cyan);
        }
        .stat-card.emerald::after { background: var(--neon-emerald); }
        .stat-card.amber::after { background: var(--neon-amber); }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            font-family: var(--font-mono);
            color: var(--text-light);
            margin: 0.25rem 0;
        }
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: var(--font-mono);
        }
        .message-card {
            background: rgba(15, 20, 34, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
            transition: border-color 0.2s ease;
        }
        .message-card.unread {
            border-left: 4px solid var(--neon-cyan);
            background: rgba(15, 20, 34, 0.95);
        }
        .message-card.security-disclosure {
            border-left: 4px solid var(--neon-amber);
        }
        .msg-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }
        .msg-meta strong {
            color: var(--text-light);
        }
        .msg-body {
            background: rgba(8, 11, 17, 0.6);
            padding: 1rem;
            border-radius: 6px;
            font-family: inherit;
            line-height: 1.6;
            margin: 0.75rem 0;
            white-space: pre-wrap;
            border: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.95rem;
        }
        .msg-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        .btn-action {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-muted);
            padding: 0.4rem 0.85rem;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            font-family: var(--font-mono);
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }
        .btn-delete:hover {
            border-color: #ff4757;
            color: #ff4757;
        }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-family: var(--font-mono);
            font-weight: 600;
        }
        .badge-disclosure {
            background: rgba(255, 183, 0, 0.15);
            color: var(--neon-amber);
            border: 1px solid rgba(255, 183, 0, 0.4);
        }
        .badge-normal {
            background: rgba(0, 243, 255, 0.1);
            color: var(--neon-cyan);
            border: 1px solid rgba(0, 243, 255, 0.3);
        }
        .alert-banner {
            padding: 0.85rem 1.25rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-family: var(--font-mono);
            font-size: 0.9rem;
        }
        .alert-banner.success {
            background: rgba(0, 255, 157, 0.15);
            border: 1px solid var(--neon-emerald);
            color: var(--neon-emerald);
        }
        .alert-banner.danger {
            background: rgba(255, 71, 87, 0.15);
            border: 1px solid #ff4757;
            color: #ff6b81;
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="admin-nav-title">
            <span>🛡️ SOC DASHBOARD</span>
            <span style="font-size: 0.8rem; color: var(--neon-emerald); font-weight: normal;">[AUTHENTICATED]</span>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <span style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--text-muted);">
                User: <strong style="color: var(--text-light);"><?= htmlspecialchars($_SESSION['admin_user'] ?? 'admin', ENT_QUOTES, 'UTF-8'); ?></strong>
            </span>
            <a href="../index.php" target="_blank" class="btn-action">View Site ↗</a>
            <a href="logout.php" class="btn-action btn-delete">Terminate Session</a>
        </div>
    </nav>

    <div class="admin-container">
        <?php if (!empty($feedbackMessage)): ?>
            <div class="alert-banner <?= $feedbackType; ?>">
                <?= htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Telemetry Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Inquiries</div>
                <div class="stat-number"><?= $totalMessages; ?></div>
            </div>
            <div class="stat-card emerald">
                <div class="stat-label">Pending / Unread</div>
                <div class="stat-number"><?= $unreadCount; ?></div>
            </div>
            <div class="stat-card amber">
                <div class="stat-label">Security Disclosures</div>
                <div class="stat-number"><?= $secDisclosureCount; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Database Connection</div>
                <div class="stat-number" style="font-size: 1.25rem; color: <?= $pdo ? 'var(--neon-emerald)' : 'var(--neon-amber)'; ?>;">
                    <?= $pdo ? '● ONLINE (MySQL)' : '○ FALLBACK (JSON)'; ?>
                </div>
            </div>
        </div>

        <!-- Section Heading -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h2 style="font-family: var(--font-mono); font-size: 1.25rem; color: var(--text-light);">
                > INCOMING_TRANSMISSIONS_QUEUE (<?= count($messages); ?>)
            </h2>
        </div>

        <?php if (empty($messages)): ?>
            <div class="message-card" style="text-align: center; padding: 3rem 1rem;">
                <p style="color: var(--text-muted); font-family: var(--font-mono); margin: 0;">
                    [✓] No incoming transmissions pending in buffer.
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <?php 
                    $isUnread = empty($msg['is_read']);
                    $isSec = !empty($msg['is_security_disclosure']);
                ?>
                <div class="message-card <?= $isUnread ? 'unread' : ''; ?> <?= $isSec ? 'security-disclosure' : ''; ?>">
                    <div class="msg-meta">
                        <span><strong>#<?= htmlspecialchars((string)$msg['id'], ENT_QUOTES, 'UTF-8'); ?></strong></span>
                        <span>From: <strong><?= htmlspecialchars($msg['name'], ENT_QUOTES, 'UTF-8'); ?></strong> &lt;<?= htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8'); ?>&gt;</span>
                        <span>Time: <?= htmlspecialchars($msg['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span>IP: <code><?= htmlspecialchars($msg['ip_address'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></code></span>
                        <span>
                            <?php if ($isSec): ?>
                                <span class="badge badge-disclosure">⚠️ VULNERABILITY DISCLOSURE</span>
                            <?php else: ?>
                                <span class="badge badge-normal">GENERAL INQUIRY</span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <h3 style="color: var(--text-light); font-size: 1.1rem; margin-bottom: 0.5rem;">
                        Subject: <?= htmlspecialchars($msg['subject'], ENT_QUOTES, 'UTF-8'); ?>
                    </h3>

                    <div class="msg-body"><?= htmlspecialchars($msg['message'], ENT_QUOTES, 'UTF-8'); ?></div>

                    <div class="msg-actions">
                        <?php if ($isUnread): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="action" value="mark_read">
                                <input type="hidden" name="message_id" value="<?= htmlspecialchars((string)$msg['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn-action">✓ Mark Reviewed</button>
                            </form>
                        <?php endif; ?>

                        <form method="POST" style="display:inline;" onsubmit="return confirm('Purge this message permanently?');">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="message_id" value="<?= htmlspecialchars((string)$msg['id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" class="btn-action btn-delete">✕ Delete</button>
                        </form>

                        <a href="mailto:<?= htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8'); ?>?subject=Re: <?= urlencode($msg['subject']); ?>" class="btn-action" style="text-decoration:none;">
                            ✉ Reply via Email
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
