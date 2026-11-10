<?php
/**
 * The Template for displaying all single products
 * Uses the Ivermectin Australia theme's WooCommerce product layout.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// On product pages, display currency as $ without the "A" prefix
add_filter('woocommerce_currency_symbol', function ($symbol, $currency) {
    return '$';
}, 999, 2);

get_header('shop');

global $product;

// Ensure we have a proper WC_Product object
if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

if (!$product) {
    get_footer('shop');
    return;
}

$product_modules = get_field('page_modules', $product->get_id()) ?: [];
$product_summary_module = '';
$product_description_module = '';

foreach ($product_modules as $module) {
    $layout = $module['acf_fc_layout'] ?? '';

    if ($layout === 'product_summary' && $product_summary_module === '') {
        $product_summary_module = $module['content'] ?? '';
    } elseif ($layout === 'product_description' && $product_description_module === '') {
        $product_description_module = $module['content'] ?? '';
    }
}

// Retain the former ACF fields as a fallback for products that have not been migrated.
$acf_short = $product_summary_module ?: get_field('short_description', $product->get_id()) ?: get_post_meta($product->get_id(), 'short_desc', true);

if ($acf_short) {
    $summary = $acf_short ?: '';
} else {
// Parse the product summary from legacy HTML comments when present.
    $summary_raw = $product->get_short_description();
    preg_match('/<!-- en -->(.+?)<!-- \/en -->/s', $summary_raw, $en_match);
    preg_match('/<!-- ms -->(.+?)<!-- \/ms -->/s', $summary_raw, $ms_match);
    $summary = !empty($en_match[1]) ? trim($en_match[1]) : strip_tags($summary_raw, '<p><a><strong><b><i><em><ul><ol><li><br>');
}

$image = wp_get_attachment_image_url($product->get_image_id(), 'large') ?: '';

// Fetch variations if it's a variable product
$variations = [];
if ($product->is_type('variable')) {
    $variations = $product->get_available_variations();
    if (function_exists('ivermectin_sort_product_variations')) {
        ivermectin_sort_product_variations($variations, $product);
    }
}
$default_variation_index = 0;
$lowest_variation_price = INF;
foreach ($variations as $variation_index => $variation_data) {
    if (!isset($variation_data['display_price']) || !is_numeric($variation_data['display_price'])) {
        continue;
    }
    if (isset($variation_data['is_in_stock']) && !$variation_data['is_in_stock']) {
        continue;
    }

    $variation_price = (float) $variation_data['display_price'];
    if ($variation_price < $lowest_variation_price) {
        $lowest_variation_price = $variation_price;
        $default_variation_index = $variation_index;
    }
}

$text_under_image = get_field('text_under_product_image', $product->get_id()); // ACF field
$product_id = $product->get_id();
$review_summary = ivermectin_get_product_review_summary($product_id);
$reviews = $review_summary['reviews'];
$unit_price = ivermectin_get_product_unit_price($product);
$display_unit_price = $unit_price;
$unit_price_lead = 'From ';
if ($product->is_type('variable') && isset($variations[$default_variation_index])) {
    $default_variation = $variations[$default_variation_index];
    $default_variation_product = wc_get_product($default_variation['variation_id'] ?? 0);
    $default_pack_count = $default_variation_product
        ? ivermectin_get_product_pack_count($product, $default_variation_product)
        : 0;
    if ($default_pack_count > 0) {
        $display_unit_price = (float) $default_variation['display_price'] / $default_pack_count;
        $unit_price_lead = '';
    }
}
$unit_currency = get_woocommerce_currency();
$unit_currency_symbol = '$';
$unit_price_decimals = wc_get_price_decimals();
$unit_price_decimal_separator = wc_get_price_decimal_separator();
$unit_price_thousand_separator = wc_get_price_thousand_separator();
$product_badge = trim((string) get_field('product_badge', $product_id));
if ($product_badge === '' && $product->is_featured()) {
    $product_badge = ivermectin_get_product_presentation_option('best_seller_label', 'Best Seller');
}
$free_shipping_message = ivermectin_get_product_presentation_option('free_shipping_message');
$free_shipping_threshold = get_field('free_shipping_threshold', 'option');
$free_shipping_threshold = is_numeric($free_shipping_threshold) ? $free_shipping_threshold : 0;
$product_specs_heading = ivermectin_get_product_presentation_option('product_specs_heading', 'Product specs');
$no_reviews_text = ivermectin_get_product_presentation_option('product_no_reviews_text', 'No reviews yet');
$product_specs = [
    [
        'label' => ivermectin_get_product_presentation_option('product_specs_active_label', 'Active Ingredient'),
        'value' => get_field('product_active_ingredient', $product_id),
    ],
    [
        'label' => ivermectin_get_product_presentation_option('product_specs_indication_label', 'Indication'),
        'value' => get_field('product_indication', $product_id),
    ],
    [
        'label' => ivermectin_get_product_presentation_option('product_specs_manufacturer_label', 'Manufacturer'),
        'value' => get_field('product_manufacturer', $product_id) ?: (ivermectin_get_product_attribute_by_label($product, '/manufacturer|brand/i') ?: $product->get_meta('brand')),
    ],
    [
        'label' => ivermectin_get_product_presentation_option('product_specs_strength_label', 'Strength'),
        'value' => ivermectin_get_product_strength($product),
    ],
    [
        'label' => ivermectin_get_product_presentation_option('product_specs_packaging_label', 'Packaging'),
        'value' => get_field('product_packaging', $product_id) ?: ivermectin_get_product_attribute_by_label($product, '/packaging/i'),
    ],
    [
        'label' => ivermectin_get_product_presentation_option('product_specs_delivery_label', 'Delivery Time'),
        'value' => get_field('product_delivery_time', $product_id),
    ],
];
// Count only the populated specifications that the list below actually displays.
$product_specs = array_values(array_filter($product_specs, static function (array $spec): bool {
    return trim((string) ($spec['label'] ?? '')) !== ''
        && trim(wp_strip_all_tags((string) ($spec['value'] ?? ''))) !== '';
}));
?>

<div class="relative overflow-hidden bg-gradient-to-b from-primary-soft via-primary-soft to-white bg-pattern-dots py-4 md:py-6 border-b border-sky-100">
    <div class="container-site">
        <nav class="iva-product-breadcrumb inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 rounded-full border border-sky-300/80 bg-white/90 px-4 py-1.5 shadow-sm backdrop-blur" data-testid="breadcrumb">
            <a href="<?= home_url('/') ?>"
                class="hover:text-primary transition-colors font-bold"><?= "Home" ?></a>
            <span class="text-sky-300">/</span>
            <a href="<?= wc_get_page_permalink('shop') ?>"
                class="hover:text-primary transition-colors font-bold"><?= "Products" ?></a>
            <span class="text-sky-300">/</span>
            <span class="text-primary font-extrabold"><?= esc_html($product->get_name()) ?></span>
        </nav>
    </div>
</div>

<section class="container-site py-7 md:py-10">
    <div class="iva-product-layout grid md:grid-cols-2 gap-8 lg:gap-12">
        <!-- Left Column: Image Area -->
        <div class="min-w-0 md:sticky md:top-24 md:self-start">
            <div class="rounded-3xl overflow-hidden bg-[radial-gradient(ellipse_at_center,rgba(186,230,253,0.35)_0%,rgba(255,255,255,0.95)_70%)] border border-sky-300/80 p-6 md:p-10 flex items-center justify-center relative shadow-sm group hover:shadow-xl hover:border-primary transition-all duration-300"
                data-testid="product-image">
                <img src="<?= esc_url($image) ?>"
                    alt="<?= esc_attr($product->get_name()) ?>"
                    class="max-w-full max-h-[460px] object-contain transition-transform duration-500 group-hover:scale-105">
            </div>

            <!-- ACF: Text under product image -->
            <?php if (!empty($text_under_image)): ?>
                <div class="mt-6 prose prose-slate prose-sm max-w-none rounded-3xl border border-sky-200/80 bg-white p-6 shadow-sm">
                    <?= $text_under_image ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Info Area -->
        <div class="min-w-0">
            <div class="mb-3 flex flex-wrap items-center gap-2">
                <?php if ($product_badge !== ''): ?>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500 px-3.5 py-1 text-xs font-black uppercase tracking-wider text-white shadow-sm">
                        <svg class="w-3.5 h-3.5 text-white fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <?= esc_html($product_badge) ?>
                    </span>
                <?php endif; ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3.5 py-1 text-xs font-bold text-primary border border-sky-300 shadow-sm">
                    <span class="h-2 w-2 rounded-full <?= $product->is_in_stock() ? 'bg-emerald-500 animate-pulse' : 'bg-destructive' ?>" aria-hidden="true"></span>
                    <?= esc_html($product->is_in_stock() ? 'In stock' : 'Out of stock') ?>
                </span>
            </div>
            <h1 class="font-heading text-2xl md:text-4xl font-extrabold mb-3 text-primary tracking-tight"
                data-testid="text-product-title">
                <?= esc_html($product->get_name()) ?>
            </h1>

            <div class="mb-5 flex flex-wrap items-baseline gap-3">
                <span class="text-3xl md:text-4xl font-black text-primary" data-testid="text-product-price">
                    <?php
                    if ($product->is_type('variable')) {
                        $initial_price = isset($variations[$default_variation_index]['display_price'])
                            ? (float) $variations[$default_variation_index]['display_price']
                            : (float) $product->get_variation_price('min');
                        echo wp_kses_post(wc_price($initial_price));
                    } else {
                        echo $product->get_price_html();
                    }
                    ?>
                </span>
            </div>

            <div class="prose prose-sm text-slate-600 mb-6 max-w-none leading-relaxed" data-testid="text-product-description">
                <?= wp_kses_post($summary) ?>
            </div>

            <?php if ($free_shipping_message !== '' && is_numeric($free_shipping_threshold) && (float) $free_shipping_threshold > 0): ?>
                <div class="mb-6 flex items-center gap-3.5 rounded-2xl border border-amber-300 bg-gradient-to-r from-amber-50 via-orange-50/50 to-amber-50 p-4 shadow-sm">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm" aria-hidden="true">
                        <!-- Colorful Star/Gift SVG -->
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </span>
                    <?php
                    $free_shipping_threshold = (float) $free_shipping_threshold;
                    $free_shipping_decimals = $free_shipping_threshold === floor($free_shipping_threshold)
                        ? 0
                        : wc_get_price_decimals();
                    ?>
                    <span class="text-sm font-semibold text-slate-800"><?= esc_html($free_shipping_message) ?> <strong class="font-extrabold text-primary"><?= wp_kses_post(wc_price($free_shipping_threshold, ['decimals' => $free_shipping_decimals])) ?></strong></span>
                </div>
            <?php endif; ?>

            <?php if ($product->is_in_stock() && !empty($variations)): ?>

                <form id="product-purchase-form" class="mb-6 cart custom-variations-form" action="" method="post" enctype='multipart/form-data'>

                    <div class="space-y-5" data-testid="variation-selector">
                        <div>
                            <label
                                class="text-sm font-bold text-foreground mb-2 block"><?= "Choose Quantity (prices in AUD)" ?></label>

                            <div class="space-y-1.5" id="variation-rows">
                                <?php 
                                // Determine the correct attribute key dynamically (e.g., attribute_pa_pack-size)
                                $first_attr_key = 'attribute_pa_quantity';
                                if (!empty($variations) && !empty($variations[0]['attributes'])) {
                                    $keys = array_keys($variations[0]['attributes']);
                                    if (!empty($keys)) {
                                        $first_attr_key = $keys[0];
                                    }
                                }
                                
                                $sticky_default_pack_label = '';
                                foreach ($variations as $i => $variation):
                                    // Read pack quantity from the actual variation attribute.
                                    $qty_str = isset($variation['attributes'][$first_attr_key]) ? $variation['attributes'][$first_attr_key] : '';
                                    if (empty($qty_str) && !empty($variation['attributes'])) {
                                        $qty_str = reset($variation['attributes']); // Fallback
                                    }

                                    $variation_product = wc_get_product($variation['variation_id']);
                                    $first_attr_name = preg_replace('/^attribute_/', '', $first_attr_key);
                                    $display_qty_str = $variation_product ? $variation_product->get_attribute($first_attr_name) : '';
                                    $display_qty_str = $display_qty_str ?: str_replace(['-', '_'], ' ', (string) $qty_str);
                                    $display_qty_str = trim(preg_replace('/\s+/', ' ', wp_strip_all_tags($display_qty_str)));
                                    $display_qty_str = $display_qty_str !== '' ? ucwords($display_qty_str) : '';
                                    if (preg_match('/^\d+$/', $display_qty_str)) {
                                        $display_qty_str .= ' Tabs';
                                    }
                                    $qty_num = $variation_product ? ivermectin_get_product_pack_count($product, $variation_product) : 0;

                                    $price_num = (float) $variation['display_price'];
                                    $per_tab_price = $qty_num > 0 ? $price_num / $qty_num : null;
                                    $is_active = ($i === $default_variation_index);
                                    if ($is_active) {
                                        $sticky_default_pack_label = $display_qty_str;
                                    }
                                    $sticky_price_label = wp_strip_all_tags(wc_price($price_num));
                                    ?>
                                    <button type="button"
                                        class="variation-row-btn w-full flex items-center justify-between gap-3 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border text-left transition-all duration-200 <?php echo $is_active ? 'border-primary bg-sky-50 text-primary active-row shadow-sm' : 'border-sky-200/80 bg-white text-slate-700 hover:border-sky-400 hover:bg-sky-50/40'; ?>"
                                        data-id="<?= esc_attr($variation['variation_id']) ?>"
                                        data-qty="<?= esc_attr($qty_num) ?>" data-price="<?= esc_attr($price_num) ?>"
                                        data-pack-label="<?= esc_attr($display_qty_str) ?>" data-display-price="<?= esc_attr($sticky_price_label) ?>"
                                        data-display-price-html="<?= esc_attr(wc_price($price_num)) ?>"
                                        data-per-tab="<?= esc_attr($per_tab_price ?? '') ?>" data-val="<?= esc_attr($qty_str) ?>">

                                        <span class="text-xs sm:text-sm font-extrabold flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full <?php echo $is_active ? 'bg-primary' : 'bg-slate-300'; ?>"></span>
                                            <?= esc_html($display_qty_str) ?>
                                        </span>
                                        <span class="text-right shrink-0">
                                            <span
                                                class="text-xs sm:text-sm font-black text-primary block leading-snug"><?= wp_kses_post(wc_price($price_num)) ?></span>
                                            
                                        </span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="flex items-center gap-3">
                            <label
                                class="text-xs sm:text-sm font-bold text-primary"><?= "Quantity" ?></label>
                            <div class="flex items-center border border-sky-300 rounded-xl bg-white shadow-sm overflow-hidden">
                                <button type="button" id="qty-dec"
                                    class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-slate-500 hover:text-primary hover:bg-sky-50 transition-colors font-bold text-base">-</button>
                                <span id="qty-val" class="w-8 sm:w-10 text-center text-xs sm:text-sm font-extrabold text-primary">1</span>
                                <button type="button" id="qty-inc"
                                    class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-slate-500 hover:text-primary hover:bg-sky-50 transition-colors font-bold text-base">+</button>
                            </div>
                        </div>

                        <!-- Hidden WooCommerce fields required for cart -->
                        <input type="hidden" name="add-to-cart" value="<?= absint($product->get_id()) ?>" />
                        <input type="hidden" name="product_id" value="<?= absint($product->get_id()) ?>" />
                        <input type="hidden" name="variation_id" class="variation_id"
                            value="<?= esc_attr($variations[$default_variation_index]['variation_id'] ?? '') ?>" />
                        <input type="hidden" name="<?= esc_attr($first_attr_key) ?>" class="variation_attribute_hidden"
                            value="<?= esc_attr($variations[$default_variation_index]['attributes'][$first_attr_key] ?? '') ?>" />
                        <input type="hidden" name="quantity" class="form-quantity" value="1" />

                        <!-- Checkout Button -->
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500 hover:from-amber-600 hover:to-amber-700 px-4 py-2.5 sm:px-6 sm:py-3 text-xs sm:text-sm font-black uppercase tracking-wide sm:tracking-wider text-white shadow-md hover:shadow-xl hover:-translate-y-0.5 transition-all disabled:opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-4.5 sm:w-4.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span id="submit-btn-text">
                                <?= "Checkout & Pay" ?> -
                                <?= wp_kses_post(wc_price((float) $variations[$default_variation_index]['display_price'])) ?>
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Script to handle variation row selection & quantity calculation -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const rows = document.querySelectorAll('.variation-row-btn');
                        const inputVarId = document.querySelector('input.variation_id');
                        const inputAttr = document.querySelector('input.variation_attribute_hidden');
                        const inputQty = document.querySelector('input.form-quantity');

                        const qtyVal = document.getElementById('qty-val');
                        const btnDec = document.getElementById('qty-dec');
                        const btnInc = document.getElementById('qty-inc');
                        const btnText = document.getElementById('submit-btn-text');
                        const stickyBar = document.getElementById('sticky-product-bar');
                        const stickyPack = document.getElementById('sticky-product-pack');
                        const stickyPrice = document.getElementById('sticky-product-price');
                        const stickySubmit = document.getElementById('sticky-product-submit');
                        const purchaseForm = document.getElementById('product-purchase-form');

                        const selectedRow = document.querySelector('.variation-row-btn.active-row') || rows[0];
                        let selectedPrice = parseFloat(selectedRow.dataset.price);
                        let quantity = 1;

                        if (selectedRow) {
                            if (stickyPack) stickyPack.textContent = selectedRow.dataset.packLabel || '';
                        }

                        function updateBtnText() {
                            const total = selectedPrice * quantity;
                            btnText.innerHTML = '<?= "Checkout & Pay" ?> - $' + total.toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            if (stickyPrice) {
                                stickyPrice.textContent = '$' + total.toLocaleString('en-AU', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                        }

                        if (selectedRow && stickyPrice) {
                            stickyPrice.textContent = selectedRow.dataset.displayPrice || '';
                        }

                        rows.forEach(row => {
                            row.addEventListener('click', function () {
                                // Update UI states
                                rows.forEach(r => r.className = "variation-row-btn w-full flex items-center justify-between gap-3 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border text-left transition-all duration-200 border-sky-200/80 bg-white text-slate-700 hover:border-sky-400 hover:bg-sky-50/40");
                                this.className = "variation-row-btn active-row w-full flex items-center justify-between gap-3 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl border text-left transition-all duration-200 border-primary bg-sky-50 text-primary shadow-sm";

                                // Update Hidden inputs
                                inputVarId.value = this.dataset.id;
                                inputAttr.value = this.dataset.val;

                                // Update local variables
                                selectedPrice = parseFloat(this.dataset.price);
                                const mainPriceEl = document.querySelector('[data-testid="text-product-price"]');
                                if (mainPriceEl && this.dataset.displayPriceHtml) {
                                    mainPriceEl.innerHTML = this.dataset.displayPriceHtml;
                                }
                                if (stickyPack) stickyPack.textContent = this.dataset.packLabel || '';
                                updateBtnText();
                            });
                        });

                        if (stickyBar && purchaseForm) {
                            const toggleStickyBar = () => {
                                const showStickyBar = purchaseForm.getBoundingClientRect().bottom <= 0;
                                stickyBar.classList.toggle('translate-y-full', !showStickyBar);
                                stickyBar.classList.toggle('opacity-0', !showStickyBar);
                                stickyBar.classList.toggle('pointer-events-none', !showStickyBar);
                                stickyBar.classList.toggle('translate-y-0', showStickyBar);
                                stickyBar.classList.toggle('opacity-100', showStickyBar);
                                stickyBar.setAttribute('aria-hidden', showStickyBar ? 'false' : 'true');
                                stickyBar.inert = !showStickyBar;
                                document.body.classList.toggle('has-sticky-product-bar', showStickyBar);
                            };
                            let scrollFrame = 0;
                            const queueStickyUpdate = () => {
                                if (scrollFrame) return;
                                scrollFrame = window.requestAnimationFrame(() => {
                                    toggleStickyBar();
                                    scrollFrame = 0;
                                });
                            };

                            window.addEventListener('scroll', queueStickyUpdate, { passive: true });
                            window.addEventListener('resize', queueStickyUpdate);
                            window.addEventListener('pageshow', queueStickyUpdate);
                            queueStickyUpdate();

                            if (stickySubmit) {
                                stickySubmit.addEventListener('click', () => {
                                    if (typeof purchaseForm.requestSubmit === 'function') {
                                        purchaseForm.requestSubmit();
                                    } else {
                                        purchaseForm.submit();
                                    }
                                });
                            }
                        }

                        // Quantity listeners
                        btnDec.addEventListener('click', function () {
                            if (quantity > 1) {
                                quantity--;
                                qtyVal.innerText = quantity;
                                inputQty.value = quantity;
                                updateBtnText();
                            }
                        });

                        btnInc.addEventListener('click', function () {
                            quantity++;
                            qtyVal.innerText = quantity;
                            inputQty.value = quantity;
                            updateBtnText();
                        });
                    });
                </script>

            <?php else: ?>
                <div class="mb-6">
                    <span
                        class="w-full flex items-center justify-center rounded-md bg-destructive-soft px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-destructive">
                        <?= "Out of Stock" ?>
                    </span>
                </div>
            <?php endif; ?>

            <details open class="mt-6 overflow-hidden rounded-3xl border border-sky-300/80 bg-white shadow-sm">
                <summary class="group flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4 font-bold text-primary bg-sky-50/60 border-b border-sky-200 marker:hidden [&::-webkit-details-marker]:hidden">
                    <span class="inline-flex items-center gap-2 text-sm sm:text-base font-heading">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-100 text-primary">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </span>
                        <span><?= esc_html($product_specs_heading ?: '') ?> (<?= count($product_specs) ?>)</span>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-primary transition-transform duration-200 group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </summary>
                <dl class="divide-y divide-dashed divide-sky-100 px-5 py-2">
                    <?php foreach ($product_specs as $spec): ?>
                        <?php
                        $spec_label = trim((string) ($spec['label'] ?? ''));
                        $spec_value = trim(wp_strip_all_tags((string) ($spec['value'] ?? '')));
                        if ($spec_label === '' || $spec_value === '') {
                            continue;
                        }
                        ?>
                        <div class="flex items-start justify-between gap-4 py-3 text-sm">
                            <dt class="text-slate-500 font-medium"><?= esc_html($spec_label) ?></dt>
                            <dd class="text-right font-bold text-primary"><?= esc_html($spec_value) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </details>
        </div>
    </div>
    <!-- ACF: Extra Tabs (Removed as requested) -->
</section>

<?php if ($product->is_in_stock() && !empty($variations)): ?>
    <?php $sticky_default_variation = $variations[$default_variation_index] ?? null; ?>
    <div id="sticky-product-bar" class="fixed inset-x-0 bottom-0 z-40 translate-y-full border-t border-sky-300/80 bg-white/95 opacity-0 shadow-[0_-8px_24px_rgba(12,65,97,0.12)] backdrop-blur-md transition duration-300 pointer-events-none" aria-hidden="true" inert>
        <div class="container-site flex items-center justify-between gap-2 py-2 sm:gap-4 sm:py-3">
            <!-- Left: Product info / Price -->
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                <?php if ($image !== ''): ?>
                    <img src="<?= esc_url($image) ?>" alt="" class="hidden min-[420px]:block sm:block h-10 w-10 sm:h-12 sm:w-12 shrink-0 rounded-xl border border-sky-200 bg-white object-contain p-1">
                <?php endif; ?>
                <div class="min-w-0">
                    <p class="hidden sm:block truncate text-xs font-extrabold text-slate-900 sm:text-sm max-w-[200px] md:max-w-none"><?= esc_html($product->get_name()) ?></p>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-1.5 sm:mt-0.5">
                        <span id="sticky-product-price" class="font-black text-sm sm:text-base text-primary leading-tight" data-currency-symbol="<?= esc_attr(get_woocommerce_currency_symbol()) ?>"><?= esc_html(wp_strip_all_tags(wc_price((float) ($sticky_default_variation['display_price'] ?? 0)))) ?></span>
                        <span class="hidden sm:inline text-slate-400" aria-hidden="true"> · </span>
                        <span id="sticky-product-pack" class="text-[11px] sm:text-xs font-semibold text-slate-500 truncate max-w-[90px] sm:max-w-none"><?= esc_html($sticky_default_pack_label ?? '') ?></span>
                    </div>
                </div>
            </div>

            <!-- Right: Add to Cart Button -->
            <div class="flex items-center justify-end shrink-0">
                <button id="sticky-product-submit" type="button" class="w-auto inline-flex shrink-0 items-center justify-center gap-1.5 sm:gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 px-4 py-2.5 sm:px-7 sm:py-3 text-xs sm:text-sm font-black uppercase tracking-wider text-white shadow-md hover:shadow-lg transition-all active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span><?= esc_html(ivermectin_label('add_to_cart_label')) ?></span>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Product Description Section -->
<?php
$acf_main = $product_description_module ?: get_post_meta($product->get_id(), 'main_desc', true);
$main_desc = '';

if ($acf_main) {
    $main_desc = $acf_main;
} else {
    $main_desc = get_the_content();
}

if (!empty(trim(strip_tags($main_desc)))): 
?>
<?php ivermectin_render_article_section((string) apply_filters('the_content', $main_desc), true, 'default', true); ?>
<?php endif; ?>

<?php
// Content stored by the previous theme: extra tabs and product FAQs.
$legacy_tabs = have_rows('extra_tabs', $product_id) ? get_field('extra_tabs', $product_id) : [];
// The FAQs module (Page Modules > Product FAQs) replaces the old FAQ field once a product has one.
$has_faq_module = false;
if (have_rows('page_modules', $product_id)) {
    while (have_rows('page_modules', $product_id)) {
        the_row();
        if (get_row_layout() === 'product_faqs') { $has_faq_module = true; }
    }
}
$legacy_faqs = (!$has_faq_module && have_rows('faqs', $product_id)) ? get_field('faqs', $product_id) : [];
?>
<?php if ($legacy_tabs) : ?>
<section class="iva-section-tight bg-white">
    <div class="container-site max-w-5xl space-y-5">
        <?php foreach ($legacy_tabs as $legacy_tab) : if (empty($legacy_tab['tab_content'])) { continue; } ?>
            <div class="iva-panel">
                <?php if (!empty($legacy_tab['tab_title'])) : ?><h2 class="iva-h3"><?= esc_html($legacy_tab['tab_title']) ?></h2><?php endif; ?>
                <div class="prose iva-prose mt-4 max-w-none"><?= wp_kses_post($legacy_tab['tab_content']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php if ($legacy_faqs) {
    ivermectin_render_faq_section((string) get_field('faq_title', $product_id), $legacy_faqs, 'bg-surface');
} ?>

<?php
// Product reviews (reviews linked to this product).
$show_product_reviews = apply_filters('ivermectin_show_product_reviews', true);
if ($show_product_reviews) {
    get_template_part('template-parts/product-reviews', null, [
        'product_id' => $product->get_id(),
        'product_name' => $product->get_name(),
    ]);
}
?>

<?php
if (have_rows('page_modules', $product->get_id())) {
    while (have_rows('page_modules', $product->get_id())) {
        the_row();
        $layout = get_row_layout();
        if (in_array($layout, ['product_summary', 'product_description', 'reviews', 'reviews_carousel'], true)) {
            continue;
        }
        get_template_part('modules/content', $layout);
    }
} else {
    ivermectin_render_modules($product->get_id());
}
?>

<?php get_footer('shop'); ?>
