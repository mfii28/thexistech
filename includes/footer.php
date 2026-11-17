<?php
/**
 * includes/footer.php
 * Main footer partial with dynamic quick links, social media icons, and script assets.
 */
require_once __DIR__ . '/config.php';

$nav_items = $site_db['navigation'] ?? [];
$socials   = $site_db['socials'] ?? [];
?>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                
                <!-- Company Info -->
                <div class="footer-info">
                    <h3><?= e($site_db['name'] ?? 'Thexis Tech') ?></h3>
                    <p><?= e($site_db['tagline'] ?? 'Creating digital excellence in Africa') ?></p>
                </div>

                <!-- Quick Navigation Links (Dynamic loop) -->
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <?php foreach ($nav_items as $item): ?>
                        <a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
                    <?php endforeach; ?>
                </div>

                <!-- Social Media Channels (Dynamic loop) -->
                <div class="footer-social">
                    <h4>Follow Us</h4>
                    <div class="social-icons">
                        <?php foreach ($socials as $social): ?>
                            <a href="<?= e($social['url']) ?>" 
                               class="social-icon" 
                               aria-label="<?= e($social['name']) ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="<?= e($social['name']) ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <?= $social['svg'] ?>
                                </svg>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Newsletter Subscription -->
                <div class="footer-newsletter">
                    <h4>Newsletter</h4>
                    <form class="newsletter-form" id="newsletter-form">
                        <input type="email" name="newsletter_email" placeholder="Enter your email" required aria-label="Email Address">
                        <button type="submit">Subscribe</button>
                    </form>
                    <div id="newsletter-status" style="display:none; margin-top:8px; font-size:13px; color:#10b981;"></div>
                </div>

            </div>

            <!-- Copyright Bar -->
            <div class="footer-bottom">
                <p>&copy; <span id="copyright-year"><?= date('Y') ?></span> <?= e($site_db['name'] ?? 'Thexis Tech') ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Optimized Vendor Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Main Application Script -->
    <script src="script.js"></script>
</body>
</html>
