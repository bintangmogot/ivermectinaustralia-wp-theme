<?php
/**
 * Refund Policy Module
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$title = get_sub_field('title') ?: 'Refund & Reshipment Policy';
$subtitle = get_sub_field('subtitle') ?: 'Our commitment to hassle-free delivery: free replacements if your package is delayed, damaged, or lost in transit.';
$badges = get_sub_field('badges') ?: [];
$sections = get_sub_field('sections') ?: [];

$default_badges = [
    '100% Free Reshipment',
    'Discreet Parcel Protection',
    'Dedicated Order Support'
];

$default_sections = [
    [
        'title' => '1. Free Reshipment Guarantee',
        'content' => 'If your parcel is lost in transit, seized, or has not arrived within 21 business days from the date of dispatch, Waklert Australia will immediately issue a free reshipment of your complete order. We manage all follow-ups with logistics carriers so you do not have to.'
    ],
    [
        'title' => '2. Damaged or Incomplete Shipments',
        'content' => 'In the unlikely event that your blister packs arrive damaged or items are missing, please take clear photos of the packaging and blister foils within 48 hours of delivery and email them to our support team. We will dispatch replacement items at zero additional charge.'
    ],
    [
        'title' => '3. Address Errors & Incorrect Postcodes',
        'content' => 'Please verify your postal address carefully before placing your order. We are unable to provide complimentary reshipments if a parcel is lost due to an invalid or incomplete address supplied during checkout. If a parcel is returned to sender, re-delivery fees may apply.'
    ]
];
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
        <!-- Breadcrumbs -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]">Refund Policy</span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
                Delivery Guarantee
            </span>
        </div>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($title) ?>
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
            <?= esc_html($subtitle) ?>
        </p>

        <!-- Guarantee Badges -->
        <div class="mt-6 flex flex-wrap justify-center gap-2.5">
            <?php if (!empty($badges)): ?>
                <?php foreach ($badges as $item): if (empty($item['badge'])) continue; ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 border border-dashed border-sky-300 px-3.5 py-1.5 text-xs font-bold text-[#0c4161] shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <?= esc_html($item['badge']) ?>
                </span>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($default_badges as $b): ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 border border-dashed border-sky-300 px-3.5 py-1.5 text-xs font-bold text-[#0c4161] shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <?= esc_html($b) ?>
                </span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</header>

<section class="py-7 md:py-10 bg-gradient-to-b from-white via-slate-50/50 to-white">
    <div class="container-site max-w-4xl space-y-6">
        <?php if (!empty($sections)): ?>
            <?php foreach ($sections as $idx => $section): ?>
            <article class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-8 shadow-xs transition-all hover:border-sky-300">
                <?php if (!empty($section['title'])): ?>
                <div class="flex items-center gap-2 mb-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-sky-100 font-mono text-xs font-bold text-[#0c4161]">
                        <?= sprintf('%02d', $idx + 1) ?>
                    </span>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-[#0c4161] m-0">
                        <?= esc_html($section['title']) ?>
                    </h2>
                </div>
                <?php endif; ?>
                <?php if (!empty($section['content'])): ?>
                    <div class="prose max-w-none text-slate-600 text-sm sm:text-base leading-relaxed mt-3">
                        <?= wp_kses_post(wpautop($section['content'])) ?>
                    </div>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach ($default_sections as $idx => $sec): ?>
            <article class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-8 shadow-xs transition-all hover:border-sky-300">
                <div class="flex items-center gap-2 mb-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-sky-100 font-mono text-xs font-bold text-[#0c4161]">
                        <?= sprintf('%02d', $idx + 1) ?>
                    </span>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-[#0c4161] m-0">
                        <?= esc_html($sec['title']) ?>
                    </h2>
                </div>
                <div class="prose max-w-none text-slate-600 text-sm sm:text-base leading-relaxed mt-3">
                    <?= wp_kses_post(wpautop($sec['content'])) ?>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Support Callout Box -->
        <div class="mt-10 rounded-3xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-[#f0f6fa] via-white to-sky-50/40 p-8 sm:p-10 shadow-xs text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                Need Help With a Parcel?
            </span>
            <h2 class="mt-2.5 font-heading font-black text-xl sm:text-2xl text-[#0c4161]">
                Questions About an Order or Reshipment?
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2 mb-6 max-w-lg mx-auto leading-relaxed">
                Contact our customer support team with your order reference number and we will resolve any logistics enquiry immediately.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="<?= esc_url(home_url('/contact/')) ?>" class="inline-flex items-center gap-2 rounded-full bg-[#0c4161] hover:bg-[#082c42] text-white font-bold px-7 py-3 text-sm shadow-sm transition-colors">
                    Contact Order Support &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
