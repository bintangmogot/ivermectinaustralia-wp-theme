<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="related">

		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Alternative Medicine', 'woocommerce' ) );

		if ( $heading ) :
			?>
			<h3 class='sub-heading'><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>
		<div class='featured-product-list'>	
		<?php //woocommerce_product_loop_start(); ?>

			<?php foreach ( $related_products as $related_product ) : ?>

				<?php
				$post_object = get_post( $related_product->get_id() );
				setup_postdata( $GLOBALS['post'] = $post_object );

				
				global $product;
				?>
				<div class=' featured-product '>	
					<div class='featured-product-inner'>
						
						<div class='image'><?php woocommerce_template_loop_product_thumbnail()?></div>									
						<div class='info'>	
							<?php $rating_count = $product->get_rating_count();
							$review_count = $product->get_review_count();
							$average      = $product->get_average_rating();
							
							if ( $rating_count > 0 ) : ?>

								<!--<div class="woocommerce-product-rating">
									<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
									<?php if ( comments_open() ) : ?>
										<?php //phpcs:disable ?>
										<?php printf( _n( '%s happy customer', '%s happy customers', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>
										<?php // phpcs:enable ?>
									<?php endif ?>
								</div>-->

							<?php endif; ?>
							
							<?php woocommerce_template_loop_product_title()?>
							<?php woocommerce_template_single_price()?>
							<?php woocommerce_template_loop_add_to_cart()?>		
						</div>
						
						<div class='entry-content'><?php the_field('short_description')?></div>
					</div>
				</div>

			<?php endforeach; ?>
		
		<?php //woocommerce_product_loop_end(); ?>
		</div>
	</section>
	<?php
endif;

wp_reset_postdata();
