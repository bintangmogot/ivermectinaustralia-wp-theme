<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals bg-white rounded-xl border border-stone-200 p-6 lg:p-8 shadow-sm <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="text-xl font-bold font-heading text-slate-900 mb-5"><?php esc_html_e( 'Carts Totals', 'woocommerce' ); ?></h2>



	<div class="w-full text-sm">

		<!-- Subtotal -->
		<div class="cart-totals-flex py-4 border-b border-stone-100">
			<div class="font-medium text-slate-500"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></div>
			<div class="font-bold text-slate-900" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></div>
		</div>

		<!-- Coupons -->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-totals-flex py-4 border-b border-stone-100 text-emerald-600 cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<div class="font-medium"><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
				<div class="font-bold" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
			</div>
		<?php endforeach; ?>

		<!-- Shipping -->
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<!-- Custom Shipping Display Wrapper -->
			<div class="py-4 border-b border-stone-100">
				<div class="font-bold text-slate-900 text-base mb-3"><?php esc_html_e( 'Shipment', 'woocommerce' ); ?></div>
				<table class="w-full custom-cart-shipping-table">
					<tbody>
						<?php wc_cart_totals_shipping_html(); ?>
					</tbody>
				</table>
			</div>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
			<div class="cart-totals-flex py-4 border-b border-stone-100 shipping">
				<div class="font-medium text-slate-500"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></div>
				<div data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></div>
			</div>
		<?php endif; ?>

		<!-- Fees -->
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="cart-totals-flex py-4 border-b border-stone-100 fee">
				<div class="font-medium text-slate-500"><?php echo esc_html( $fee->name ); ?></div>
				<div class="font-bold text-slate-900" data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></div>
			</div>
		<?php endforeach; ?>

		<!-- Taxes -->
		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
					?>
					<div class="cart-totals-flex py-4 border-b border-stone-100 tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<div class="font-medium text-slate-500"><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div class="font-bold text-slate-900" data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="cart-totals-flex py-4 border-b border-stone-100 tax-total">
					<div class="font-medium text-slate-500"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div class="font-bold text-slate-900" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<!-- Total -->
		<div class="cart-totals-flex pt-6 pb-2 order-total">
			<div class="text-lg font-black text-slate-900"><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
			<div class="text-xl font-black text-slate-900" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></div>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</div>

	<div class="wc-proceed-to-checkout mt-8">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
