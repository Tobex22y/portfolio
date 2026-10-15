# Full-Stack Web Development & Cybersecurity Portfolio

A modern, production-grade portfolio website engineered specifically to showcase dual mastery in **Full-Stack Web Development** and **Cybersecurity / Application Security (AppSec)**.

Built with **HTML5**, **Modern CSS3**, **Vanilla JavaScript (ES6+)**, **PHP 8+**, and **MySQL / MariaDB**.

---

## 🛡️ Key Features & Architectural Highlights

### 1. Dual Specialization Showcase
- **Web Development**: Semantic markup, responsive design system, dynamic DOM manipulation, REST APIs, OOP PHP patterns, and optimized database indexing.
- **Cybersecurity & AppSec**: Active defenses against OWASP Top 10 vulnerabilities (SQLi, XSS, CSRF, Session Fixation, Brute Force), security header enforcement, and Zero-Trust principles.

### 2. Interactive Components
- **Cyber Terminal Simulator (`>_ CLI`)**: An interactive, draggable/collapsible terminal with commands (`help`, `whoami`, `skills`, `projects`, `scan`, `contact`, `cat flag.txt`, `clear`) and command history navigation.
- **Live OWASP Mitigation Lab**: An interactive side-by-side visualizer demonstrating common web attack vectors (SQL Injection, XSS, CSRF, Weak Hashing) and the exact defensive PHP code patterns that neutralize them.
- **Dynamic Case Study Modal**: In-depth architecture, security highlights, and GitHub repository links for each showcased project.
- **Filterable Portfolio**: Filter by *All Projects*, *Web Apps*, *Cybersecurity*, or *Full-Stack*.

### 3. Fortified Backend (PHP + MySQL)
- **Prepared Statements via PDO**: Guaranteed immunity from SQL Injection (`PDO::ATTR_EMULATE_PREPARES => false`).
- **Cryptographic Anti-CSRF Protection**: Unpredictable 256-bit random tokens verified with constant-time `hash_equals()`.
- **In-Session Rate Limiting**: Throttles contact submissions and admin login brute-force attempts.
- **Spam Honeypot**: Silently traps automated form bots without disrupting legitimate user experience.
- **Security Headers Enforced**: Strict `Content-Security-Policy`, `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, and `Referrer-Policy`.
- **SOC Admin Portal**: A secure dashboard (`admin/login.php`) protected with password hashing (`PASSWORD_BCRYPT`) where you can inspect incoming inquiries, review security vulnerability disclosures, and see visitor IP traces.

---

## 📁 Project Directory Structure

```
portfolio/
├── index.php                 # Master portfolio entry (HTML5 + dynamic PHP headers)
├── README.md                 # Complete documentation & quickstart guide
├── config/
│   ├── db.php                # PDO MySQL connection with graceful fallback
│   └── security.php          # CSRF, security headers, rate limiting, sanitization
├── api/
│   ├── contact.php           # Secure contact form API (POST + CSRF + PDO)
│   ├── projects.php          # Dynamic projects JSON endpoint
│   ├── skills.php            # Dynamic skills JSON endpoint
│   └── csrf-token.php        # Provides fresh session CSRF token
├── admin/
│   ├── login.php             # Admin authentication portal (Rate limited)
│   ├── index.php             # SOC dashboard & message management
│   └── logout.php            # Session termination & cookie purge
├── assets/
│   ├── css/
│   │   ├── style.css         # Cyber-clean dark theme, variables, typography
│   │   └── terminal.css      # Terminal emulator overlay styling
│   └── js/
│       ├── main.js           # Navigation, typewriter, filtering, modal, contact AJAX
│       ├── terminal.js       # Terminal CLI emulator logic & commands
│       └── security-demo.js  # Live OWASP vulnerability & defense visualizer
└── database/
    └── schema.sql            # MySQL table definitions and seed data
```

---

## 🚀 Quick Start Guide

### Option 1: Instant Launch via PHP Built-in Server (Recommended for quick testing)

1. Open PowerShell / Command Prompt in this folder:
   ```powershell
   cd C:\Users\HP\Desktop\portfolio
   ```

2. Start the PHP server using XAMPP's PHP:
   ```powershell
   & "C:\xampp\php\php.exe" -S localhost:8000
   ```

3. Open your browser and navigate to:
   - **Portfolio Website**: [http://localhost:8000](http://localhost:8000)
   - **Admin Portal**: [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)

*(Note: The portfolio includes built-in memory fallbacks, so it works smoothly even before the database is imported!)*

---

### Option 2: Full XAMPP Apache & MariaDB/MySQL Setup

1. **Start Apache & MySQL**:
   - Open **XAMPP Control Panel** (`C:\xampp\xampp-control.exe`).
   - Click **Start** next to **Apache** and **MySQL**.

2. **Import Database**:
   - Open phpMyAdmin in your browser: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Click the **Import** tab.
   - Choose the file: `C:\Users\HP\Desktop\portfolio\database\schema.sql`.
   - Click **Go** (or execute the SQL script in MySQL Workbench / CLI).
   - Alternatively via Command Prompt:
     ```powershell
     & "C:\xampp\mysql\bin\mysql.exe" -u root < "C:\Users\HP\Desktop\portfolio\database\schema.sql"
     ```

3. **Symlink or Copy to `htdocs`**:
   - Create a symbolic link from `C:\xampp\htdocs\portfolio` pointing to this directory:
     ```powershell
     New-Item -ItemType SymbolicLink -Path "C:\xampp\htdocs\portfolio" -Target "C:\Users\HP\Desktop\portfolio"
     ```
   - Then access the portfolio at: [http://localhost/portfolio/](http://localhost/portfolio/)

---

## 🔐 Admin Credentials

A pre-seeded administrator account is included in `database/schema.sql` (and configured in fallback mode):

- **URL**: `http://localhost:8000/admin/login.php` (or `http://localhost/portfolio/admin/login.php`)
- **Username**: `admin`
- **Password**: `Admin@Portfolio2026!`

In the admin dashboard, you can:
- View real-time incoming contact submissions.
- Filter between standard inquiries and high-priority **Security Disclosures**.
- Inspect client IP addresses and user agents safely.
- Mark messages as reviewed or delete them.

---

## ⚙️ Customization Guide

- **Personal Information & Bio**: Edit the Hero, About, and Contact sections in `index.php`.
- **Projects & Case Studies**: Modify or add rows in `database/schema.sql` or customize the fallback list in `api/projects.php`.
- **Skills & Proficiencies**: Update the skills in `index.php` or `api/skills.php`.
- **Database Connection**: Edit credentials in `config/db.php` if your MySQL username, host, or password differs from the standard XAMPP defaults (`localhost`, `root`, blank password).

---

## 📜 License
MIT License. Feel free to use and adapt this portfolio template for your career, job applications, and security consulting showcases!
