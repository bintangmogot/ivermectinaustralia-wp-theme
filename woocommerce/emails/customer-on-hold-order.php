<?php
/**
 * Customer on-hold order email
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );

$customer_first_name = $order->get_billing_first_name();

$intro_copy = function_exists( 'get_field' ) ? get_field( 'email_on_hold_intro', 'option' ) : '';

$instruction_copy = function_exists( 'get_field' ) ? get_field( 'email_on_hold_instruction', 'option' ) : '';

$warning_copy = function_exists( 'get_field' ) ? get_field( 'email_on_hold_warning', 'option' ) : '';
?>

<p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #0F172A; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;"><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $customer_first_name ) ); ?></p>

<p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #0F172A; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;"><?php echo esc_html( $intro_copy ); ?></p>

<p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #0F172A; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <?php
    $formatted_instruction = wp_kses_post( $instruction_copy );
    // Clean any plain strong tags around targets to prevent double wrapping
    $formatted_instruction = preg_replace( '/<strong>\s*(EFT or PayID)\s*<\/strong>/i', '$1', $formatted_instruction );
    $formatted_instruction = preg_replace( '/<strong>\s*(order number)\s*<\/strong>/i', '$1', $formatted_instruction );
    if ( strpos( $formatted_instruction, '#E32400' ) === false && strpos( $formatted_instruction, '#e32400' ) === false ) {
        $formatted_instruction = str_ireplace(
            'EFT or PayID',
            '<strong style="color: #E32400; font-weight: 700;">EFT or PayID</strong>',
            $formatted_instruction
        );
        $formatted_instruction = str_ireplace(
            'order number',
            '<strong style="color: #E32400; font-weight: 700;">order number</strong>',
            $formatted_instruction
        );
    }
    echo $formatted_instruction;
    ?>
</p>

<p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #0F172A; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <strong style="color: #0F172A; font-weight: 800;"><?php echo esc_html( wp_strip_all_tags( $warning_copy ) ); ?></strong>
</p>

<?php
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );


/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
