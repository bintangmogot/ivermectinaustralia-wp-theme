<?php
/** Module: how_to_order. Sub fields: tag, heading, description, steps (icon_preset, icon_image, title, desc, icon_svg). */
$tag         = trim((string) get_sub_field('tag'));
$heading     = trim((string) get_sub_field('heading'));
$description = trim((string) get_sub_field('description'));
$steps       = array_values(array_filter(get_sub_field('steps') ?: [], static function ($step) {
    return !empty($step['title']);
}));
if (!$steps) {
    return;
}
?>
<section class="relative isolate overflow-hidden bg-ink py-8 text-white md:py-10" id="how-to-order">
    <?php ivermectin_output_pattern_rings(); ?>
    <div class="container-site relative z-10">
        <?php if ($tag !== '' || $heading !== '' || $description !== '') : ?>
            <div class="mb-6 max-w-2xl">
                <?php if ($tag !== '') : ?><p class="iva-eyebrow text-white/70"><?= esc_html($tag) ?></p><?php endif; ?>
                <?php if ($heading !== '') : ?><h2 class="mt-2 font-heading text-2xl font-extrabold text-white md:text-3xl"><?= esc_html($heading) ?></h2><?php endif; ?>
                <?php if ($description !== '') : ?><p class="mt-2 text-white/75"><?= esc_html($description) ?></p><?php endif; ?>
            </div>
        <?php endif; ?>
        <ol class="grid gap-0 md:grid-cols-3">
            <?php foreach ($steps as $index => $step) : ?>
                <li class="border-b border-white/15 py-5 md:border-b-0 md:border-l md:px-6 md:first:border-l-0 md:first:pl-0">
                    <div class="flex items-center gap-3">
                        <span class="font-heading text-5xl font-extrabold leading-none text-accent"><?= esc_html($index + 1) ?></span>
                        <span class="grid h-12 w-12 place-items-center rounded-lg bg-white">
                            <?= ivermectin_render_card_icon([
                                'icon_image'  => $step['icon_image'] ?? null,
                                'icon_preset' => $step['icon_preset'] ?? 'auto',
                                'icon_svg'    => $step['icon_svg'] ?? '',
                                'title'       => $step['title'],
                                'desc'        => $step['desc'] ?? '',
                                'size_class'  => 'h-9 w-9',
                            ]) ?>
                        </span>
                    </div>
                    <h3 class="mt-3 font-heading text-lg font-bold text-white"><?= esc_html($step['title']) ?></h3>
                    <?php if (!empty($step['desc'])) : ?><p class="mt-1 text-sm leading-relaxed text-white/75"><?= esc_html($step['desc']) ?></p><?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
