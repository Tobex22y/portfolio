/**
 * Main Application Script
 * Orchestrates animations, navigation, dynamic filtering, modals, and AJAX contact handling
 */

document.addEventListener('DOMContentLoaded', () => {
    initTypewriter();
    initMobileNav();
    initScrollSpy();
    initProjectFiltering();
    initProjectModal();
    initSkillsTabs();
    initContactForm();
});

/**
 * Typewriter effect for hero title specialties
 */
function initTypewriter() {
    const target = document.getElementById('typewriterText');
    if (!target) return;

    const phrases = [
        'Full-Stack Web Developer',
        'Cybersecurity Specialist',
        'Ethical Hacker & AppSec Auditor',
        'DevSecOps & Cloud Builder'
    ];

    let phraseIdx = 0;
    let charIdx = 0;
    let isDeleting = false;
    let typeSpeed = 90;

    function type() {
        const current = phrases[phraseIdx];
        
        if (isDeleting) {
            target.textContent = current.substring(0, charIdx - 1);
            charIdx--;
            typeSpeed = 40;
        } else {
            target.textContent = current.substring(0, charIdx + 1);
            charIdx++;
            typeSpeed = 90;
        }

        if (!isDeleting && charIdx === current.length) {
            isDeleting = true;
            typeSpeed = 1800; // Pause at full word
        } else if (isDeleting && charIdx === 0) {
            isDeleting = false;
            phraseIdx = (phraseIdx + 1) % phrases.length;
            typeSpeed = 400;
        }

        setTimeout(type, typeSpeed);
    }

    type();
}

/**
 * Mobile navigation menu toggle
 */
function initMobileNav() {
    const burgerBtn = document.getElementById('burgerBtn');
    const navLinks = document.getElementById('navLinks');

    if (burgerBtn && navLinks) {
        burgerBtn.addEventListener('click', () => {
            navLinks.classList.toggle('mobile-open');
        });

        navLinks.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('mobile-open');
            });
        });
    }
}

/**
 * Scroll spy for highlighting active navigation links
 */
function initScrollSpy() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        let current = '';
        const scrollY = window.pageYOffset;

        sections.forEach(section => {
            const sectionHeight = section.offsetHeight;
            const sectionTop = section.offsetTop - 100;
            const sectionId = section.getAttribute('id');

            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                current = sectionId;
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });
}

/**
 * Project category filtering (All, Web, Security, Fullstack)
 */
function initProjectFiltering() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');

            projectCards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    card.style.display = 'flex';
                    card.style.animation = 'fadeIn 0.4s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

/**
 * Project modal dialog preview
 */
function initProjectModal() {
    const modalBackdrop = document.getElementById('projectModal');
    const modalClose = document.getElementById('modalClose');
    const modalTitle = document.getElementById('modalTitle');
    const modalDetails = document.getElementById('modalDetails');
    const modalTags = document.getElementById('modalTags');
    const modalSecurity = document.getElementById('modalSecurity');
    const modalGithub = document.getElementById('modalGithub');
    const modalTriggers = document.querySelectorAll('.btn-project-modal');

    if (!modalBackdrop) return;

    modalTriggers.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const card = btn.closest('.project-card');
            if (!card) return;

            const title = card.querySelector('.project-title')?.textContent || '';
            const details = card.getAttribute('data-details') || card.querySelector('.project-desc')?.textContent || '';
            const tags = card.getAttribute('data-tags') || '';
            const secNotes = card.getAttribute('data-security') || '';
            const githubUrl = card.getAttribute('data-github') || '#';

            if (modalTitle) modalTitle.textContent = title;
            if (modalDetails) modalDetails.textContent = details;
            if (modalSecurity) modalSecurity.textContent = secNotes;
            if (modalGithub) modalGithub.href = githubUrl;

            if (modalTags) {
                modalTags.innerHTML = '';
                tags.split(',').forEach(tag => {
                    const pill = document.createElement('span');
                    pill.className = 'tag-pill';
                    pill.textContent = tag.trim();
                    modalTags.appendChild(pill);
                });
            }

            modalBackdrop.classList.add('active');
        });
    });

    if (modalClose) {
        modalClose.addEventListener('click', () => {
            modalBackdrop.classList.remove('active');
        });
    }

    modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) {
            modalBackdrop.classList.remove('active');
        }
    });
}

/**
 * Skills category tabs switcher
 */
function initSkillsTabs() {
    const tabs = document.querySelectorAll('.skills-tabs .tab-btn');
    const skillCards = document.querySelectorAll('.skill-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const category = tab.getAttribute('data-category');

            skillCards.forEach(card => {
                const cardCat = card.getAttribute('data-category');
                if (category === 'all' || cardCat === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Animate skill progress bars when in viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const fills = entry.target.querySelectorAll('.skill-bar-fill');
                fills.forEach(fill => {
                    const percent = fill.getAttribute('data-percent') || '85';
                    fill.style.width = `${percent}%`;
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    const skillsSection = document.getElementById('skills');
    if (skillsSection) {
        observer.observe(skillsSection);
    }
}

/**
 * Contact form submission via AJAX with CSRF & real-time feedback
 */
function initContactForm() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const csrfInput = form.querySelector('input[name="csrf_token"]');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = form.querySelector('#contactName')?.value.trim();
        const email = form.querySelector('#contactEmail')?.value.trim();
        const subject = form.querySelector('#contactSubject')?.value.trim();
        const message = form.querySelector('#contactMessage')?.value.trim();
        const isDisclosure = form.querySelector('#contactDisclosure')?.checked ? 1 : 0;
        const honeypot = form.querySelector('#websiteHoneypot')?.value;

        // Basic Client-Side Validation
        if (!name || name.length < 2) {
            showToast('Please provide your name (at least 2 characters).', true);
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            showToast('Please provide a valid email address.', true);
            return;
        }

        if (!subject || subject.length < 2) {
            showToast('Please specify an inquiry subject.', true);
            return;
        }

        if (!message || message.length < 10) {
            showToast('Message body must contain at least 10 characters.', true);
            return;
        }

        // Disable button during transit
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>⚡ ENCRYPTING & TRANSMITTING...</span>';

        const payload = {
            name: name,
            email: email,
            subject: subject,
            message: message,
            is_security_disclosure: isDisclosure,
            website_url_honeypot: honeypot,
            csrf_token: csrfInput?.value || ''
        };

        try {
            const response = await fetch('api/contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showToast(`✓ ${data.message}`);
                form.reset();
                if (data.new_csrf && csrfInput) {
                    csrfInput.value = data.new_csrf;
                }
            } else {
                showToast(data.error || 'Transmission failed. Please verify fields and try again.', true);
                if (data.new_csrf && csrfInput) {
                    csrfInput.value = data.new_csrf;
                }
            }
        } catch (err) {
            console.error('Submission error:', err);
            showToast('Network error: Unable to contact endpoint. Check connection.', true);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
}

/**
 * Toast notifications
 */
function showToast(message, isError = false) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast ${isError ? 'error' : ''}`;
    toast.innerHTML = `<span>${isError ? '⚠️' : '🛡️'}</span> <div>${escapeHtml(message)}</div>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 50);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 4500);
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
