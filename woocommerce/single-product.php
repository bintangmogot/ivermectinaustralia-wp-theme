<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
	<div class='container single-product-page'>
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?> 

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>
	
	</div>
	<div class='single-product-bottom-wrapper'>
		<div class='reviews-section section'>
			<div class='reviews-wrapper '>	
				<div class='container'>
					<h3 class='sub-heading'>What Our Customers Say</h3>				
					<div class='reviews-slider'>
						<?php 
							$args = array( 'post_type' => 'reviews', 'posts_per_page' =>-1); 				
							$wp_query = new WP_Query($args);
						?>
						<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							<div class='review-wrapper'>
								<div class='review'>
									<div class='star-rating'><span style='width:<?php echo get_field('rating')*20?>%;'></span></div>
									<header><?php the_title()?></header>
									<div class='entry-content'><?php the_content()?></div>
									<name><?php the_field('name')?></name>
								</div>
							</div>
						<?php endwhile ?>
						<?php wp_reset_postdata();?>
						<?php wp_reset_query();?>
					</div>
				</div>
			</div>
		</div>
		
		<div class='feature-product-section section section-<?php echo $section;?>'>
			<div class='container'>	
				<h3 class='sub-heading'>Alternative Medicine</h3>
				<div class='featured-product-list'>	
					<?php 
						$args = array( 'post_type' => 'product', 'posts_per_page' =>-1, 'post__not_in'=>array(get_the_ID()));	
						$wp_query = new WP_Query($args);
					?>	
					<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<?php global $product; ?>
						<div class=' featured-product '>
							
							<div class='featured-product-inner'>
																	
								<div class='info'>	
									
									<div class='in-stock'>IN STOCK</div>
									
									<div class='image'><?php woocommerce_template_loop_product_thumbnail()?></div>
									<?php woocommerce_template_loop_product_title()?>
									<?php woocommerce_template_single_price()?>

									<?php woocommerce_template_loop_add_to_cart()?>		
								</div>
								
								<div class='entry-content'><?php the_field('short_description')?></div>
							</div>
						</div>
					<?php endwhile ?>
					<?php wp_reset_postdata();?>
					<?php wp_reset_query();?>
				</div>	
			</div>	
		</div>			
		
		
		<?php if(get_field('faqs')):?>
			<div class='product-faqs accordion-section section wowo fadeInUp '>
				<div class='container '>					
					<h3 class='faqs-title'><?php the_field('faq_title')?></h3>			
					<div class='row '>
					<?php while(have_rows('faqs')):the_row()?>
						<div class='col-lg-6 px-4'>
							<div class='accordion active' itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
								<header class='accordion-title' itemprop="name" ><?php the_sub_field('question')?><span class='accordion-toggle'><i class='icon ion-plus-round'></i></span></header>
								<div class='entry-content accordion-content' itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text"><?php the_sub_field('answer')?></div></div>
							</div>
						</div>
					<?php endwhile?>				
					</div>				
				</div>				
			</div>	
		<?php endif?>
		
		
<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
