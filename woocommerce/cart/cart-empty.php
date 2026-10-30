<?php
/**
 * Empty cart page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

// Remove default empty cart message so we can style our own
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
?>

<div class="max-w-2xl mx-auto py-12 md:py-20 text-center">
    
    <div class="mb-8 flex justify-center">
        <div class="w-32 h-32 bg-stone-100 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
    </div>

    <h2 class="font-heading text-3xl font-black text-slate-900 mb-4">
        <?= "Your cart is empty" ?>
    </h2>
    
    <p class="text-slate-500 mb-10 max-w-md mx-auto">
        <?= "Looks like you haven't added anything to your cart yet. Discover our premium products and get started." ?>
    </p>

    <?php do_action( 'woocommerce_cart_is_empty' ); ?>

    <?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
        <p class="return-to-shop">
            <a class="inline-flex rounded-full bg-primary px-5 py-2.5 sm:px-8 sm:py-3.5 text-xs sm:text-sm font-bold uppercase tracking-wider text-white shadow-sm transition-all hover:bg-primary-dark hover:-translate-y-0.5" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                <?php
                    echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', 'Return to shop' ) );
                ?>
            </a>
        </p>
    <?php endif; ?>

</div>
