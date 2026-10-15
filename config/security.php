<?php
/**
 * Security Utilities & HTTP Hardening Suite
 * Includes CSRF Protection, HTTP Headers, Rate Limiting, and Input Sanitization
 */

declare(strict_types=1);

// Initialize secure session if not started
if (session_status() === PHP_SESSION_NONE) {
    // Harden session cookie attributes against XSS and session hijacking
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => true,      // Prevents JavaScript access to session cookie
        'samesite' => 'Lax',     // Mitigates CSRF on top-level navigations
    ]);
    session_start();
}

/**
 * Sends defensive HTTP headers to protect against common web attacks
 */
function sendSecurityHeaders(): void
{
    // Prevent MIME-type sniffing
    header('X-Content-Type-Options: nosniff');

    // Prevent clickjacking via iframes
    header('X-Frame-Options: SAMEORIGIN');

    // Legacy XSS filter protection
    header('X-XSS-Protection: 1; mode=block');

    // Referrer policy privacy protection
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Permissions policy to restrict browser features
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

    // Content Security Policy (allows local assets, Google Fonts, and FontAwesome / cdnjs icons)
    $csp = "default-src 'self'; " .
           "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; " .
           "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .
           "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; " .
           "img-src 'self' data: https:; " .
           "connect-src 'self'; " .
           "base-uri 'self'; " .
           "form-action 'self';";
    header("Content-Security-Policy: {$csp}");
}

/**
 * Generates or retrieves existing CSRF token for the active session
 *
 * @return string
 */
function getCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validates provided CSRF token using timing-safe comparison
 *
 * @param string|null $token
 * @return bool
 */
function verifyCsrfToken(?string $token): bool
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitizes input string to prevent XSS vulnerabilities
 *
 * @param mixed $input
 * @return mixed
 */
function sanitizeInput(mixed $input): mixed
{
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }

    if (is_string($input)) {
        // Strip null bytes
        $clean = str_replace(chr(0), '', $input);
        // Convert special characters to HTML entities
        return htmlspecialchars(trim($clean), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    return $input;
}

/**
 * Simple in-session IP / action rate limiter to mitigate brute-force and flood spam
 *
 * @param string $action Unique identifier for the action (e.g. 'contact_form')
 * @param int $maxAttempts Maximum allowed requests in the time window
 * @param int $windowSeconds Duration of the rate limit window in seconds
 * @return bool True if allowed, false if limit exceeded
 */
function checkRateLimit(string $action, int $maxAttempts = 5, int $windowSeconds = 60): bool
{
    $now = time();
    if (!isset($_SESSION['rate_limits'])) {
        $_SESSION['rate_limits'] = [];
    }
    if (!isset($_SESSION['rate_limits'][$action])) {
        $_SESSION['rate_limits'][$action] = [];
    }

    // Filter out attempts outside current window
    $_SESSION['rate_limits'][$action] = array_filter(
        $_SESSION['rate_limits'][$action],
        fn($timestamp) => ($now - $timestamp) < $windowSeconds
    );

    if (count($_SESSION['rate_limits'][$action]) >= $maxAttempts) {
        return false;
    }

    // Record this attempt
    $_SESSION['rate_limits'][$action][] = $now;
    return true;
}

/**
 * Helper to get the client IP address safely
 *
 * @return string
 */
function getClientIp(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Take the first IP in comma-separated list
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';
}
