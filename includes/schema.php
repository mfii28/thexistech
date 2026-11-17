<?php
/**
 * includes/schema.php
 * Generates Schema.org JSON-LD structured data for Google Rich Results & E-E-A-T signals.
 */

require_once __DIR__ . '/config.php';

function generate_schema_json(): string {
    global $site_db, $services_db;

    $base_url = 'https://' . SITE_DOMAIN;
    $logo_url = $base_url . '/' . ltrim($site_db['logo'] ?? 'images/asset2.png', '/');

    // ── 1. Organization & ProfessionalService ────────────────────────────────
    $same_as = [];
    if (!empty($site_db['socials'])) {
        foreach ($site_db['socials'] as $soc) {
            if (!empty($soc['url']) && $soc['url'] !== '#') {
                $same_as[] = $soc['url'];
            }
        }
    }

    $offers = [];
    if (!empty($services_db)) {
        foreach ($services_db as $serv) {
            $offers[] = [
                '@type'       => 'Offer',
                'itemOffered' => [
                    '@type'       => 'Service',
                    'name'        => $serv['title'],
                    'description' => $serv['desc'],
                    'provider'    => [
                        '@type' => 'Organization',
                        'name'  => $site_db['name']
                    ]
                ]
            ];
        }
    }

    $org_schema = [
        '@context'            => 'https://schema.org',
        '@type'               => ['Organization', 'ProfessionalService'],
        '@id'                 => $base_url . '/#organization',
        'name'                => $site_db['name'],
        'url'                 => $base_url,
        'logo'                => $logo_url,
        'image'               => $logo_url,
        'description'         => $site_db['description'],
        'foundingDate'        => '2023',
        /*
        'founder'             => [
            '@type' => 'Person',
            'name'  => 'Isaac Yanney',
            'jobTitle' => 'Chief Executive Officer'
        ],
        */
        'address'             => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '123 Innovation Street',
            'addressLocality' => 'Accra',
            'addressCountry'  => 'GH'
        ],
        'contactPoint'        => [
            '@type'             => 'ContactPoint',
            'telephone'         => $site_db['contact']['phone'] ?? '+233 26 630 6442',
            'contactType'       => 'customer support',
            'email'             => $site_db['contact']['email'] ?? 'Thexistech@gmail.com',
            'areaServed'        => ['GH', 'NG', 'US', 'GB', 'Worldwide'],
            'availableLanguage' => ['English']
        ],
        'sameAs'              => $same_as,
        'hasOfferCatalog'     => [
            '@type'           => 'OfferCatalog',
            'name'            => 'Thexis Tech Digital & IT Services',
            'itemListElement' => $offers
        ]
    ];

    // ── 2. WebSite Schema ───────────────────────────────────────────────────
    $website_schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'WebSite',
        '@id'         => $base_url . '/#website',
        'name'        => $site_db['name'],
        'url'         => $base_url,
        'publisher'   => [
            '@id' => $base_url . '/#organization'
        ]
    ];

    // Combine in @graph
    $graph = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            $org_schema,
            $website_schema
        ]
    ];

    return json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
