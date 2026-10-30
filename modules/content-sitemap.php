<?php
/**
 * Sitemap Module
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$title = get_sub_field('title') ?: "Sitemap";
$subtitle = get_sub_field('subtitle');
if (empty($subtitle) || strpos($subtitle, 'ModafinilMY') !== false) {
    $subtitle = "Browse all main pages, armodafinil products, Australian delivery hubs, and support policies on Waklert Australia.";
}

// Main Pages
$main_links = [
    ['label' => 'Home', 'url' => home_url('/')],
    ['label' => 'Shop All Products', 'url' => home_url('/shop/')],
    ['label' => 'About Us', 'url' => home_url('/about-us/')],
    ['label' => 'Customer Reviews', 'url' => home_url('/reviews/')],
    ['label' => 'Frequently Asked Questions', 'url' => home_url('/faq/')],
    ['label' => 'Track Your Order', 'url' => home_url('/track-order/')],
    ['label' => 'Buyer’s Guide', 'url' => home_url('/buyers-guide/')],
    ['label' => 'Armodafinil Information Guide', 'url' => home_url('/armodafinil-guide/')],
    ['label' => 'Contact Support', 'url' => home_url('/contact/')]
];

// Policies & Legal
$legal_links = [
    ['label' => 'Terms & Conditions', 'url' => home_url('/terms-and-conditions/')],
    ['label' => 'Privacy Policy', 'url' => home_url('/privacy/')],
    ['label' => 'Refund & Reshipment Policy', 'url' => home_url('/refund-policy/')],
    ['label' => 'Shipping Policy', 'url' => home_url('/shipping-policy/')],
    ['label' => 'Medical Website Disclaimer', 'url' => home_url('/medical-disclaimer/')],
    ['label' => 'Sitemap', 'url' => home_url('/sitemap/')]
];

// Query WooCommerce Products dynamically
$products = get_posts([
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
]);

// Australian Delivery Cities
$cities = get_posts([
    'post_type' => 'city',
    'post_status' => 'publish',
    'posts_per_page' => 20,
    'orderby' => 'menu_order title',
    'order' => 'ASC'
]);
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
        <!-- Breadcrumbs -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]">Sitemap</span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                Site Directory
            </span>
        </div>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($title) ?>
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
            <?= esc_html($subtitle) ?>
        </p>
    </div>
</header>

<section class="py-7 md:py-10 bg-gradient-to-b from-white via-slate-50/50 to-white">
    <div class="container-site max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Column 1: Main Pages -->
            <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-[#0c4161]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </span>
                    <h2 class="font-heading text-base font-black text-[#0c4161] uppercase tracking-wider">
                        Main Pages
                    </h2>
                </div>
                <ul class="space-y-2.5">
                    <?php foreach ($main_links as $link): ?>
                        <li>
                            <a href="<?= esc_url($link['url']) ?>" class="group flex items-center gap-2 text-sm text-slate-600 hover:text-[#0c4161] font-medium transition-all">
                                <span class="h-1.5 w-1.5 rounded-full bg-sky-300 group-hover:bg-[#0c4161] transition-colors"></span>
                                <span class="group-hover:translate-x-0.5 transition-transform"><?= esc_html($link['label']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Column 2: Products -->
            <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </span>
                    <h2 class="font-heading text-base font-black text-[#0c4161] uppercase tracking-wider">
                        Products
                    </h2>
                </div>
                <ul class="space-y-2.5">
                    <?php if ($products): ?>
                        <?php foreach ($products as $prod): ?>
                            <li>
                                <a href="<?= esc_url(get_permalink($prod->ID)) ?>" class="group flex items-center gap-2 text-sm text-slate-600 hover:text-[#0c4161] font-medium transition-all">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 group-hover:bg-[#0c4161] transition-colors"></span>
                                    <span class="group-hover:translate-x-0.5 transition-transform"><?= esc_html($prod->post_title) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a href="<?= esc_url(home_url('/shop/')) ?>" class="text-sm text-slate-600 hover:text-[#0c4161]">View All Products</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 3: Australia Delivery Hubs -->
            <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <h2 class="font-heading text-base font-black text-[#0c4161] uppercase tracking-wider">
                        Australia Delivery
                    </h2>
                </div>
                <ul class="space-y-2.5">
                    <?php if ($cities): ?>
                        <?php foreach (array_slice($cities, 0, 10) as $c): ?>
                            <li>
                                <a href="<?= esc_url(get_permalink($c->ID)) ?>" class="group flex items-center gap-2 text-sm text-slate-600 hover:text-[#0c4161] font-medium transition-all">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 group-hover:bg-[#0c4161] transition-colors"></span>
                                    <span class="group-hover:translate-x-0.5 transition-transform"><?= esc_html(get_field('city_name', $c->ID) ?: $c->post_title) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><span class="text-sm text-slate-500">Express Delivery Nationwide</span></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 4: Legal & Policies -->
            <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span>
                    <h2 class="font-heading text-base font-black text-[#0c4161] uppercase tracking-wider">
                        Policies &amp; Legal
                    </h2>
                </div>
                <ul class="space-y-2.5">
                    <?php foreach ($legal_links as $link): ?>
                        <li>
                            <a href="<?= esc_url($link['url']) ?>" class="group flex items-center gap-2 text-sm text-slate-600 hover:text-[#0c4161] font-medium transition-all">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-300 group-hover:bg-[#0c4161] transition-colors"></span>
                                <span class="group-hover:translate-x-0.5 transition-transform"><?= esc_html($link['label']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
    </div>
</section>
