<?php
/**
 * includes/header.php
 * Main header partial with dynamic navigation and meta tags loaded from JSON db.
 */
require_once __DIR__ . '/config.php';

$page_title = $page_title ?? ($site_db['name'] . ' - ' . $site_db['tagline']);
$page_desc  = $page_desc  ?? ($site_db['description'] ?? '');
$nav_items  = $site_db['navigation'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="<?= e($page_desc) ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($page_title) ?>">
    <meta property="og:description" content="<?= e($page_desc) ?>">
    <meta property="og:image" content="<?= e($site_db['logo'] ?? 'images/asset2.png') ?>">
    <meta property="og:url" content="https://<?= e(SITE_DOMAIN) ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($page_title) ?>">
    <meta name="twitter:description" content="<?= e($page_desc) ?>">
    <meta name="twitter:image" content="<?= e($site_db['logo'] ?? 'images/asset2.png') ?>">

    <!-- Canonical & Sitemap -->
    <link rel="canonical" href="https://<?= e(SITE_DOMAIN) ?>/">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">

    <!-- Schema.org JSON-LD Structured Data (Google Rich Results & E-E-A-T) -->
    <?php require_once __DIR__ . '/schema.php'; ?>
    <script type="application/ld+json">
    <?= generate_schema_json() ?>
    </script>

    <title><?= e($page_title) ?></title>

    <!-- Google Fonts (Outfit for headings + Inter for body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS (Animate on Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Swiper Carousel CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header Navigation -->
    <header class="header">
        <nav class="navbar container">
            <div class="logo">
                <a href="index.php#home" aria-label="<?= e($site_db['name']) ?> Home">
                    <img src="<?= e($site_db['logo'] ?? 'images/asset2.png') ?>" alt="<?= e($site_db['name']) ?> Logo" class="logo-image">
                </a>
            </div>

            <!-- Desktop Navigation Links (dynamic loop) -->
            <div class="nav-links">
                <?php foreach ($nav_items as $item): ?>
                    <a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Hamburger Button -->
            <button class="mobile-menu-btn" aria-label="Toggle Navigation Menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>

        <!-- Mobile Navigation Menu (dynamic loop) -->
        <div class="mobile-menu">
            <?php foreach ($nav_items as $item): ?>
                <a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
            <?php endforeach; ?>
        </div>
    </header>
