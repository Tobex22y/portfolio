<?php
/**
 * Skills API Endpoint
 * Returns grouped technical skills for Web Development & Cybersecurity
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/security.php';

sendSecurityHeaders();
header('Content-Type: application/json; charset=utf-8');

$fallbackSkills = [
    'web' => [
        ['name' => 'HTML5 & Modern CSS3', 'proficiency' => 95, 'experience' => '4+ Years', 'icon' => 'code', 'badge' => 'Frontend'],
        ['name' => 'Vanilla JavaScript (ES6+)', 'proficiency' => 90, 'experience' => '4+ Years', 'icon' => 'terminal', 'badge' => 'Core Scripting'],
        ['name' => 'PHP 8.x (OOP, MVC, Security)', 'proficiency' => 92, 'experience' => '3+ Years', 'icon' => 'server', 'badge' => 'Backend Engineering'],
        ['name' => 'MySQL & MariaDB Optimization', 'proficiency' => 88, 'experience' => '3+ Years', 'icon' => 'database', 'badge' => 'Relational DB & Indexes'],
        ['name' => 'RESTful APIs & Microservices', 'proficiency' => 90, 'experience' => '3+ Years', 'icon' => 'cpu', 'badge' => 'Data Exchange'],
        ['name' => 'Responsive UI & Design Systems', 'proficiency' => 92, 'experience' => '4+ Years', 'icon' => 'layout', 'badge' => 'UI / UX']
    ],
    'security' => [
        ['name' => 'OWASP Top 10 Mitigation', 'proficiency' => 95, 'experience' => '3+ Years', 'icon' => 'shield-check', 'badge' => 'Application Security'],
        ['name' => 'Web App Penetration Testing', 'proficiency' => 88, 'experience' => '2+ Years', 'icon' => 'crosshair', 'badge' => 'Offensive Security'],
        ['name' => 'Network Packet Analysis (Wireshark)', 'proficiency' => 85, 'experience' => '3+ Years', 'icon' => 'wifi', 'badge' => 'Traffic Forensics'],
        ['name' => 'Applied Cryptography & Hashing', 'proficiency' => 86, 'experience' => '2+ Years', 'icon' => 'key', 'badge' => 'Encryption (AES/RSA)'],
        ['name' => 'Vulnerability Scanning (Nmap, Burp)', 'proficiency' => 90, 'experience' => '3+ Years', 'icon' => 'search', 'badge' => 'Recon & Auditing'],
        ['name' => 'Linux System Hardening & Bash', 'proficiency' => 88, 'experience' => '3+ Years', 'icon' => 'command', 'badge' => 'Host Defense']
    ]
];

$pdo = getDBConnection();
$skills = [];

if ($pdo !== null) {
    try {
        $stmt = $pdo->query("SELECT * FROM `skills` ORDER BY `id` ASC");
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $cat = $row['category'];
            $skills[$cat][] = [
                'name'        => $row['name'],
                'proficiency' => (int)$row['proficiency'],
                'experience'  => $row['experience'],
                'icon'        => $row['icon'],
                'badge'       => $row['badge_text']
            ];
        }
    } catch (PDOException $e) {
        error_log('[SKILLS DB QUERY ERROR] ' . $e->getMessage());
    }
}

if (empty($skills)) {
    $skills = $fallbackSkills;
}

echo json_encode([
    'success' => true,
    'skills'  => $skills,
    'source'  => $pdo !== null ? 'DATABASE' : 'MEMORY_STORE'
]);
