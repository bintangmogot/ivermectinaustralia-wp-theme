<?php
/**
 * Catalog comparison for the products currently listed in the shop.
 * Product details, variation pricing and stock are read from WooCommerce.
 */

if (!function_exists('wc_get_product')) {
    return;
}

$catalog_products = array_values(array_filter(
    wc_get_products([
        'status' => 'publish',
        'limit' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]),
    static function ($product) {
        return $product instanceof WC_Product && $product->is_visible();
    }
));

$get_strength = static function ($product) {
    foreach ($product->get_attributes() as $attribute) {
        $attribute_name = is_object($attribute) && method_exists($attribute, 'get_name')
            ? $attribute->get_name()
            : '';
        $attribute_label = $attribute_name ? wc_attribute_label($attribute_name) : '';

        if ($attribute_label && preg_match('/strength|dosage/i', $attribute_label)) {
            $strength = $product->get_attribute($attribute_name);
            if ($strength !== '') {
                return $strength;
            }
        }
    }

    if (preg_match('/\b\d+(?:\.\d+)?\s?mg\b/i', $product->get_name(), $matches)) {
        return preg_replace('/\s+/', ' ', $matches[0]);
    }

    return '';
};

$get_pack_size = static function ($product) {
    foreach ($product->get_attributes() as $attribute) {
        $attribute_name = is_object($attribute) && method_exists($attribute, 'get_name')
            ? $attribute->get_name()
            : '';
        $attribute_label = $attribute_name ? wc_attribute_label($attribute_name) : '';

        if ($attribute_label && preg_match('/quantity|pack|size/i', $attribute_label)) {
            $pack_size = $product->get_attribute($attribute_name);
            if ($pack_size !== '') {
                return $pack_size;
            }
        }
    }

    return $product->get_attribute('pa_quantity') ?: $product->get_attribute('quantity');
};

