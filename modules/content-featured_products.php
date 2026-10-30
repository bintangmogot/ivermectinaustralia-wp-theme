<?php
/**
 * Module: Featured Products
 * Matches the original React design exactly
 */

$tag = get_sub_field('subheading') ?: "MOST POPULAR in 2026";

$heading = get_sub_field('title') ?: "Armodafinil Products";

// Featured product slugs (matching original JS: HOME_PRODUCT_SLUGS)
$featured_slugs = ['waklert-150mg', 'artvigil-50mg', 'armod-50mg-armodafinil'];

$products = get_sub_field('products');
if (!$products) {
    // Fallback: Get by the exact slugs from the original design
    $products = [];
    foreach ($featured_slugs as $slug) {
        $found = get_page_by_path($slug, OBJECT, 'product');
        if ($found) {
            $products[] = wc_get_product($found->ID);
        }
    }
    // If still empty, get latest 4
    if (empty($products)) {
        $products = wc_get_products(array(
            'limit' => 4,
            'status' => 'publish',
        ));
    }
}
?>
<section class="section-padding bg-gradient-to-b from-[#f8fafc] via-[#eff6fb] to-[#f8fafc] bg-pattern-dots border-t-2 border-dashed border-sky-200/80 relative isolate overflow-hidden" data-testid="featured-products">
    <div class="container-custom relative z-10">
        <div class="text-center mb-10 md:mb-12">
            <span class="inline-flex items-center gap-2 bg-amber-100/90 text-amber-900 border border-amber-300 text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-amber-500 animate-pulse-subtle">
                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.173 1.93-1.897 3.19C7.457 7.747 6.452 9.5 5.56 10.742a1 1 0 00.323 1.488c.954.558 1.956.88 2.97.946.06.495.176 1.05.353 1.62.33 1.066.868 2.213 1.666 3.16.48.57 1.08.97 1.768 1.034.708.066 1.428-.216 1.996-.75 1.134-1.067 1.868-2.677 2.158-4.218.29-1.543.16-3.076-.25-4.437a1 1 0 00-.73-.67c-.89-.25-1.74-.63-2.48-1.13-.23-.15-.43-.33-.61-.53-.41-.45-.71-.98-.94-1.54-.22-.55-.32-1.15-.32-1.76z" clip-rule="evenodd" />
                </svg>
                <?= esc_html($tag) ?>
            </span>
            <h2 class="font-heading text-2xl md:text-4xl font-black text-ink tracking-tight mb-3">
                <?= esc_html($heading) ?>
            </h2>
            <p class="text-sm md:text-base text-muted-foreground max-w-xl mx-auto leading-relaxed">
                Genuine Sun Pharma and HAB Pharma Armodafinil tablets shipped domestically across Australia.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <?php 
            if($products):
                foreach($products as $p):
                    // Handle both ACF post objects and wc_get_products instances
                    $product_id = is_numeric($p) ? $p : (is_a($p, 'WC_Product') ? $p->get_id() : $p->ID);
                    if(!$product_id) continue;
                    
                    $post_object = get_post($product_id);
                    setup_postdata($GLOBALS['post'] =& $post_object);
                    wc_get_template_part('content', 'product-horizontal');
                endforeach;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="<?= wc_get_page_permalink('shop') ?>" class="inline-flex items-center gap-2 border-2 border-primary-light text-primary-dark font-bold px-5 py-2.5 sm:px-7 sm:py-3 rounded-full hover:bg-primary-light hover:text-white hover:border-primary-light transition-all uppercase tracking-wider sm:tracking-widest text-xs sm:text-sm">
                <?= "View All Products" ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>
</section>
