<?php

namespace Webkul\Admin\Http\Controllers\Marketing;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdHealthController extends Controller
{
    public function index()
    {
        $customJs = (string) core()->getConfigData('general.content.custom_scripts.custom_javascript');

        $checks = $this->buildChecks($customJs);

        $passed = collect($checks)->where('status', true)->count();
        $total  = count($checks);
        $score  = $total > 0 ? (int) round(($passed / $total) * 100) : 0;

        $byCategory = collect($checks)->groupBy('category');

        return view('admin::marketing.ad-health.index', compact('checks', 'byCategory', 'score', 'passed', 'total'));
    }

    private function buildChecks(string $customJs): array
    {
        $configUrl  = route('admin.configuration.index', 'general.content');
        $sitemapUrl = route('admin.marketing.search_seo.sitemaps.index');
        $shopBase   = base_path('packages/Webkul/Shop/src/Resources/views');
        $scBase     = base_path('packages/Webkul/SocialCommerce/src');

        /* ── Config values ── */
        $fbEnabled = (bool) core()->getConfigData('general.content.facebook_pixel.enabled');
        $fbId      = trim((string) core()->getConfigData('general.content.facebook_pixel.pixel_id'));
        $ttEnabled = (bool) core()->getConfigData('general.content.tiktok_pixel.enabled');
        $ttId      = trim((string) core()->getConfigData('general.content.tiktok_pixel.pixel_id'));
        $gaEnabled = (bool) core()->getConfigData('general.content.google_analytics.enabled');
        $gaId      = trim((string) core()->getConfigData('general.content.google_analytics.measurement_id'));

        /* ── Code-based checks ── */
        $listenerPath = $scBase . '/Listeners/InjectTrackingPixels.php';
        $listenerCode = File::exists($listenerPath) ? File::get($listenerPath) : '';
        $hasPurchase  = str_contains(strtolower($listenerCode), 'purchase')
                     || str_contains(strtolower($customJs), 'purchase');

        $read = fn(string $rel) => File::exists($shopBase . $rel) ? File::get($shopBase . $rel) : '';

        $homeCode      = $read('/home/index.blade.php');
        $layoutCode    = $read('/components/layouts/index.blade.php');
        $productCode   = $read('/products/view.blade.php');
        $breadcrumbCode= $read('/partials/breadcrumbs.blade.php');

        $hasSitemap = DB::table('sitemaps')->count() > 0 || File::exists(public_path('sitemap.xml'));

        return [
            /* ── Pixels ───────────────────────────────────────── */
            [
                'key'      => 'fb_pixel',
                'category' => 'pixels',
                'label'    => 'Facebook / Meta Pixel',
                'icon'     => 'fab fa-facebook',
                'status'   => $fbEnabled && $fbId !== '',
                'priority' => 'critical',
                'message'  => ($fbEnabled && $fbId !== '')
                    ? 'Pixel ID محدد ومفعّل ✓'
                    : 'غير مفعّل أو الـ Pixel ID ناقص',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح الإعدادات',
            ],
            [
                'key'      => 'fb_purchase',
                'category' => 'pixels',
                'label'    => 'Facebook — Purchase Event',
                'icon'     => 'fab fa-facebook',
                'status'   => $hasPurchase,
                'priority' => 'critical',
                'message'  => $hasPurchase
                    ? 'Purchase event موجود ✓'
                    : 'الـ pixel مش بيتتبع الطلبات المكتملة — مش هتعرف ROAS',
                'fix_url'  => null,
                'fix_label'=> null,
            ],
            [
                'key'      => 'tiktok_pixel',
                'category' => 'pixels',
                'label'    => 'TikTok Pixel',
                'icon'     => 'fab fa-tiktok',
                'status'   => $ttEnabled && $ttId !== '',
                'priority' => 'critical',
                'message'  => ($ttEnabled && $ttId !== '')
                    ? 'Pixel ID محدد ومفعّل ✓'
                    : 'غير مفعّل أو الـ Pixel ID ناقص',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح الإعدادات',
            ],
            [
                'key'      => 'tiktok_purchase',
                'category' => 'pixels',
                'label'    => 'TikTok — Purchase Event',
                'icon'     => 'fab fa-tiktok',
                'status'   => $hasPurchase,
                'priority' => 'critical',
                'message'  => $hasPurchase
                    ? 'Purchase event موجود ✓'
                    : 'الـ pixel مش بيتتبع الطلبات المكتملة',
                'fix_url'  => null,
                'fix_label'=> null,
            ],
            [
                'key'      => 'ga4',
                'category' => 'pixels',
                'label'    => 'Google Analytics 4',
                'icon'     => 'fab fa-google',
                'status'   => $gaEnabled && $gaId !== '',
                'priority' => 'high',
                'message'  => ($gaEnabled && $gaId !== '')
                    ? 'Measurement ID محدد ✓'
                    : 'غير مفعّل أو الـ Measurement ID ناقص',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح الإعدادات',
            ],
            [
                'key'      => 'gtm',
                'category' => 'pixels',
                'label'    => 'Google Tag Manager (GTM)',
                'icon'     => 'fab fa-google',
                'status'   => str_contains($customJs, 'GTM-') || str_contains($customJs, 'googletagmanager'),
                'priority' => 'high',
                'message'  => (str_contains($customJs, 'GTM-') || str_contains($customJs, 'googletagmanager'))
                    ? 'GTM container موجود في Custom JS ✓'
                    : 'أضفه في Configure → Custom JavaScript',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح Custom JS',
            ],
            [
                'key'      => 'google_ads',
                'category' => 'pixels',
                'label'    => 'Google Ads Conversion',
                'icon'     => 'fab fa-google',
                'status'   => str_contains($customJs, '/AW-') || str_contains($customJs, 'google_conversion'),
                'priority' => 'high',
                'message'  => (str_contains($customJs, '/AW-') || str_contains($customJs, 'google_conversion'))
                    ? 'Google Ads tag موجود ✓'
                    : 'أضف Google Ads conversion tag في Custom JavaScript',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح Custom JS',
            ],
            [
                'key'      => 'snapchat',
                'category' => 'pixels',
                'label'    => 'Snapchat Pixel',
                'icon'     => 'fab fa-snapchat',
                'status'   => str_contains($customJs, 'snaptr('),
                'priority' => 'medium',
                'message'  => str_contains($customJs, 'snaptr(')
                    ? 'Snapchat pixel موجود ✓'
                    : 'أضف Snapchat pixel في Custom JavaScript',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح Custom JS',
            ],
            [
                'key'      => 'pinterest',
                'category' => 'pixels',
                'label'    => 'Pinterest Tag',
                'icon'     => 'fab fa-pinterest',
                'status'   => str_contains($customJs, 'pintrk('),
                'priority' => 'medium',
                'message'  => str_contains($customJs, 'pintrk(')
                    ? 'Pinterest tag موجود ✓'
                    : 'أضف Pinterest tag في Custom JavaScript',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح Custom JS',
            ],
            [
                'key'      => 'twitter_pixel',
                'category' => 'pixels',
                'label'    => 'Twitter / X Pixel',
                'icon'     => 'fab fa-x-twitter',
                'status'   => str_contains($customJs, 'twq('),
                'priority' => 'low',
                'message'  => str_contains($customJs, 'twq(')
                    ? 'Twitter/X pixel موجود ✓'
                    : 'أضف Twitter pixel في Custom JavaScript',
                'fix_url'  => $configUrl,
                'fix_label'=> 'افتح Custom JS',
            ],

            /* ── Meta Tags ────────────────────────────────────── */
            [
                'key'      => 'og_products',
                'category' => 'meta',
                'label'    => 'Open Graph — صفحات المنتج',
                'icon'     => 'icon-share',
                'status'   => str_contains($productCode, 'og:title'),
                'priority' => 'high',
                'message'  => str_contains($productCode, 'og:title')
                    ? 'og:title + og:image + og:description موجودين ✓'
                    : 'مفيش OG tags على صفحات المنتج',
                'fix_url'  => null,
                'fix_label'=> null,
            ],
            [
                'key'      => 'og_home',
                'category' => 'meta',
                'label'    => 'Open Graph — الـ Homepage',
                'icon'     => 'icon-share',
                'status'   => str_contains($homeCode, 'og:'),
                'priority' => 'medium',
                'message'  => str_contains($homeCode, 'og:')
                    ? 'OG tags موجودة على الـ Homepage ✓'
                    : 'الـ Homepage مفيهاش OG tags — بيأثر على شكل الإعلانات',
                'fix_url'  => null,
                'fix_label'=> null,
            ],
            [
                'key'      => 'twitter_cards',
                'category' => 'meta',
                'label'    => 'Twitter Cards',
                'icon'     => 'fab fa-x-twitter',
                'status'   => str_contains($productCode, 'twitter:card'),
                'priority' => 'medium',
                'message'  => str_contains($productCode, 'twitter:card')
                    ? 'Twitter Cards موجودة على صفحات المنتج ✓'
                    : 'مفيش Twitter Cards',
                'fix_url'  => null,
                'fix_label'=> null,
            ],
            [
                'key'      => 'canonical',
                'category' => 'meta',
                'label'    => 'Canonical URLs',
                'icon'     => 'icon-link',
                'status'   => str_contains($layoutCode, 'canonical'),
                'priority' => 'high',
                'message'  => str_contains($layoutCode, 'canonical')
                    ? 'Canonical tags موجودة ✓'
                    : 'مفيش canonical tags — بيسبب duplicate content ويأثر على جودة الـ landing pages',
                'fix_url'  => null,
                'fix_label'=> null,
            ],

            /* ── Structured Data ──────────────────────────────── */
            [
                'key'      => 'product_schema',
                'category' => 'schema',
                'label'    => 'Product Schema (JSON-LD)',
                'icon'     => 'icon-sort',
                'status'   => str_contains($productCode, 'application/ld+json') || str_contains($productCode, 'schema'),
                'priority' => 'high',
                'message'  => (str_contains($productCode, 'application/ld+json') || str_contains($productCode, 'schema'))
                    ? 'Product schema موجود — بيساعد Google Shopping ✓'
                    : 'مفيش Product schema',
                'fix_url'  => null,
                'fix_label'=> null,
            ],
            [
                'key'      => 'breadcrumb_schema',
                'category' => 'schema',
                'label'    => 'Breadcrumb Schema',
                'icon'     => 'icon-sort',
                'status'   => str_contains($breadcrumbCode, 'BreadcrumbList'),
                'priority' => 'medium',
                'message'  => str_contains($breadcrumbCode, 'BreadcrumbList')
                    ? 'BreadcrumbList schema موجود ✓'
                    : 'Breadcrumbs HTML بس — مفيش JSON-LD',
                'fix_url'  => null,
                'fix_label'=> null,
            ],

            /* ── Technical ────────────────────────────────────── */
            [
                'key'      => 'sitemap',
                'category' => 'technical',
                'label'    => 'XML Sitemap',
                'icon'     => 'icon-sitemap',
                'status'   => $hasSitemap,
                'priority' => 'high',
                'message'  => $hasSitemap
                    ? 'Sitemap مضبوط ✓'
                    : 'مفيش sitemap — بيأثر على الـ indexing',
                'fix_url'  => $sitemapUrl,
                'fix_label'=> 'أضف Sitemap',
            ],
            [
                'key'      => 'robots',
                'category' => 'technical',
                'label'    => 'Robots.txt',
                'icon'     => 'icon-settings',
                'status'   => File::exists(public_path('robots.txt')),
                'priority' => 'high',
                'message'  => File::exists(public_path('robots.txt'))
                    ? 'robots.txt موجود ✓'
                    : 'مفيش robots.txt',
                'fix_url'  => null,
                'fix_label'=> null,
            ],
        ];
    }
}
