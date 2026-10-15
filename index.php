<?php
/**
 * Main Portfolio Entry Point
 * Full-Stack Web Development & Cybersecurity Portfolio
 */

declare(strict_types=1);

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/security.php';

// Dispatch defensive HTTP security headers
sendSecurityHeaders();

// Generate or retrieve current CSRF token for forms and AJAX
$csrfToken = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio of a Full-Stack Web Developer and Cybersecurity Specialist. Demonstrating secure web engineering, AppSec, penetration testing, and hardened systems.">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
    <title>CyberDev Portfolio | Web Development & Cybersecurity Specialist</title>

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/terminal.css">
</head>
<body>

    <!-- 1. Top Navigation Bar -->
    <header class="navbar">
        <div class="container nav-inner">
            <a href="#hero" class="brand-logo">
                <span class="brand-shield">🛡️</span>
                <span>CYBER<span class="cyan-text">DEV</span></span>
                <span class="sys-indicator"><span class="pulse-dot"></span> SECURE</span>
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#skills" class="nav-link">Arsenal</a></li>
                <li><a href="#security-demo" class="nav-link">Live Defense</a></li>
                <li><a href="#projects" class="nav-link">Projects</a></li>
                <li><a href="#certifications" class="nav-link">Credentials</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>

            <div class="nav-actions">
                <button type="button" class="btn-terminal-toggle" title="Open Interactive Cyber Terminal">
                    <span>>_</span> <span>CLI</span>
                </button>
                <button type="button" class="burger-btn" id="burgerBtn" aria-label="Toggle navigation menu">
                    ☰
                </button>
            </div>
        </div>
    </header>

    <!-- 2. Hero Section -->
    <section class="hero" id="hero">
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="hero-badge-row">
                    <span class="hero-badge"><span class="cyan-text">●</span> WEB ENGINEERING</span>
                    <span class="hero-badge"><span class="emerald-text">●</span> OFFENSIVE & DEFENSIVE APPSEC</span>
                    <span class="hero-badge"><span class="amber-text">●</span> OWASP TOP 10 HARDENED</span>
                </div>

                <h1 class="hero-title">
                    Building Robust Web Systems, <br>
                    <span class="highlight">Defending Against Exploits</span>
                </h1>

                <div class="typewriter-line">
                    <span>> </span>
                    <span id="typewriterText"></span>
                    <span class="cursor"></span>
                </div>

                <p class="hero-desc">
                    Bridging high-performance full-stack web engineering (HTML5, Modern CSS, JavaScript, PHP, MySQL) with rigorous adversarial cybersecurity. I engineer responsive, scalable web applications and stress-test them to neutralize vulnerabilities before production deployment.
                </p>

                <div class="hero-cta">
                    <a href="#projects" class="btn btn-primary">⚡ Explore Projects</a>
                    <button type="button" class="btn btn-secondary btn-terminal-toggle">🖥️ Interactive CLI</button>
                    <a href="#contact" class="btn btn-secondary">✉️ Encrypted Contact</a>
                </div>

                <div class="stats-ribbon">
                    <div class="stat-item">
                        <div class="stat-val">4+ <span>YRS</span></div>
                        <div class="stat-desc">Full-Stack Dev</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-val">100<span>%</span></div>
                        <div class="stat-desc">Prepared Queries</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-val">50+ <span>FLS</span></div>
                        <div class="stat-desc">Vulns Mitigated</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-val">A+ <span>SSL</span></div>
                        <div class="stat-desc">Header Posture</div>
                    </div>
                </div>
            </div>

            <!-- Radar Cyber Visualizer -->
            <div class="hero-visual">
                <div class="radar-container">
                    <div class="radar-ring ring-1"></div>
                    <div class="radar-ring ring-2"></div>
                    <div class="radar-ring ring-3"></div>
                    <div class="radar-sweep"></div>
                    
                    <div class="radar-center-card">
                        <div class="shield-icon-large">🛡️</div>
                        <h3 style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 0.25rem;">Threat Neutralizer</h3>
                        <p style="font-family: var(--font-mono); font-size: 0.78rem; color: var(--neon-emerald);">
                            [ ACTIVE MONITORING ]
                        </p>
                        <div style="margin-top: 0.75rem; font-family: var(--font-mono); font-size: 0.72rem; color: var(--text-muted); border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.5rem;">
                            <div>SQLi: BLOCKED</div>
                            <div>XSS: ESCAPED</div>
                            <div>CSRF: TOKENIZED</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. About Section (Dual Specialty) -->
    <section class="section" id="about">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">> CORE_SPECIALIZATIONS</span>
                <h2 class="section-title">The Intersection of Code & Security</h2>
                <p class="section-desc">
                    Modern applications cannot treat security as an afterthought. I combine proactive full-stack development with a defensive DevSecOps mindset.
                </p>
            </div>

            <div class="about-grid">
                <!-- Card 1: Web Development -->
                <div class="about-card">
                    <span class="about-icon">💻</span>
                    <h3>Web Engineering & Architecture</h3>
                    <p>
                        Specializing in clean, semantic frontend experiences and resilient, decoupled backend architectures. I design fluid responsive interfaces with Vanilla JS/CSS Grid, and pair them with hardened PHP/MySQL REST APIs with sub-millisecond query execution.
                    </p>
                    <ul class="about-list">
                        <li><span class="bullet">▸</span> Semantic HTML5, Mobile-First CSS3, Glassmorphism</li>
                        <li><span class="bullet">▸</span> Vanilla JavaScript (ES6+), Event-Driven Architecture</li>
                        <li><span class="bullet">▸</span> Modern PHP 8.x (OOP, MVC Principles, Strict Typing)</li>
                        <li><span class="bullet">▸</span> Optimized MySQL/MariaDB Schemas & Indexed Queries</li>
                    </ul>
                </div>

                <!-- Card 2: Cybersecurity -->
                <div class="about-card cyber">
                    <span class="about-icon">🔒</span>
                    <h3>Cybersecurity & Application Defense</h3>
                    <p>
                        Operating with an offensive mindset to build unyielding defenses. From threat modeling and automated SAST/DAST scanning to manual code audits and web app penetration testing, I ensure systems are built resilient against real-world adversaries.
                    </p>
                    <ul class="about-list">
                        <li><span class="bullet" style="color: var(--neon-emerald);">▸</span> OWASP Top 10 Mitigation (SQLi, XSS, CSRF, SSRF, IDOR)</li>
                        <li><span class="bullet" style="color: var(--neon-emerald);">▸</span> Web App Penetration Testing (Burp Suite, OWASP ZAP)</li>
                        <li><span class="bullet" style="color: var(--neon-emerald);">▸</span> Network Reconnaissance & Forensics (Nmap, Wireshark)</li>
                        <li><span class="bullet" style="color: var(--neon-emerald);">▸</span> Applied Cryptography (AES-256-GCM, Bcrypt, Argon2id)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Skills & Arsenal Section -->
    <section class="section" id="skills">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">> TECHNICAL_INVENTORY</span>
                <h2 class="section-title">Skills & Defense Arsenal</h2>
                <p class="section-desc">
                    Comprehensive proficiencies across full-stack engineering and information security domains.
                </p>
            </div>

            <div class="skills-tabs">
                <button type="button" class="tab-btn active" data-category="all">All Disciplines</button>
                <button type="button" class="tab-btn" data-category="web">Web Development</button>
                <button type="button" class="tab-btn" data-category="security">Cybersecurity</button>
            </div>

            <div class="skills-grid" id="skillsGrid">
                <!-- Web Skills -->
                <div class="skill-card" data-category="web">
                    <div class="skill-head">
                        <span class="skill-name">HTML5 & Modern CSS3</span>
                        <span class="skill-badge">Frontend</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="95"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 95%</span>
                        <span>4+ Years</span>
                    </div>
                </div>

                <div class="skill-card" data-category="web">
                    <div class="skill-head">
                        <span class="skill-name">Vanilla JavaScript (ES6+)</span>
                        <span class="skill-badge">Frontend / DOM</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="90"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 90%</span>
                        <span>4+ Years</span>
                    </div>
                </div>

                <div class="skill-card" data-category="web">
                    <div class="skill-head">
                        <span class="skill-name">PHP 8.x (Secure OOP)</span>
                        <span class="skill-badge">Backend</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="92"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 92%</span>
                        <span>3+ Years</span>
                    </div>
                </div>

                <div class="skill-card" data-category="web">
                    <div class="skill-head">
                        <span class="skill-name">MySQL & MariaDB Tuning</span>
                        <span class="skill-badge">Database</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="88"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 88%</span>
                        <span>3+ Years</span>
                    </div>
                </div>

                <!-- Security Skills -->
                <div class="skill-card" data-category="security">
                    <div class="skill-head">
                        <span class="skill-name">OWASP Top 10 Mitigation</span>
                        <span class="skill-badge" style="color:var(--neon-emerald); background:rgba(0,255,157,0.1);">AppSec</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="95"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 95%</span>
                        <span>3+ Years</span>
                    </div>
                </div>

                <div class="skill-card" data-category="security">
                    <div class="skill-head">
                        <span class="skill-name">Web App Penetration Testing</span>
                        <span class="skill-badge" style="color:var(--neon-emerald); background:rgba(0,255,157,0.1);">Offensive</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="88"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 88%</span>
                        <span>2+ Years</span>
                    </div>
                </div>

                <div class="skill-card" data-category="security">
                    <div class="skill-head">
                        <span class="skill-name">Traffic Forensics (Wireshark)</span>
                        <span class="skill-badge" style="color:var(--neon-emerald); background:rgba(0,255,157,0.1);">Network</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="85"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 85%</span>
                        <span>3+ Years</span>
                    </div>
                </div>

                <div class="skill-card" data-category="security">
                    <div class="skill-head">
                        <span class="skill-name">Applied Cryptography</span>
                        <span class="skill-badge" style="color:var(--neon-emerald); background:rgba(0,255,157,0.1);">AES / RSA</span>
                    </div>
                    <div class="skill-bar-bg">
                        <div class="skill-bar-fill" data-percent="86"></div>
                    </div>
                    <div class="skill-footer">
                        <span>Proficiency: 86%</span>
                        <span>2+ Years</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Interactive Live Security Visualizer -->
    <section class="section" id="security-demo">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">> DEFENSIVE_PLAYGROUND</span>
                <h2 class="section-title">Live OWASP Mitigation Lab</h2>
                <p class="section-desc">
                    Explore interactive code comparisons demonstrating how classic vulnerabilities are neutralized in this portfolio and production projects.
                </p>
            </div>

            <div class="sec-demo-container">
                <div class="sec-demo-nav">
                    <button type="button" class="sec-demo-tab active" data-demo="sqli">1. SQL Injection</button>
                    <button type="button" class="sec-demo-tab" data-demo="xss">2. Cross-Site Scripting (XSS)</button>
                    <button type="button" class="sec-demo-tab" data-demo="csrf">3. CSRF Protection</button>
                    <button type="button" class="sec-demo-tab" data-demo="auth">4. Hardened Auth & Hashing</button>
                </div>

                <div id="secDemoContainer">
                    <!-- Hydrated via security-demo.js -->
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Projects & Case Studies -->
    <section class="section" id="projects">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">> SHOWCASE_DIRECTORY</span>
                <h2 class="section-title">Engineered Projects & Security Studies</h2>
                <p class="section-desc">
                    Full-stack web applications and cybersecurity utilities built with performance and security from day zero.
                </p>
            </div>

            <div class="project-filters">
                <button type="button" class="filter-btn active" data-filter="all">All Projects</button>
                <button type="button" class="filter-btn" data-filter="web">Web Apps</button>
                <button type="button" class="filter-btn" data-filter="security">Cybersecurity</button>
                <button type="button" class="filter-btn" data-filter="fullstack">Full-Stack</button>
            </div>

            <div class="projects-grid" id="projectsGrid">
                <!-- Project 1 -->
                <div class="project-card" data-category="security"
                     data-details="SentinelGuard acts as an application layer shield. Built with modern PHP 8 and an interactive JS telemetry dashboard, it decodes payload representations, handles regex-based and heuristic payload inspection, and generates real-time alert logs with IP geolocation."
                     data-tags="PHP 8, MySQL, RegEx, OWASP Top 10, Security Headers, Vanilla JS"
                     data-security="Heuristic SQLi detection, zero-day payload scrubbing, automated IP throttling, CSP Level 3 policy enforcement"
                     data-github="https://github.com">
                    <span class="project-category-badge security">Cybersecurity</span>
                    <div class="project-icon">🛡️</div>
                    <h3 class="project-title">SentinelGuard: Custom WAF</h3>
                    <p class="project-desc">
                        A lightweight reverse-proxy Web Application Firewall that intercepts HTTP requests, inspects payloads for OWASP Top 10 vectors, and applies rate limiting with IP reputation scoring.
                    </p>
                    <div class="project-security-box">
                        <span>🛡️ Hardened:</span> Heuristic SQLi filter, IP throttling, CSP Level 3
                    </div>
                    <div class="project-tags">
                        <span class="tag-pill">PHP 8</span>
                        <span class="tag-pill">MySQL</span>
                        <span class="tag-pill">OWASP Top 10</span>
                        <span class="tag-pill">Vanilla JS</span>
                    </div>
                    <div class="project-actions">
                        <button type="button" class="btn-project btn-project-modal">Case Study ↗</button>
                        <a href="https://github.com" target="_blank" class="btn-project btn-project-code">GitHub Repo</a>
                    </div>
                </div>

                <!-- Project 2 -->
                <div class="project-card" data-category="fullstack"
                     data-details="A modular commerce architecture designed with defensive software engineering from ground zero. All user input is strictly validated via parameterized PDO queries, session hijacking protections are enabled with strict cookie attributes, and audit trails log all sensitive administrative actions."
                     data-tags="HTML5, Modern CSS, JavaScript, PHP, MySQL, Stripe API, RBAC"
                     data-security="Strict Session Isolation (HttpOnly, SameSite=Strict), Bcrypt hashing with pepper, CSRF tokens on all mutation endpoints"
                     data-github="https://github.com">
                    <span class="project-category-badge fullstack">Full-Stack</span>
                    <div class="project-icon">🛍️</div>
                    <h3 class="project-title">Nexus Commerce Engine</h3>
                    <p class="project-desc">
                        Enterprise-grade commerce engine built with vanilla frontend aesthetics, robust PHP backend, strict Role-Based Access Control (RBAC), and PCI-DSS compliant checkout simulation.
                    </p>
                    <div class="project-security-box">
                        <span>🛡️ Hardened:</span> Strict Session Isolation, Bcrypt, Tokenized CSRF
                    </div>
                    <div class="project-tags">
                        <span class="tag-pill">JavaScript</span>
                        <span class="tag-pill">PHP 8</span>
                        <span class="tag-pill">MySQL</span>
                        <span class="tag-pill">RBAC</span>
                    </div>
                    <div class="project-actions">
                        <button type="button" class="btn-project btn-project-modal">Case Study ↗</button>
                        <a href="https://github.com" target="_blank" class="btn-project btn-project-code">GitHub Repo</a>
                    </div>
                </div>

                <!-- Project 3 -->
                <div class="project-card" data-category="security"
                     data-details="VulnHunter performs non-intrusive passive web analysis. It crafts custom curl requests to capture HTTP headers, assesses cookie configurations, tests for CORS misconfigurations, and ranks domain security posture on an A+ to F scale."
                     data-tags="PHP CLI, cURL, OpenSSL, JavaScript, Chart.js, HTML5"
                     data-security="Passive reconnaissance only, strict rate-limited scans, sanitization of domain inputs preventing command injection"
                     data-github="https://github.com">
                    <span class="project-category-badge security">Cybersecurity</span>
                    <div class="project-icon">🔍</div>
                    <h3 class="project-title">VulnHunter: Header & SSL Scanner</h3>
                    <p class="project-desc">
                        Automated web reconnaissance and audit utility that inspects target domains for missing security headers (HSTS, CSP, X-Frame), evaluates SSL/TLS cipher suites, and outputs remediation advisories.
                    </p>
                    <div class="project-security-box">
                        <span>🛡️ Hardened:</span> Command injection defense, strict scan throttling
                    </div>
                    <div class="project-tags">
                        <span class="tag-pill">PHP CLI</span>
                        <span class="tag-pill">cURL</span>
                        <span class="tag-pill">OpenSSL</span>
                        <span class="tag-pill">CSS Grid</span>
                    </div>
                    <div class="project-actions">
                        <button type="button" class="btn-project btn-project-modal">Case Study ↗</button>
                        <a href="https://github.com" target="_blank" class="btn-project btn-project-code">GitHub Repo</a>
                    </div>
                </div>

                <!-- Project 4 -->
                <div class="project-card" data-category="web"
                     data-details="The server and MySQL database never see plaintext data or the encryption keys. The decryption key is kept in the URL hash fragment (#), which is never transmitted to the backend server. Includes self-destruct timers and burn-on-read capabilities."
                     data-tags="Web Crypto API, AES-GCM, JavaScript, PHP, MySQL, CSS Grid"
                     data-security="Zero-knowledge architecture, client-side PBKDF2 key derivation, ephemeral database records with TTL auto-purge"
                     data-github="https://github.com">
                    <span class="project-category-badge web">Web App</span>
                    <div class="project-icon">🔐</div>
                    <h3 class="project-title">CipherVault: Secret Sharer</h3>
                    <p class="project-desc">
                        Client-side encrypted pastebin where data is encrypted using Web Crypto API (AES-256-GCM) in the browser before ever reaching the server. Zero-knowledge architecture with burn-on-read timers.
                    </p>
                    <div class="project-security-box">
                        <span>🛡️ Hardened:</span> Zero-knowledge, in-browser PBKDF2, self-destruct TTL
                    </div>
                    <div class="project-tags">
                        <span class="tag-pill">Web Crypto API</span>
                        <span class="tag-pill">AES-GCM</span>
                        <span class="tag-pill">PHP/MySQL</span>
                    </div>
                    <div class="project-actions">
                        <button type="button" class="btn-project btn-project-modal">Case Study ↗</button>
                        <a href="https://github.com" target="_blank" class="btn-project btn-project-code">GitHub Repo</a>
                    </div>
                </div>

                <!-- Project 5 -->
                <div class="project-card" data-category="security"
                     data-details="Designed to bridge the gap between development speed and security verification. Ingests git commit hooks, runs static linters (PHPStan, Semgrep, Trivy), flags hardcoded secrets, and breaks the build if critical CVEs are detected."
                     data-tags="Docker, GitHub Actions, Semgrep, Bash, Python, Trivy, PHP"
                     data-security="Secret scanning, CVE alerting, branch protection gates, automated SBOM generation"
                     data-github="https://github.com">
                    <span class="project-category-badge security">Cybersecurity</span>
                    <div class="project-icon">🔀</div>
                    <h3 class="project-title">DevSecOps Vulnerability Pipeline</h3>
                    <p class="project-desc">
                        Automated CI/CD security pipeline integration that conducts SAST, Software Composition Analysis (SCA), and container image vulnerability scans prior to deployment.
                    </p>
                    <div class="project-security-box">
                        <span>🛡️ Hardened:</span> Automated secret scanning, policy enforcement gates
                    </div>
                    <div class="project-tags">
                        <span class="tag-pill">GitHub Actions</span>
                        <span class="tag-pill">Semgrep</span>
                        <span class="tag-pill">Trivy</span>
                        <span class="tag-pill">Docker</span>
                    </div>
                    <div class="project-actions">
                        <button type="button" class="btn-project btn-project-modal">Case Study ↗</button>
                        <a href="https://github.com" target="_blank" class="btn-project btn-project-code">GitHub Repo</a>
                    </div>
                </div>

                <!-- Project 6 -->
                <div class="project-card" data-category="web"
                     data-details="Built with lightweight vanilla JavaScript visualizations and a secure PHP REST API backend. Provides system administrators with instant visibility into server health, socket connections, and unexpected daemon activity."
                     data-tags="JavaScript, CSS Flexbox, PHP REST API, Linux Sysfs, MySQL"
                     data-security="Token-based API authentication, parameter binding, hardened daemon endpoints, audit log tracking"
                     data-github="https://github.com">
                    <span class="project-category-badge web">Web App</span>
                    <div class="project-icon">📈</div>
                    <h3 class="project-title">CloudPulse: Port & Telemetry Monitor</h3>
                    <p class="project-desc">
                        Interactive infrastructure dashboard tracking real-time server load, active TCP/UDP ports, network throughput, and potential unauthorized SSH/HTTP brute-force anomalies.
                    </p>
                    <div class="project-security-box">
                        <span>🛡️ Hardened:</span> Authenticated API tokens, parameter binding, audit logs
                    </div>
                    <div class="project-tags">
                        <span class="tag-pill">JavaScript</span>
                        <span class="tag-pill">PHP API</span>
                        <span class="tag-pill">MySQL</span>
                        <span class="tag-pill">SysOps</span>
                    </div>
                    <div class="project-actions">
                        <button type="button" class="btn-project btn-project-modal">Case Study ↗</button>
                        <a href="https://github.com" target="_blank" class="btn-project btn-project-code">GitHub Repo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Certifications & Badges -->
    <section class="section" id="certifications">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">> INDUSTRY_CREDENTIALS</span>
                <h2 class="section-title">Certifications & Accreditations</h2>
                <p class="section-desc">
                    Recognized industry credentials in cybersecurity, cloud infrastructure, and software engineering.
                </p>
            </div>

            <div class="certs-grid">
                <div class="cert-card">
                    <div class="cert-badge-icon">🎖️</div>
                    <h3 class="cert-title">CompTIA Security+</h3>
                    <div class="cert-issuer">CompTIA International</div>
                    <div class="cert-status">● VERIFIED CREDENTIAL</div>
                </div>

                <div class="cert-card">
                    <div class="cert-badge-icon">⚔️</div>
                    <h3 class="cert-title">Certified Ethical Hacker</h3>
                    <div class="cert-issuer">EC-Council / eJPT Equivalent</div>
                    <div class="cert-status">● VERIFIED CREDENTIAL</div>
                </div>

                <div class="cert-card">
                    <div class="cert-badge-icon">☁️</div>
                    <h3 class="cert-title">AWS Certified Cloud Practitioner</h3>
                    <div class="cert-issuer">Amazon Web Services</div>
                    <div class="cert-status">● VERIFIED CREDENTIAL</div>
                </div>

                <div class="cert-card">
                    <div class="cert-badge-icon">🎯</div>
                    <h3 class="cert-title">TryHackMe / HTB Top 2%</h3>
                    <div class="cert-issuer">Capture The Flag Platforms</div>
                    <div class="cert-status">● ACTIVE RANKING</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Contact & Secure Dispatcher -->
    <section class="section" id="contact">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">> DISPATCH_INTERFACE</span>
                <h2 class="section-title">Transmit a Secure Inquiry</h2>
                <p class="section-desc">
                    Have a web project in mind, need a security vulnerability audit, or want to discuss full-stack engineering? Send a secure message below.
                </p>
            </div>

            <div class="contact-grid">
                <!-- Left: Information & PGP -->
                <div class="contact-info">
                    <div class="contact-info-card">
                        <div class="contact-info-title">
                            <span>📡 DIRECT CHANNELS</span>
                        </div>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.75rem;">
                            Available for select full-stack web development projects, application security audits, and security consulting engagements.
                        </p>
                        <div style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--text-light); display: flex; flex-direction: column; gap: 0.4rem;">
                            <div>Email: <a href="mailto:dev.security@example.com" style="color: var(--neon-cyan); text-decoration: none;">dev.security@example.com</a></div>
                            <div>Location: Remote / Worldwide</div>
                            <div>Response Time: &lt; 24 Hours SLA</div>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="contact-info-title">
                            <span>🔑 PGP PUBLIC KEY FINGERPRINT</span>
                        </div>
                        <div class="pgp-key-box">
                            4A8F 9021 B73C D104 88FE<br>
                            E219 55C0 398A 2C1D 44E5
                        </div>
                        <div style="margin-top: 0.5rem; font-family: var(--font-mono); font-size: 0.75rem; color: var(--neon-emerald);">
                            [✓] 4096-bit RSA Signature Verified
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="contact-info-title">
                            <span>🛡️ DEFENSIVE PROTOCOLS</span>
                        </div>
                        <p style="color: var(--text-muted); font-size: 0.82rem; line-height: 1.5;">
                            All transmissions through this form are protected by active CSRF session tokens, strict rate limiting, input sanitization, and parameterized storage.
                        </p>
                    </div>
                </div>

                <!-- Right: Secure Form -->
                <div class="contact-form-card">
                    <form id="contactForm" novalidate autocomplete="off">
                        <!-- CSRF Token Hidden Input -->
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

                        <!-- Honeypot Bot Trap (Invisible to real users) -->
                        <input type="text" name="website_url_honeypot" id="websiteHoneypot" class="honeypot-trap" tabindex="-1" autocomplete="off">

                        <div class="form-row">
                            <div class="form-field">
                                <label for="contactName">> SENDER_NAME *</label>
                                <input type="text" id="contactName" name="name" class="form-input" placeholder="e.g. Alex Hunter" required>
                            </div>

                            <div class="form-field">
                                <label for="contactEmail">> SENDER_EMAIL *</label>
                                <input type="email" id="contactEmail" name="email" class="form-input" placeholder="alex@company.com" required>
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="contactSubject">> SUBJECT_HEADER *</label>
                            <input type="text" id="contactSubject" name="subject" class="form-input" placeholder="Web Development / Security Audit Inquiry" required>
                        </div>

                        <div class="form-field">
                            <label for="contactMessage">> MESSAGE_PAYLOAD *</label>
                            <textarea id="contactMessage" name="message" class="form-textarea" rows="5" placeholder="Detail your project specifications or security objectives..." required></textarea>
                        </div>

                        <div class="form-field">
                            <label class="form-checkbox-label">
                                <input type="checkbox" id="contactDisclosure" name="is_security_disclosure" value="1">
                                <span>⚠️ Flag as Vulnerability / Security Disclosure</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; border: none;">
                            <span>⚡ DISPATCH SECURE TRANSMISSION</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Case Study Modal -->
    <div class="modal-backdrop" id="projectModal" role="dialog" aria-modal="true">
        <div class="modal-dialog">
            <button type="button" class="modal-close" id="modalClose" aria-label="Close modal">✕</button>
            <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--neon-cyan); text-transform: uppercase; margin-bottom: 0.5rem;">
                CASE_STUDY_DEEP_DIVE
            </div>
            <h3 id="modalTitle" style="font-size: 1.4rem; color: var(--text-light); margin-bottom: 1rem;">Project Overview</h3>
            <p id="modalDetails" style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;"></p>
            
            <div style="margin-bottom: 1.25rem;">
                <h4 style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--neon-emerald); margin-bottom: 0.4rem;">
                    🛡️ Security & Defensive Architectural Highlights:
                </h4>
                <div id="modalSecurity" style="background: rgba(8, 11, 17, 0.7); border-left: 3px solid var(--neon-emerald); padding: 0.75rem; font-family: var(--font-mono); font-size: 0.85rem; color: var(--text-light); border-radius: 0 4px 4px 0;"></div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                    Tech Stack Arsenal:
                </h4>
                <div id="modalTags" style="display: flex; flex-wrap: wrap; gap: 0.4rem;"></div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <a id="modalGithub" href="#" target="_blank" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
                    Inspect Code on GitHub ↗
                </a>
            </div>
        </div>
    </div>

    <!-- 10. Interactive Terminal Window Overlay -->
    <div class="terminal-overlay" id="terminalOverlay">
        <div class="terminal-header">
            <div class="terminal-title">
                <span>🖥️ SEC-OPS TERMINAL v2.4</span>
                <span style="font-size: 0.7rem; color: var(--neon-emerald);">[SESSION: ENCRYPTED]</span>
            </div>
            <div class="terminal-controls">
                <button type="button" class="terminal-btn btn-term-min" title="Minimize"></button>
                <button type="button" class="terminal-btn btn-term-max" title="Maximize"></button>
                <button type="button" class="terminal-btn btn-term-close" title="Close"></button>
            </div>
        </div>

        <div class="terminal-body" id="termOutput">
            <div class="term-banner">
  _____       _                _____             
 / ____|     | |              |  __ \            
