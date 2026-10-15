<?php
/**
 * Projects API Endpoint
 * Returns project portfolio items with filtering and security notes
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

sendSecurityHeaders();
header('Content-Type: application/json; charset=utf-8');

$category = isset($_GET['category']) ? trim((string)$_GET['category']) : 'all';

$fallbackProjects = [
    [
        'id' => 1,
        'title' => 'SentinelGuard: Custom Web Application Firewall (WAF)',
        'category' => 'security',
        'description' => 'A lightweight, real-time reverse-proxy WAF that intercepts HTTP requests, inspects payloads for OWASP Top 10 vectors (SQLi, XSS, Path Traversal), and applies rate limiting with IP reputation scoring.',
        'full_details' => 'SentinelGuard acts as an application-layer shield. Built with modern PHP 8 and an interactive JS telemetry dashboard, it decodes payload representations, handles regex-based and heuristic payload inspection, and generates real-time alert logs with IP geolocation.',
        'tags' => 'PHP 8, MySQL, RegEx, OWASP Top 10, Security Headers, Vanilla JS',
        'security_features' => 'Heuristic SQLi detection, zero-day payload scrubbing, automated IP throttling, CSP Level 3 policy enforcement',
        'live_url' => '#',
        'github_url' => 'https://github.com/example/sentinel-guard-waf',
        'icon_badge' => 'shield-check',
        'featured' => 1
    ],
    [
        'id' => 2,
        'title' => 'Nexus Commerce: Hardened E-Commerce Platform',
        'category' => 'fullstack',
        'description' => 'Enterprise-grade full-stack commerce engine built with vanilla frontend aesthetics, robust PHP backend, strict Role-Based Access Control (RBAC), and PCI-DSS compliant checkout simulation.',
        'full_details' => 'A modular commerce architecture designed with defensive software engineering from ground zero. All user input is strictly validated via parameterized PDO queries, session hijacking protections are enabled with strict cookie attributes, and audit trails log all sensitive administrative actions.',
        'tags' => 'HTML5, Modern CSS, JavaScript, PHP, MySQL, Stripe API, RBAC',
        'security_features' => 'Strict Session Isolation (HttpOnly, SameSite=Strict), Bcrypt hashing with pepper, CSRF tokens on all mutation endpoints',
        'live_url' => '#',
        'github_url' => 'https://github.com/example/nexus-commerce',
        'icon_badge' => 'shopping-bag',
        'featured' => 1
    ],
    [
        'id' => 3,
        'title' => 'VulnHunter: Web Security Header & SSL Analyzer',
        'category' => 'security',
        'description' => 'Automated web reconnaissance and audit utility that inspects target domains for missing security headers (HSTS, CSP, X-Frame), evaluates SSL/TLS handshake cipher suites, and outputs remediation advisories.',
        'full_details' => 'VulnHunter performs non-intrusive passive web analysis. It crafts custom curl requests to capture HTTP headers, assesses cookie configurations, tests for CORS misconfigurations, and ranks domain security posture on an A+ to F scale.',
        'tags' => 'PHP CLI, cURL, OpenSSL, JavaScript, Chart.js, HTML5',
        'security_features' => 'Passive reconnaissance only, strict rate-limited scans, sanitization of domain inputs preventing command injection',
        'live_url' => '#',
        'github_url' => 'https://github.com/example/vuln-hunter-scanner',
        'icon_badge' => 'search',
        'featured' => 1
    ],
    [
        'id' => 4,
        'title' => 'CipherVault: Zero-Knowledge Secret Sharer',
        'category' => 'web',
        'description' => 'Client-side encrypted pastebin and credential sharing application where data is encrypted using Web Crypto API (AES-256-GCM) in the browser before ever reaching the server.',
        'full_details' => 'The server and MySQL database never see plaintext data or the encryption keys. The decryption key is kept in the URL hash fragment (#), which is never transmitted to the backend server. Includes self-destruct timers and burn-on-read capabilities.',
        'tags' => 'Web Crypto API, AES-GCM, JavaScript, PHP, MySQL, CSS Grid',
        'security_features' => 'Zero-knowledge architecture, client-side PBKDF2 key derivation, ephemeral database records with TTL auto-purge',
        'live_url' => '#',
        'github_url' => 'https://github.com/example/cipher-vault',
        'icon_badge' => 'lock',
        'featured' => 1
    ],
    [
        'id' => 5,
        'title' => 'DevSecOps CI/CD Vulnerability Pipeline',
        'category' => 'security',
        'description' => 'Automated security testing pipeline integration that conducts Static Application Security Testing (SAST), Software Composition Analysis (SCA), and container image vulnerability scans prior to deployment.',
        'full_details' => 'Designed to bridge the gap between development speed and security verification. Ingests git commit hooks, runs static linters (PHPStan, Semgrep, Trivy), flags hardcoded secrets, and breaks the build if critical CVEs are detected.',
        'tags' => 'Docker, GitHub Actions, Semgrep, Bash, Python, Trivy, PHP',
        'security_features' => 'Secret scanning, CVE alerting, branch protection gates, automated SBOM generation',
        'live_url' => '#',
        'github_url' => 'https://github.com/example/devsecops-pipeline',
        'icon_badge' => 'git-merge',
        'featured' => 1
    ],
    [
        'id' => 6,
        'title' => 'CloudPulse: Real-Time Server Telemetry & Port Monitor',
        'category' => 'web',
        'description' => 'Interactive infrastructure monitoring dashboard tracking server resource usage, active TCP/UDP ports, network throughput, and potential unauthorized SSH/HTTP brute-force anomalies.',
        'full_details' => 'Built with lightweight vanilla JavaScript visualizations and a secure PHP REST API backend. Provides system administrators with instant visibility into server health, socket connections, and unexpected daemon activity.',
        'tags' => 'JavaScript, CSS Flexbox, PHP REST API, Linux Sysfs, MySQL',
        'security_features' => 'Token-based API authentication, parameter binding, hardened daemon endpoints, audit log tracking',
        'live_url' => '#',
        'github_url' => 'https://github.com/example/cloud-pulse-monitor',
        'icon_badge' => 'activity',
        'featured' => 1
    ]
];

$pdo = getDBConnection();
$projects = [];

if ($pdo !== null) {
    try {
        if ($category !== 'all') {
            $stmt = $pdo->prepare("SELECT * FROM `projects` WHERE `category` = :cat ORDER BY `featured` DESC, `id` ASC");
            $stmt->execute([':cat' => $category]);
        } else {
            $stmt = $pdo->query("SELECT * FROM `projects` ORDER BY `featured` DESC, `id` ASC");
        }
        $projects = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('[PROJECTS DB QUERY ERROR] ' . $e->getMessage());
    }
}

// Fallback to static data if database is empty or not connected
if (empty($projects)) {
    if ($category !== 'all') {
        $projects = array_values(array_filter($fallbackProjects, fn($p) => $p['category'] === $category));
    } else {
        $projects = $fallbackProjects;
    }
}

echo json_encode([
    'success'  => true,
    'category' => $category,
    'total'    => count($projects),
    'projects' => $projects,
    'source'   => $pdo !== null ? 'DATABASE' : 'MEMORY_STORE'
]);
