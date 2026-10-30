<?php
/**
 * The template for displaying product content in a horizontal layout
 * Inspired by kamagraaus.com
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

if (empty($product) || !$product->is_visible()) {
    return;
}

$title = $product->get_title();
$link = $product->get_permalink();
$image = wp_get_attachment_image_url($product->get_image_id(), 'medium_large') ?: wc_placeholder_img_src();

// Use shop_page_text ACF field for excerpt, fallback to standard post excerpt
$excerpt = get_field('shop_page_text', $product->get_id());
if (empty($excerpt)) {
    $excerpt = $product->get_short_description() ?: get_the_excerpt();
}
// Don't overly trim it in PHP so the JS toggle can reveal the rest
$excerpt = wp_trim_words(strip_shortcodes(wp_strip_all_tags($excerpt)), 60, '...');

// Custom price logic: change range to "From" for variable products
if ( $product->is_type('variable') ) {
    $min_price = $product->get_variation_price('min', true);
    $price_html = 'From ' . wc_price($min_price);
} else {
    $price_html = $product->get_price_html();
}
?>
<div class="group relative flex flex-col sm:flex-row bg-white border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
    
    <!-- Image full width, no padding, object-contain so it never cuts -->
    <a href="<?= esc_url($link) ?>" class="sm:w-2/5 md:w-[42%] relative block bg-radial-[at_center] from-sky-50 to-white shrink-0 overflow-hidden min-h-[220px] sm:min-h-[auto] border-b sm:border-b-0 sm:border-r-2 sm:border-dashed sm:border-sky-200/80 p-4 flex items-center justify-center">
        <img src="<?= esc_url($image) ?>" alt="<?= esc_attr($title) ?>" class="w-full h-full max-h-[240px] object-contain transition-transform duration-500 group-hover:scale-105 group-hover:rotate-1" loading="lazy" />
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-400 text-slate-950 border border-amber-500/30 shadow-xs">
                ★ POPULAR
            </span>
        </div>
    </a>
    
    <div class="p-6 sm:p-7 flex flex-col justify-between flex-1 bg-white">
        <div>
            <!-- Bestseller / Category Tag -->
            <div class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-widest text-amber-600 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-amber-500">
                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                </svg>
                AUSTRALIAN DISPATCH
            </div>

            <!-- Title -->
            <a href="<?= esc_url($link) ?>" class="block font-heading text-xl md:text-2xl font-black text-slate-900 leading-tight hover:text-[#0c4161] transition-colors mb-2.5">
                <?= esc_html($title) ?>
            </a>

            <!-- Excerpt with Read More Toggle -->
            <div class="mb-4">
                <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 transition-all duration-300">
                    <?= esc_html($excerpt) ?>
                </p>
                <?php if (str_word_count($excerpt) > 15): ?>
                <button type="button" onclick="let p = this.previousElementSibling; p.classList.toggle('line-clamp-2'); this.innerText = p.classList.contains('line-clamp-2') ? 'Read more' : 'Show less';" class="text-xs font-bold text-[#0c4161] hover:underline mt-1">Read more</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="pt-4 border-t-2 border-dashed border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-auto">
            <!-- Price -->
            <div class="text-sm text-slate-600 font-medium">
                <span class="block text-[11px] uppercase tracking-wider text-slate-400 font-bold">Price in AUD</span>
                <span class="font-black text-slate-950 text-lg md:text-xl [&>span.amount]:!font-black [&>del]:opacity-50 [&>del]:font-normal [&>del]:text-sm [&>ins]:no-underline"><?= wp_kses_post($price_html) ?></span>
            </div>
            
            <!-- Shop Now Link (Compact on Mobile) -->
            <a href="<?= esc_url($link) ?>" class="inline-flex items-center justify-center gap-2 bg-[#0c4161] hover:bg-[#082c42] text-white font-extrabold text-xs sm:text-sm py-2 px-4 sm:py-2.5 sm:px-6 rounded-lg sm:rounded-xl group/btn shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.02]">
                <span>Order Now</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform group-hover/btn:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>
