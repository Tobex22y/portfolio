/**
 * Interactive Cyber Terminal Simulator
 * Emulates a Linux / SecOps environment in browser
 */

document.addEventListener('DOMContentLoaded', () => {
    const terminalOverlay = document.getElementById('terminalOverlay');
    const termInput = document.getElementById('termInput');
    const termOutput = document.getElementById('termOutput');
    const toggleButtons = document.querySelectorAll('.btn-terminal-toggle');
    const closeBtn = document.querySelector('.btn-term-close');
    const minBtn = document.querySelector('.btn-term-min');
    const maxBtn = document.querySelector('.btn-term-max');
    const quickCmdTags = document.querySelectorAll('.quick-cmd-tag');

    if (!terminalOverlay || !termInput || !termOutput) return;

    let commandHistory = [];
    let historyIndex = -1;

    // Toggle terminal visibility
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            terminalOverlay.classList.toggle('open');
            if (terminalOverlay.classList.contains('open')) {
                termInput.focus();
            }
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            terminalOverlay.classList.remove('open');
        });
    }

    if (minBtn) {
        minBtn.addEventListener('click', () => {
            terminalOverlay.classList.toggle('minimized');
        });
    }

    if (maxBtn) {
        maxBtn.addEventListener('click', () => {
            terminalOverlay.style.width = terminalOverlay.style.width === '95vw' ? '620px' : '95vw';
            terminalOverlay.style.height = terminalOverlay.style.height === '85vh' ? '440px' : '85vh';
        });
    }

    // Quick Command buttons
    quickCmdTags.forEach(tag => {
        tag.addEventListener('click', () => {
            const cmd = tag.getAttribute('data-cmd');
            if (cmd) {
                termInput.value = cmd;
                handleCommand(cmd);
                termInput.value = '';
            }
        });
    });

    // Command Input Handling
    termInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const command = termInput.value.trim();
            if (command) {
                commandHistory.push(command);
                historyIndex = commandHistory.length;
            }
            handleCommand(command);
            termInput.value = '';
            termOutput.scrollTop = termOutput.scrollHeight;
        } else if (e.key === 'ArrowUp') {
            if (commandHistory.length > 0 && historyIndex > 0) {
                historyIndex--;
                termInput.value = commandHistory[historyIndex];
            }
            e.preventDefault();
        } else if (e.key === 'ArrowDown') {
            if (historyIndex < commandHistory.length - 1) {
                historyIndex++;
                termInput.value = commandHistory[historyIndex];
            } else {
                historyIndex = commandHistory.length;
                termInput.value = '';
            }
            e.preventDefault();
        }
    });

    function printLine(text, className = '') {
        const line = document.createElement('div');
        line.className = `term-line ${className}`;
        line.innerHTML = text;
        termOutput.appendChild(line);
        termOutput.scrollTop = termOutput.scrollHeight;
    }

    function handleCommand(cmdRaw) {
        const clean = cmdRaw.trim();
        printLine(`<span class="term-prompt">guest@cyber-sec:~$</span> ${escapeHtml(clean)}`);

        if (!clean) return;

        const parts = clean.split(' ');
        const mainCmd = parts[0].toLowerCase();
        const arg = parts.slice(1).join(' ');

        switch (mainCmd) {
            case 'help':
                printLine(`Available Commands:`, 'term-info');
                printLine(`  <span class="term-success">whoami</span>    - Display professional bio & security profile`);
                printLine(`  <span class="term-success">skills</span>    - List core engineering & AppSec skills`);
                printLine(`  <span class="term-success">projects</span>  - Show deployed projects & case studies`);
                printLine(`  <span class="term-success">scan &lt;url&gt;</span> - Run simulated vulnerability & port scan`);
                printLine(`  <span class="term-success">contact</span>   - Get encrypted contact details`);
                printLine(`  <span class="term-success">uptime</span>    - View system status and defense telemetry`);
                printLine(`  <span class="term-success">date</span>      - Show local server timestamp`);
                printLine(`  <span class="term-success">cat &lt;file&gt;</span>- Read file contents (try: <span class="term-warning">cat flag.txt</span>)`);
                printLine(`  <span class="term-success">clear</span>     - Clear terminal buffer`);
                break;

            case 'whoami':
                printLine(`[+] Role: Full-Stack Web Developer & Cybersecurity Engineer`, 'term-info');
                printLine(`[+] Specialty: Secure Web Architecture, Hardened APIs, OWASP Top 10 Defense`);
                printLine(`[+] Mindset: "Building high-performance software with an adversarial defense-in-depth posture."`);
                break;

            case 'skills':
                printLine(`[+] TECHNICAL ARSENAL:`, 'term-info');
                printLine(`  Frontend  : HTML5, Modern CSS3, JavaScript (ES6+), Responsive UI [95%]`);
                printLine(`  Backend   : PHP 8+, MySQL/MariaDB, PDO, REST APIs, JSON [92%]`);
                printLine(`  AppSec    : OWASP Top 10, Penetration Testing, Code Audit [95%]`);
                printLine(`  Tools     : Burp Suite, Wireshark, Nmap, Git, Linux, Docker [90%]`);
                break;

            case 'projects':
                printLine(`[+] FEATURED PROJECTS:`, 'term-info');
                printLine(`  1. <span class="term-info">SentinelGuard</span> - Reverse-Proxy Web Application Firewall (PHP/MySQL)`);
                printLine(`  2. <span class="term-info">Nexus Commerce</span> - Hardened Full-Stack E-Commerce Engine (RBAC)`);
                printLine(`  3. <span class="term-info">VulnHunter</span> - Security Header & SSL/TLS Cipher Analyzer`);
                printLine(`  4. <span class="term-info">CipherVault</span> - Zero-Knowledge Secret Sharer (Web Crypto API)`);
                printLine(`Type or click 'whoami' or scroll down to the Projects section for full code & demos.`);
                break;

            case 'scan':
                const target = arg || 'localhost';
                printLine(`[*] Initiating non-intrusive scan against target: <span class="term-warning">${escapeHtml(target)}</span>...`, 'term-info');
                setTimeout(() => {
                    printLine(`[+] Resolving DNS... OK [IPv4 verified]`);
                }, 300);
                setTimeout(() => {
                    printLine(`[+] Checking Port 80 (HTTP) ... OPEN [Status: 301 Redirect to HTTPS]`);
                    printLine(`[+] Checking Port 443 (HTTPS) ... OPEN [TLS 1.3 / Strict-Transport-Security ACTIVE]`);
                }, 700);
                setTimeout(() => {
                    printLine(`[+] Checking Security Headers:`, 'term-success');
                    printLine(`    ✓ Content-Security-Policy: ENFORCED`);
                    printLine(`    ✓ X-Frame-Options: SAMEORIGIN`);
                    printLine(`    ✓ X-Content-Type-Options: nosniff`);
                    printLine(`    ✓ Anti-CSRF Token Validation: ACTIVE`);
                    printLine(`[✓] Scan Complete: 0 Critical Vulnerabilities Detected. Posture: HARDENED (Grade A+)`, 'term-success');
                }, 1200);
                break;

            case 'contact':
                printLine(`[+] SECURE DISPATCH CHANNELS:`, 'term-info');
                printLine(`  Email: dev.security@example.com`);
                printLine(`  GitHub: https://github.com`);
                printLine(`  PGP Key ID: 0x9B8A2C1D (RSA 4096-bit)`);
                printLine(`  Encrypted form available below in the Contact section.`);
                break;

            case 'cat':
                if (arg === 'flag.txt') {
                    printLine(`🏁 CTF FLAG FOUND:`, 'term-warning');
                    printLine(`<span class="term-success" style="font-size: 1.1em; font-weight: bold;">FLAG{s3cur3_c0d1ng_m33ts_d3f3ns1v3_3ng1n33r1ng}</span>`);
                    printLine(`Congratulations, ethical hacker! Mention this flag in your inquiry message for priority review.`);
                } else if (arg) {
                    printLine(`cat: ${escapeHtml(arg)}: Permission denied or file not in guest sandbox.`, 'term-error');
                } else {
                    printLine(`Usage: cat &lt;filename&gt;`, 'term-dim');
                }
                break;

            case 'sudo':
                printLine(`[!] Nice attempt! This incident will be reported to /var/log/soc_audit.log.`, 'term-error');
                break;

            case 'uptime':
                printLine(`17:58:00 up 99.98% uptime, 1 active user, load average: 0.04, 0.08, 0.05`, 'term-info');
                printLine(`Firewall: Active | Fail2ban: 0 banned IPs | WAF: Nominal`, 'term-success');
                break;

            case 'date':
                printLine(new Date().toUTCString(), 'term-dim');
                break;

            case 'clear':
                termOutput.innerHTML = '';
                break;

            default:
                printLine(`bash: command not found: ${escapeHtml(clean)}. Type <span class="term-info">help</span> for commands.`, 'term-error');
                break;
        }
    }

    function escapeHtml(str) {
        return str.replace(/[&<>"']/g, (m) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[m]));
    }
});
