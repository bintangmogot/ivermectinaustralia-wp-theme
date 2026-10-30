<?php
/**
 * Module: About Delivery
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$heading = get_sub_field('heading') ?: 'Delivery Across Australia';
$desc = get_sub_field('desc') ?: 'Current delivery options, transit times, and parcel tracking are confirmed during ordering and dispatch.';
$items = get_sub_field('list_items') ?: [];

if (!$heading && !$desc && !$items) {
    return;
}

$fallback_presets = ['australia_delivery', 'express_shipping', 'discreet_sealed', 'po_box_delivery'];
?>

<section class="py-6 md:py-10 bg-white">
    <div class="container-site max-w-5xl">
        <div class="text-center max-w-3xl mx-auto mb-8">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                Shipping &amp; Dispatch
            </span>
            <h2 class="mt-2 font-heading font-black text-2xl sm:text-3xl md:text-4xl text-[#0c4161]"><?= esc_html($heading) ?></h2>
            <?php if ($desc): ?>
                <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed whitespace-pre-line"><?= esc_html($desc) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($items): ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($items as $idx => $item):
                $item_title  = (string) ($item['title'] ?? '');
                $item_desc   = (string) ($item['desc'] ?? '');
                $icon_preset = (string) ($item['icon_preset'] ?? 'auto');
                $icon_image  = $item['icon_image'] ?? null;
                $icon_svg    = (string) ($item['icon_svg'] ?? '');

                $icon_html = waklert_render_card_icon([
                    'icon_image'     => $icon_image,
                    'icon_preset'    => $icon_preset,
                    'icon_svg'       => $icon_svg,
                    'title'          => $item_title,
                    'desc'           => $item_desc,
                    'default_preset' => $fallback_presets[$idx % count($fallback_presets)] ?? 'express_shipping',
                    'size_class'     => 'h-10 w-10 shrink-0',
                ]);
            ?>
            <div class="relative flex flex-col rounded-2xl border-2 border-dashed border-sky-200/80 bg-gradient-to-br from-sky-50/40 via-white to-transparent p-6 shadow-xs transition-all hover:border-sky-400 hover:shadow-md">
                <div class="flex items-center gap-3.5 mb-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white border border-sky-100 shadow-xs">
                        <?= $icon_html ?>
                    </span>
                    <?php if ($item_title !== ''): ?>
                        <h3 class="font-heading font-bold text-base text-slate-900 m-0"><?= esc_html($item_title) ?></h3>
                    <?php endif; ?>
                </div>
                <?php if ($item_desc !== ''): ?>
                    <p class="text-sm text-slate-600 leading-relaxed m-0"><?= esc_html($item_desc) ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
