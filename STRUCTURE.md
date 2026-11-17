# Site Architecture & XML Sitemap Documentation (Thexis Tech)

## 1. Business Profile & Discovery
- **Business Type**: B2B Software Engineering, Web/Mobile App Development & IT Consulting Agency.
- **Domain**: `https://thexistech.com/`
- **Location**: Accra, Ghana (Serving regional and international clients).
- **Target Audience**: Startups, SMEs, and enterprises seeking custom digital solutions.

---

## 2. Current Sitemap Inventory (`sitemap.xml`)

| Metric | Value | Compliance Status |
|---|---|---|
| **Total URLs** | 1 (Canonical Single Page) | ✅ Well within 50,000 URL limit |
| **Total Size** | < 1 KB uncompressed | ✅ Well within 50 MB limit |
| **Protocol** | HTTPS strictly enforced | ✅ Passes security checks |
| **Sitemap Index Required** | No (< 50k URLs) | ✅ Single clean XML file |
| **XML Standard** | Sitemaps.org 0.9 + Google Image Extension 1.1 | ✅ Fully Validated |
| **Deprecated Tags** | No `<priority>` or `<changefreq>` used | ✅ Google 2024+ Compliant |
| **`<lastmod>` Format** | W3C Date (`YYYY-MM-DD`: `2026-11-17`) | ✅ Valid format |
| **robots.txt Reference** | Declared in `robots.txt` | ✅ Auto-discoverable by crawlers |

### Active Sitemap Entries

```xml
https://thexistech.com/
  ├── Section: #home (Hero & Overview)
  ├── Section: #services (Web Dev, Mobile Dev, UI/UX, Data Science, Cloud, IT Consulting)
  ├── Section: #about (Company bio, Vision, Mission, Track record)
  ├── Section: #founders (Team directory)
  ├── Section: #projects (Featured client apps & SaaS platforms)
  └── Section: #contact (Lead capture form & contact channels)
```

---

## 3. SEO Quality Gates & Crawl Rules

1. **No Anchor Fragment Indexing**: Search engines (Google, Bing, Yandex) do not index URL hash fragments (`#services`, `#contact`) as separate indexed pages. The canonical URL `https://thexistech.com/` represents the full page authority.
2. **Disallowed Internal Routes in `robots.txt`**:
   - `/data/`: Protected backend JSON database files.
   - `/includes/`: Internal PHP templates and configuration.
   - `/contact-handler.php`: Backend form POST endpoint (prevents crawler hits).
3. **Image SEO**: Core branding and visual assets are included with `<image:image>` and `<image:loc>` without deprecated metadata tags.

---

## 4. Recommended Expansion Roadmap (Multi-Page Scalability)

As Thexis Tech expands with dedicated landing pages, the sitemap should grow according to the following architecture:

```
https://thexistech.com/
├── /services/
│   ├── web-development
│   ├── mobile-app-development
│   ├── ui-ux-design
│   ├── data-science-analytics
│   └── cloud-devops
├── /case-studies/
│   ├── cryptocurrency-tracker
│   └── clarityfi-budget-app
└── /blog/ (or /insights/)
    └── [industry-specific-articles]
```

When the site transitions to multiple dedicated URLs:
- Add each clean canonical URL to `sitemap.xml`.
- If publishing more than 1,000 news articles or blog posts, split by category with a `<sitemapindex>` parent file.
