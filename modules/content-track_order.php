<?php
/**
 * Module: Track Order
 * Displays the complete Australia Post Order Tracking section.
 * All content, headlines, steps, and cards are 100% editable from WP Admin.
 */

// Optional prefilled tracking number from query string
$tracking_param = isset($_GET['tracking']) ? sanitize_text_field(wp_unslash($_GET['tracking'])) : '';
if (!$tracking_param && isset($_GET['order_id'])) {
    $tracking_param = sanitize_text_field(wp_unslash($_GET['order_id']));
}

// 1. Hero & Intro Fields
$badge_text = get_sub_field('badge_text') ?: 'Live Australia Post Tracking';
$page_title = get_sub_field('heading') ?: get_the_title() ?: 'Track Your Parcel';
$page_desc  = get_sub_field('description') ?: 'Follow your Waklert Australia delivery from our warehouse to your doorstep with real-time Australia Post tracking.';
$intro_p1   = get_sub_field('intro_callout_p1') ?: 'Welcome to the Waklert Australia order tracking page. Every parcel we dispatch is shipped with Australia Post and comes with a unique tracking number, so you can follow your delivery from our warehouse to your door.';
$intro_p2   = get_sub_field('intro_callout_p2') ?: "Your tracking number was emailed to you when your order was dispatched. Enter it below and we'll take you straight to the live Australia Post tracking results for your parcel.";

// 2. Tracking Form Fields
$form_heading        = get_sub_field('form_heading') ?: 'Enter Your Tracking Number';
$form_instructions   = get_sub_field('form_instructions') ?: 'Tracking numbers are usually 10–23 characters long, e.g. 33ABC1234567. You can find yours in your dispatch confirmation email.';
$input_placeholder   = get_sub_field('input_placeholder') ?: 'e.g. 33ABC1234567';
$button_text         = get_sub_field('track_btn_text') ?: 'Track My Order';
$redirect_notice     = get_sub_field('redirect_notice') ?: "You'll be redirected to the official Australia Post tracking page (auspost.com.au), where your parcel's latest scan events are shown automatically.";

// 3. How Order Tracking Works Steps Repeater
$how_heading = get_sub_field('how_works_heading') ?: 'How Order Tracking Works';
$how_steps   = get_sub_field('how_works_steps');
if (empty($how_steps) || !is_array($how_steps)) {
    $how_steps = [
        [
            'title' => '1. Order Dispatched',
            'description' => 'Once your order leaves our facility, we email you a dispatch confirmation containing your Australia Post tracking number.'
        ],
        [
            'title' => '2. Enter Your Number',
            'description' => 'Paste the tracking number into the field above and select "Track My Order". We\'ll open your parcel\'s results on the Australia Post website.'
        ],
        [
            'title' => '3. Follow Your Parcel',
            'description' => 'Australia Post updates scan events at each stage — accepted, in transit, onboard for delivery, and delivered.'
        ]
    ];
}

// 4. Need Help & Support Fields
$help_title    = get_sub_field('help_heading') ?: 'Need Help?';
$help_desc     = get_sub_field('help_description') ?: "Can't find your tracking number or no scans after 3 business days? Contact our support team and we'll look into it.";
$help_btn_text = get_sub_field('help_btn_text') ?: 'Contact Support';
$help_btn_url  = get_sub_field('help_btn_url') ?: home_url('/contact/');

// 5. Trust Cards Repeater
$trust_cards = get_sub_field('trust_cards');
if (empty($trust_cards) || !is_array($trust_cards)) {
    $trust_cards = [
        [
            'card_type'   => 'shipping',
            'title'       => 'Free Shipping',
            'description' => 'On orders over $200 AUD — enjoy fast, secure, and discreet delivery right to your doorstep.'
        ],
        [
            'card_type'   => 'support',
            'title'       => '24/7 Support',
            'description' => 'We’re here to help anytime — day or night, our team is ready to assist you with your orders and inquiries.'
        ],
        [
            'card_type'   => 'payment',
            'title'       => 'Secure Payment',
            'description' => 'Shop with confidence — all transactions are 100% safe, encrypted, and protected for your peace of mind.'
        ]
    ];
}
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9" data-testid="track-order-hero">
    <div class="container-site max-w-5xl mx-auto px-4 relative z-10">
        <!-- Breadcrumbs -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]"><?= esc_html($page_title) ?></span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                <?= esc_html($badge_text) ?>
            </span>
        </div>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($page_title) ?>
        </h1>
        <p class="mx-auto mt-3 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
            <?= esc_html($page_desc) ?>
        </p>
    </div>
