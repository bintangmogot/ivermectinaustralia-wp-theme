<?php
/**
 * Module: Why Choose Us (Audiences / Key Benefits)
 *
 * Supports 3 flexible ways to control each card's icon in WP Admin (ACF):
 * 1. Custom Icon Image (`icon_image`) — Upload any PNG/SVG/WebP icon (e.g. from Flaticon or SVGRepo)
 * 2. Icon Style Preset (`icon_preset`) — Pick from 13 built-in multi-color flat vector icons or leave on Auto-Match
 * 3. Custom SVG Code (`icon_svg`) — Paste raw colorful `<svg>` markup
 */

$tag = get_sub_field('tag');
$heading = get_sub_field('heading') ?: "Why Buy Modafinil from Waklert Australia?";
$desc = get_sub_field('description') ?: "At Waklert Australia, we pride ourselves on providing a quick, reliable, and affordable service for all your Modafinil and Armodafinil needs. Here's why you should choose us:";

$is_about_page = is_page('about-us');

$section_classes = $is_about_page 
    ? 'relative isolate overflow-hidden section-padding bg-primary-dark text-white' 
    : 'section-padding bg-gradient-to-b from-[#f8fafc] via-[#eff6fb] to-[#f8fafc] bg-pattern-dots text-foreground border-y-2 border-dashed border-sky-200/80 relative isolate overflow-hidden';

$tag_classes = $is_about_page
    ? 'inline-flex items-center gap-2 bg-white/10 text-amber-300 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 backdrop-blur-sm border border-white/20'
    : 'inline-flex items-center gap-2 bg-sky-100 text-[#0c4161] border border-sky-300 text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 shadow-xs';

$fallback_presets = ['parcel_guarantee', 'live_chat_support', 'medical_rx_clarity', 'aud_checkout'];

// Build items list from ACF repeater
$items = [];
if (have_rows('audiences')) {
    $idx = 0;
    while (have_rows('audiences')) {
        the_row();
        $card_title  = (string) get_sub_field('title');
        $card_desc   = (string) get_sub_field('desc');
        $icon_preset = (string) (get_sub_field('icon_preset') ?: 'auto');
        $icon_image  = get_sub_field('icon_image');
        $icon_svg    = (string) get_sub_field('icon_svg');

        $icon_html = waklert_render_card_icon([
            'icon_image'     => $icon_image,
            'icon_preset'    => $icon_preset,
            'icon_svg'       => $icon_svg,
            'title'          => $card_title,
            'desc'           => $card_desc,
            'default_preset' => $fallback_presets[$idx % 4] ?? 'parcel_guarantee',
            'size_class'     => 'w-14 h-14 shrink-0',
        ]);

        $items[] = [
            'icon'  => $icon_html,
            'title' => $card_title,
            'desc'  => $card_desc,
        ];
        $idx++;
    }
}

// Fallback cards if repeater is empty
if (empty($items)) {
    $default_cards = [
        [
            'preset' => 'parcel_guarantee',
            'title'  => "If it doesn't land, we fix it",
            'desc'   => "Every order ships with Australia Post tracking. If a parcel stalls or goes missing in transit, we reship it free of charge or issue a full refund.",
        ],
        [
            'preset' => 'live_chat_support',
            'title'  => "Real people on chat",
            'desc'   => "Have a question before or after ordering? Reach our local support team via SMS, WhatsApp, or email 7 days a week for fast, practical answers.",
        ],
        [
            'preset' => 'medical_rx_clarity',
            'title'  => "Straight up about what we sell - no candy coating",
            'desc'   => "Armodafinil is a Schedule 4 prescription medicine in Australia. We provide clear batch details, strength guides, and transparent ordering information.",
        ],
        [
            'preset' => 'aud_checkout',
            'title'  => "Simple AUD checkout",
            'desc'   => "Pay securely in Australian Dollars (AUD) using local PayID or direct bank transfer — no hidden international conversion fees or complicated steps.",
        ],
    ];

    foreach ($default_cards as $card) {
        $items[] = [
            'icon'  => waklert_render_card_icon([
                'icon_preset' => $card['preset'],
                'title'       => $card['title'],
                'desc'        => $card['desc'],
                'size_class'  => 'w-14 h-14 shrink-0',
            ]),
            'title' => $card['title'],
            'desc'  => $card['desc'],
        ];
    }
}
?>

<section class="<?= esc_attr($section_classes) ?>" data-testid="<?= $is_about_page ? 'about-principles' : 'why-choose-us' ?>">
    <?php if ($is_about_page) waklert_output_pattern_rings(); ?>
    <div class="container-site relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-12 md:mb-14">
            <?php if (!empty($tag)): ?>
                <span class="<?= esc_attr($tag_classes) ?>">
                    <?= esc_html($tag) ?>
                </span>
            <?php endif; ?>
            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-extrabold <?= $is_about_page ? 'text-white' : 'text-ink' ?> tracking-tight mb-3.5">
                <?= esc_html($heading) ?>
            </h2>
            <?php if (!empty($desc)): ?>
                <p class="<?= $is_about_page ? 'text-white/80' : 'text-muted-foreground' ?> text-sm md:text-base leading-relaxed max-w-2xl mx-auto">
                    <?= esc_html($desc) ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- 4-Card Grid matching reference design -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
            <?php foreach ($items as $card): ?>
                <div class="group flex flex-col h-full rounded-2xl bg-white border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                    <!-- Top Half: Pure white surface with colorful illustration & bold title -->
                    <div class="bg-white p-6 pb-6 flex flex-col items-center text-center justify-start min-h-[180px]">
                        <div class="w-16 h-16 mb-4 flex items-center justify-center shrink-0 transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-1">
                            <?= $card['icon'] ?>
                        </div>
                        <h3 class="font-heading font-extrabold text-[17px] md:text-lg text-ink text-center leading-snug tracking-tight">
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
