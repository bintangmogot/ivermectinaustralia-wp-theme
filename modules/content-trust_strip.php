<?php
/**
 * Module: Trust Strip (4 Cards below Hero)
 * Follows the colorful card design rules:
 * - Multi-color vibrant vector icons (editable via Icon Preset, Custom Image, or SVG in ACF)
 * - 2-tone card structure (White top + Navy bottom block)
 * - Dashed borders & Lift/scale animations
 */

$fallback_presets = ['australia_delivery', 'discreet_sealed', 'aud_checkout', 'live_chat_support'];
$default_descs = [
    "Tracked delivery via Australia Post to all states and territories.",
    "Direct batch-tested pharmaceuticals from accredited manufacturers.",
    "Encrypted checkout with local PayID & Australian bank EFT.",
    "Shipped in generic, unbranded satchels for complete privacy."
];

/**
 * Generate a context-aware fallback description if the badge's `desc` subfield is left empty.
 */
if (!function_exists('waklert_default_trust_badge_desc')) {
    function waklert_default_trust_badge_desc(string $label, int $idx, array $default_descs): string {
        $lower = strtolower(trim($label));
        if (str_contains($lower, 'focused') || str_contains($lower, 'category') || str_contains($lower, 'range')) {
            return 'Dedicated exclusively to genuine armodafinil tablets and verified pack sizes.';
        }
        if (str_contains($lower, 'checkout') && str_contains($lower, 'delivery')) {
            return 'Transparent shipping options, transit estimates, and Australia Post tracking at checkout.';
        }
        if (str_contains($lower, 'listing') || str_contains($lower, 'detail')) {
            return 'Clear active ingredient, strength, manufacturer, and pack options on every page.';
        }
        if (str_contains($lower, 'support') || str_contains($lower, 'question') || str_contains($lower, 'chat')) {
            return 'Direct Australian customer care via SMS, WhatsApp, and email 7 days a week.';
        }
        if (str_contains($lower, 'sealed')) {
            return 'Original factory-sealed blister packs from licensed pharmaceutical manufacturers.';
        }
        if (str_contains($lower, 'discreet')) {
            return 'Plain, unmarked satchels with no external branding for complete privacy.';
        }
        if (str_contains($lower, 'checkout') || str_contains($lower, 'payid') || str_contains($lower, 'secure')) {
            return 'Encrypted AUD checkout supporting local PayID and Australian bank transfer.';
        }
        if (str_contains($lower, 'australia') || str_contains($lower, 'delivery') || str_contains($lower, 'shipping')) {
            return 'Tracked delivery via Australia Post to metro, regional, and PO Box addresses.';
        }
        return $default_descs[$idx % 4] ?? $default_descs[0];
    }
}

$trust_items = [];
if (have_rows('badges')) {
    $idx = 0;
    while (have_rows('badges')) {
        the_row();
        $label       = (string) (get_sub_field('label') ?: 'Guaranteed Service');
        $custom_desc = trim((string) get_sub_field('desc'));
        $desc        = $custom_desc !== '' ? $custom_desc : waklert_default_trust_badge_desc($label, $idx, $default_descs);
        $icon_preset = (string) (get_sub_field('icon_preset') ?: 'auto');
        $icon_image  = get_sub_field('icon_image');
        $icon_svg    = (string) get_sub_field('icon_svg');

        $icon_html = waklert_render_card_icon([
            'icon_image'     => $icon_image,
            'icon_preset'    => $icon_preset,
            'icon_svg'       => $icon_svg,
            'title'          => $label,
            'desc'           => $desc,
            'default_preset' => $fallback_presets[$idx % 4] ?? 'australia_delivery',
            'size_class'     => 'w-14 h-14 shrink-0',
        ]);

        $trust_items[] = [
            'icon'  => $icon_html,
            'title' => $label,
            'desc'  => $desc,
        ];
        $idx++;
    }
}

if (empty($trust_items)) {
    $defaults = [
        [
            'preset' => 'australia_delivery',
            'title'  => "Australia-wide Delivery",
            'desc'   => "Tracked delivery via Australia Post to all states and territories.",
        ],
        [
            'preset' => 'discreet_sealed',
            'title'  => "Sealed Product Packaging",
            'desc'   => "Direct batch-tested pharmaceuticals from accredited manufacturers.",
        ],
        [
            'preset' => 'aud_checkout',
            'title'  => "Secure Local Checkout",
            'desc'   => "Encrypted checkout with local PayID & Australian bank EFT.",
        ],
        [
            'preset' => 'po_box_delivery',
            'title'  => "Discreet Packaging",
            'desc'   => "Shipped in generic, unbranded satchels for complete privacy.",
        ],
    ];
    foreach ($defaults as $card) {
        $trust_items[] = [
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

<section class="section-padding bg-gradient-to-b from-[#f0f6fa] via-[#e9f3f9] to-[#f8fafc] bg-pattern-dots border-y-2 border-dashed border-sky-200/80 relative isolate overflow-hidden" data-testid="trust-badges">
    <div class="container-site relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
            <?php foreach ($trust_items as $item): ?>
            <div class="group flex flex-col h-full rounded-2xl bg-white border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 ease-out">
                <!-- Top Half: Pure white surface with colorful illustration & bold title -->
                <div class="bg-white p-6 pb-5 flex flex-col items-center text-center justify-start min-h-[176px]">
                    <div class="w-16 h-16 mb-3 flex items-center justify-center shrink-0 transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-1">
                        <?= $item['icon'] ?>
                    </div>
                    <h3 class="font-heading font-extrabold text-[16px] md:text-[17px] text-ink text-center leading-snug tracking-tight">
                        <?= esc_html($item['title']) ?>
                    </h3>
                </div>

                <!-- Bottom Half: Solid theme colored block with description -->
                <div class="bg-[#0c4161] group-hover:bg-[#082c42] text-white p-4 md:p-5 text-center text-xs md:text-[13px] leading-relaxed font-normal text-white/95 flex-1 flex items-center justify-center transition-colors duration-300 border-t-2 border-dashed border-white/20">
                    <p class="leading-relaxed">
                        <?= esc_html($item['desc']) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
