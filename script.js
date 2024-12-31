document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    // Mobile Menu Toggle
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuLinks = document.querySelectorAll('.mobile-menu a');
    document.getElementById('copyright-year').textContent = new Date().getFullYear();

    // Toggle menu when hamburger is clicked
    menuBtn.addEventListener('click', () => {
        menuBtn.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    // Close menu when a link is clicked
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', () => {
            menuBtn.classList.remove('active'); // Reset hamburger icon
            mobileMenu.classList.remove('active'); // Hide menu
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
            menuBtn.classList.remove('active');
            mobileMenu.classList.remove('active');
        }
    });

    // Particles Background
    particlesJS('particles-js', {
        particles: {
            number: {
                value: 80,
                density: {
                    enable: true,
                    value_area: 800
                }
            },
            color: {
                value: '#6C63FF'
            },
            shape: {
                type: 'circle'
            },
            opacity: {
                value: 0.5,
                random: false
            },
            size: {
                value: 3,
                random: true
            },
            line_linked: {
                enable: true,
                distance: 150,
                color: '#6C63FF',
                opacity: 0.4,
                width: 1
            },
            move: {
                enable: true,
                speed: 6,
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
                onhover: {
                    enable: true,
                    mode: 'repulse'
                },
                onclick: {
                    enable: true,
                    mode: 'push'
                },
                resize: true
            }
        },
        retina_detect: true
    });

    // Enhanced Scroll Animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '50px'
    };

    const animateOnScroll = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add appropriate animation class based on data attribute
                const animationType = entry.target.dataset.animation || 'fade-in-up';
                entry.target.classList.add(animationType);
                animateOnScroll.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Select elements to animate
    const elementsToAnimate = document.querySelectorAll('[data-animation]');
    elementsToAnimate.forEach(el => {
        animateOnScroll.observe(el);
    });

    // Counter Animation
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.round(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        observer.observe(counter);
    });

    // Form Validation and Submission
    const contactForm = document.querySelector('.contact-form');
    const newsletterForm = document.querySelector('.newsletter-form');

    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        // Add form validation and submission logic here
        alert('Thank you for your message! We will get back to you soon.');
        contactForm.reset();
    });

    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        // Add newsletter subscription logic here
        alert('Thank you for subscribing to our newsletter!');
        newsletterForm.reset();
    });

    // Initialize Map
    const mapOptions = {
        center: { lat: 5.6037, lng: -0.1870 }, // Accra coordinates
        zoom: 15
    };

    const map = new google.maps.Map(document.getElementById('map'), mapOptions);
    const marker = new google.maps.Marker({
        position: mapOptions.center,
        map: map,
        title: 'EleTech Pro'
    });

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                // Close mobile menu if open
                mobileMenu.classList.remove('active');
            }
        });
    });

    // Initialize Swiper
    const swiper = new Swiper('.swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });

    // Get all sections that we want to track
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-links a');

    function updateActiveLink() {
        let currentSection = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= (sectionTop - sectionHeight / 3)) {
                currentSection = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').substring(1) === currentSection) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', updateActiveLink);
    updateActiveLink(); // Initial call to set the active link on page load
});

document.addEventListener('DOMContentLoaded', function() {
    const carouselTrack = document.querySelector('.carousel-track');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const founderCards = document.querySelectorAll('.founder-card');
    const cardWidth = founderCards[0].offsetWidth + 20; // Width + margin
    let currentIndex = 0;
    let autoScrollInterval;

    // Clone the first few cards and append them to the end for seamless looping
    const firstFewCards = Array.from(founderCards).slice(0, 3); // Clone the first 3 cards
    firstFewCards.forEach(card => {
        const clone = card.cloneNode(true);
        carouselTrack.appendChild(clone);
    });

    // Function to move to the next card
    function nextCard() {
        if (currentIndex < founderCards.length - 1) {
            currentIndex++;
        } else {
            // If it's the last card, instantly reset to the first card without animation
            carouselTrack.style.transition = 'none';
            carouselTrack.style.transform = `translateX(0)`;
            // Force a reflow to apply the reset without animation
            void carouselTrack.offsetWidth;
            // Restore the transition and move to the first card
            carouselTrack.style.transition = 'transform 0.5s ease-in-out';
            currentIndex = 0;
        }
        carouselTrack.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }

    // Function to move to the previous card
    function prevCard() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            // If it's the first card, instantly reset to the last card without animation
            carouselTrack.style.transition = 'none';
            carouselTrack.style.transform = `translateX(-${(founderCards.length - 1) * cardWidth}px)`;
            // Force a reflow to apply the reset without animation
            void carouselTrack.offsetWidth;
            // Restore the transition and move to the last card
            carouselTrack.style.transition = 'transform 0.5s ease-in-out';
            currentIndex = founderCards.length - 1;
        }
        carouselTrack.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }

    // Auto-scroll functionality
    function startAutoScroll() {
        autoScrollInterval = setInterval(nextCard, 3000); // Change card every 3 seconds
    }

    function stopAutoScroll() {
        clearInterval(autoScrollInterval);
    }

    // Event listeners for manual navigation
    nextBtn.addEventListener('click', () => {
        stopAutoScroll();
        nextCard();
        startAutoScroll();
    });

    prevBtn.addEventListener('click', () => {
        stopAutoScroll();
        prevCard();
        startAutoScroll();
    });

    // Start auto-scroll on page load
    startAutoScroll();

    // Pause auto-scroll on hover
    carouselTrack.addEventListener('mouseenter', stopAutoScroll);
    carouselTrack.addEventListener('mouseleave', startAutoScroll);
});