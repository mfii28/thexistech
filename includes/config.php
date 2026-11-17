<?php
/**
 * includes/config.php
 * Global configuration, email settings, and database loaders for Thexis Tech.
 */

// Report errors in development, keep clean in production
error_reporting(E_ALL);
ini_set('display_errors', '0');

// ─── Site & Mail Settings ───────────────────────────────────────────────────
define('SITE_NAME', 'Thexis Tech');
define('SITE_DOMAIN', $_SERVER['HTTP_HOST'] ?? 'thexistech.com');

/**
 * EMAIL CONFIGURATION FOR CPANEL:
 * - MAIL_RECIPIENT: The email where contact form submissions will be sent.
 * - MAIL_SENDER: The "From" email address. In cPanel, this MUST match your cPanel domain
 *   (e.g., info@thexistech.com or no-reply@thexistech.com) to prevent emails from going to spam.
 * - MAIL_DRIVER: 'mail' (recommended for cPanel native mail) or 'smtp'.
 */
define('MAIL_RECIPIENT', 'Thexistech@gmail.com');
define('MAIL_SENDER', 'no-reply@' . preg_replace('/^www\./', '', SITE_DOMAIN));
define('MAIL_SENDER_NAME', 'Thexis Tech Contact Form');
define('MAIL_DRIVER', 'mail'); // Options: 'mail' or 'smtp'

// Optional SMTP settings (only used if MAIL_DRIVER === 'smtp')
define('SMTP_HOST', 'mail.' . preg_replace('/^www\./', '', SITE_DOMAIN));
define('SMTP_PORT', 465); // 465 (SSL) or 587 (TLS)
define('SMTP_USER', 'no-reply@' . preg_replace('/^www\./', '', SITE_DOMAIN));
define('SMTP_PASS', '');
define('SMTP_SECURE', 'ssl'); // 'ssl' or 'tls'

// ─── JSON Database Loader ───────────────────────────────────────────────────
/**
 * Load a JSON file from the data/ directory.
 * Results are cached in memory during script execution.
 *
 * @param string $key Name of the file without .json (e.g. 'site', 'services', 'team')
 * @param array $default Default value if file not found or invalid
 * @return array
 */
function get_db(string $key, array $default = []): array {
    static $cache = [];
    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $filepath = dirname(__DIR__) . '/data/' . $key . '.json';
    if (!file_exists($filepath)) {
        return $default;
    }

    $content = file_get_contents($filepath);

    // Strip comments (/* ... */ and //) and trailing commas to allow commenting in JSON
    $clean_content = preg_replace('!/\*.*?\*/!s', '', $content);
    $clean_content = preg_replace('!^\s*//.*$!m', '', $clean_content);
    $clean_content = preg_replace('/,\s*([\]\}])/', '$1', $clean_content);

    $data = json_decode($clean_content, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        return $default;
    }

    $cache[$key] = $data;
    return $data;
}

// ─── Helper Functions ───────────────────────────────────────────────────────
/**
 * Escape HTML output securely.
 */
function e(?string $str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Resolve a team member's photo or generate a clean SVG/avatar fallback.
 */
function get_team_image(string $name, ?string $filename): string {
    if (!empty($filename)) {
        $local_path = dirname(__DIR__) . '/images/team/' . $filename;
        if (file_exists($local_path)) {
            return 'images/team/' . rawurlencode($filename);
        }
    }
    // High-quality fallback avatar
    return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=6C63FF&color=ffffff&size=300&font-size=0.38';
}

/**
 * Pre-load main database files for convenience.
 */
$site_db     = get_db('site');
$services_db = get_db('services');
$team_db     = get_db('team');
$projects_db = get_db('projects');
$about_db    = get_db('about');
