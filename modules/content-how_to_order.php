<?php
/**
 * Module: how_to_order. Sub fields: tag, heading, description, steps (icon_preset, icon_image, title, desc, icon_svg), image.
 * Large numbered cards on a mist band, joined by a line that draws in on scroll. Numbered because the steps are a sequence.
 * The payment and delivery logos under the steps come from Theme Settings > Footer (Payments Accepted and Shipping Partner
 * images), shown when show_payment_logos is on. The image field is kept for other layouts and not shown here.
 */
$tag         = trim((string) get_sub_field('tag'));
$heading     = trim((string) get_sub_field('heading'));
$description = trim((string) get_sub_field('description'));
$steps       = array_values(array_filter(get_sub_field('steps') ?: [], static function ($step) {
    return !empty($step['title']);
}));
if (!$steps) {
    return;
}
$logos = [];
if (get_sub_field('show_payment_logos')) {
    foreach (['footer_payment_images', 'footer_shipping_images'] as $logo_field) {
        foreach ((array) get_field($logo_field, 'option') as $logo) {
            $logo_id = is_array($logo['image'] ?? null) ? (int) ($logo['image']['ID'] ?? 0) : (int) ($logo['image'] ?? 0);
            if ($logo_id) {
                $logos[] = ['id' => $logo_id, 'alt' => trim((string) ($logo['alt_text'] ?? ''))];
            }
        }
    }
}
$columns  = ['md:grid-cols-1', 'md:grid-cols-2', 'md:grid-cols-3', 'md:grid-cols-4'][min(4, count($steps)) - 1];
?>
<section class="iva-section iva-section-tight iva-steps bg-surface">
    <div class="container-site">
        <?php ivermectin_section_head($tag, $heading, $description); ?>

        <div class="relative mx-auto max-w-5xl" data-iva-steps>
            <?php if (count($steps) === 3) : ?><span class="iva-steps-line" aria-hidden="true"></span><?php endif; ?>
            <ol class="iva-steps-list grid gap-5 <?= esc_attr($columns) ?>">
            <?php foreach ($steps as $index => $step) : ?>
                <li class="iva-step">
                    <span class="iva-step-num"><?= esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                    <span class="iva-icon-tile mx-auto mt-6 grid h-14 w-14 place-items-center rounded-2xl">
                        <?= ivermectin_render_card_icon([
                            'icon_image'  => $step['icon_image'] ?? null,
                            'icon_preset' => $step['icon_preset'] ?? 'auto',
                            'icon_svg'    => $step['icon_svg'] ?? '',
                            'title'       => $step['title'],
                            'desc'        => $step['desc'] ?? '',
                            'size_class'  => 'h-9 w-9',
                        ]) ?>
                    </span>
                    <h3 class="iva-h3 mt-5"><?= esc_html($step['title']) ?></h3>
                    <?php if (!empty($step['desc'])) : ?><p class="iva-body mt-2"><?= esc_html($step['desc']) ?></p><?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ol>
        </div>

        <?php if ($logos) : ?>
            <div class="mt-12 flex flex-wrap items-center justify-center gap-x-10 gap-y-4" data-iva-stagger>
                <?php foreach ($logos as $logo) : ?>
                    <?= wp_get_attachment_image($logo['id'], 'medium', false, ['class' => 'h-8 w-auto mix-blend-multiply', 'alt' => $logo['alt'], 'loading' => 'lazy']) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