$get_variations = static function ($product) {
    if (!$product->is_type('variable')) {
        return [];
    }

    $variations = [];
    foreach ($product->get_children() as $variation_id) {
        $variation = wc_get_product($variation_id);
        if (!$variation || !$variation->variation_is_visible()) {
            continue;
        }

        $pack_size = '';
        foreach ($variation->get_attributes() as $attribute_name => $attribute_value) {
            $normalized_name = preg_replace('/^attribute_/', '', $attribute_name);
            $attribute_label = wc_attribute_label($normalized_name, $product);

            if (preg_match('/quantity|pack|size/i', $attribute_label)) {
                $pack_size = $variation->get_attribute($normalized_name) ?: $attribute_value;
                break;
            }
        }

        if ($pack_size === '') {
            $pack_size = wc_get_formatted_variation($variation, true, false, false);
        }

        $pack_size = trim(preg_replace('/\s+/', ' ', str_replace(['-', '_'], ' ', wp_strip_all_tags($pack_size))));
        $variations[] = [
            'product' => $variation,
            'pack_size' => $pack_size !== '' ? ucwords($pack_size) : '',
        ];
    }

    usort($variations, static function ($left, $right) {
        preg_match('/\d+/', $left['pack_size'], $left_number);
        preg_match('/\d+/', $right['pack_size'], $right_number);
        return ((int) ($left_number[0] ?? PHP_INT_MAX)) <=> ((int) ($right_number[0] ?? PHP_INT_MAX));
    });

    return $variations;
};
?>
<section id="product-comparison" class="relative isolate scroll-mt-24 overflow-hidden bg-ink py-8 text-primary-foreground md:py-12" data-testid="product-comparison">
    <?php waklert_output_pattern_rings(); ?>
    <div class="container-site relative z-10">
        <header class="mx-auto mb-8 max-w-3xl text-center md:mb-10">
            <span class="mb-4 inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-primary-foreground">Product comparison</span>
            <h2 class="font-heading text-2xl font-black text-primary-foreground md:text-4xl">Compare our products</h2>
            <p class="mt-3 text-base leading-relaxed text-primary-foreground/80">
                Compare listed product details, pack sizes and current prices. Prices and availability come from the product listings.
            </p>
        </header>

        <div role="region" aria-label="Product comparison carousel" tabindex="0" class="-mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain px-4 pb-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-300 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950 md:mx-0 md:grid md:grid-cols-3 md:items-stretch md:gap-5 md:overflow-visible md:px-0 md:pb-0">
            <?php foreach ($catalog_products as $product): ?>
                <article class="flex h-full w-[86%] max-w-sm shrink-0 snap-start flex-col rounded-2xl border border-border bg-card p-5 shadow-card md:w-auto md:max-w-none md:shrink md:p-6">
                    <?php
                    $review_summary = waklert_get_product_review_summary($product->get_id());
                    $unit_price = waklert_get_product_unit_price($product);
                    $no_reviews_text = waklert_get_product_presentation_option('product_no_reviews_text', 'No reviews yet');
                    $short_description = trim($product->get_short_description());
                    ?>
                    <a href="<?= esc_url(get_permalink($product->get_id())) ?>" class="mb-5 flex min-h-40 items-center justify-center overflow-hidden rounded-xl bg-white p-4" aria-label="View <?= esc_attr($product->get_name()) ?>">
                        <?= $product->get_image('woocommerce_thumbnail', ['class' => 'h-36 w-full object-contain', 'alt' => $product->get_name()]) ?>
                    </a>

                    <h3 class="font-heading text-xl font-bold text-ink">
                        <a class="hover:text-primary-dark" href="<?= esc_url(get_permalink($product->get_id())) ?>">
                            <?= esc_html($product->get_name()) ?>
                        </a>
                    </h3>
                    <?php if ($short_description !== ''): ?>
                        <div class="mt-2 text-sm leading-relaxed text-muted-foreground">
                            <?= wp_kses_post($short_description) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (apply_filters('waklert_show_product_card_reviews', false)): ?>
                        <?php if ($review_summary['review_count'] > 0): ?>
                            <a href="<?= esc_url(get_permalink($product->get_id()) . '#product-reviews') ?>" class="mt-2 inline-flex w-fit items-center gap-1.5 text-xs text-muted-foreground transition-colors hover:text-primary-dark" aria-label="Read <?= esc_attr($review_summary['review_count']) ?> product reviews">
                                <?php if ($review_summary['rating_count'] > 0): ?>
                                    <span class="text-accent" aria-hidden="true">★</span>
                                    <span class="font-semibold text-ink"><?= esc_html(number_format($review_summary['average_rating'], 1)) ?></span>
                                    <span aria-hidden="true">·</span>
                                <?php endif; ?>
                                <span>(<?= esc_html($review_summary['review_count']) ?> <?= esc_html($review_summary['review_count'] === 1 ? 'review' : 'reviews') ?>)</span>
                            </a>
                        <?php else: ?>
                            <a href="<?= esc_url(get_permalink($product->get_id()) . '#product-reviews') ?>" class="mt-2 inline-flex w-fit text-xs text-muted-foreground transition-colors hover:text-primary-dark">
                                <?= esc_html($no_reviews_text) ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($unit_price !== null): ?>
                        <p class="mt-1 text-sm font-semibold text-primary-dark">From <?= wp_kses_post(wc_price($unit_price, array('decimals' => 2))) ?>/tab</p>
                    <?php endif; ?>

                    <dl class="mt-4 divide-y divide-border border-y border-border text-sm">
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="font-semibold text-muted-foreground">Strength</dt>
                            <dd class="text-right font-medium text-ink"><?= esc_html($get_strength($product) ?: 'Not listed') ?></dd>
                        </div>

                        <div class="py-3">
                            <dt class="font-semibold text-muted-foreground">Price</dt>
                            <dd class="mt-1 max-w-full break-words font-semibold text-ink [&_.price]:whitespace-normal">
                                <?php $product_price_html = waklert_get_product_card_price_html($product); ?>
                                <?= $product_price_html ? wp_kses_post($product_price_html) : 'Price unavailable' ?>
                            </dd>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="font-semibold text-muted-foreground">Availability</dt>
                            <dd class="text-right font-medium <?= $product->is_in_stock() ? 'text-primary-dark' : 'text-destructive' ?>">
                                <?= esc_html($product->is_in_stock() ? 'In stock' : 'Currently unavailable') ?>
                            </dd>
                        </div>
                    </dl>

                    <?php $variations = $get_variations($product); ?>
                    <?php if ($variations): ?>
                        <details class="mt-4 mb-4 rounded-xl border border-border bg-background px-4 py-3">
                            <summary class="cursor-pointer font-semibold text-primary-dark marker:text-primary">
                                View pack sizes and prices
                            </summary>
                            <ul class="mt-3 divide-y divide-border text-sm">
                                <?php foreach ($variations as $variation_row): ?>
                                    <?php
                                    $variation = $variation_row['product'];
                                    $pack_label = $variation_row['pack_size'] ?: 'Pack size not listed';
                                    $variation_price_html = waklert_get_product_card_price_html($variation);
                                    ?>
                                    <li class="flex items-start justify-between gap-3 py-2 first:pt-0 last:pb-0">
                                        <span>
                                            <span class="block font-medium text-ink"><?= esc_html($pack_label) ?></span>
                                            <span class="text-xs <?= $variation->is_in_stock() ? 'text-primary-dark' : 'text-destructive' ?>">
                                                <?= esc_html($variation->is_in_stock() ? 'In stock' : 'Currently unavailable') ?>
                                            </span>
                                        </span>
                                        <span class="shrink-0 text-right font-semibold text-ink">
                                            <?= $variation_price_html ? wp_kses_post($variation_price_html) : 'Price unavailable' ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    <?php elseif ($product->is_type('variable')): ?>
                        <p class="mt-4 rounded-xl border border-border bg-background px-4 py-3 text-sm text-muted-foreground">
                            Pack sizes and prices are currently unavailable.
                        </p>
                    <?php else: ?>
                        <?php $pack_size = $get_pack_size($product); ?>
                        <p class="mt-4 rounded-xl border border-border bg-background px-4 py-3 text-sm text-muted-foreground">
                            <span class="font-semibold text-ink">Pack size:</span>
                            <?= esc_html($pack_size !== '' ? $pack_size : 'Not listed') ?>
                        </p>
                    <?php endif; ?>

                    <a href="<?= esc_url(get_permalink($product->get_id())) ?>" class="mt-auto inline-flex items-center justify-center rounded-full bg-primary px-4 py-2 sm:px-5 sm:py-3 text-xs sm:text-sm font-bold text-primary-foreground transition-colors hover:bg-primary-dark">
                        View product options
                    </a>
                </article>
            <?php endforeach; ?>
            <?php if (!$catalog_products): ?>
                <p class="col-span-full rounded-xl border border-white/20 bg-white/10 p-6 text-center text-primary-foreground">
                    No products are currently available to compare.
                </p>
            <?php endif; ?>
        </div>

        <p class="mx-auto mt-6 max-w-3xl text-center text-sm leading-relaxed text-primary-foreground/80">
            Product information is for comparison only. Speak with a qualified health professional about medicine selection.
        </p>
    </div>
</section>