| |    _   _ | |__    ___ _ __| |  | | _____   __
| |   | | | || '_ \  / _ \ '__| |  | |/ _ \ \ / /
| |___| |_| || |_) ||  __/ |  | |__| |  __/\ V / 
 \_____\__, ||_.__/  \___|_|  |_____/ \___| \_/  
        __/ |                                    
       |___/   [Web Dev & Cybersecurity CLI]     
            </div>
            <div class="term-line term-info">Type <span class="term-success">help</span> to list available commands.</div>
            <div class="term-line term-dim">Try commands like: <span class="term-warning">whoami</span>, <span class="term-warning">skills</span>, <span class="term-warning">projects</span>, <span class="term-warning">scan localhost</span>, or <span class="term-warning">cat flag.txt</span></div>
        </div>

        <div class="terminal-quick-cmds">
            <span class="quick-cmd-tag" data-cmd="help">help</span>
            <span class="quick-cmd-tag" data-cmd="whoami">whoami</span>
            <span class="quick-cmd-tag" data-cmd="skills">skills</span>
            <span class="quick-cmd-tag" data-cmd="projects">projects</span>
            <span class="quick-cmd-tag" data-cmd="scan localhost">scan</span>
            <span class="quick-cmd-tag" data-cmd="cat flag.txt">cat flag.txt</span>
            <span class="quick-cmd-tag" data-cmd="clear">clear</span>
        </div>

        <div class="term-prompt-line" style="padding: 0.5rem 1rem; background: rgba(15, 22, 38, 0.95); border-top: 1px solid rgba(0, 243, 255, 0.15);">
            <span class="term-prompt">guest@cyber-portfolio:~$</span>
            <input type="text" id="termInput" class="term-input" placeholder="type a command..." autocomplete="off" spellcheck="false">
        </div>
    </div>

    <!-- 11. Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="brand-logo" style="margin-bottom: 0.75rem;">
                        <span class="brand-shield">🛡️</span>
                        <span>CYBER<span class="cyan-text">DEV</span></span>
                    </div>
                    <p class="footer-desc">
                        Engineering secure, responsive web applications with robust PHP/MySQL architectures and rigorous cybersecurity practices.
                    </p>
                </div>

                <div>
                    <div class="footer-col-title">Navigation</div>
                    <ul class="footer-links">
                        <li><a href="#about">About & Philosophy</a></li>
                        <li><a href="#skills">Skills & Arsenal</a></li>
                        <li><a href="#security-demo">Live OWASP Demo</a></li>
                        <li><a href="#projects">Projects Showcase</a></li>
                        <li><a href="#contact">Contact & PGP</a></li>
                    </ul>
                </div>

                <div>
                    <div class="footer-col-title">Control & Links</div>
                    <ul class="footer-links">
                        <li><a href="https://github.com" target="_blank">GitHub Repository ↗</a></li>
                        <li><a href="https://linkedin.com" target="_blank">LinkedIn Profile ↗</a></li>
                        <li><a href="admin/login.php" style="color: var(--neon-amber);">🔐 SOC Admin Portal</a></li>
                        <li><a href="database/schema.sql" download style="color: var(--neon-emerald);">📥 database.sql Schema</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    &copy; <?= date('Y'); ?> CyberDev Portfolio. All Rights Reserved. Built with HTML5, CSS3, JS, PHP & MySQL.
                </div>
                <div>
                    <span style="color: var(--neon-emerald);">●</span> Zero Known Vulnerabilities • Hardened via OWASP Guidelines
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/terminal.js"></script>
    <script src="assets/js/security-demo.js"></script>
</body>
</html>
