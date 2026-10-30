<?php
/**
 * Module: How to Order (Steps)
 * Follows the colorful card design rules:
 * - Multi-color vibrant vector icons (editable via Icon Preset, Custom Image, or SVG in ACF)
 * - 2-tone card structure (White top with colorful icon + Navy bottom block)
 * - Dashed borders & Lift/scale animations
 */

$tag = get_sub_field('tag') ?: "Simple Process";
$heading = get_sub_field('heading') ?: "Three Steps from Product Information to Delivery";
$description = get_sub_field('description') ?: "Simple, transparent, and private. Ordering Armodafinil across Australia takes just 3 straightforward steps.";

$is_about_page = is_page('about-us');
$section_classes = $is_about_page 
    ? 'relative isolate overflow-hidden section-padding bg-primary-dark text-white' 
    : 'section-padding bg-gradient-to-b from-[#f0f6fa] via-[#e9f3f9] to-[#f8fafc] bg-pattern-dots border-y-2 border-dashed border-sky-200/80 relative isolate overflow-hidden';

$tag_classes = $is_about_page
    ? 'inline-flex items-center gap-2 bg-white/10 text-amber-300 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 backdrop-blur-sm border border-white/20'
    : 'inline-flex items-center gap-2 bg-amber-100/90 text-amber-900 border border-amber-300/80 text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 shadow-xs';

$fallback_presets = ['browse_catalogue', 'aud_checkout', 'express_shipping'];

// Step items from ACF repeater
$step_items = [];
if (have_rows('steps')) {
    $idx = 0;
    while (have_rows('steps')) {
        the_row();
        $step_title  = (string) get_sub_field('title');
        $step_desc   = (string) get_sub_field('desc');
        $icon_preset = (string) (get_sub_field('icon_preset') ?: 'auto');
        $icon_image  = get_sub_field('icon_image');
        $icon_svg    = (string) get_sub_field('icon_svg');

        $icon_html = waklert_render_card_icon([
            'icon_image'     => $icon_image,
            'icon_preset'    => $icon_preset,
            'icon_svg'       => $icon_svg,
            'title'          => $step_title,
            'desc'           => $step_desc,
            'default_preset' => $fallback_presets[$idx % 3] ?? 'browse_catalogue',
            'size_class'     => 'w-14 h-14 shrink-0',
        ]);

        $step_items[] = [
            'icon'  => $icon_html,
            'title' => $step_title,
            'desc'  => $step_desc,
            'step'  => sprintf('%02d', $idx + 1),
        ];
        $idx++;
    }
}

if (empty($step_items)) {
    $defaults = [
        [
            'preset' => 'browse_catalogue',
            'title'  => "Review the Range",
            'desc'   => "Compare available armodafinil brands, strengths and pack sizes. Read the product information before making a selection.",
            'step'   => "01",
        ],
        [
            'preset' => 'aud_checkout',
            'title'  => "Confirm Your Order",
            'desc'   => "Choose an available payment method at checkout. Order details are handled through the secure checkout process.",
            'step'   => "02",
        ],
        [
            'preset' => 'express_shipping',
            'title'  => "Track Delivery",
            'desc'   => "When an order is dispatched, Australia Post tracking details are provided so you can follow its progress.",
            'step'   => "03",
        ],
    ];
    foreach ($defaults as $card) {
        $step_items[] = [
            'icon'  => waklert_render_card_icon([
                'icon_preset' => $card['preset'],
                'title'       => $card['title'],
                'desc'        => $card['desc'],
                'size_class'  => 'w-14 h-14 shrink-0',
            ]),
            'title' => $card['title'],
            'desc'  => $card['desc'],
            'step'  => $card['step'],
        ];
    }
}
?>

<section class="<?= esc_attr($section_classes) ?>" data-testid="<?= $is_about_page ? 'about-site-guide' : 'how-it-works' ?>">
    <?php if ($is_about_page) waklert_output_pattern_rings(); ?>
    <div class="container-site relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-6 md:mb-8">
            <?php if (!empty($tag)): ?>
                <span class="<?= esc_attr($tag_classes) ?>">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse-subtle"></span>
                    <?= esc_html($tag) ?>
                </span>
            <?php endif; ?>
            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-extrabold <?= $is_about_page ? 'text-white' : 'text-ink' ?> tracking-tight mb-3.5">
                <?= esc_html($heading) ?>
            </h2>
            <?php if (!empty($description)): ?>
                <p class="<?= $is_about_page ? 'text-white/80' : 'text-muted-foreground' ?> text-sm md:text-base leading-relaxed max-w-2xl mx-auto">
                    <?= esc_html($description) ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- 3-Card Grid matching the 2-Tone Dashed Card Rule -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch max-w-5xl mx-auto">
            <?php foreach ($step_items as $card): ?>
                <div class="group flex flex-col h-full rounded-2xl bg-white border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                    <!-- Top Half: Pure white surface with colorful illustration, step pill & bold title -->
                    <div class="bg-white p-6 pb-6 flex flex-col items-center text-center justify-start relative min-h-[205px]">
                        <!-- Step Badge -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-[11px] font-black tracking-widest uppercase mb-4 shadow-xs">
                            <span>STEP <?= esc_html($card['step']) ?></span>
                        </div>

                        <!-- Colorful Vector Illustration -->
                        <div class="w-16 h-16 mb-4 flex items-center justify-center shrink-0 transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-1">
                            <?= $card['icon'] ?>
                        </div>

                        <!-- Card Title -->
                        <h3 class="font-heading font-extrabold text-[18px] md:text-xl text-ink text-center leading-snug tracking-tight">
                            <?= esc_html($card['title']) ?>
                        </h3>
                    </div>

                    <!-- Bottom Half: Solid theme colored block with description -->
                    <div class="bg-[#0c4161] group-hover:bg-[#082c42] text-white p-5 md:p-6 text-center text-xs md:text-[13px] leading-relaxed font-normal text-white/95 flex-1 flex items-center justify-center transition-colors duration-300 border-t-2 border-dashed border-white/20">
                        <p class="leading-relaxed">
                            <?= esc_html($card['desc']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
