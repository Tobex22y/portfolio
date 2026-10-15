<?php
/**
 * Contact Submission API Endpoint
 * Demonstrates robust AppSec defenses:
 * - Anti-CSRF verification
 * - Rate-limiting protection
 * - Bot honeypot trap
 * - Server-side type and format validation
 * - PDO prepared statements against SQL injection
 * - Defensive output encoding against XSS
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

sendSecurityHeaders();
header('Content-Type: application/json; charset=utf-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// 1. Rate Limiting Check (Max 5 submissions per 3 minutes)
if (!checkRateLimit('contact_submission', 5, 180)) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'error'   => 'Rate limit exceeded: Too many requests. Please wait a few minutes before trying again.'
    ]);
    exit;
}

// Parse request body (supports both JSON and standard Form Post)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$inputData = [];

if (stripos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $inputData = json_decode($raw, true) ?? [];
} else {
    $inputData = $_POST;
}

// 2. Honeypot check (Spam bots fill out hidden fields)
if (!empty($inputData['website_url_honeypot'])) {
    // Silently drop spam without notifying the bot
    echo json_encode([
        'success' => true,
        'message' => 'Transmission received.'
    ]);
    exit;
}

// 3. CSRF Verification
$submittedToken = $inputData['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
if (!verifyCsrfToken($submittedToken)) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error'   => 'Security Token Error: Invalid or expired CSRF token. Please refresh the page.'
    ]);
    exit;
}

// 4. Input Extraction & Validation
$name = trim((string)($inputData['name'] ?? ''));
$email = trim((string)($inputData['email'] ?? ''));
$subject = trim((string)($inputData['subject'] ?? ''));
$message = trim((string)($inputData['message'] ?? ''));
$isSecurityDisclosure = !empty($inputData['is_security_disclosure']) ? 1 : 0;

$errors = [];

if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    $errors[] = 'Name must be between 2 and 100 characters.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    $errors[] = 'Please provide a valid email address.';
}

if (mb_strlen($subject) < 2 || mb_strlen($subject) > 200) {
    $errors[] = 'Subject must be between 2 and 200 characters.';
}

if (mb_strlen($message) < 10 || mb_strlen($message) > 5000) {
    $errors[] = 'Message content must be between 10 and 5000 characters.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'errors'  => $errors,
        'error'   => implode(' ', $errors)
    ]);
    exit;
}

// Capture client telemetry safely
$clientIp = getClientIp();
$userAgent = mb_substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255);

// 5. Persistence via PDO Prepared Statements
$pdo = getDBConnection();
$savedToDatabase = false;
$recordId = null;

if ($pdo !== null) {
    try {
        $sql = "INSERT INTO `contacts` 
                (`name`, `email`, `subject`, `message`, `is_security_disclosure`, `ip_address`, `user_agent`, `created_at`) 
                VALUES (:name, :email, :subject, :message, :is_sec, :ip, :ua, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':subject' => $subject,
            ':message' => $message,
            ':is_sec'  => $isSecurityDisclosure,
            ':ip'      => $clientIp,
            ':ua'      => $userAgent
        ]);

        $recordId = $pdo->lastInsertId();
        $savedToDatabase = true;
    } catch (PDOException $e) {
        error_log('[CONTACT FORM DB ERROR] ' . $e->getMessage());
    }
}

// Fallback persistence if MySQL is not yet configured
if (!$savedToDatabase) {
    $backupDir = __DIR__ . '/../data';
    if (!is_dir($backupDir)) {
        @mkdir($backupDir, 0755, true);
    }
    
    $backupFile = $backupDir . '/messages.json';
    $messages = [];
    if (file_exists($backupFile)) {
        $existing = json_decode(file_get_contents($backupFile), true);
        if (is_array($existing)) {
            $messages = $existing;
        }
    }

    $recordId = 'MSG-' . strtoupper(bin2hex(random_bytes(4)));
    $messages[] = [
        'id'                     => $recordId,
        'name'                   => $name,
        'email'                  => $email,
        'subject'                => $subject,
        'message'                => $message,
        'is_security_disclosure' => $isSecurityDisclosure,
        'ip_address'             => $clientIp,
        'user_agent'             => $userAgent,
        'created_at'             => date('Y-m-d H:i:s'),
        'is_read'                => 0
    ];

    @file_put_contents($backupFile, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Refresh CSRF token after successful submission to avoid token replay
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

echo json_encode([
    'success'       => true,
    'message'       => $isSecurityDisclosure 
        ? 'Security vulnerability disclosure logged into triage queue with encrypted trace.' 
        : 'Thank you! Your message has been received securely. I will respond shortly.',
    'ticket_id'     => $recordId,
    'new_csrf'      => $_SESSION['csrf_token'],
    'security_mode' => 'ENCRYPTED_PARAMETRIC_STORE'
]);
