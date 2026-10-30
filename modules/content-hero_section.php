<?php
/**
 * Module: hero_section.
 * Sub fields: location_text, heading, subtitle, description, hero_product, primary and secondary buttons,
 * stats (value, label), background_image, mobile_image. Empty fields render nothing.
 */
$location    = trim((string) get_sub_field('location_text'));
$heading     = trim((string) get_sub_field('heading'));
$subtitle    = trim((string) get_sub_field('subtitle'));
$description = trim((string) get_sub_field('description'));
$primary_btn = trim((string) get_sub_field('primary_button_text'));
$primary_url = trim((string) get_sub_field('primary_button_link'));
$second_btn  = trim((string) get_sub_field('secondary_button_text'));
$second_url  = trim((string) get_sub_field('secondary_button_link'));
$stats       = get_sub_field('stats') ?: [];
$product     = class_exists('WooCommerce') && get_sub_field('hero_product') ? wc_get_product((int) get_sub_field('hero_product')) : null;

$image_field = get_sub_field('background_image');
$image_url   = is_array($image_field) ? ($image_field['url'] ?? '') : (is_numeric($image_field) ? (string) wp_get_attachment_image_url((int) $image_field, 'large') : (string) $image_field);

if ($heading === '' && $description === '' && !$product) {
    return;
}

$qty_heading = function_exists('ivermectin_get_product_presentation_option') ? ivermectin_get_product_presentation_option('quantity_heading') : '';
?>
<section class="relative isolate overflow-hidden bg-primary text-white" data-testid="hero-section">
    <?php ivermectin_output_pattern_rings(); ?>
    <div class="container-site relative z-10 grid gap-8 py-8 md:py-12 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
        <div>
            <?php if ($location !== '') : ?><p class="iva-eyebrow text-white/70"><?= esc_html($location) ?></p><?php endif; ?>
            <?php if ($heading !== '') : ?><h1 class="mt-3 font-heading text-4xl font-extrabold leading-[1.08] text-white md:text-5xl lg:text-[3.25rem]"><?= esc_html($heading) ?></h1><?php endif; ?>
            <?php if ($subtitle !== '') : ?><p class="mt-3 text-lg font-semibold text-white/90"><?= esc_html($subtitle) ?></p><?php endif; ?>
            <?php if ($description !== '') : ?><p class="mt-4 max-w-xl text-base leading-relaxed text-white/80"><?= esc_html($description) ?></p><?php endif; ?>

            <?php if (($primary_btn !== '' && $primary_url !== '') || ($second_btn !== '' && $second_url !== '')) : ?>
                <div class="mt-6 flex flex-wrap gap-3">
                    <?php if ($primary_btn !== '' && $primary_url !== '') : ?><a href="<?= esc_url($primary_url) ?>" class="btn-cta"><?= esc_html($primary_btn) ?></a><?php endif; ?>
                    <?php if ($second_btn !== '' && $second_url !== '') : ?><a href="<?= esc_url($second_url) ?>" class="btn-ghost"><?= esc_html($second_btn) ?></a><?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($stats) : ?>
                <dl class="mt-8 grid max-w-xl grid-cols-3 border-t border-white/30">
                    <?php foreach ($stats as $stat) : if (empty($stat['value'])) { continue; } ?>
                        <div class="border-l border-white/20 py-3 pl-4 first:border-l-0 first:pl-0">
                            <dt class="font-heading text-2xl font-extrabold text-white sm:text-3xl"><?= esc_html($stat['value']) ?></dt>
                            <dd class="text-xs text-white/70 sm:text-sm"><?= esc_html($stat['label'] ?? '') ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>
        </div>

        <?php if ($product && $product->is_visible()) :
            $first_price = $product->get_price_html();
            if ($product->is_type('variable')) {
                $variations = $product->get_available_variations();
                if (function_exists('ivermectin_sort_product_variations')) {
                    ivermectin_sort_product_variations($variations, $product);
                }
                if ($variations) {
                    $first_price = wc_price($variations[0]['display_price']);
                }
            }
            $strength = function_exists('ivermectin_get_product_strength') ? ivermectin_get_product_strength($product) : '';
            ?>
            <div class="overflow-hidden rounded-xl bg-white text-foreground shadow-xl" data-iva-qcard>
                <div class="flex items-center justify-between gap-3 border-b border-border bg-primary-soft px-5 py-3 text-sm font-semibold text-primary">
                    <span><?= esc_html($product->get_name()) ?></span>
                    <span class="inline-flex items-center gap-1.5 <?= $product->is_in_stock() ? 'text-emerald-700' : 'text-destructive' ?>"><span class="h-2 w-2 rounded-full <?= $product->is_in_stock() ? 'bg-emerald-500' : 'bg-destructive' ?>" aria-hidden="true"></span><?= esc_html($product->is_in_stock() ? __('In stock', 'ivermectin-australia') : __('Out of stock', 'ivermectin-australia')) ?></span>
                </div>
                <div class="space-y-4 p-5">
                    <div class="flex items-center gap-4">
                        <?php if ($product->get_image_id()) : ?><span class="grid h-20 w-20 shrink-0 place-items-center overflow-hidden rounded-lg border border-border bg-primary-softer"><?= wp_get_attachment_image($product->get_image_id(), 'thumbnail', false, ['class' => 'h-full w-full object-contain']) ?></span><?php endif; ?>
                        <?php if ($strength !== '') : ?><p class="font-heading text-3xl font-extrabold text-primary"><?= esc_html($strength) ?></p><?php endif; ?>
                    </div>
                    <?php if ($product->is_type('variable')) : ?>
                        <div>
                            <?php if ($qty_heading !== '') : ?><p class="mb-2 text-sm font-bold"><?= esc_html($qty_heading) ?></p><?php endif; ?>
                            <?php get_template_part('template-parts/quantity-picker', null, ['product' => $product]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-heading text-3xl font-extrabold text-primary" data-iva-price><?= wp_kses_post($first_price) ?></span>
                        <a href="<?= esc_url($product->get_permalink()) ?>" class="btn-navy"><?= esc_html__('Add to Cart', 'ivermectin-australia') ?></a>
                    </div>
                </div>
            </div>
        <?php elseif ($image_url !== '') : ?>
            <div class="overflow-hidden rounded-xl border border-white/20"><img src="<?= esc_url($image_url) ?>" alt="<?= esc_attr($heading) ?>" class="h-auto w-full object-cover" loading="eager" fetchpriority="high"></div>
        <?php endif; ?>
    </div>
</section>
