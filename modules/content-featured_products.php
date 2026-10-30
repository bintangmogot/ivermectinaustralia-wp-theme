<?php
/**
 * Module: featured_products (Popular Products).
 * Sub fields: subheading, title, description, products (relationship), button_text.
 * Without a selection, the latest published products are shown. Nothing is invented.
 */
if (!class_exists('WooCommerce')) {
    return;
}

$eyebrow     = trim((string) get_sub_field('subheading'));
$heading     = trim((string) get_sub_field('title'));
$description = trim((string) get_sub_field('description'));
$button_text = trim((string) get_sub_field('button_text'));
$products    = get_sub_field('products');

if (!$products) {
    $products = wc_get_products(['limit' => 3, 'status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC']);
}
if (!$products) {
    return;
}
?>
<section class="py-8 md:py-10" id="products">
    <div class="container-site">
        <?php if ($eyebrow !== '' || $heading !== '' || $button_text !== '') : ?>
            <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <?php if ($eyebrow !== '') : ?><p class="iva-eyebrow text-link"><?= esc_html($eyebrow) ?></p><?php endif; ?>
                    <?php if ($heading !== '') : ?><h2 class="mt-2 font-heading text-2xl font-extrabold text-primary md:text-3xl"><?= esc_html($heading) ?></h2><?php endif; ?>
                    <?php if ($description !== '') : ?><p class="mt-2 max-w-2xl text-muted-foreground"><?= esc_html($description) ?></p><?php endif; ?>
                </div>
                <?php if ($button_text !== '') : ?><a href="<?= esc_url(wc_get_page_permalink('shop')) ?>" class="inline-flex items-center gap-1.5 font-bold text-link hover:underline"><?= esc_html($button_text) ?><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a><?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            <?php
            foreach ($products as $item) {
                $product_id = is_numeric($item) ? (int) $item : (is_a($item, 'WC_Product') ? $item->get_id() : (int) $item->ID);
                $post_object = get_post($product_id);
                if (!$post_object) {
                    continue;
                }
                $GLOBALS['post'] = $post_object;
                setup_postdata($post_object);
                wc_setup_product_data($post_object);
                wc_get_template_part('content', 'product-pills');
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
