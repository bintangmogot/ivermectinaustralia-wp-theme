<?php
/**
 * Delivery area page. Every string comes from the city ACF fields.
 */
defined('ABSPATH') || exit;

$read = static function ($name) use ($args) {
    return isset($args['fields']) ? ($args['fields'][$name] ?? '') : get_field($name);
};
$text = static function ($name) use ($read) {
    return trim((string) $read($name));
};

$city         = $text('city_name') ?: get_the_title();
$region       = $text('region');
$hero_heading = $text('hero_heading');
$hero_desc    = $text('hero_desc');
$about_html   = (string) $read('desc');
$features     = $read('features') ?: [];
$reasons      = $read('reasons') ?: [];
$faqs         = $read('faqs') ?: [];
$reviews      = $read('reviews') ?: [];
$products     = $read('selected_products') ?: [];
$badges       = $read('trust_badges') ?: [];
$shop_url     = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '';

$summary = array_filter([
    ['tab' => $text('summary_heading') ?: $city, 'value' => $text('delivery_days') ?: $text('delivery_note'), 'note' => $text('carrier'), 'icon' => 'express_shipping', 'tone' => ''],
    ['tab' => 'Shipping',  'value' => $text('shipping_note'),  'note' => '', 'icon' => 'affordable_value', 'tone' => 'blue'],
    ['tab' => 'Packaging', 'value' => $text('packaging_note'), 'note' => '', 'icon' => 'discreet_sealed',  'tone' => ''],
    ['tab' => 'Tracking',  'value' => $text('tracking_note'),  'note' => '', 'icon' => 'australia_delivery', 'tone' => 'red'],
], static function ($item) {
    return $item['value'] !== '';
});
?>
<section class="relative isolate overflow-hidden bg-primary text-primary-foreground">
    <?php ivermectin_output_pattern_rings(); ?>
    <div class="container-site relative z-10 py-8 md:py-12">
        <?php if ($region !== '') : ?>
            <p class="text-sm font-semibold uppercase tracking-wider text-white/70"><?= esc_html($region) ?></p>
        <?php endif; ?>
        <h1 class="mt-2 max-w-3xl font-heading text-3xl font-extrabold leading-tight text-white sm:text-4xl md:text-5xl"><?= esc_html($hero_heading ?: $city) ?></h1>
        <?php if ($hero_desc !== '') : ?>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-white/85"><?= esc_html($hero_desc) ?></p>
        <?php endif; ?>
        <?php if ($shop_url && $text('shop_button_label') !== '') : ?>
            <a href="<?= esc_url($shop_url) ?>" class="btn-cta mt-5"><?= esc_html($text('shop_button_label')) ?></a>
        <?php endif; ?>
    </div>
</section>

<?php if ($summary) : ?>
<section class="py-6 md:py-8">
    <div class="container-site grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($summary as $item) : ?>
            <div class="lcard">
                <span class="lcard-tab <?= esc_attr($item['tone'] ? 'lcard-tab-' . $item['tone'] : '') ?>"><?= esc_html($item['tab']) ?></span>
                <div class="flex items-start gap-3 p-4">
                    <?= ivermectin_icon($item['icon'], 'h-10 w-10') ?>
                    <div>
                        <p class="font-heading text-base font-bold text-foreground"><?= esc_html($item['value']) ?></p>
                        <?php if ($item['note'] !== '') : ?><p class="mt-0.5 text-sm text-muted-foreground"><?= esc_html($item['note']) ?></p><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($about_html !== '' || $features) : ?>