</header>

<!-- Main Content Area -->
<div class="container-site max-w-5xl mx-auto px-4 py-6 sm:py-8 space-y-7 sm:space-y-9">

    <!-- Intro Callout Box (Yellow/Amber Accent Line) -->
    <div class="rounded-2xl border border-slate-200/90 border-l-[6px] border-l-amber-500 bg-gradient-to-r from-amber-50/40 via-white to-white p-5 sm:p-7 shadow-xs">
        <div class="text-sm sm:text-base leading-relaxed text-slate-700 m-0 space-y-3 prose-a:text-[#0c4161] prose-a:font-bold prose-a:underline hover:prose-a:text-amber-600">
            <p class="m-0"><?= wp_kses_post(wpautop($intro_p1)) ?></p>
            <?php if ($intro_p2): ?>
                <p class="mt-3 m-0"><?= wp_kses_post(wpautop($intro_p2)) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Australia Post Tracking Tool Card (Clean, Focused Single Card) -->
    <section class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-white p-6 sm:p-8 md:p-10 shadow-sm relative overflow-hidden" aria-label="Australia Post Order Tracking">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="text-center sm:text-left">
                <div class="inline-flex items-center gap-2 rounded-full bg-sky-100 border border-sky-300/80 px-3 py-1 text-xs font-black uppercase tracking-wider text-[#0c4161] mb-2.5">
                    <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span><?= esc_html($form_heading) ?></span>
                </div>
                <h2 class="font-heading text-2xl sm:text-3xl font-black text-[#0c4161] tracking-tight">
                    <?= esc_html($form_heading) ?>
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                    <?= wp_kses_post($form_instructions) ?>
                </p>
            </div>

            <!-- Interactive Tracking Form with Pill / Capsule Styling -->
            <form id="auspost-tracking-form" action="https://auspost.com.au/mypost/track/search" method="GET" target="_blank" rel="noopener noreferrer" class="space-y-3">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch">
                    <div class="relative flex-1">
                        <label for="auspost-tracking-input" class="sr-only"><?= esc_html($form_heading) ?></label>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 sm:pl-5 text-sky-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="auspost-tracking-input" 
                            name="id" 
                            value="<?= esc_attr($tracking_param) ?>" 
                            placeholder="<?= esc_attr($input_placeholder) ?>" 
                            autocomplete="off"
                            spellcheck="false"
                            class="w-full rounded-full border-2 border-sky-300/80 bg-slate-50/50 pl-12 pr-5 py-3.5 sm:py-4 text-base sm:text-lg font-mono tracking-wider text-slate-900 placeholder:text-slate-400 focus:border-[#0c4161] focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100 transition-all shadow-inner"
                        >
                    </div>
                    <button 
                        type="submit" 
                        id="auspost-tracking-btn"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-amber-500 via-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 active:scale-[0.98] text-white px-7 sm:px-9 py-3.5 sm:py-4 text-sm sm:text-base font-black uppercase tracking-wider shadow-md hover:shadow-lg hover:shadow-amber-500/25 transition-all shrink-0 cursor-pointer"
                    >
                        <span><?= esc_html($button_text) ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
                <div id="tracking-error-msg" class="hidden text-xs font-semibold text-rose-600 pl-4" role="alert"></div>
            </form>

            <!-- Info Alert Box (Soft Yellow/Amber) -->
            <div class="rounded-2xl border border-amber-200/90 bg-amber-50/70 p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed m-0">
                    <?= wp_kses_post($redirect_notice) ?>
                </p>
            </div>
        </div>
    </section>

    <!-- "How Order Tracking Works" Section -->
    <?php if (!empty($how_steps)): ?>
    <section class="space-y-6 pt-2" aria-labelledby="how-tracking-works-heading">
        <div class="border-b border-slate-200 pb-3">
            <h2 id="how-tracking-works-heading" class="font-heading text-2xl sm:text-3xl font-black text-[#0c4161] tracking-tight">
                <?= esc_html($how_heading) ?>
            </h2>
        </div>

        <div class="space-y-4">
            <?php foreach ($how_steps as $step): 
                $stitle = $step['title'] ?? '';
                $sdesc  = $step['description'] ?? '';
                if (!$stitle) continue;
            ?>
            <div class="rounded-2xl border border-slate-200 bg-white border-t-[4px] border-t-amber-500 p-5 sm:p-6 shadow-xs hover:border-slate-300 transition-all">
                <h3 class="font-heading text-base sm:text-lg font-black text-slate-900 mb-2">
                    <?= esc_html($stitle) ?>
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed m-0">
                    <?= esc_html($sdesc) ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- "Need Help?" Card -->
    <section class="rounded-2xl border-2 border-dashed border-sky-300/80 bg-gradient-to-r from-sky-50/60 via-white to-sky-50/40 p-6 sm:p-7 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5" aria-labelledby="need-help-heading">
        <div class="space-y-1.5 max-w-xl">
            <h3 id="need-help-heading" class="font-heading text-lg sm:text-xl font-extrabold text-[#0c4161]">
                <?= esc_html($help_title) ?>
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed m-0">
                <?= wp_kses_post($help_desc) ?>
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="<?= esc_url($help_btn_url) ?>" class="inline-flex items-center gap-2 rounded-xl bg-[#0c4161] hover:bg-[#082a40] text-white px-5 py-2.5 text-xs sm:text-sm font-extrabold uppercase tracking-wider shadow-sm transition-all">
                <span><?= esc_html($help_btn_text) ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </a>
        </div>
    </section>

    <!-- 3-Column Value Proposition / Trust Features -->
    <?php if (!empty($trust_cards)): ?>
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6 pt-2 pb-6" aria-label="Key Benefits">
        <?php foreach ($trust_cards as $card): 
            $ctype = $card['card_type'] ?? 'shipping';
            $ctitle = $card['title'] ?? '';
            $cdesc = $card['description'] ?? '';
            $cimage = $card['icon_image'] ?? null;
            if (!$ctitle) continue;

            $legacy_map = [
                'shipping' => 'express_shipping',
                'support'  => 'live_chat_support',
                'payment'  => 'aud_checkout',
            ];
            $resolved_preset = $legacy_map[$ctype] ?? $ctype;
            $card_icon_html = waklert_render_card_icon([
                'icon_image'     => $cimage,
                'icon_preset'    => $resolved_preset,
                'title'          => $ctitle,
                'desc'           => $cdesc,
                'default_preset' => 'express_shipping',
                'size_class'     => 'w-10 h-10 shrink-0',
            ]);
        ?>
        <div class="rounded-2xl border-2 border-dashed border-sky-200/90 bg-white p-6 shadow-xs hover:border-sky-400 hover:shadow-md transition-all flex flex-col items-center text-center">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white p-2 shadow-lg shadow-black/10 border-2 border-sky-100 mb-4 shrink-0">
                <?= $card_icon_html ?>
            </div>
            <h3 class="font-heading font-extrabold text-slate-900 text-base mb-2"><?= esc_html($ctitle) ?></h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed m-0">
                <?= esc_html($cdesc) ?>
            </p>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

</div>

<!-- Client-side Tracker Redirection Script -->
<script>
(function() {
    const form = document.getElementById('auspost-tracking-form');
    const input = document.getElementById('auspost-tracking-input');
    const errorMsg = document.getElementById('tracking-error-msg');

    if (!form || !input) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const rawVal = input.value || '';
        const cleanVal = rawVal.replace(/[\s\-]/g, '');

        if (cleanVal) {
            // When user enters a tracking number, open Australia Post details page which automatically fetches and displays the scan results
            const targetUrl = 'https://auspost.com.au/mypost/track/details/' + encodeURIComponent(cleanVal);
            window.open(targetUrl, '_blank', 'noopener,noreferrer');
        } else {
            // If input is empty, direct link to Australia Post tracking portal
            const portalUrl = 'https://auspost.com.au/mypost/track/';
            window.open(portalUrl, '_blank', 'noopener,noreferrer');
        }
    });

    input.addEventListener('input', function() {
        if (errorMsg && !errorMsg.classList.contains('hidden')) {
            errorMsg.classList.add('hidden');
        }
    });
})();
</script>
