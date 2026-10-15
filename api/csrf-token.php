<?php
/**
 * CSRF Token API Endpoint
 * Provides the active session's anti-CSRF token to AJAX clients
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';

sendSecurityHeaders();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

echo json_encode([
    'csrf_token' => getCsrfToken(),
    'timestamp'  => time(),
    'status'     => 'ACTIVE'
]);
