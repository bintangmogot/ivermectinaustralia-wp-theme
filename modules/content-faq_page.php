<?php
/**
 * FAQ Full Page Module
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$eyebrow = get_sub_field('eyebrow') ?: 'FAQ & Support';
$heading = get_sub_field('heading') ?: 'Frequently Asked Questions';
$desc = get_sub_field('description') ?: 'General information about armodafinil products, Australian prescription requirements, ordering and delivery.';
$categories = get_sub_field('faq_categories') ?: [];
$cta_heading = get_sub_field('cta_heading') ?: 'Still Have Questions?';
$cta_desc = get_sub_field('cta_desc') ?: 'Contact our Australian support team for assistance with orders, Australia Post tracking, and product information.';
$cta_btn = get_sub_field('cta_btn_text') ?: 'Contact Us';
$cta_link = get_sub_field('cta_btn_link') ?: home_url('/contact/');

$whatsapp_number = function_exists('get_field') ? get_field('whatsapp_number', 'option') : '';
if (!$whatsapp_number && function_exists('waklert_get_whatsapp_number')) {
    $whatsapp_number = waklert_get_whatsapp_number();
}
$whatsapp_clean = $whatsapp_number ? preg_replace('/[^0-9]/', '', $whatsapp_number) : '61488841833';
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9" data-testid="faq-page-hero">
    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
        <!-- Breadcrumbs -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]">FAQ</span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                <?= esc_html($eyebrow) ?>
            </span>
        </div>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($heading) ?>
        </h1>

        <?php if ($desc): ?>
        <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
            <?= esc_html($desc) ?>
        </p>
        <?php endif; ?>

        <!-- Quick Jump Category Navigation Pills -->
        <?php if (!empty($categories)): ?>
        <div class="mt-7 flex flex-wrap items-center justify-center gap-2">
            <?php foreach ($categories as $c_idx => $cat): 
                $cat_title = $cat['title'] ?? ('Category ' . ($c_idx + 1));
                $cat_slug = sanitize_title($cat_title);
            ?>
            <a href="#cat-<?= esc_attr($cat_slug) ?>" 
               class="inline-flex items-center gap-1.5 rounded-full bg-white/95 border border-dashed border-sky-300 px-3.5 py-1.5 text-xs font-bold text-[#0c4161] hover:bg-[#0c4161] hover:text-white hover:border-[#0c4161] transition-all shadow-2xs">
                <span><?= esc_html($cat_title) ?></span>
                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-sky-100 group-hover:bg-white/20 text-[10px] font-mono text-[#0c4161] font-bold">
                    <?= count($cat['items'] ?? []) ?>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</header>

<!-- Main FAQ Content Section -->
<section class="py-7 md:py-10 bg-gradient-to-b from-white via-slate-50/40 to-white">
    <div class="container-site max-w-4xl space-y-12">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $c_idx => $category): 
                $cat_title = $category['title'] ?? '';
                $cat_slug = sanitize_title($cat_title);
                $items = $category['items'] ?? [];
            ?>
            <div id="cat-<?= esc_attr($cat_slug) ?>" class="scroll-mt-28">
                <!-- Category Section Header -->
                <?php if ($cat_title): ?>
                <div class="flex items-center justify-between gap-3 mb-5 pb-3 border-b-2 border-dashed border-sky-200/80">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-100 font-mono text-xs font-black text-[#0c4161]">
                            <?= sprintf('%02d', $c_idx + 1) ?>
                        </span>
                        <h2 class="font-heading text-lg sm:text-xl md:text-2xl font-black text-[#0c4161] m-0">
                            <?= esc_html($cat_title) ?>
                        </h2>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200/60 px-2.5 py-0.5 text-xs font-bold text-slate-500">
                        <?= count($items) ?> questions
                    </span>
                </div>
                <?php endif; ?>

                <!-- Accordion Stack -->
                <?php if (!empty($items)): ?>
                <div class="space-y-3.5">
                    <?php foreach ($items as $i_idx => $item): 
                        $q = $item['question'] ?? '';
                        $a = $item['answer'] ?? '';
                        // First item in first category starts open for quick preview
                        $is_open = ($c_idx === 0 && $i_idx === 0) ? 'open' : '';
                    ?>
                    <details <?= $is_open ?> class="group rounded-2xl border-2 border-dashed border-sky-200/80 bg-white overflow-hidden shadow-xs hover:border-sky-300 transition-all duration-200 open:border-sky-300 open:shadow-sm">
                        <summary class="flex items-center justify-between gap-4 p-5 cursor-pointer list-none select-none bg-white transition-colors hover:bg-sky-50/40 group-open:bg-sky-50/50">
                            <div class="flex items-center gap-3.5 text-left">
                                <!-- Distinct Question Icon -->
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-sky-100 border border-sky-200/80 shadow-2xs group-hover:scale-105 transition-transform" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none">
                                        <circle cx="12" cy="12" r="9" fill="#0C4161" />
                                        <text x="12" y="16" font-family="system-ui, -apple-system, sans-serif" font-size="11" font-weight="900" fill="#F59E0B" text-anchor="middle">?</text>
                                    </svg>
                                </div>
                                <h3 class="font-heading text-sm sm:text-base font-extrabold text-[#0c4161] group-hover:text-sky-950 transition-colors leading-snug">
                                    <?= esc_html($q) ?>
                                </h3>
                            </div>
                            <!-- Rotating Arrow Chevron Button -->
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-all duration-200 group-hover:bg-[#0c4161] group-hover:text-white group-open:bg-[#0c4161] group-open:text-white" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transform transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>
                        <!-- Answer Block with Dashed Divider -->
                        <div class="border-t-2 border-dashed border-sky-200/70 bg-gradient-to-b from-sky-50/30 via-white to-white px-6 pb-6 pt-4 text-sm sm:text-[15px] leading-relaxed text-slate-600 prose max-w-none [&_a]:text-[#0c4161] [&_a]:font-bold hover:[&_a]:underline">
                            <?= wp_kses_post(wpautop($a)) ?>
                        </div>
                    </details>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- 2-Tone Support CTA Card -->
        <div class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-[#f0f6fa] via-white to-sky-50/50 p-8 sm:p-10 md:p-12 shadow-xs text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-2xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                Direct Customer Care
            </span>
            <h2 class="mt-3 font-heading text-xl sm:text-2xl md:text-3xl font-black text-[#0c4161]">
                <?= esc_html($cta_heading) ?>
            </h2>
            <p class="mx-auto mt-2 mb-6 max-w-lg text-sm sm:text-base leading-relaxed text-slate-600">
                <?= esc_html($cta_desc) ?>
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="https://wa.me/<?= esc_attr($whatsapp_clean) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 text-xs sm:text-sm shadow-sm transition-colors">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2zm.01 1.67c2.2 0 4.26.86 5.82 2.41a8.17 8.17 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.188 8.188 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24zm4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.41-1.75-.14-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.44.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.07-.12-.22-.19-.47-.32z"/>
                    </svg>
                    WhatsApp Support &rarr;
                </a>
                <a href="<?= esc_url($cta_link) ?>" class="inline-flex items-center gap-2 rounded-full bg-[#0c4161] hover:bg-[#082c42] text-white font-bold px-6 py-3 text-xs sm:text-sm shadow-sm transition-colors">
                    <?= esc_html($cta_btn) ?> &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
