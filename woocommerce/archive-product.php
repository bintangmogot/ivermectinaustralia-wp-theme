<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 */

defined('ABSPATH') || exit;

get_header('shop');

$shop_page_id = wc_get_page_id('shop');

// Check if ACF modules exist on the Shop page
if (have_rows('modules', $shop_page_id)) {
    echo '<main>';
    while (have_rows('modules', $shop_page_id)) {
        the_row();
        $layout = get_row_layout();

        // Omit product comparison and guide sections from the shop page as requested.
        // FAQ content is now displayed.
        if (is_shop() && in_array($layout, ['product_guide', 'catalog_comparison', 'comparison', 'seo_text'], true)) {
            continue;
        }

        get_template_part('modules/content', $layout);
    }
    echo '</main>';
} else {
    // Fallback if no ACF modules are configured for the Shop page
?>

<section class="relative isolate overflow-hidden bg-gradient-to-br from-[#082a3f] via-[#0c4161] to-[#125883] bg-pattern-dots-dark text-white py-8 md:py-10 text-center border-b-2 border-dashed border-sky-400/30">
    <div class="container-site max-w-4xl relative z-10 px-4">
        <span class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/15 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-amber-300 shadow-sm mb-4">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            Full Catalogue
        </span>
        <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
            <?= esc_html(woocommerce_page_title(false)) ?>
        </h1>
        <p class="mx-auto mt-3 max-w-2xl text-base leading-relaxed text-sky-100/90 font-medium">
            <?= "Browse and buy genuine Armodafinil tablets online in Australia. Fast Australia-wide delivery on all orders." ?>
        </p>
    </div>
</section>

<section class="relative overflow-hidden bg-gradient-to-b from-[#f0f6fa] via-[#e9f2f8] to-[#f8fafc] bg-pattern-dots py-8 md:py-12">
    <div class="container-site relative z-10 px-3 sm:px-4">
        <?php
        if (woocommerce_product_loop()) {
            woocommerce_product_loop_start();

            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();
                    do_action('woocommerce_shop_loop');
                    wc_get_template_part('content', 'product');
                }
            }

            woocommerce_product_loop_end();
        } else {
            do_action('woocommerce_no_products_found');
        }
        ?>
    </div>
</section>

<?php
// Guide Section from React
?>
<section class="relative overflow-hidden bg-[#eef5fa] bg-pattern-dots py-14 md:py-20 border-t-2 border-dashed border-sky-200/80">
    <div class="container-site max-w-4xl relative z-10 px-4">
        <div class="text-center mb-10">
            <span class="inline-flex items-center gap-2 rounded-full bg-sky-100 border border-sky-300/80 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-[#0c4161] shadow-sm mb-3">
                Buyer's Guide
            </span>
            <h2 class="font-heading text-2xl md:text-4xl font-extrabold text-[#0c4161]"><?= "Choosing the Right Modafinil" ?></h2>
            <p class="mt-3 text-base leading-relaxed text-slate-600 max-w-2xl mx-auto">
                <?= "With several brands and dosages available, the right product depends on your experience level, desired effects, and budget. Below is a comparison of our most popular options." ?>
            </p>
        </div>
        
        <div class="space-y-6">
            <div class="group rounded-3xl border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] bg-white p-6 sm:p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500 text-white font-black text-sm">1</span>
                    <h3 class="font-heading text-xl font-bold text-slate-900 group-hover:text-[#0c4161] transition-colors"><?= "Modalert 200mg — The Gold Standard" ?></h3>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-slate-600"><?= "Manufactured by Sun Pharma, Modalert is the most widely recognised modafinil brand worldwide. Each tablet contains the standard 200mg clinical dose and delivers a clean, sustained boost in focus lasting 10–12 hours." ?></p>
                <div class="mt-4 pt-3 border-t border-dashed border-sky-100 flex items-center justify-between">
                    <p class="text-xs font-bold text-[#0c4161] bg-sky-50 px-3 py-1 rounded-full"><?= "Best for: First-time users who want a trusted, proven brand." ?></p>
                </div>
            </div>
            
            <div class="group rounded-3xl border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] bg-white p-6 sm:p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-600 text-white font-black text-sm">2</span>
                    <h3 class="font-heading text-xl font-bold text-slate-900 group-hover:text-[#0c4161] transition-colors"><?= "Modvigil 200mg — Affordable Alternative" ?></h3>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-slate-600"><?= "Produced by HAB Pharma with the same 200mg dose as Modalert. Many users report very similar effects at a lower price, with a slightly gentler onset that suits all-day productivity." ?></p>
                <div class="mt-4 pt-3 border-t border-dashed border-sky-100 flex items-center justify-between">
                    <p class="text-xs font-bold text-[#0c4161] bg-sky-50 px-3 py-1 rounded-full"><?= "Best for: Budget-conscious buyers or those who want a smoother onset." ?></p>
                </div>
            </div>

            <div class="group rounded-3xl border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] bg-white p-6 sm:p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#0c4161] text-white font-black text-sm">3</span>
                    <h3 class="font-heading text-xl font-bold text-slate-900 group-hover:text-[#0c4161] transition-colors"><?= "Modasmart 400mg — Extended Duration" ?></h3>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-slate-600"><?= "Double the standard dose in a single tablet, designed for experienced users who need longer-lasting cognitive enhancement for extended study sessions or overnight shifts." ?></p>
                <div class="mt-4 pt-3 border-t border-dashed border-sky-100 flex items-center justify-between">
                    <p class="text-xs font-bold text-[#0c4161] bg-sky-50 px-3 py-1 rounded-full"><?= "Best for: Experienced users who need 14+ hours of sustained focus." ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
} // End ACF check 
?>

<?php get_footer('shop'); ?>
