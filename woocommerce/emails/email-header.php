<?php
/**
 * Email Header - Waklert Australia Bespoke Design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.7.0
 */

defined( 'ABSPATH' ) || exit;

$store_name = $store_name ?? get_bloginfo( 'name', 'display' );
if ( empty( $store_name ) ) {
    $store_name = 'Waklert Australia';
}

$logo_url = function_exists( 'waklert_get_logo_url' ) ? waklert_get_logo_url( 'header' ) : '';
if ( empty( $logo_url ) ) {
    $theme_logo = get_template_directory() . '/assets/images/waklert-australia-logo.png';
    if ( file_exists( $theme_logo ) ) {
        $logo_url = get_template_directory_uri() . '/assets/images/waklert-australia-logo.png';
    }
}
$header_image_url = home_url( '/' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<title><?php echo esc_html( $store_name ); ?></title>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<table width="100%" id="outer_wrapper" role="presentation" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><!-- Deliberately empty for responsive centering --></td>
				<td width="640" style="max-width: 640px; margin: 0 auto;">
					<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
						<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="inner_wrapper" role="presentation">
							<tr>
								<td align="center" valign="top">
									
									<!-- Preheader Preview Text for Email Clients -->
									<div style="display: none; font-size: 1px; color: #ffffff; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;">
										Waklert Australia &bull; Order Confirmation
									</div>

									<!-- Brand Top Bar / Logo Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_brand_header" role="presentation" style="margin-bottom: 20px;">
										<tr>
											<td align="center" style="padding: 24px 20px 12px 20px;">
												<?php if ( $logo_url ) : ?>
													<a href="<?php echo esc_url( $header_image_url ); ?>" target="_blank" style="text-decoration: none; display: inline-block;">
														<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $store_name ); ?>" width="240" style="max-width: 240px; height: auto; display: block; border: 0; outline: none; margin: 0 auto;" />
													</a>
												<?php else : ?>
													<a href="<?php echo esc_url( $header_image_url ); ?>" target="_blank" style="text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 26px; font-weight: 800; color: #0C4161; letter-spacing: -0.5px;">
														<?php echo esc_html( $store_name ); ?>
													</a>
												<?php endif; ?>
												<p style="margin: 8px 0 0 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 700; color: #0284C7; letter-spacing: 0.8px; text-transform: uppercase;">
													Official Australian Express Order Confirmation
												</p>
											</td>
										</tr>
									</table>

									<!-- Main Container Card -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" role="presentation">
										<tr>
											<td align="center" valign="top">
												<!-- Gradient Banner Header -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" role="presentation">
													<tr>
														<td id="header_wrapper" align="center">
															<h1><?php echo esc_html( $email_heading ); ?></h1>
															<p class="email-subheading">Thank you for shopping with Waklert Australia</p>
														</td>
													</tr>
												</table>
												<!-- End Header -->
											</td>
										</tr>
										<tr>
											<td align="center" valign="top">
												<!-- Body Content -->
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body" role="presentation">
													<tr>
														<td valign="top" id="body_content">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation">
																<tr>
																	<td valign="top" id="body_content_inner_cell">
																		<div id="body_content_inner">
