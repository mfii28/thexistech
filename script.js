/**
 * script.js — Thexis Tech
 * Handles UI interactions, AOS animations, particles, swiper carousel, dynamic counters, and contact AJAX.
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // ── Update Copyright Year ──────────────────────────────────────────────────
    const yearEl = document.getElementById('copyright-year');
    if (yearEl) {
        yearEl.textContent = new Date().getFullYear();
    }

    // ── AOS (Animate On Scroll) Initialization ────────────────────────────────
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    }

    // ── Mobile Menu Toggle ────────────────────────────────────────────────────
    const menuBtn    = document.querySelector('.mobile-menu-btn');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (menuBtn && mobileMenu) {
        const toggleMenu = () => {
            const isOpen = menuBtn.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            menuBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        };

        menuBtn.addEventListener('click', toggleMenu);

        // Close menu when a link inside mobile menu is clicked
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menuBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
                menuBtn.setAttribute('aria-expanded', 'false');
            });
        });

        // Close menu when clicking anywhere outside
        document.addEventListener('click', (e) => {
            if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                menuBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
                menuBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ── Particles Background ──────────────────────────────────────────────────
    const particlesContainer = document.getElementById('particles-js');
    if (particlesContainer && typeof particlesJS !== 'undefined') {
        particlesJS('particles-js', {
            particles: {
                number: { value: 70, density: { enable: true, value_area: 800 } },
                color: { value: '#6C63FF' },
                shape: { type: 'circle' },
                opacity: { value: 0.4, random: false },
                size: { value: 3, random: true },
                line_linked: {
                    enable: true,
                    distance: 140,
                    color: '#6C63FF',
                    opacity: 0.35,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 5,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'repulse' },
                    onclick:  { enable: true, mode: 'push' },
                    resize: true
                }
            },
            retina_detect: true
        });
    }

    // ── Animated Stat Counters with Suffix ────────────────────────────────────
    const counters = document.querySelectorAll('.counter');
    if (counters.length > 0 && 'IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                const counter = entry.target;
                const target  = parseInt(counter.getAttribute('data-target'), 10) || 0;
                const suffix  = counter.getAttribute('data-suffix') || '';
                const duration = 2000;
                const startTime = performance.now();

                const updateCount = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    // Ease-out quad
                    const easeProgress = 1 - (1 - progress) * (1 - progress);
                    const currentVal = Math.floor(easeProgress * target);

                    counter.textContent = currentVal + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.textContent = target + suffix;
                    }
                };

                requestAnimationFrame(updateCount);
                observer.unobserve(counter);
            });
        }, { threshold: 0.25 });

        counters.forEach(c => counterObserver.observe(c));
    }

    // ── Active Navigation Link Highlighting on Scroll ─────────────────────────
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-links a, .mobile-menu a');

    const updateActiveNav = () => {
        let currentId = '';
        const scrollPosition = window.scrollY + 120;

        sections.forEach(sec => {
            const top = sec.offsetTop;
            const height = sec.offsetHeight;
            if (scrollPosition >= top && scrollPosition < top + height) {
                currentId = sec.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === '#' + currentId) {
                link.classList.add('active');
            } else if (href.startsWith('#')) {
                link.classList.remove('active');
            }
        });
    };

    window.addEventListener('scroll', updateActiveNav, { passive: true });
    updateActiveNav();

    // ── Smooth Scroll for Anchor Links ────────────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#' || targetId === '') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                const headerOffset = 80;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ── Swiper Carousel for Founders / Team ───────────────────────────────────
    if (typeof Swiper !== 'undefined' && document.querySelector('.founders-swiper')) {
        new Swiper('.founders-swiper', {
            slidesPerView: 1,
            slidesPerGroup: 1,
            spaceBetween: 25,
            loop: true,
            grabCursor: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                480:  { slidesPerView: 1.5, spaceBetween: 20 },
                640:  { slidesPerView: 2,   spaceBetween: 25 },
                1024: { slidesPerView: 3,   spaceBetween: 30 },
                1280: { slidesPerView: 4,   spaceBetween: 30 },
            },
        });
    }

    // ── Contact Form AJAX Handling ────────────────────────────────────────────
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const statusEl  = document.getElementById('form-status');
            const submitBtn = document.getElementById('submit-btn');
            const btnText   = submitBtn.querySelector('.btn-text');
            const btnLoad   = submitBtn.querySelector('.btn-loading');

            // Reset status
            statusEl.style.display = 'none';
            statusEl.className = 'form-alert';

            // Client-side quick validation
            const name    = (document.getElementById('name')?.value || '').trim();
            const email   = (document.getElementById('email')?.value || '').trim();
            const subject = (document.getElementById('subject')?.value || '').trim();
            const message = (document.getElementById('message')?.value || '').trim();

            if (!name || !email || !subject || !message) {
                statusEl.style.display = 'block';
                statusEl.classList.add('form-alert--error');
                statusEl.textContent = 'Please fill out all required fields.';
                return;
            }

            // Set Loading state
            submitBtn.disabled = true;
            if (btnText) btnText.style.display = 'none';
            if (btnLoad) btnLoad.style.display = 'inline-block';

            try {
                const formData = new FormData(contactForm);
                const response = await fetch('contact-handler.php', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                statusEl.style.display = 'block';

                if (response.ok && data.success) {
                    statusEl.classList.add('form-alert--success');
                    statusEl.textContent = data.message || 'Thank you! Your message has been sent successfully.';
                    contactForm.reset();
                } else {
                    statusEl.classList.add('form-alert--error');
                    statusEl.textContent = data.message || 'Failed to send message. Please try again.';
                }
            } catch (err) {
                statusEl.style.display = 'block';
                statusEl.classList.add('form-alert--error');
                statusEl.textContent = 'Connection error. Please try again or email us directly at Thexistech@gmail.com.';
            } finally {
                submitBtn.disabled = false;
                if (btnText) btnText.style.display = 'inline-block';
                if (btnLoad) btnLoad.style.display = 'none';
            }
        });
    }

    // ── Newsletter Form Handling ──────────────────────────────────────────────
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const status = document.getElementById('newsletter-status');
            if (status) {
                status.style.display = 'block';
                status.textContent = 'Thank you for subscribing to our updates!';
            }
            newsletterForm.reset();
            setTimeout(() => {
                if (status) status.style.display = 'none';
            }, 5000);
        });
    }

});
