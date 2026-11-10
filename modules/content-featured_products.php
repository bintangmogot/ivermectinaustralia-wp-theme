<?php
/**
 * Module: featured_products.
 * Sub fields: subheading (pill tag), title, description, products (relationship), button_text (link to the shop).
 * Without a selection, the latest published products are shown. Nothing is invented.
 */
if (!class_exists('WooCommerce')) {
    return;
}

$tag         = trim((string) get_sub_field('subheading'));
$heading     = trim((string) get_sub_field('title'));
$description = trim((string) get_sub_field('description'));
$button_text = trim((string) get_sub_field('button_text'));
$products    = get_sub_field('products');

if (!$products) {
    $products = wc_get_products(['limit' => 4, 'status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC']);
}
if (!$products) {
    return;
}
?>
<section class="iva-section iva-section-tight bg-white" id="products">
    <div class="container-site">
        <?php ivermectin_section_head($tag, $heading, $description); ?>

        <div class="grid grid-cols-2 gap-2.5 sm:gap-4 md:gap-6" data-iva-stagger>
            <?php
            foreach ($products as $item) {
                $product_id = is_numeric($item) ? (int) $item : (is_a($item, 'WC_Product') ? $item->get_id() : (int) $item->ID);
                get_template_part('template-parts/product-row-card', null, ['product' => wc_get_product($product_id)]);
            }
            ?>
        </div>

        <?php if ($button_text !== '') : ?>
            <div class="mt-12 text-center">
                <a href="<?= esc_url(wc_get_page_permalink('shop')) ?>" class="iva-btn-pill"><?= esc_html($button_text) ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>
