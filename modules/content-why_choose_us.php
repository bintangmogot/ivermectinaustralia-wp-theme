<?php
/** Module: why_choose_us. Sub fields: tag, heading, description, audiences (icon_preset, icon_image, title, desc, icon_svg). */
$tag         = trim((string) get_sub_field('tag'));
$heading     = trim((string) get_sub_field('heading'));
$description = trim((string) get_sub_field('description'));
$items       = array_values(array_filter(get_sub_field('audiences') ?: [], static function ($item) {
    return !empty($item['title']);
}));
if (!$items) {
    return;
}
?>
<section class="py-8 md:py-10">
    <div class="container-site">
        <?php if ($tag !== '' || $heading !== '' || $description !== '') : ?>
            <div class="mb-6 max-w-2xl">
                <?php if ($tag !== '') : ?><p class="iva-eyebrow text-link"><?= esc_html($tag) ?></p><?php endif; ?>
                <?php if ($heading !== '') : ?><h2 class="mt-2 font-heading text-2xl font-extrabold text-primary md:text-3xl"><?= esc_html($heading) ?></h2><?php endif; ?>
                <?php if ($description !== '') : ?><p class="mt-2 text-muted-foreground"><?= esc_html($description) ?></p><?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($items as $index => $item) : ?>
                <div class="lcard">
                    <span class="lcard-tab"><?= esc_html(sprintf('%02d', $index + 1)) ?></span>
                    <div class="p-5">
                        <?= ivermectin_render_card_icon([
                            'icon_image'  => $item['icon_image'] ?? null,
                            'icon_preset' => $item['icon_preset'] ?? 'auto',
                            'icon_svg'    => $item['icon_svg'] ?? '',
                            'title'       => $item['title'],
                            'desc'        => $item['desc'] ?? '',
                            'size_class'  => 'h-12 w-12',
                        ]) ?>
                        <h3 class="mt-3 font-heading text-lg font-bold text-foreground"><?= esc_html($item['title']) ?></h3>
                        <?php if (!empty($item['desc'])) : ?><p class="mt-1.5 text-sm leading-relaxed text-muted-foreground"><?= esc_html($item['desc']) ?></p><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
