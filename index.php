<?php
/**
 * index.php — Thexis Tech
 * Dynamically rendered from JSON data files (data/*.json) using clean PHP loops and components.
 * Optimized for cPanel shared hosting and fast performance.
 */

require_once __DIR__ . '/includes/config.php';

// Flash messages from server-side fallback
$is_sent  = isset($_GET['sent']) && $_GET['sent'] === '1';
$is_error = isset($_GET['error']) && $_GET['error'] === '1';
$err_msg  = isset($_GET['msg']) ? urldecode($_GET['msg']) : 'An error occurred while sending your message. Please try again.';

// Include Header
include __DIR__ . '/includes/header.php';
?>

    <!-- ═══════════════════════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════════════════════════ -->
    <section id="home" class="hero">
        <div id="particles-js"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <div class="hero-badge" data-aos="fade-up">
                        <span class="badge-dot"></span> <?= e($site_db['hero']['subtitle'] ?? 'One-Stop Solution For All Your Business Needs') ?>
                    </div>
                    <h1 class="title" data-aos="fade-up" data-aos-delay="150">
                        <span class="title-line">
                            Creating <span class="highlight"><?= e($site_db['hero']['title_main'] ?? 'Softwares') ?></span>
                        </span>
                        <span class="title-line">
                            <?= e($site_db['hero']['title_sub'] ?? '& Digital Excellence') ?>
                        </span>
                    </h1>
                    <div class="cta-buttons" data-aos="fade-up" data-aos-delay="300">
                        <a href="<?= e($site_db['hero']['cta_link'] ?? '#projects') ?>" class="primary-btn">
                            <?= e($site_db['hero']['cta_text'] ?? 'Explore Our Work') ?>
                        </a>
                        <a href="#contact" class="secondary-btn">
                            Contact Us
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="<?= e($site_db['hero']['image'] ?? 'images/asset4.png') ?>" 
                         alt="<?= e($site_db['name']) ?> Collaborative Team" 
                         class="main-image"
                         loading="eager">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         SERVICES SECTION (Mapped from data/services.json)
    ═══════════════════════════════════════════════════════════════════════════ -->
    <section id="services" class="services">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Elevating Your Digital Presence</h2>
                <p class="section-subtitle">Through Exceptional Services &amp; Innovative Engineering</p>
            </div>

            <div class="services-grid">
                <?php foreach ($services_db as $service): ?>
                    <div class="service-card" data-aos="fade-up" data-aos-delay="<?= (int)($service['delay'] ?? 0) ?>">
                        <div class="service-content">
                            <div class="service-icon">
                                <i class="<?= e($service['icon'] ?? 'fas fa-cog') ?>"></i>
                            </div>
                            <h3><?= e($service['title']) ?></h3>
                            <p class="service-desc"><?= e($service['desc']) ?></p>
                            
                            <?php if (!empty($service['features'])): ?>
                                <ul class="service-list">
                                    <?php foreach ($service['features'] as $feature): ?>
                                        <li><?= e($feature) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <a href="<?= e($service['link'] ?? '#contact') ?>" class="learn-more">Get Started →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         ABOUT SECTION (Mapped from data/about.json)
    ═══════════════════════════════════════════════════════════════════════════ -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text" data-aos="fade-right">
                    
                    <?php if (!empty($about_db['sections'])): ?>
                        <?php foreach ($about_db['sections'] as $sec): ?>
                            <h2 class="section-title"><?= e($sec['title']) ?></h2>
                            <p><?= e($sec['content']) ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Animated Stats Counters (Dynamic loop) -->
                    <?php if (!empty($about_db['stats'])): ?>
                        <div class="stats">
                            <?php foreach ($about_db['stats'] as $stat): ?>
                                <div class="stat">
                                    <h3 class="counter" data-target="<?= (int)$stat['target'] ?>" data-suffix="<?= e($stat['suffix'] ?? '') ?>">0</h3>
                                    <p><?= e($stat['label']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="about-image" data-aos="fade-left">
                    <img src="<?= e($about_db['image'] ?? 'images/asset1.png') ?>" 
                         alt="<?= e($about_db['image_alt'] ?? 'About Thexis Tech') ?>"
                         loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         TEAM / FOUNDERS SECTION (Mapped from data/team.json)
    ═══════════════════════════════════════════════════════════════════════════ -->
    <section id="founders" class="founders">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Our Team</h2>
                <p class="section-subtitle">Meet the visionary minds shaping digital transformation</p>
            </div>

            <!-- Swiper Carousel -->
            <div class="swiper founders-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($team_db as $member): 
                        $img_src = get_team_image($member['name'], $member['img'] ?? null);
                    ?>
                        <div class="swiper-slide founder-card" data-aos="fade-up">
                            <div class="founder-image">
                                <img src="<?= e($img_src) ?>" 
                                     alt="<?= e($member['name']) ?>" 
                                     loading="lazy">
                                <div class="founder-info">
                                    <h3><?= e($member['name']) ?></h3>
                                    <p class="founder-role"><?= e($member['role']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Swiper Controls -->
                <div class="swiper-button-next" aria-label="Next Slide"></div>
                <div class="swiper-button-prev" aria-label="Previous Slide"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         PROJECTS SECTION (Mapped from data/projects.json)
    ═══════════════════════════════════════════════════════════════════════════ -->
    <section id="projects" class="projects">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Featured Projects</h2>
                <p class="section-subtitle">Explore some of our recent engineering and digital solutions</p>
            </div>

            <div class="project-grid">
                <?php foreach ($projects_db as $index => $project): ?>
                    <div class="project-card" data-aos="fade-up" data-aos-delay="<?= $index * 150 ?>">
                        <a href="<?= e($project['url']) ?>" 
                           target="<?= str_starts_with($project['url'], '#') ? '_self' : '_blank' ?>" 
                           rel="noopener noreferrer"
                           title="<?= e($project['title']) ?>">
                            <img src="<?= e($project['image']) ?>" alt="<?= e($project['title']) ?>" loading="lazy">
                            <div class="project-info">
                                <h3><?= e($project['title']) ?></h3>
                                <p><?= e($project['category']) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════════════════
         CONTACT SECTION (Integrated with contact-handler.php)
    ═══════════════════════════════════════════════════════════════════════════ -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Get in Touch</h2>
                <p class="section-subtitle">Have a project in mind? Let's discuss how we can build it together.</p>
            </div>

            <div class="contact-content">
                
                <!-- Contact Information Cards -->
                <div class="contact-info" data-aos="fade-right">
                    
                    <!-- Location Card -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <h3>Our Location</h3>
                        </div>
                        <p><?= e($site_db['contact']['location'] ?? 'Accra, Ghana') ?></p>
                    </div>

                    <!-- Email Card -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <h3>Email Us</h3>
                        </div>
                        <p>
                            <a href="mailto:<?= e($site_db['contact']['email']) ?>">
                                <?= e($site_db['contact']['email']) ?>
                            </a>
                        </p>
                    </div>

                    <!-- Phone Card -->
                    <div class="info-card">
                        <div class="info-header">
                            <div class="icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <h3>Call Us</h3>
                        </div>
                        <p>
                            <a href="tel:<?= e($site_db['contact']['phone_raw'] ?? '') ?>">
                                <?= e($site_db['contact']['phone']) ?>
                            </a>
                        </p>
                    </div>

                </div>

                <!-- Contact Form (Direct AJAX submission to contact-handler.php) -->
                <form class="contact-form" id="contact-form" action="contact-handler.php" method="POST" data-aos="fade-left">
                    
                    <!-- Flash message container (for non-JS or JS fallback) -->
                    <?php if ($is_sent): ?>
                        <div class="form-alert form-alert--success">
                            Thank you! Your message has been sent successfully. We will be in touch soon.
                        </div>
                    <?php elseif ($is_error): ?>
                        <div class="form-alert form-alert--error">
                            <?= e($err_msg) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Dynamic AJAX response message container -->
                    <div id="form-status" class="form-alert" style="display:none;" role="alert"></div>

                    <!-- Honeypot anti-spam field (hidden from human visitors) -->
                    <div style="display:none !important;" aria-hidden="true">
                        <label for="_gotcha_field">Do not fill this field</label>
                        <input type="text" id="_gotcha_field" name="_gotcha" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder=" " required minlength="2">
                            <label for="name">Full Name</label>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder=" " required>
                            <label for="email">Email Address</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" id="subject" name="subject" placeholder=" " required minlength="3">
                        <label for="subject">Subject</label>
                    </div>

                    <div class="form-group">
                        <textarea id="message" name="message" rows="5" placeholder=" " required minlength="10"></textarea>
                        <label for="message">Your Message</label>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn">
                        <span class="btn-text">Send Message</span>
                        <span class="btn-loading" style="display:none;">
                            <i class="fas fa-spinner fa-spin"></i> Sending...
                        </span>
                    </button>
                </form>

            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
