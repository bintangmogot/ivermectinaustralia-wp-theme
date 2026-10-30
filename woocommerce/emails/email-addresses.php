<?php
/**
 * Email Addresses - Waklert Australia Bespoke Design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.6.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sent_to_admin = $sent_to_admin ?? false;
$address       = $order->get_formatted_billing_address();
$shipping      = $order->get_formatted_shipping_address();
$has_shipping  = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping;
?>
<div style="margin: 28px 0 10px 0;">
	<table id="addresses" cellspacing="0" cellpadding="0" border="0" role="presentation" style="width: 100%; border-collapse: separate; border-spacing: 0; vertical-align: top;">
		<tr>
			<td class="font-family text-align-left address-column billing-address-col" style="border: 0; padding: 0 <?php echo $has_shipping ? '10px' : '0'; ?> 0 0; vertical-align: top;" width="<?php echo $has_shipping ? '50%' : '100%'; ?>" valign="top">
				<h3 style="color: #0C4161; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 700; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
					<?php esc_html_e( 'Billing address', 'woocommerce' ); ?>
				</h3>
				<address class="address" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px 18px; font-style: normal; font-size: 14px; line-height: 1.6; color: #0F172A; min-height: 140px; box-sizing: border-box; display: block;">
					<?php echo wp_kses_post( $address ? $address : esc_html__( 'N/A', 'woocommerce' ) ); ?>
					<?php if ( $order->get_billing_phone() ) : ?>
						<br/><a href="tel:<?php echo esc_attr( $order->get_billing_phone() ); ?>" style="color: #0284C7; text-decoration: none; font-weight: 600;"><?php echo esc_html( $order->get_billing_phone() ); ?></a>
					<?php endif; ?>
					<?php if ( $order->get_billing_email() ) : ?>
						<br/><a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>" style="color: #0284C7; text-decoration: none;"><?php echo esc_html( $order->get_billing_email() ); ?></a>
					<?php endif; ?>
					<?php do_action( 'woocommerce_email_customer_address_section', 'billing', $order, $sent_to_admin, false ); ?>
				</address>
			</td>
			<?php if ( $has_shipping ) : ?>
				<td class="font-family text-align-left address-column shipping-address-col" style="border: 0; padding: 0 0 0 10px; vertical-align: top;" width="50%" valign="top">
					<h3 style="color: #0C4161; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 700; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">
						<?php esc_html_e( 'Shipping address', 'woocommerce' ); ?>
					</h3>
					<address class="address" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px 18px; font-style: normal; font-size: 14px; line-height: 1.6; color: #0F172A; min-height: 140px; box-sizing: border-box; display: block;">
						<?php echo wp_kses_post( $shipping ); ?>
						<?php if ( $order->get_shipping_phone() ) : ?>
							<br/><a href="tel:<?php echo esc_attr( $order->get_shipping_phone() ); ?>" style="color: #0284C7; text-decoration: none; font-weight: 600;"><?php echo esc_html( $order->get_shipping_phone() ); ?></a>
						<?php endif; ?>
						<?php do_action( 'woocommerce_email_customer_address_section', 'shipping', $order, $sent_to_admin, false ); ?>
					</address>
				</td>
			<?php endif; ?>
		</tr>
	</table>
</div>
