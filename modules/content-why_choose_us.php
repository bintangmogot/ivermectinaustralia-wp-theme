<?php
/**
 * Module: why_choose_us. Sub fields: tag, heading, description, audiences (icon_preset, icon_image, title, desc, icon_svg), image.
 * The page's navy band: homepage intro centered, items in a grid split by hairlines.
 * With an image, the photo sits beside the grid and drifts slowly on scroll.
 */
$tag         = trim((string) get_sub_field('tag'));
$heading     = trim((string) get_sub_field('heading'));
$description = trim((string) get_sub_field('description'));
$image_id    = (int) get_sub_field('image');
$items       = array_values(array_filter(get_sub_field('audiences') ?: [], static function ($item) {
    return !empty($item['title']);
}));
if (!$items) {
    return;
}
$grid_columns = count($items) === 1 ? '' : 'sm:grid-cols-2';
?>
<section class="iva-section iva-band bg-ink text-white">
    <div class="container-site">
        <?php ivermectin_section_head($tag, $heading, $description, 'dark', is_front_page() ? 'center' : 'left'); ?>

        <div class="grid gap-6">
            <?php if ($image_id) : ?>
                <div class="iva-band-photo" data-iva-parallax>
                    <?= wp_get_attachment_image($image_id, 'large', false, ['class' => 'h-full w-full object-cover', 'alt' => '', 'loading' => 'lazy']) ?>
                </div>
            <?php endif; ?>

            <div class="iva-band-grid grid <?= esc_attr($grid_columns) ?>" data-iva-stagger>
                <?php foreach ($items as $item) : ?>
                    <article class="flex flex-col p-7 md:p-10">
                        <span class="iva-band-icon grid h-14 w-14 place-items-center rounded-2xl">
                            <?= ivermectin_render_card_icon([
                                'icon_image'  => $item['icon_image'] ?? null,
                                'icon_preset' => $item['icon_preset'] ?? 'auto',
                                'icon_svg'    => $item['icon_svg'] ?? '',
                                'title'       => $item['title'],
                                'desc'        => $item['desc'] ?? '',
                                'size_class'  => 'h-9 w-9',
                            ]) ?>
                        </span>
                        <h3 class="mt-7 font-heading text-[1.5rem] font-bold leading-tight text-white md:text-[1.625rem]"><?= esc_html($item['title']) ?></h3>
                        <?php if (!empty($item['desc'])) : ?><p class="mt-3 text-[1.0625rem] leading-relaxed text-white/65"><?= esc_html($item['desc']) ?></p><?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
