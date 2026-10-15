<?php
/**
 * Admin Logout Handler
 * Securely terminates session and purges auth cookies
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/security.php';

// Unset all session variables
$_SESSION = [];

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy session
session_destroy();

header('Location: login.php');
exit;
