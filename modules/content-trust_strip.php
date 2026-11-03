<?php
/**
 * Module: trust_strip. Sub fields: badges (icon_preset, icon_image, label, desc, icon_svg).
 * A white band between thin rules: icon tile beside each label, four across on desktop.
 */
$badges = get_sub_field('badges') ?: [];
$badges = array_values(array_filter($badges, static function ($badge) {
    return !empty($badge['label']);
}));
if (!$badges) {
    return;
}
$columns = ['lg:grid-cols-1', 'lg:grid-cols-2', 'lg:grid-cols-3', 'lg:grid-cols-4'][min(4, count($badges)) - 1];
?>
<section class="iva-trust border-y border-border bg-white">
    <ul class="container-site grid grid-cols-1 gap-8 py-12 sm:grid-cols-2 md:py-14 <?= esc_attr($columns) ?>" data-iva-stagger>
        <?php foreach ($badges as $badge) : ?>
            <li class="flex items-start gap-4">
                <span class="iva-icon-tile grid h-14 w-14 shrink-0 place-items-center rounded-2xl">
                    <?= ivermectin_render_card_icon([
                        'icon_image'  => $badge['icon_image'] ?? null,
                        'icon_preset' => $badge['icon_preset'] ?? 'auto',
                        'icon_svg'    => $badge['icon_svg'] ?? '',
                        'title'       => $badge['label'],
                        'desc'        => $badge['desc'] ?? '',
                        'size_class'  => 'h-9 w-9',
                    ]) ?>
                </span>
                <span>
                    <span class="block font-heading text-[1.1875rem] font-bold leading-snug text-primary"><?= esc_html($badge['label']) ?></span>
                    <?php if (!empty($badge['desc'])) : ?><span class="mt-1 block text-[0.9375rem] leading-relaxed text-muted-foreground"><?= esc_html($badge['desc']) ?></span><?php endif; ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
