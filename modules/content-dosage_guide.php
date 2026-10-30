<?php
/**
 * Armodafinil Information Guide Module.
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$page_badge       = (string) get_sub_field('page_badge') ?: 'Medicine Information Guide';
$page_title       = (string) get_sub_field('page_title') ?: 'Armodafinil Information Guide for Australian Customers';
$page_subtitle    = (string) get_sub_field('page_subtitle') ?: 'General information about clinical formulations, dosing schedules, and responsible personal importation under TGA guidelines.';
$disclaimer_label = (string) get_sub_field('disclaimer_label') ?: 'Medical Disclaimer';
$disclaimer       = (string) get_sub_field('disclaimer') ?: 'Armodafinil is a Schedule 4 prescription medicine in Australia. Use it strictly as directed by an Australian registered healthcare professional.';

$guide_heading    = (string) get_sub_field('guide_heading') ?: 'Formulations & Dosage Profiles';
$guide_intro      = (string) get_sub_field('guide_intro') ?: 'Armodafinil contains pure, enantiopure (R)-enantiomer molecules. Compare common daily dosages below:';
$products_heading = (string) get_sub_field('products_heading') ?: 'Products Available in Australia';
$products_button_text = (string) get_sub_field('products_button_text') ?: 'Shop All Products';
$products_button_link = (string) get_sub_field('products_button_link') ?: '/shop/';
$timing_heading   = (string) get_sub_field('timing_heading') ?: 'Administration Schedule & Timing';
$timing_intro     = (string) get_sub_field('timing_intro') ?: 'Because Armodafinil possesses an extended 15-hour elimination half-life, timing is critical for sleep architecture:';
$import_heading   = (string) get_sub_field('import_heading') ?: 'Australian Prescription & TGA Guidelines';
$import_intro     = (string) get_sub_field('import_intro') ?: 'Important regulatory standards for ordering Schedule 4 prescription goods into Australian territory:';
$faq_heading      = (string) get_sub_field('faq_heading') ?: 'Frequently Asked Questions';
$cta_heading      = (string) get_sub_field('cta_heading') ?: 'Review Genuine Formulations';
$cta_description  = (string) get_sub_field('cta_description') ?: 'Browse our verified blister packs from Sun Pharma and HAB Pharma with express dispatch across Australia.';
$shop_button_text = (string) get_sub_field('shop_button_text') ?: 'Browse Catalogue';
$shop_button_link = (string) get_sub_field('shop_button_link') ?: '/shop/';
$contact_button_text = (string) get_sub_field('contact_button_text') ?: 'Contact Support';
$contact_button_link = (string) get_sub_field('contact_button_link') ?: '/contact/';

$dosage_cards  = get_sub_field('dosage_cards');
$product_cards = get_sub_field('product_cards');
$timing_items  = get_sub_field('timing_items');
$import_checks = get_sub_field('malaysia_tips');
$faq_items     = get_sub_field('faq_items');

$dosage_cards  = is_array($dosage_cards) ? $dosage_cards : [];
$product_cards = is_array($product_cards) ? $product_cards : [];
$timing_items  = is_array($timing_items) ? $timing_items : [];
$import_checks = is_array($import_checks) ? $import_checks : [];
$faq_items     = is_array($faq_items) ? $faq_items : [];
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
        <!-- Breadcrumbs -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]">Armodafinil Guide</span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <?php if ($page_badge !== ''): ?>
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                <?= esc_html($page_badge) ?>
            </span>
        </div>
        <?php endif; ?>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($page_title) ?>
        </h1>

        <?php if ($page_subtitle !== ''): ?>
        <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
            <?= nl2br(esc_html($page_subtitle)) ?>
        </p>
        <?php endif; ?>

        <!-- Medical Disclaimer Banner -->
        <?php if ($disclaimer_label !== '' || $disclaimer !== ''): ?>
        <div class="mt-6 mx-auto max-w-3xl flex items-start gap-3 rounded-2xl border-2 border-dashed border-amber-300 bg-amber-50/90 p-4 text-left text-xs sm:text-sm text-amber-950 shadow-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <?php if ($disclaimer_label !== ''): ?><strong class="font-bold text-amber-900"><?= esc_html($disclaimer_label) ?>:</strong> <?php endif; ?>
                <?= nl2br(esc_html($disclaimer)) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</header>

<!-- Dosage Cards Section -->
<section class="py-7 md:py-10 bg-white">
    <div class="container-site max-w-6xl">
        <?php if ($guide_heading !== '' || $guide_intro !== ''): ?>
            <div class="mb-10 text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                    Dosage Breakdown
                </span>
                <?php if ($guide_heading !== ''): ?>
                    <h2 class="mt-2 font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161]">
                        <?= esc_html($guide_heading) ?>
                    </h2>
                <?php endif; ?>
                <?php if ($guide_intro !== ''): ?>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                        <?= nl2br(esc_html($guide_intro)) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($dosage_cards): ?>
            <div class="grid gap-6 md:grid-cols-3">
                <?php foreach ($dosage_cards as $i => $card): ?>
                    <article class="flex h-full flex-col justify-between rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-7 shadow-xs transition-all duration-200 hover:border-sky-400 hover:shadow-md">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 font-heading text-sm font-black text-[#0c4161]">
                                    <?= esc_html(sprintf('%02d', $i + 1)) ?>
                                </span>
                                <?php if (!empty($card['label'])): ?>
                                    <span class="rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-amber-800">
                                        <?= esc_html($card['label']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($card['dose'])): ?>
                                <h3 class="font-heading text-xl font-black text-slate-900 mb-2">
                                    <?= esc_html($card['dose']) ?>
                                </h3>
                            <?php endif; ?>
                            <?php if (!empty($card['desc'])): ?>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    <?= nl2br(esc_html($card['desc'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Products Available in Australia -->
<section class="py-7 md:py-10 bg-gradient-to-b from-slate-50/60 via-white to-slate-50/40">
    <div class="container-site max-w-6xl">
        <?php if ($products_heading !== ''): ?>
            <div class="mb-10 text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                    Catalogue
                </span>
                <h2 class="mt-2 font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161]">
                    <?= esc_html($products_heading) ?>
                </h2>
            </div>
        <?php endif; ?>

        <?php if ($product_cards): ?>
            <div class="grid gap-5 md:grid-cols-2">
                <?php foreach ($product_cards as $prod): ?>
                    <?php if (empty($prod['link'])) { continue; } ?>
                    <a href="<?= esc_url($prod['link']) ?>" class="group flex items-center gap-5 rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs transition-all hover:border-sky-400 hover:shadow-md">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-sky-100 font-heading text-xl font-black text-[#0c4161] group-hover:bg-[#0c4161] group-hover:text-white transition-colors">
                            <?= esc_html(strtoupper(substr((string) ($prod['name'] ?? ''), 0, 1))) ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-heading font-black text-slate-900 text-lg group-hover:text-[#0c4161] transition-colors"><?= esc_html($prod['name'] ?? '') ?></p>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 group-hover:text-[#0c4161] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </div>
                            <?php if (!empty($prod['use'])): ?>
                                <p class="text-sm text-slate-600 mt-1"><?= esc_html($prod['use']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($prod['dose']) || !empty($prod['duration'])): ?>
                                <div class="mt-2.5 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs">
                                    <?php if (!empty($prod['dose'])): ?>
                                        <span class="rounded-full bg-sky-50 border border-sky-200 px-2.5 py-0.5 font-bold text-[#0c4161]"><?= esc_html($prod['dose']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($prod['duration'])): ?>
                                        <span class="text-slate-500 font-medium"><?= esc_html($prod['duration']) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($products_button_text !== '' && $products_button_link !== ''): ?>
            <div class="mt-10 text-center">
                <a href="<?= esc_url($products_button_link) ?>" class="inline-flex rounded-full bg-[#0c4161] hover:bg-[#082c42] px-7 py-3 text-sm font-bold text-white shadow-xs transition-colors">
                    <?= esc_html($products_button_text) ?> &rarr;
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Administration Schedule & Timing -->
<section class="py-7 md:py-10 bg-white">
    <div class="container-site max-w-5xl">
        <?php if ($timing_heading !== '' || $timing_intro !== ''): ?>
            <div class="mb-10 text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                    Timing Checklist
                </span>
                <?php if ($timing_heading !== ''): ?>
                    <h2 class="mt-2 font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161]">
                        <?= esc_html($timing_heading) ?>
                    </h2>
                <?php endif; ?>
                <?php if ($timing_intro !== ''): ?>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                        <?= nl2br(esc_html($timing_intro)) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($timing_items): ?>
            <div class="grid gap-6 md:grid-cols-3">
                <?php foreach ($timing_items as $item): ?>
                    <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-gradient-to-br from-sky-50/40 via-white to-transparent p-6 shadow-xs">
                        <?php if (!empty($item['time'])): ?>
                            <div class="inline-flex rounded-full bg-[#0c4161] px-3.5 py-1 text-xs font-black text-white mb-3">
                                <?= esc_html($item['time']) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['desc'])): ?>
                            <p class="text-sm leading-relaxed text-slate-600 m-0">
                                <?= nl2br(esc_html($item['desc'])) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Australian Prescription & Import Information -->
<section class="py-7 md:py-10 bg-gradient-to-b from-slate-50/60 via-white to-slate-50/40">
    <div class="container-site max-w-5xl">
        <?php if ($import_heading !== '' || $import_intro !== ''): ?>
            <div class="mb-10 text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                    TGA Compliance
                </span>
                <?php if ($import_heading !== ''): ?>
                    <h2 class="mt-2 font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161]">
                        <?= esc_html($import_heading) ?>
                    </h2>
                <?php endif; ?>
                <?php if ($import_intro !== ''): ?>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                        <?= nl2br(esc_html($import_intro)) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($import_checks): ?>
            <div class="grid gap-5 md:grid-cols-2">
                <?php foreach ($import_checks as $tip): ?>
                    <div class="flex gap-4 rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs">
                        <?php if (!empty($tip['emoji'])): ?>
                            <span class="text-3xl shrink-0 mt-0.5"><?= esc_html($tip['emoji']) ?></span>
                        <?php endif; ?>
                        <div>
                            <?php if (!empty($tip['title'])): ?>
                                <h3 class="font-heading font-black text-slate-900 text-base mb-1">
                                    <?= esc_html($tip['title']) ?>
                                </h3>
                            <?php endif; ?>
                            <?php if (!empty($tip['desc'])): ?>
                                <p class="text-sm leading-relaxed text-slate-600 m-0">
                                    <?= nl2br(esc_html($tip['desc'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- FAQs Section -->
<?php if ($faq_items): ?>
<section class="py-7 md:py-10 bg-white">
    <div class="container-site max-w-5xl">
        <div class="mb-10 text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                Questions & Answers
            </span>
            <h2 class="mt-2 font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161]">
                <?= esc_html($faq_heading) ?>
            </h2>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <?php foreach ($faq_items as $faq): ?>
                <details class="group rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs transition-all hover:border-sky-400 open:border-sky-400 open:bg-sky-50/20">
                    <summary class="flex items-center justify-between gap-4 font-heading text-base font-bold text-slate-900 cursor-pointer list-none">
                        <?= esc_html($faq['q'] ?? '') ?>
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-100 text-[#0c4161] group-open:-rotate-180 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <?php if (!empty($faq['a'])): ?>
                        <div class="mt-4 pt-3 border-t border-slate-100 text-sm leading-relaxed text-slate-600 prose prose-sm max-w-none">
                            <?= wp_kses_post(wpautop($faq['a'])) ?>
                        </div>
                    <?php endif; ?>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Bottom Support CTA -->
<section class="py-8 md:py-10 bg-white">
    <div class="container-site max-w-4xl text-center">
        <div class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-[#f0f6fa] via-white to-sky-50/30 p-8 sm:p-12 shadow-xs">
            <?php if ($cta_heading !== ''): ?>
                <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161] mb-3">
                    <?= esc_html($cta_heading) ?>
                </h2>
            <?php endif; ?>
            <?php if ($cta_description !== ''): ?>
                <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto leading-relaxed mb-8">
                    <?= nl2br(esc_html($cta_description)) ?>
                </p>
            <?php endif; ?>
            <div class="flex flex-wrap items-center justify-center gap-3.5">
                <a href="<?= esc_url($shop_button_link) ?>" class="inline-flex rounded-full bg-[#0c4161] hover:bg-[#082c42] px-7 py-3 text-sm font-bold text-white shadow-sm transition-colors">
                    <?= esc_html($shop_button_text) ?>
                </a>
                <a href="<?= esc_url($contact_button_link) ?>" class="inline-flex items-center gap-1.5 rounded-full bg-white hover:bg-slate-50 text-[#0c4161] border border-sky-300 px-6 py-3 text-sm font-bold shadow-xs transition-colors">
                    <?= esc_html($contact_button_text) ?> &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
