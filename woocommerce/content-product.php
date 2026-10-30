<?php
/**
 * Product card used in loops.
 * Label tab, image, title, short text, price and a link to choose the pack size.
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

if (empty($product) || !$product->is_visible()) {
    return;
}

$product_id     = $product->get_id();
$title          = $product->get_title();
$link           = $product->get_permalink();
$image          = wp_get_attachment_image_url($product->get_image_id(), 'medium') ?: '';
$in_stock       = $product->is_in_stock();
$unit_price     = ivermectin_get_product_unit_price($product);
$review_summary = ivermectin_get_product_review_summary($product_id);

// Short text: the per-product "shop_page_text" field from the previous theme, then the WooCommerce short description.
$shop_page_text = get_field('shop_page_text', $product_id);
$excerpt_source = $shop_page_text ?: $product->get_short_description();
$excerpt        = trim(preg_replace('/\s+/', ' ', wp_strip_all_tags((string) $excerpt_source)));

$tab_label = trim((string) get_field('product_badge', $product_id));
if ($tab_label === '' && $product->is_featured()) {
    $tab_label = ivermectin_get_product_presentation_option('best_seller_label');
}
$no_reviews_text = ivermectin_get_product_presentation_option('product_no_reviews_text');
?>
<article class="lcard group h-full">
    <?php if ($tab_label !== '') : ?><span class="lcard-tab"><?= esc_html($tab_label) ?></span><?php endif; ?>

    <a href="<?= esc_url($link) ?>" class="mx-4 mt-3 grid aspect-square place-items-center overflow-hidden rounded-lg border border-border bg-primary-softer">
        <?php if ($image !== '') : ?>
            <img src="<?= esc_url($image) ?>" alt="<?= esc_attr($title) ?>" loading="lazy" class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-105">
        <?php endif; ?>
    </a>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="font-heading text-base font-bold leading-snug text-foreground md:text-lg">
            <a href="<?= esc_url($link) ?>" class="line-clamp-2 hover:text-primary"><?= esc_html($title) ?></a>
        </h3>

        <?php if ($excerpt !== '') : ?>
            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-muted-foreground"><?= esc_html($excerpt) ?></p>
        <?php endif; ?>

        <div class="mt-4 flex flex-wrap items-baseline justify-between gap-2 border-t border-border pt-3">
            <p class="font-heading text-xl font-extrabold leading-tight text-primary"><?= wp_kses_post(ivermectin_get_product_card_price_html($product)) ?></p>
            <?php if ($unit_price !== null) : ?>
                <span class="text-xs font-semibold text-muted-foreground"><?= wp_kses_post(wc_price($unit_price, array('decimals' => 2))) ?>/tab</span>
            <?php endif; ?>
        </div>

        <div class="mt-auto pt-4">
            <?php if (apply_filters('ivermectin_show_product_card_reviews', true)) : ?>
                <div class="mb-3 text-xs text-muted-foreground">
                    <?php if ($review_summary['review_count'] > 0) : ?>
                        <a href="<?= esc_url($link . '#product-reviews') ?>" class="inline-flex items-center gap-1.5 hover:text-primary">
                            <?php if ($review_summary['rating_count'] > 0) : ?>
                                <span class="iva-stars" aria-hidden="true">&#9733;</span>
                                <span class="font-semibold text-foreground"><?= esc_html(number_format($review_summary['average_rating'], 1)) ?></span>
                            <?php endif; ?>
                            <span>(<?= esc_html($review_summary['review_count']) ?>)</span>
                        </a>
                    <?php elseif ($no_reviews_text !== '') : ?>
                        <a href="<?= esc_url($link . '#product-reviews') ?>" class="hover:text-primary"><?= esc_html($no_reviews_text) ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($in_stock) : ?>
                <a href="<?= esc_url($link) ?>" class="btn-cta w-full"><?= esc_html__('Select Pack Size', 'ivermectin-australia') ?></a>
            <?php else : ?>
                <span class="flex w-full items-center justify-center rounded-lg bg-muted px-4 py-3 text-sm font-bold uppercase text-muted-foreground"><?= esc_html__('Out of Stock', 'ivermectin-australia') ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>
