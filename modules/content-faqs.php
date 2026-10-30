<?php
/**
 * Module: FAQs
 * Follows the colorful card design rules:
 * - Multi-color vibrant question icons
 * - 2-tone card structure (White header + Soft tinted answer block with dashed divider)
 * - Dashed borders
 * - Patterned gradient background
 * - Smooth hover and expansion animations
 */

$eyebrow = get_sub_field('eyebrow') ?: "FAQ";
$show_eyebrow = get_sub_field('show_eyebrow') !== false;
$heading = get_sub_field('heading') ?: "Frequently Asked Questions";
$show_link = get_sub_field('show_full_faq_link') !== false;
$link = get_sub_field('link_text') ?: "Full FAQ";
$link_url = get_sub_field('link_url') ?: home_url('/faq');
?>
<section class="section-padding bg-gradient-to-b from-[#f8fafc] via-[#eff6fb] to-[#f8fafc] bg-pattern-dots border-t-2 border-dashed border-sky-200/80 relative isolate overflow-hidden" data-testid="faq-strip">
    <div class="container-custom max-w-4xl relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 md:mb-12">
            <?php if($show_eyebrow): ?>
            <span class="inline-flex items-center gap-2 bg-sky-100 text-[#0c4161] border border-sky-300 text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#0c4161] animate-pulse-subtle"></span>
                <?= esc_html($eyebrow) ?>
            </span>
            <?php endif; ?>
            <h2 class="font-heading text-2xl md:text-4xl font-black text-ink tracking-tight mb-3">
                <?= esc_html($heading) ?>
            </h2>
            <p class="text-sm md:text-base text-muted-foreground leading-relaxed max-w-xl mx-auto">
                Practical information regarding ordering, domestic shipping, and Armodafinil prescription requirements in Australia.
            </p>
        </div>

        <div class="space-y-4">
            <?php 
            if(have_rows('faq_items')): 
                while(have_rows('faq_items')): the_row();
            ?>
            <details class="group bg-white border-2 border-dashed border-sky-300/80 open:border-[#0c4161] hover:border-[#0c4161] rounded-2xl overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300">
                <summary class="flex items-center justify-between gap-4 p-5 md:p-6 cursor-pointer list-none select-none bg-white transition-colors duration-200 group-open:bg-sky-50/60">
                    <div class="flex items-center gap-3.5 text-left">
                        <!-- Colorful Question Icon SVG -->
                        <div class="w-10 h-10 rounded-xl bg-sky-100 border border-sky-200/80 flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none">
                                <circle cx="12" cy="12" r="9" fill="#0C4161" />
                                <text x="12" y="16" font-family="system-ui, -apple-system, sans-serif" font-size="12" font-weight="900" fill="#F59E0B" text-anchor="middle">?</text>
                            </svg>
                        </div>
                        <h3 class="font-heading font-black text-ink text-base md:text-[17px] group-hover:text-[#0c4161] transition-colors leading-snug">
                            <?= esc_html(get_sub_field('question')) ?>
                        </h3>
                    </div>
                    <!-- Animated Rotating Arrow Button -->
                    <div class="w-9 h-9 rounded-full bg-slate-100 group-open:bg-[#0c4161] group-open:text-white flex items-center justify-center shrink-0 text-slate-500 transition-all duration-300 group-hover:bg-[#0c4161] group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform group-open:rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </summary>
                <!-- Answer block with dashed divider -->
                <div class="px-6 pb-6 pt-4 text-sm md:text-[15px] text-slate-600 leading-relaxed border-t-2 border-dashed border-sky-200/70 bg-sky-50/30 prose prose-sm max-w-none [&_a]:text-[#0c4161] [&_a]:font-bold hover:[&_a]:underline">
                    <?= wp_kses_post(wpautop(get_sub_field('answer'))) ?>
                </div>
            </details>
            <?php 
                endwhile;
            else:
                // Default FAQs
                $defaults = [
                    ['q' => "Is Armodafinil legal in Australia?", 'a' => "In Australia, armodafinil is a Schedule 4 prescription-only medication. You should hold a valid prescription from an Australian-registered medical practitioner before purchasing or using armodafinil."],
                    ['q' => "How long does shipping take within Australia?", 'a' => "All orders are dispatched locally within Australia. Express domestic deliveries typically arrive within 2-5 business days depending on whether your delivery address is in a metro city (Sydney, Melbourne, Brisbane) or regional/remote location."],
                    ['q' => "Do you deliver to PO Boxes and Parcel Lockers?", 'a' => "Yes, we ship via Australia Post which delivers directly to residential street addresses, Australian PO Boxes, and Australia Post 24/7 Parcel Lockers nationwide."],
                    ['q' => "How is my privacy protected during shipping?", 'a' => "All orders are packaged in plain, unbranded Australia Post satchels with zero mention of pharmaceuticals, Waklert, or medication on the exterior."]
                ];
                foreach ($defaults as $f):
            ?>
            <details class="group bg-white border-2 border-dashed border-sky-300/80 open:border-[#0c4161] hover:border-[#0c4161] rounded-2xl overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300">
                <summary class="flex items-center justify-between gap-4 p-5 md:p-6 cursor-pointer list-none select-none bg-white transition-colors duration-200 group-open:bg-sky-50/60">
                    <div class="flex items-center gap-3.5 text-left">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 border border-sky-200/80 flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none">
                                <circle cx="12" cy="12" r="9" fill="#0C4161" />
                                <text x="12" y="16" font-family="system-ui, -apple-system, sans-serif" font-size="12" font-weight="900" fill="#F59E0B" text-anchor="middle">?</text>
                            </svg>
                        </div>
                        <h3 class="font-heading font-black text-ink text-base md:text-[17px] group-hover:text-[#0c4161] transition-colors leading-snug">
                            <?= esc_html($f['q']) ?>
                        </h3>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-100 group-open:bg-[#0c4161] group-open:text-white flex items-center justify-center shrink-0 text-slate-500 transition-all duration-300 group-hover:bg-[#0c4161] group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform group-open:rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </summary>
                <div class="px-6 pb-6 pt-4 text-sm md:text-[15px] text-slate-600 leading-relaxed border-t-2 border-dashed border-sky-200/70 bg-sky-50/30 prose prose-sm max-w-none [&_a]:text-[#0c4161] [&_a]:font-bold hover:[&_a]:underline">
                    <?= wp_kses_post(wpautop($f['a'])) ?>
                </div>
            </details>
            <?php endforeach; endif; ?>
        </div>

        <?php if($show_link): ?>
        <div class="text-center mt-10">
            <a href="<?= esc_url($link_url) ?>" class="inline-flex items-center gap-2 border-2 border-dashed border-sky-300/90 bg-white hover:bg-[#0c4161] text-[#0c4161] hover:text-white font-extrabold px-5 py-2.5 sm:px-8 sm:py-3.5 rounded-full transition-all duration-300 uppercase tracking-wider sm:tracking-widest text-xs shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <span><?= esc_html($link) ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
