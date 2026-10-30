<?php
/**
 * Shared, field-driven Australia Delivery page template.
 * Redesigned with creative 2-tone dashed brand theme, colorful vector icons, and smooth micro-interactions.
 */
defined('ABSPATH') || exit;

$read = static function ($name) use ($args) {
    return isset($args['fields']) ? ($args['fields'][$name] ?? '') : get_field($name);
};

$city         = trim((string) $read('city_name')) ?: get_the_title();
$region       = $read('region') ?: 'Australia';
$days         = $read('delivery_days');
$demographic  = $read('demographic');
$industry     = $read('industry');
$facts_date   = $read('facts_date');
$facts_source = $read('facts_source');
$shop         = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
$contact      = home_url('/contact/');
$product_anchor = wp_unique_id('delivery-products-');

$summary = [
    "Delivery to {$city}" => [
        'val'  => $days ?: ($read('delivery_note') ?: 'Confirm your postcode'),
        'sub'  => 'Australia Post Express',
        'badge'=> 'bg-emerald-100 text-emerald-800 border-emerald-200',
    ],
    'Shipping' => [
        'val'  => $read('shipping_note') ?: 'Options at checkout',
        'sub'  => 'Free on orders $299+',
        'badge'=> 'bg-amber-100 text-amber-800 border-amber-200',
    ],
    'Packaging' => [
        'val'  => $read('packaging_note') ?: 'Plain outer packaging',
        'sub'  => '100% Discreet & sealed',
        'badge'=> 'bg-sky-100 text-[#0c4161] border-sky-200',
    ],
    'Tracking' => [
        'val'  => $read('tracking_note') ?: 'Provided after dispatch',
        'sub'  => 'Live AusPost Tracking',
        'badge'=> 'bg-indigo-100 text-indigo-800 border-indigo-200',
    ],
];

$features = $read('features') ?: [
    ['feat' => 'Compare available strengths and pack sizes'],
    ['feat' => 'Check delivery details for your postcode'],
    ['feat' => 'Review shipping and payment options at checkout'],
    ['feat' => 'Contact the team for order support'],
];

$reasons = $read('reasons') ?: [
    [
        'title' => 'A focused product range',
        'text'  => 'Compare armodafinil brands, strengths and pack sizes with clear product information.',
    ],
    [
        'title' => "Delivery information for {$city}",
        'text'  => 'Check your postcode, shipping options and estimated timing before placing an order.',
    ],
    [
        'title' => 'Help with your order',
        'text'  => 'Find answers in our FAQ or contact the team for help with ordering and delivery.',
    ],
];

$faqs = $read('faqs') ?: [
    [
        'question' => "How long does delivery to {$city} take?",
        'answer'   => $days ? "{$days}. Contact our team to confirm the current estimate for your address." : 'Contact our team with your postcode to confirm the current delivery estimate before ordering.',
    ],
    [
        'question' => 'Is the packaging discreet?',
        'answer'   => 'Orders are prepared in plain outer packaging. Product and regulatory information remains on the medicine packaging where required.',
    ],
    [
        'question' => 'Where can I check shipping and payment options?',
        'answer'   => 'Available options are displayed at checkout. Check the details for your delivery address before confirming your order.',
    ],
    [
        'question' => 'Who can help with a delayed order?',
        'answer'   => 'Check your tracking details, then contact our team with your order number for help. See the refund policy for the applicable terms.',
    ],
];

$whatsapp_raw = get_field('whatsapp_button_url', 'option') ?: get_field('whatsapp_number', 'option');
$whatsapp     = function_exists('waklert_format_whatsapp_url') ? waklert_format_whatsapp_url($whatsapp_raw) : esc_url($whatsapp_raw ?: home_url('/contact/'));
?>

<!-- ============================================================
     1. HERO SECTION: 2-Tone Gradient + Ambient Rings + Dashed Delivery Glance Card
     ============================================================ -->
