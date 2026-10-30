<?php
/** Module: page_hero. Sub fields: eyebrow, title, subtitle, bullets (bullet). */
$eyebrow  = trim((string) get_sub_field('eyebrow'));
$title    = trim((string) get_sub_field('title'));
$subtitle = trim((string) get_sub_field('subtitle'));
$bullets  = array_filter(array_column(get_sub_field('bullets') ?: [], 'bullet'));

if ($title === '') {
    if (function_exists('is_shop') && is_shop() && function_exists('wc_get_page_id') && wc_get_page_id('shop') > 0) {
        $title = get_the_title(wc_get_page_id('shop'));
    } else {
        $title = get_the_title();
    }
}
?>
<header class="relative isolate overflow-hidden bg-primary text-white">
    <?php ivermectin_output_pattern_rings(); ?>
    <div class="container-site relative z-10 py-8 md:py-12">
        <?php if ($eyebrow !== '') : ?><p class="iva-eyebrow text-white/70"><?= esc_html($eyebrow) ?></p><?php endif; ?>
        <h1 class="mt-2 max-w-3xl font-heading text-3xl font-extrabold leading-tight text-white sm:text-4xl md:text-5xl"><?= esc_html($title) ?></h1>
        <?php if ($subtitle !== '') : ?><p class="mt-3 max-w-2xl text-base leading-relaxed text-white/80"><?= esc_html($subtitle) ?></p><?php endif; ?>
        <?php if ($bullets) : ?>
            <ul class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-sm text-white/85">
                <?php foreach ($bullets as $bullet) : ?><li class="flex items-center gap-2"><?= ivermectin_icon('no_customs', 'h-5 w-5') ?><?= esc_html($bullet) ?></li><?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</header>
