<?php
/** Module: trust_strip. Sub fields: badges (icon_preset, icon_image, label, desc, icon_svg). */
$badges = get_sub_field('badges') ?: [];
$badges = array_values(array_filter($badges, static function ($badge) {
    return !empty($badge['label']);
}));
if (!$badges) {
    return;
}
?>
<section class="border-b border-border bg-white">
    <div class="container-site grid grid-cols-2 lg:grid-cols-4">
        <?php foreach ($badges as $badge) : ?>
            <div class="flex items-center gap-3 px-2 py-4 lg:border-l lg:border-border lg:px-5 lg:first:border-l-0 lg:first:pl-0">
                <?= ivermectin_render_card_icon([
                    'icon_image'  => $badge['icon_image'] ?? null,
                    'icon_preset' => $badge['icon_preset'] ?? 'auto',
                    'icon_svg'    => $badge['icon_svg'] ?? '',
                    'title'       => $badge['label'],
                    'desc'        => $badge['desc'] ?? '',
                    'size_class'  => 'h-10 w-10 shrink-0',
                ]) ?>
                <div>
                    <p class="text-sm font-bold leading-snug text-foreground"><?= esc_html($badge['label']) ?></p>
                    <?php if (!empty($badge['desc'])) : ?><p class="text-xs text-muted-foreground"><?= esc_html($badge['desc']) ?></p><?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