<section class="py-6 md:py-8">
    <div class="container-site grid gap-6 lg:grid-cols-[1.4fr_1fr]">
        <?php if ($about_html !== '') : ?>
            <div class="lcard">
                <?php if ($text('about_label') !== '') : ?><span class="lcard-tab"><?= esc_html($text('about_label')) ?></span><?php endif; ?>
                <div class="p-5">
                    <?php if ($text('about_heading') !== '') : ?><h2 class="font-heading text-2xl font-extrabold text-primary"><?= esc_html($text('about_heading')) ?></h2><?php endif; ?>
                    <div class="prose mt-3 max-w-none"><?= wp_kses_post($about_html) ?></div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($features) : ?>
            <div class="lcard">
                <?php if ($text('features_heading') !== '') : ?><span class="lcard-tab lcard-tab-blue"><?= esc_html($text('features_heading')) ?></span><?php endif; ?>
                <ul class="space-y-3 p-5">
                    <?php foreach ($features as $row) : if (empty($row['feat'])) { continue; } ?>
                        <li class="flex items-start gap-3 text-sm text-foreground">
                            <?= ivermectin_icon('no_customs', 'h-6 w-6') ?>
                            <span><?= esc_html($row['feat']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($reasons) : ?>
<section class="py-6 md:py-8">
    <div class="container-site">
        <?php if ($text('reasons_heading') !== '') : ?><h2 class="mb-4 font-heading text-2xl font-extrabold text-primary"><?= esc_html($text('reasons_heading')) ?></h2><?php endif; ?>
        <div class="grid gap-4 md:grid-cols-3">
            <?php foreach ($reasons as $i => $row) : if (empty($row['title']) && empty($row['text'])) { continue; } ?>
                <div class="lcard">
                    <span class="lcard-tab"><?= esc_html(sprintf('%02d', $i + 1)) ?></span>
                    <div class="p-5">
                        <h3 class="font-heading text-lg font-bold text-foreground"><?= esc_html($row['title'] ?? '') ?></h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground"><?= esc_html($row['text'] ?? '') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($products) : ?>
<section class="py-6 md:py-8">
    <div class="container-site">
        <?php if ($text('products_heading') !== '') : ?><h2 class="font-heading text-2xl font-extrabold text-primary"><?= esc_html($text('products_heading')) ?></h2><?php endif; ?>
        <?php if ($text('products_desc') !== '') : ?><p class="mt-1 text-muted-foreground"><?= esc_html($text('products_desc')) ?></p><?php endif; ?>
        <div class="products mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <?php
            global $post;
            foreach ($products as $product_post) {
                $post = is_object($product_post) ? $product_post : get_post((int) $product_post);
                if (!$post) {
                    continue;
                }
                setup_postdata($post);
                wc_setup_product_data($post);
                wc_get_template_part('content', 'product');
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($reviews) : ?>
<section class="py-6 md:py-8">
    <div class="container-site grid gap-4 md:grid-cols-3">
        <?php foreach ($reviews as $row) : if (empty($row['text'])) { continue; } ?>
            <figure class="lcard">
                <div class="p-5">
                    <?php if (!empty($row['rating'])) : ?><div class="iva-stars" aria-label="<?= esc_attr((int) $row['rating']) ?> out of 5"><?= str_repeat('&#9733;', max(0, min(5, (int) $row['rating']))) ?></div><?php endif; ?>
                    <blockquote class="mt-2 text-sm leading-relaxed text-foreground"><?= esc_html($row['text']) ?></blockquote>
                    <figcaption class="mt-3 text-sm font-bold text-foreground"><?= esc_html($row['author'] ?? '') ?></figcaption>
                </div>
                <?php if (!empty($row['date'])) : ?><div class="lcard-foot"><?= esc_html($row['date']) ?></div><?php endif; ?>
            </figure>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($faqs) { ivermectin_render_faq_section((string) $text('faq_heading'), $faqs, 'bg-surface'); } ?>

<?php if ($text('cta_heading') !== '') : ?>
<section class="py-6 md:py-8">
    <div class="container-site">
        <div class="relative overflow-hidden rounded-xl bg-ink p-6 text-white md:p-8">
            <?php ivermectin_output_pattern_rings(); ?>
            <div class="relative z-10 max-w-2xl">
                <h2 class="font-heading text-2xl font-extrabold text-white"><?= esc_html($text('cta_heading')) ?></h2>
                <?php if ($text('cta_desc') !== '') : ?><p class="mt-2 text-white/80"><?= esc_html($text('cta_desc')) ?></p><?php endif; ?>
                <?php if ($shop_url && $text('cta_button_label') !== '') : ?><a href="<?= esc_url($shop_url) ?>" class="btn-cta mt-4"><?= esc_html($text('cta_button_label')) ?></a><?php endif; ?>
                <?php if ($badges) : ?>
                    <ul class="mt-5 flex flex-wrap gap-2">
                        <?php foreach ($badges as $row) : if (empty($row['label'])) { continue; } ?>
                            <li class="rounded-md border border-white/20 px-3 py-1 text-xs font-semibold text-white/90"><?= esc_html($row['label']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
