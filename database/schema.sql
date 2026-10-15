-- =========================================================
-- Database Schema for Web Dev & Cybersecurity Portfolio
-- Compatible with MySQL 5.7+ & MariaDB 10.4+
-- =========================================================

CREATE DATABASE IF NOT EXISTS `portfolio_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `portfolio_db`;

-- 1. Contacts & Inquiries Table
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `is_security_disclosure` TINYINT(1) DEFAULT 0,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Projects & Case Studies Table
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `category` VARCHAR(50) NOT NULL, -- 'web', 'security', 'fullstack'
    `description` TEXT NOT NULL,
    `full_details` TEXT NULL,
    `tags` VARCHAR(255) NOT NULL,
    `security_features` TEXT NULL,
    `live_url` VARCHAR(255) NULL,
    `github_url` VARCHAR(255) NULL,
    `icon_badge` VARCHAR(50) DEFAULT 'shield',
    `featured` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Technical Skills Table
CREATE TABLE IF NOT EXISTS `skills` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(50) NOT NULL, -- 'web', 'security', 'tools'
    `name` VARCHAR(100) NOT NULL,
    `proficiency` INT NOT NULL, -- 0 to 100
    `experience` VARCHAR(50) NOT NULL,
    `icon` VARCHAR(50) NOT NULL,
    `badge_text` VARCHAR(50) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Admin Users Table
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `last_login` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- SEED DATA
-- =========================================================

-- Seed Admin User (Username: admin, Password: Admin@Portfolio2026!)
INSERT INTO `admin_users` (`username`, `password_hash`, `email`)
VALUES ('admin', '$2y$10$9kKMUTV95dNN/NvorKnOt.Y4mCbs4OVVCd5ox8k2uRpR5J80VCFc2', 'admin@example.com')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Seed Projects
INSERT INTO `projects` (`title`, `category`, `description`, `full_details`, `tags`, `security_features`, `live_url`, `github_url`, `icon_badge`, `featured`) VALUES
(
    'SentinelGuard: Custom Web Application Firewall (WAF)',
    'security',
    'A lightweight, real-time reverse-proxy WAF that intercepts HTTP requests, inspects payloads for OWASP Top 10 vectors (SQLi, XSS, Path Traversal), and applies rate limiting with IP reputation scoring.',
    'SentinelGuard acts as an application layer shield. Built with modern PHP 8 and an interactive JS telemetry dashboard, it decodes payload representations, handles regex-based and heuristic payload inspection, and generates real-time alert logs with IP geolocation.',
    'PHP 8, MySQL, RegEx, OWASP Top 10, Security Headers, Vanilla JS',
    'Heuristic SQLi detection, zero-day payload scrubbing, automated IP throttling, CSP Level 3 policy enforcement',
    '#',
    'https://github.com/example/sentinel-guard-waf',
    'shield-check',
    1
),
(
    'Nexus Commerce: Hardened E-Commerce Platform',
    'fullstack',
    'Enterprise-grade full-stack commerce engine built with vanilla frontend aesthetics, robust PHP backend, strict Role-Based Access Control (RBAC), and PCI-DSS compliant checkout simulation.',
    'A modular commerce architecture designed with defensive software engineering from ground zero. All user input is strictly validated via parameterized PDO queries, session hijacking protections are enabled with strict cookie attributes, and audit trails log all sensitive administrative actions.',
    'HTML5, Modern CSS, JavaScript, PHP, MySQL, Stripe API, RBAC',
    'Strict Session Isolation (HttpOnly, SameSite=Strict), Bcrypt hashing with pepper, CSRF tokens on all mutation endpoints',
    '#',
    'https://github.com/example/nexus-commerce',
    'shopping-bag',
    1
),
(
    'VulnHunter: Web Security Header & SSL Analyzer',
    'security',
    'Automated web reconnaissance and audit utility that inspects target domains for missing security headers (HSTS, CSP, X-Frame), evaluates SSL/TLS handshake cipher suites, and outputs remediation advisories.',
    'VulnHunter performs non-intrusive passive web analysis. It crafts custom curl requests to capture HTTP headers, assesses cookie configurations, tests for CORS misconfigurations, and ranks domain security posture on an A+ to F scale.',
    'PHP CLI, cURL, OpenSSL, JavaScript, Chart.js, HTML5',
    'Passive reconnaissance only, strict rate-limited scans, sanitization of domain inputs preventing command injection',
    '#',
    'https://github.com/example/vuln-hunter-scanner',
    'search',
    1
),
(
    'CipherVault: Zero-Knowledge Secret Sharer',
    'web',
    'Client-side encrypted pastebin and credential sharing application where data is encrypted using Web Crypto API (AES-256-GCM) in the browser before ever reaching the server.',
    'The server and MySQL database never see plaintext data or the encryption keys. The decryption key is kept in the URL hash fragment (#), which is never transmitted to the backend server. Includes self-destruct timers and burn-on-read capabilities.',
    'Web Crypto API, AES-GCM, JavaScript, PHP, MySQL, CSS Grid',
    'Zero-knowledge architecture, client-side PBKDF2 key derivation, ephemeral database records with TTL auto-purge',
    '#',
    'https://github.com/example/cipher-vault',
    'lock',
    1
),
(
    'DevSecOps CI/CD Vulnerability Pipeline',
    'security',
    'Automated security testing pipeline integration that conducts Static Application Security Testing (SAST), Software Composition Analysis (SCA), and container image vulnerability scans prior to deployment.',
    'Designed to bridge the gap between development speed and security verification. Ingests git commit hooks, runs static linters (PHPStan, Semgrep, Trivy), flags hardcoded secrets, and breaks the build if critical CVEs are detected.',
    'Docker, GitHub Actions, Semgrep, Bash, Python, Trivy, PHP',
    'Secret scanning, CVE alerting, branch protection gates, automated SBOM (Software Bill of Materials) generation',
    '#',
    'https://github.com/example/devsecops-pipeline',
    'git-merge',
    1
),
(
    'CloudPulse: Real-Time Server Telemetry & Port Monitor',
    'web',
    'Interactive infrastructure monitoring dashboard tracking server resource usage, active TCP/UDP ports, network throughput, and potential unauthorized SSH/HTTP brute-force anomalies.',
    'Built with lightweight vanilla JavaScript visualizations and a secure PHP REST API backend. Provides system administrators with instant visibility into server health, socket connections, and unexpected daemon activity.',
    'JavaScript, CSS Flexbox, PHP REST API, Linux Sysfs, MySQL',
    'Token-based API authentication, parameter binding, hardened daemon endpoints, audit log tracking',
    '#',
    'https://github.com/example/cloud-pulse-monitor',
    'activity',
    1
);

-- Seed Technical Skills
INSERT INTO `skills` (`category`, `name`, `proficiency`, `experience`, `icon`, `badge_text`) VALUES
-- Web Development Skills
('web', 'HTML5 & Modern CSS3', 95, '4+ Years', 'code', 'Frontend'),
('web', 'Vanilla JavaScript (ES6+)', 90, '4+ Years', 'terminal', 'Frontend / DOM'),
('web', 'PHP 8.x (OOP & Secure Patterns)', 92, '3+ Years', 'server', 'Backend'),
('web', 'MySQL & MariaDB Optimization', 88, '3+ Years', 'database', 'Database & Indexing'),
('web', 'RESTful API Design & Integration', 90, '3+ Years', 'cpu', 'Web Services'),
('web', 'Responsive UI & Glassmorphism', 92, '4+ Years', 'layout', 'Design Systems'),

-- Cybersecurity Skills
('security', 'OWASP Top 10 Mitigation', 95, '3+ Years', 'shield-check', 'AppSec'),
('security', 'Web App Penetration Testing', 88, '2+ Years', 'crosshair', 'Offensive Security'),
('security', 'Network Security & Wireshark', 85, '3+ Years', 'wifi', 'SecOps'),
('security', 'Cryptography (AES, RSA, Hashing)', 86, '2+ Years', 'key', 'Security Engineering'),
('security', 'Vulnerability Assessment (Nmap, Burp)', 90, '3+ Years', 'search', 'Ethical Hacking'),
('security', 'Linux Hardening & Bash Scripting', 88, '3+ Years', 'command', 'Systems');
