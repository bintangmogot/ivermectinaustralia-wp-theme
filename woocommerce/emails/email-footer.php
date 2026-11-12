<?php
/**
 * Email Footer - Ivermectin Australia Bespoke Design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

$site_url = home_url( '/' );
?>
																		</div>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<!-- End Body -->
											</td>
										</tr>
									</table>
									<!-- End Main Container Card -->

								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Footer Card -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_footer" role="presentation">
										<tr>
											<td valign="top" style="padding: 24px 20px 32px 20px; text-align: center;">

												<!-- Australia Post Express Guarantee -->
												<p style="margin: 0 0 10px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; color: #64748B; line-height: 1.5;">
													<?php echo esc_html( ivermectin_label( 'field_iva_email_shipping_message' ) ); ?>
												</p>

												<!-- Copyright -->
												<p style="margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; color: #94A3B8;">
													&copy; <?php echo esc_html( date( 'Y' ) ); ?> <a href="<?php echo esc_url( $site_url ); ?>" style="color: #64748B; text-decoration: underline; font-weight: 600;"><?php echo esc_html( ivermectin_label( 'field_iva_email_footer_store_name' ) ); ?></a>. <?php echo esc_html( ivermectin_label( 'field_iva_email_footer_rights' ) ); ?>
												</p>

											</td>
										</tr>
									</table>
									<!-- End Footer -->
								</td>
							</tr>
						</table>
					</div>
				</td>
				<td><!-- Deliberately empty for responsive centering --></td>
			</tr>
		</table>
	</body>
</html>