<section class="relative isolate overflow-hidden bg-gradient-to-r from-[#082a3f] via-[#0c4161] to-[#125883] bg-pattern-dots-dark text-white border-b-2 border-dashed border-sky-400/30">
    <?php waklert_output_pattern_rings(); ?>

    <div class="container-site relative z-10 grid items-center gap-10 py-8 sm:py-10 md:py-12 lg:py-14 lg:grid-cols-[1.15fr_.85fr] lg:gap-14">
        <!-- Left: City Pitch & Actions -->
        <div>
            <!-- Breadcrumbs Pill -->
            <div class="mb-5 sm:mb-6">
                <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center gap-1.5 rounded-full border border-dashed border-sky-300/50 bg-white/10 px-3.5 py-1 text-xs font-medium text-sky-200 backdrop-blur-sm">
                    <a class="font-bold text-white transition-colors hover:text-amber-300" href="<?= esc_url(home_url('/')) ?>">Home</a>
                    <span aria-hidden="true" class="text-sky-300/60">/</span>
                    <a class="text-sky-200 transition-colors hover:text-white" href="<?= esc_url(home_url('/sitemap/')) ?>">Australia Delivery</a>
                    <span aria-hidden="true" class="text-sky-300/60">/</span>
                    <span aria-current="page" class="font-extrabold text-amber-300"><?= esc_html($city) ?></span>
                </nav>
            </div>

            <!-- Region Eyebrow -->
            <div class="mb-4">
                <span class="inline-flex items-center gap-2 rounded-full border border-dashed border-amber-400/60 bg-amber-400/15 px-3.5 py-1 text-xs font-black uppercase tracking-widest text-amber-300 shadow-xs">
                    <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse" aria-hidden="true"></span>
                    <?= esc_html($region) ?>
                </span>
            </div>

            <!-- Main Heading -->
            <h1 class="max-w-2xl font-heading text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem] font-black leading-[1.12] tracking-tight text-white drop-shadow-sm">
                <?= esc_html($read('hero_heading') ?: "Armodafinil Delivery to {$city}") ?>
            </h1>

            <!-- Subtitle -->
            <p class="mt-4 sm:mt-5 max-w-xl text-sm sm:text-base leading-relaxed text-sky-100/90 font-medium">
                <?= esc_html($read('hero_desc') ?: "Explore the Waklert Australia range and check ordering and delivery information for your {$city} address.") ?>
            </p>

            <!-- Compact Action Buttons -->
            <div class="mt-7 sm:mt-8 flex flex-wrap items-center gap-3">
                <a class="group inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 px-5 py-2.5 sm:px-7 sm:py-3.5 text-xs sm:text-sm font-black uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-400" href="<?= esc_url($shop) ?>">
                    <span><?= esc_html($read('shop_button_label') ?: 'Shop now') ?></span>
                    <svg aria-hidden="true" class="h-3.5 w-3.5 sm:h-4 sm:w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a class="inline-flex items-center justify-center rounded-full border-2 border-dashed border-sky-300/60 bg-white/10 hover:bg-white/20 px-4 py-2.5 sm:px-6 sm:py-3.5 text-xs sm:text-sm font-extrabold uppercase tracking-wide text-white backdrop-blur-sm transition-all duration-300 hover:-translate-y-0.5 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white" href="#<?= esc_attr($product_anchor) ?>">
                    <?= esc_html($read('products_button_label') ?: 'View products') ?>
                </a>
            </div>

            <!-- Mini Trust Badges -->
            <div class="mt-6 pt-5 border-t border-dashed border-white/15 flex flex-wrap items-center gap-4 sm:gap-6 text-xs text-sky-200/90 font-medium">
                <span class="inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">✓</span> 100% Genuine Sun Pharma</span>
                <span class="inline-flex items-center gap-1.5"><span class="text-emerald-400 font-bold">✓</span> Domestic AusPost Dispatch</span>
                <span class="inline-flex items-center gap-1.5"><span class="text-sky-300 font-bold">✓</span> Plain Parcel Guarantee</span>
            </div>
        </div>

        <!-- Right: 2-Tone Dashed "Delivery at a Glance" Card -->
        <aside aria-label="Delivery summary" class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-white text-slate-800 shadow-2xl p-6 sm:p-7 md:p-8 relative">
            <!-- Accent Top Stripe / Corner Badge -->
            <div class="flex items-center gap-3.5 pb-5 border-b-2 border-dashed border-sky-100">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-50 border border-dashed border-sky-200 shadow-xs">
                    <!-- Colorful Delivery Truck SVG -->
                    <svg class="h-7 w-7" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="9" width="18" height="13" rx="2" fill="#E0F2FE" stroke="#0284C7" stroke-width="1.8"/>
                        <path d="M20 12h5.5l3.5 4.5v5.5H20V12z" fill="#FEF3C7" stroke="#D97706" stroke-width="1.8"/>
                        <circle cx="8" cy="24" r="3" fill="#0C4161"/>
                        <circle cx="8" cy="24" r="1.2" fill="#FFFFFF"/>
                        <circle cx="23" cy="24" r="3" fill="#0C4161"/>
                        <circle cx="23" cy="24" r="1.2" fill="#FFFFFF"/>
                        <path d="M5 14h7M5 17h4" stroke="#0369A1" stroke-width="1.5" stroke-linecap="round"/>
                        <rect x="22" y="14" width="4" height="3" rx="0.5" fill="#DC2626"/>
                    </svg>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-widest text-[#0c4161]">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <?= esc_html($read('carrier') ?: 'Australia Delivery') ?>
                    </span>
                    <h2 class="font-heading text-lg sm:text-xl font-black text-[#0c4161] leading-tight">
                        <?= esc_html($read('summary_heading') ?: 'Delivery at a glance') ?>
                    </h2>
                </div>
            </div>

            <!-- List of Delivery Specs -->
            <div class="divide-y divide-dashed divide-sky-100/90 py-1">
                <?php foreach ($summary as $label => $item): ?>
                <div class="py-3.5 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-[#0c4161]"><?= esc_html($label) ?></p>
                        <p class="text-[11px] text-slate-500 font-medium"><?= esc_html($item['sub']) ?></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block rounded-lg px-2.5 py-1 text-xs font-extrabold border border-dashed <?= esc_attr($item['badge']) ?>">
                            <?= esc_html($item['val']) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Card Action Footer -->
            <div class="mt-4 pt-4 border-t-2 border-dashed border-sky-100">
                <a class="group flex items-center justify-between w-full rounded-xl bg-gradient-to-r from-[#0c4161] to-[#082a3f] hover:from-[#082a3f] hover:to-[#0c4161] text-white px-4 py-3 text-xs sm:text-sm font-extrabold tracking-wide shadow-md transition-all duration-300" href="<?= esc_url($contact) ?>">
                    <span><?= esc_html($read('delivery_link_label') ?: 'Check delivery for your postcode') ?></span>
                    <span class="transition-transform duration-300 group-hover:translate-x-1 font-bold">&rarr;</span>
                </a>
            </div>
        </aside>
    </div>
</section>

<!-- ============================================================
     2. TRUST BADGES STRIP (4-Col Vibrant 2-Tone Dashed Cards)
     ============================================================ -->
<div class="bg-gradient-to-b from-[#f0f6fa] to-[#f8fafc] border-b-2 border-dashed border-sky-200/80 py-5 sm:py-6">
    <div class="container-site grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <!-- Badge 1: AusPost -->
        <div class="group flex items-center gap-3 rounded-2xl border-2 border-dashed border-sky-200/80 bg-gradient-to-br from-sky-50/40 to-white p-3 sm:p-3.5 hover:border-sky-400 hover:shadow-sm transition-all">
            <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl bg-white border border-sky-200 shadow-xs transition-transform group-hover:scale-105">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="7" width="13" height="10" rx="1.5" fill="#E0F2FE" stroke="#0284C7" stroke-width="1.8"/>
                    <path d="M15 10h4l3 3.5v3.5h-7V10z" fill="#DC2626" stroke="#B91C1C" stroke-width="1.8"/>
                    <circle cx="6" cy="18" r="2" fill="#0C4161"/>
                    <circle cx="18" cy="18" r="2" fill="#0C4161"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-[#0c4161] leading-tight">AusPost Express</p>
                <p class="text-[10px] text-slate-500 mt-0.5">Fast tracked dispatch</p>
            </div>
        </div>

        <!-- Badge 2: 100% Genuine Sun Pharma -->
        <div class="group flex items-center gap-3 rounded-2xl border-2 border-dashed border-amber-200/80 bg-gradient-to-br from-amber-50/40 to-white p-3 sm:p-3.5 hover:border-amber-400 hover:shadow-sm transition-all">
            <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl bg-white border border-amber-200 shadow-xs transition-transform group-hover:scale-105">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="16" rx="3" fill="#FEF3C7" stroke="#D97706" stroke-width="1.8"/>
                    <line x1="3" y1="10" x2="21" y2="10" stroke="#D97706" stroke-width="1.5"/>
                    <circle cx="7.5" cy="7" r="1.5" fill="#D97706"/>
                    <circle cx="12" cy="7" r="1.5" fill="#D97706"/>
                    <circle cx="16.5" cy="7" r="1.5" fill="#D97706"/>
                    <circle cx="7.5" cy="14" r="1.5" fill="#0C4161"/>
                    <circle cx="12" cy="14" r="1.5" fill="#0C4161"/>
                    <circle cx="16.5" cy="14" r="1.5" fill="#0C4161"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-[#0c4161] leading-tight">100% Sealed</p>
                <p class="text-[10px] text-slate-500 mt-0.5">Original blister packs</p>
            </div>
        </div>

        <!-- Badge 3: Secure Checkout -->
        <div class="group flex items-center gap-3 rounded-2xl border-2 border-dashed border-emerald-200/80 bg-gradient-to-br from-emerald-50/40 to-white p-3 sm:p-3.5 hover:border-emerald-400 hover:shadow-sm transition-all">
            <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl bg-white border border-emerald-200 shadow-xs transition-transform group-hover:scale-105">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L4 6v6c0 5.25 3.4 10.15 8 11.35 4.6-1.2 8-6.1 8-11.35V6l-8-4z" fill="#D1FAE5" stroke="#059669" stroke-width="1.8"/>
                    <path d="M9 12l2 2 4-4" stroke="#047857" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-[#0c4161] leading-tight">Secure PayID</p>
                <p class="text-[10px] text-slate-500 mt-0.5">256-bit encrypted</p>
            </div>
        </div>

        <!-- Badge 4: Discreet -->
        <div class="group flex items-center gap-3 rounded-2xl border-2 border-dashed border-sky-200/80 bg-gradient-to-br from-sky-50/40 to-white p-3 sm:p-3.5 hover:border-sky-400 hover:shadow-sm transition-all">
            <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl bg-white border border-sky-200 shadow-xs transition-transform group-hover:scale-105">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="11" width="18" height="11" rx="2" fill="#E0F2FE" stroke="#0284C7" stroke-width="1.8"/>
                    <path d="M7 11V7a5 5 0 0110 0v4" stroke="#0C4161" stroke-width="1.8"/>
                    <circle cx="12" cy="16.5" r="1.5" fill="#0C4161"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-[#0c4161] leading-tight">Discreet Box</p>
                <p class="text-[10px] text-slate-500 mt-0.5">Zero medicine labels</p>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     3. ABOUT CITY & ORDERING GUIDANCE (2-Tone Creative Grid)
     ============================================================ -->
<section class="section-padding bg-[#f8fafc] bg-pattern-dots border-b-2 border-dashed border-sky-200/80">
    <div class="container-site grid gap-10 lg:grid-cols-[1.15fr_.85fr] lg:gap-14 items-start">
        <!-- Left: City Narrative -->
        <div>
            <div class="mb-3">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100 border border-dashed border-sky-300 px-3 py-0.5 text-xs font-extrabold uppercase tracking-widest text-[#0c4161]">
                    <svg class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <?= esc_html($read('about_label') ?: "About {$city}") ?>
                </span>
            </div>

            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-[#0c4161] leading-tight">
                <?= esc_html($read('about_heading') ?: "Delivery to {$city}" . ($region !== 'Australia' ? ", {$region}" : '')) ?>
            </h2>

            <div class="mt-5 space-y-4 text-sm sm:text-base leading-relaxed text-slate-600 [&_a]:text-[#0c4161] [&_a]:font-bold hover:[&_a]:underline">
                <?= wp_kses_post(wpautop($read('desc') ?: "Planning an order to {$city}? Browse the current range, compare pack options and confirm delivery details for your address with our team.")) ?>
            </div>

            <!-- City Statistics / QuickStats 2-Tone Card -->
            <?php if ($read('population') || $demographic || $industry): ?>
            <div class="mt-6 rounded-2xl border-2 border-dashed border-sky-200/90 bg-white p-5 shadow-xs">
                <div class="flex items-center gap-2 mb-3">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-600 font-bold text-xs">ℹ</span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#0c4161]"><?= esc_html($city) ?> Local QuickStats</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
                    <?php if ($read('population')): ?>
                    <div class="rounded-xl bg-sky-50/50 border border-dashed border-sky-100 p-3">
                        <span class="text-slate-400 block text-[11px]">Local Population</span>
                        <strong class="text-sm font-extrabold text-[#0c4161]"><?= esc_html($read('population')) ?></strong>
                    </div>
                    <?php endif; ?>

                    <?php if ($industry): ?>
                    <div class="rounded-xl bg-sky-50/50 border border-dashed border-sky-100 p-3">
                        <span class="text-slate-400 block text-[11px]">Primary Industries</span>
                        <strong class="text-xs font-bold text-slate-800"><?= esc_html($industry) ?></strong>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($demographic): ?>
                <p class="mt-3 text-xs text-slate-500 leading-relaxed"><?= esc_html($demographic) ?></p>
                <?php endif; ?>

                <?php if ($facts_source): ?>
                <p class="mt-2 text-[10px] text-slate-400 border-t border-dashed border-slate-100 pt-2">
                    <?= esc_html($facts_date ?: 'Source') ?>: <a href="<?= esc_url($facts_source) ?>" target="_blank" rel="noopener noreferrer" class="text-[#0c4161] underline hover:text-amber-600">Australian Bureau of Statistics (Census)</a>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right: 2-Tone "Ordering in {City}" Feature Checklist -->
        <aside class="rounded-3xl border-2 border-dashed border-sky-300 bg-white p-6 sm:p-8 shadow-md">
            <div class="flex items-center gap-3 pb-5 border-b-2 border-dashed border-sky-200/80 mb-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 border border-amber-200 text-amber-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-600">Ordering Guide</span>
                    <h3 class="font-heading text-lg sm:text-xl font-extrabold text-[#0c4161]">
                        <?= esc_html($read('features_heading') ?: "Ordering in {$city}") ?>
                    </h3>
                </div>
            </div>

            <ul class="space-y-3.5">
                <?php foreach ($features as $feature): if (empty($feature['feat'])) continue; ?>
                <li class="flex items-start gap-3 rounded-xl border border-dashed border-sky-100 bg-sky-50/40 p-3 transition-colors hover:bg-sky-50/80">
                    <span aria-hidden="true" class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white font-black text-xs shadow-xs mt-0.5">✓</span>
                    <span class="text-xs sm:text-sm font-semibold text-slate-700 leading-snug"><?= esc_html($feature['feat']) ?></span>
                </li>
                <?php endforeach; ?>
            </ul>

            <!-- Quick WhatsApp Assistance in Sidebar -->
            <div class="mt-6 pt-5 border-t-2 border-dashed border-sky-200/80">
                <a href="<?= esc_url($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-xl bg-emerald-50 hover:bg-emerald-100/80 border border-dashed border-emerald-300 p-3.5 text-xs font-bold text-emerald-800 transition-colors">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#25D366"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.67-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Need fast help in <?= esc_html($city) ?>?
                    </span>
                    <span class="underline">WhatsApp Us &rarr;</span>
                </a>
            </div>
        </aside>
    </div>
</section>

<!-- ============================================================
     4. "3 REASONS" - AUDIENCE-STYLE 2-TONE DASHED CARDS WITH COLORFUL ICONS
     ============================================================ -->
<section class="section-padding bg-white border-b-2 border-dashed border-sky-200/80">
    <div class="container-site">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 border border-dashed border-amber-300 px-3.5 py-1 text-xs font-black uppercase tracking-widest text-amber-800 mb-3 shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                Why Choose Us
            </span>
            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161] tracking-tight">
                <?= esc_html($read('reasons_heading') ?: count($reasons) . ' Reasons to Choose Waklert Australia') ?>
            </h2>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <?php
            // 3-Tone Palette matching Audience cards
            $card_styles = [
                0 => [
                    'border'   => 'border-sky-300 hover:border-[#0c4161]',
                    'bg_top'   => 'bg-gradient-to-br from-sky-50 via-white to-sky-50/50',
                    'num_bg'   => 'bg-[#0c4161] text-white',
                    'icon_bg'  => 'bg-sky-100/80 border-sky-200',
                    'icon_svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="4" width="18" height="16" rx="2" fill="#E0F2FE" stroke="#0284C7" stroke-width="1.8"/><path d="M7 8h10M7 12h10M7 16h6" stroke="#0369A1" stroke-width="1.8" stroke-linecap="round"/><circle cx="17" cy="15" r="3" fill="#F59E0B"/><path d="M16 15l1 1 2-2" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round"/></svg>',
                ],
                1 => [
                    'border'   => 'border-amber-300 hover:border-amber-500',
                    'bg_top'   => 'bg-gradient-to-br from-amber-50/60 via-white to-amber-50/30',
                    'num_bg'   => 'bg-amber-500 text-white',
                    'icon_bg'  => 'bg-amber-100/80 border-amber-200',
                    'icon_svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="1" y="6" width="14" height="11" rx="1.5" fill="#FEF3C7" stroke="#D97706" stroke-width="1.8"/><path d="M15 9h4.5l3.5 4.5v3.5h-8V9z" fill="#DC2626" stroke="#B91C1C" stroke-width="1.8"/><circle cx="6" cy="19" r="2.2" fill="#0C4161"/><circle cx="18" cy="19" r="2.2" fill="#0C4161"/></svg>',
                ],
                2 => [
                    'border'   => 'border-emerald-300 hover:border-emerald-600',
                    'bg_top'   => 'bg-gradient-to-br from-emerald-50/60 via-white to-emerald-50/30',
                    'num_bg'   => 'bg-emerald-600 text-white',
                    'icon_bg'  => 'bg-emerald-100/80 border-emerald-200',
                    'icon_svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" fill="#D1FAE5" stroke="#059669" stroke-width="1.8"/><circle cx="12" cy="12" r="4" fill="#FFFFFF" stroke="#0C4161" stroke-width="1.8"/><path d="M4.93 4.93L8.5 8.5M15.5 15.5L19.07 19.07M4.93 19.07L8.5 15.5M15.5 8.5L19.07 4.93" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/></svg>',
                ],
            ];

            foreach ($reasons as $index => $reason):
                $cfg = $card_styles[$index % 3];
            ?>
            <article class="group relative flex flex-col rounded-3xl border-2 border-dashed <?= esc_attr($cfg['border']) ?> bg-white overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                <!-- Top 2-Tone Banner Block -->
                <div class="<?= esc_attr($cfg['bg_top']) ?> p-6 pb-4 border-b-2 border-dashed border-sky-100/80 flex items-center justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white border border-dashed <?= esc_attr($cfg['icon_bg']) ?> shadow-xs transition-transform duration-300 group-hover:scale-110">
                        <?= $cfg['icon_svg'] ?>
                    </div>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full <?= esc_attr($cfg['num_bg']) ?> font-heading font-black text-xs shadow-xs">
                        <?= esc_html(sprintf('%02d', $index + 1)) ?>
                    </span>
                </div>

                <!-- Content Area -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-heading text-lg font-black text-[#0c4161] leading-snug mb-2">
                            <?= esc_html($reason['title'] ?? '') ?>
                        </h3>
                        <p class="text-xs sm:text-sm leading-relaxed text-slate-600">
                            <?= esc_html($reason['text'] ?? '') ?>
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-dashed border-slate-100 flex items-center gap-1.5 text-xs font-bold text-[#0c4161] group-hover:text-amber-600 transition-colors">
                        <span>Waklert Guarantee</span>
                        <span>&rarr;</span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     5. PRODUCTS COLLECTION GRID (Dashed Cards + Compact Sizing)
     ============================================================ -->
<section id="<?= esc_attr($product_anchor) ?>" class="section-padding scroll-mt-24 bg-[#f8fafc] bg-pattern-dots border-b-2 border-dashed border-sky-200/80">
    <div class="container-site">
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100 border border-dashed border-sky-300 px-3.5 py-1 text-xs font-extrabold uppercase tracking-widest text-[#0c4161] mb-3 shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <?= esc_html($read('products_label') ?: 'Direct Range') ?>
            </span>
            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161] tracking-tight">
                <?= esc_html($read('products_heading') ?: "Armodafinil for {$city} Customers") ?>
            </h2>
            <p class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed">
                <?= esc_html($read('products_desc') ?: 'Explore current products, prices and pack sizes available for delivery.') ?>
            </p>
        </div>

        <?php
        $ids = array_filter(array_map('absint', (array) $read('selected_products')));
        $query_args = ['post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC'];
        if ($ids) { $query_args['post__in'] = $ids; $query_args['orderby'] = 'post__in'; }
        if (function_exists('wc_get_product_visibility_term_ids')) {
            $visibility = wc_get_product_visibility_term_ids();
            $query_args['tax_query'] = [['taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => [$visibility['exclude-from-catalog']], 'operator' => 'NOT IN']];
        }
        $products = new WP_Query($query_args);
        if (function_exists('wc_get_product') && $products->have_posts()): ?>
            <div class="grid grid-cols-2 gap-3.5 sm:gap-5 lg:grid-cols-4">
                <?php $previous_product = $GLOBALS['product'] ?? null;
                while ($products->have_posts()): $products->the_post();
                    $GLOBALS['product'] = wc_get_product(get_the_ID());
                    wc_get_template_part('content', 'product');
                endwhile;
                wp_reset_postdata();
                $GLOBALS['product'] = $previous_product;
                ?>
            </div>
        <?php else: ?>
            <p class="text-center text-slate-500 py-8 bg-white rounded-2xl border-2 border-dashed border-sky-200">
                Check the shop for the latest product availability.
            </p>
        <?php endif; ?>

        <div class="mt-9 text-center">
            <a class="inline-flex items-center gap-2 rounded-full border-2 border-dashed border-sky-300 bg-white hover:bg-[#0c4161] text-[#0c4161] hover:text-white px-5 py-2.5 sm:px-7 sm:py-3 text-xs sm:text-sm font-extrabold uppercase tracking-wider text-primary transition-all shadow-xs hover:shadow-md hover:-translate-y-0.5" href="<?= esc_url($shop) ?>">
                <span><?= esc_html($read('all_products_label') ?: 'View all products') ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     6. CUSTOMER REVIEWS (2-Tone Cards with Star Glow)
     ============================================================ -->
<?php $reviews = $read('reviews') ?: []; if ($reviews): ?>
<section class="section-padding bg-white border-b-2 border-dashed border-sky-200/80">
    <div class="container-site">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 border border-dashed border-emerald-300 px-3.5 py-1 text-xs font-black uppercase tracking-widest text-emerald-800 mb-3 shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Verified Feedback
            </span>
            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161] tracking-tight">
                Customer Reviews from <?= esc_html($city) ?>
            </h2>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <?php foreach ($reviews as $review): $rating = max(0, min(5, (int) ($review['rating'] ?? 0))); ?>
            <figure class="rounded-3xl border-2 border-dashed border-sky-200/90 bg-gradient-to-br from-sky-50/40 via-white to-white p-6 sm:p-7 shadow-xs hover:shadow-md hover:border-sky-400 hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <?php if ($rating): ?>
                    <p aria-label="<?= esc_attr("{$rating} out of 5 stars") ?>" class="text-amber-400 tracking-wider text-sm">
                        <?= esc_html(str_repeat('★', $rating)) ?>
                    </p>
                    <?php endif; ?>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                        <span class="text-emerald-500 font-bold">✓</span> Verified Buyer
                    </span>
                </div>
                <blockquote class="text-xs sm:text-sm leading-relaxed text-slate-600 mb-4">
                    “<?= esc_html($review['text'] ?? '') ?>”
                </blockquote>
                <figcaption class="flex items-center gap-3 pt-3 border-t border-dashed border-sky-100 text-xs font-bold text-[#0c4161]">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-sky-100 text-[#0c4161] font-black text-xs border border-sky-300">
                        <?= esc_html(strtoupper(substr($review['author'] ?? 'C', 0, 1))) ?>
                    </span>
                    <span><?= esc_html($review['author'] ?? '') ?></span>
                    <span class="font-normal text-slate-400">&middot; <?= esc_html($review['date'] ?? '') ?></span>
                </figcaption>
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     7. DELIVERY FAQS (Interactive 2-Tone Dashed Accordions)
     ============================================================ -->
<section class="section-padding bg-[#f8fafc] bg-pattern-dots border-b-2 border-dashed border-sky-200/80">
    <div class="container-site max-w-4xl">
        <div class="mb-10 text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100 border border-dashed border-sky-300 px-3.5 py-1 text-xs font-extrabold uppercase tracking-widest text-[#0c4161] mb-3 shadow-xs">
                <?= esc_html($read('faq_label') ?: 'Questions Answered') ?>
            </span>
            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161] tracking-tight">
                <?= esc_html($read('faq_heading') ?: "{$city} Delivery FAQ") ?>
            </h2>
        </div>

        <div class="space-y-3.5">
            <?php foreach ($faqs as $index => $faq): if (empty($faq['question']) || empty($faq['answer'])) continue; ?>
            <details class="group rounded-2xl border-2 border-dashed border-sky-200/90 bg-white overflow-hidden transition-all duration-200 open:border-[#0c4161] open:shadow-md">
                <summary class="flex cursor-pointer list-none items-center justify-between p-4 sm:p-5 font-extrabold text-[#0c4161] text-xs sm:text-sm hover:bg-sky-50/50 [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center gap-3 pr-4">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-[#0c4161] font-black text-xs border border-sky-300">
                            <?= esc_html((string) ($index + 1)) ?>
                        </span>
                        <span><?= esc_html($faq['question']) ?></span>
                    </span>
                    <span aria-hidden="true" class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-50 text-[#0c4161] text-base font-bold transition-transform duration-200 group-open:rotate-45">
                        +
                    </span>
                </summary>
                <div class="px-5 pb-5 pt-2 text-xs sm:text-sm leading-relaxed text-slate-600 border-t-2 border-dashed border-sky-100 bg-sky-50/30 prose prose-sm max-w-none [&_a]:text-[#0c4161] [&_a]:underline [&_a]:font-bold">
                    <?= wp_kses_post(wpautop($faq['answer'])) ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>

        <p class="mt-8 text-center">
            <?php $faq_link_text = trim(str_replace(['→', '->', '&rarr;'], '', (string) ($read('faq_link_label') ?: 'Read the full FAQ'))); ?>
            <a class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-extrabold text-[#0c4161] hover:text-amber-600 transition-colors underline underline-offset-4 decoration-sky-300 hover:decoration-amber-500" href="<?= esc_url(home_url('/faq/')) ?>">
                <span><?= esc_html($faq_link_text) ?></span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </p>
    </div>
</section>

<!-- ============================================================
     8. CREATIVE 2-TONE SUPPORT & CTA BANNER (Dashed + Pattern Rings)
     ============================================================ -->
<section class="section-padding bg-white">
    <div class="container-site max-w-4xl">
        <div class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-[#082a3f] via-[#0c4161] to-[#125883] bg-pattern-dots-dark p-8 sm:p-12 md:p-14 text-white shadow-2xl border-2 border-dashed border-sky-400/40 text-center">
            <?php waklert_output_pattern_rings(); ?>

            <div class="relative z-10 max-w-2xl mx-auto">
                <span class="inline-flex items-center gap-2 rounded-full bg-amber-400/20 border border-amber-300/40 px-3.5 py-1 text-xs font-black uppercase tracking-[0.2em] text-amber-300 mb-4 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Express Dispatch
                </span>

                <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black text-white leading-tight tracking-tight mb-4">
                    <?= esc_html($read('cta_heading') ?: "Ready to Order in {$city}?") ?>
                </h2>

                <p class="text-sky-100/90 text-xs sm:text-sm md:text-base leading-relaxed mb-8 max-w-xl mx-auto font-medium">
                    <?= esc_html($read('cta_desc') ?: "Browse the range and confirm delivery options for your {$city} address before ordering.") ?>
                </p>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a class="group inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 px-5 py-2.5 sm:px-8 sm:py-3.5 text-xs sm:text-sm font-black uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/25 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl" href="<?= esc_url($shop) ?>">
                        <span><?= esc_html($read('cta_button_label') ?: 'Explore products') ?></span>
                        <svg aria-hidden="true" class="h-3.5 w-3.5 sm:h-4 sm:w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                    <a href="<?= esc_url($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full border-2 border-dashed border-white/40 bg-white/10 hover:bg-white/20 px-4 py-2.5 sm:px-6 sm:py-3.5 text-xs sm:text-sm font-bold text-white backdrop-blur-sm transition hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#25D366"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.67-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>WhatsApp Us</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
