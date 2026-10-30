<?php 
$post_id = false;
if(is_tax()){
	$post_id=get_queried_object();
}elseif(is_shop()){
	$post_id=wc_get_page_id( 'shop' );
}
?>
<?php if( have_rows('modules',$post_id) ):?>
<?php $section=1;?>
<?php while ( have_rows('modules',$post_id) ) : the_row();?>

	<div class='anchor-tag' id='section-<?php echo $section;?>'></div>
	
	<?php if( get_row_layout() == 'header_image' ):?>
		<div class='header-img-section section section-<?php echo $section;?>'>		
			
			<div class='header-img' style='background-image:url(<?php the_sub_field('image')?>)'></div>	
			<div class='header-img-content-wrapper '>
				<div class='container '>
					<div class='header-img-content'><?php the_sub_field('content')?></div>	
				</div>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'intro_black_background' ):?>
		<div class='intro-section section wowo fadeInUp section-<?php echo $section;?>' style='background-color:<?php the_sub_field('bg_color')?>'>
			<div class='container'>	
				<div class='entry-content'>
					<?php the_sub_field('content')?>
				</div>
			</div>
		</div>	
	
	<?php elseif( get_row_layout() == 'dosage_header_content' ):?>
		<div class='dosage-header-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='entry-content'>
					<?php the_sub_field('content')?>
				</div>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'video' ):?>
		<div class='video-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='row align-items-center'>	
					<div class='col-md-5'>	
						<div class='video entry-content'>
							<?php $file = get_sub_field('video');?>
							 <video width="350"  controls poster='https://modafinilaustraliaxpress.com/wp-content/uploads/2026/02/video-img.jpg'>
							  <source src="<?php echo $file['url']?>" type="video/mp4">
							Your browser does not support the video tag.
							</video> 
						</div>
					</div>
					<div class='col-md-7 '>	
						<div class='video-content'>
							<?php the_sub_field('content')?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	<?php elseif( get_row_layout() == 'breadcrumb' ):?>
		<div class='breadcrumb-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<?php
					if ( function_exists('yoast_breadcrumb') ) {
					  yoast_breadcrumb( '<div id="breadcrumbs">','</div>' );
					}
					?>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'text_-_full_width' ):?>
		<div class='text-fullwidth-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='entry-content'>
					<?php the_sub_field('content')?>
				</div>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'text_-_12_&_12' ):?>
		<div class='text-2-cols-section section section-<?php echo $section;?> <?php the_sub_field('background_color')?>'>
			<div class='container wowo fadeInUp px-4'>			
				<div class='text-content-wrapper'>	
					<div class='row g-5'>	
						<div class='col-md-6 content-left  '>	
							<div class='entry-content'><?php the_sub_field('content_left')?></div>
						</div>	
						<div class='col-md-6 content-right'>	
							<div class='entry-content'><?php the_sub_field('content_right')?></div>
						</div>	
					</div>	
				</div>	
			</div>				
		</div>	
		
	<?php elseif( get_row_layout() == 'text_-_23_&_13' ):?>
		<div class='text-2-3-cols-section section section-<?php echo $section;?> <?php the_sub_field('background_color')?>'>
			<div class='container wowo fadeInUp px-4'>			
				<div class='row g-5'>	
					<div class='col-md-7  content-left '>	
						<div class='entry-content'><?php the_sub_field('content_left')?></div>
					</div>	
					<div class='col-md-5  content-right <?php if(get_sub_field('separate_line')){echo 'line';}?>'>	
						<div class='entry-content'><?php the_sub_field('content_right')?></div>
					</div>	
				</div>	
			</div>				
		</div>	
		
	<?php elseif( get_row_layout() == 'text_-_side_image' ):?>
		<div class='text-side-image-section section section-<?php echo $section;?> '>
			<div class='container wowo fadeInUp'>			
				<div class='text-side-image-wrapper row <?php if(get_sub_field('image_position')=='right'){echo 'reverse';}?>'>	
					<div class='col-md-6 content-left  '>	
						<div class='side-image'><?php echo wp_get_attachment_image(get_sub_field('image'), 'full', 0, array('title'=> get_the_title(get_sub_field('image'))))?></div>
					</div>	
					<div class='col-md-6 content-right'>	
						<div class='entry-content text-side-image-content'><?php the_sub_field('content')?></div>
					</div>	
				</div>	
			</div>				
		</div>
		
	<?php elseif( get_row_layout() == 'html' ):?>
		<div class='html-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<?php echo get_sub_field('content')?>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'why_choose' ):?>
		<div class='location-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='intro-content-wrapper'><div class='intro-content entry-content'><?php the_sub_field('intro')?></div></div>
					<div class='location'>
						<?php while(have_rows('location')):the_row()?>
						<li><a href='<?php the_sub_field('link')?>'><?php the_sub_field('title')?></a></li>
						<?php endwhile?>
					</div>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'review_page' ):?>
		<div class='review-page-section section section-<?php echo $section;?> '>
			<div class='container wowo fadeInUp '>	
				
				<?php 
					$total_star=0;
					$count=1;
					$excellent = $very_good = $average = $poor = $terrible =0 ;
					$args = array( 'post_type' => 'reviews', 'posts_per_page' => -1);				
					$wp_query = new WP_Query($args);
					$total_review = $wp_query->found_posts;
					while ( $wp_query->have_posts() ) {
						$wp_query->the_post();
						$total_star+= get_field('rating');
						switch (get_field('rating')){
							case 5:
								$excellent++;
								break;
							case 4:
								$very_good++;
								break;
							case 3:
								$average++;
								break;
							case 2:
								$poor++;
								break;
							default:
								$terrible++;
						}
					}
					wp_reset_postdata();
					wp_reset_query();
				?>
				<h1>Latest reviews from our customers</h1>
				<div class='review-page-sumary'>
					<div class='star-rating-wrapper'>
						<div class='star-rating'><span style='width:<?php echo $total_star/(5*$total_review)*100?>%;'></span></div>
						<div class='score-rating'> <?php echo round($total_star/$total_review,1)?> rating of <?php echo $total_review?> reviews</div>
					</div>
				</div>
					
						
				
			</div>
			<div class='container wowo fadeInUp px-4'>	
				<div class='row g-5'>
				<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$args = array( 'post_type' => 'reviews', 'posts_per_page' => 12,'paged' => $paged);
					$wp_query = new WP_Query($args);					
				?>	
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<div class='col-md-6'>
						<div class='review'>
							<h3><?php the_title()?></h3>						
							<div class='star-rating'><span style='width:<?php echo get_field('rating')*20?>%;'></span></div>
							<div class='entry-content'><?php the_content()?></div>
							<name><?php the_field('name')?></name>
						</div>
					</div>
				<?php endwhile?>
				
				<?php if(my_pagination()):?>
					<div class="news-pagination" >
						<?php echo my_pagination(); ?>
					</div>
				<?php endif?>
				
				<?php wp_reset_postdata();?>
				<?php wp_reset_query();?>
				</div>
				<h3 class='review-form-title'>Leave us a review</h3>
				<div class='review-page-form'>
					<?php echo do_shortcode('[gravityform id="1" title="false" description="false" ajax="false"]');?>
				</div>
			</div>
			
		</div>
	
	<?php elseif( get_row_layout() == 'feature_product' ):?>
		<div class='feature-product-section section section-<?php echo $section;?>'>
			<div class='container'>	
				<h3 class='sub-heading'><?php the_sub_field('heading')?></h3>
				<div class='featured-product-list'>	
						
					<?php foreach (get_sub_field('feature_product') as $post):?>
						<?php setup_postdata($post); global $product;?>
						<div class=' featured-product '>
							
							<div class='featured-product-inner'>
																	
								<div class='info'>	
									<?php $rating_count = $product->get_rating_count();
									$review_count = $product->get_review_count();
									$average      = $product->get_average_rating();
									
									if ( $rating_count > 0 ) : ?>

										<!--<div class="woocommerce-product-rating">
											<?php //echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
											<?php if ( comments_open() ) : ?>
												<?php //phpcs:disable ?>
												<?php //printf( _n( '%s happy customer', '%s happy customers', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>
												<?php // phpcs:enable ?>
											<?php endif ?>
										</div>-->

									<?php endif; ?>
									<div class='in-stock'>IN STOCK</div>
									
									<div class='image'><?php woocommerce_template_loop_product_thumbnail()?></div>
									<?php woocommerce_template_loop_product_title()?>
									<?php woocommerce_template_single_price()?>
									<!--<div class='price-per-unit'><?php the_field('price_per_unit')?></div>-->
									<?php woocommerce_template_loop_add_to_cart()?>		
								</div>
								
								<div class='entry-content'><?php the_field('short_description')?></div>
							</div>
						</div>
					<?php endforeach ?>
					<?php  wp_reset_postdata(); ?>		
				</div>	
			</div>	
		</div>			
		
	<?php elseif( get_row_layout() == 'icons' ):?>
		<div class='icons-section section section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='entry-content intro-content'><?php the_sub_field('intro_content')?></div>
				<div class='icon-list wowo fadeInRight'>
					<?php while(has_sub_field('icons')):?>	
						<div class='ico'>
							<div class='i-wrapper'><?php echo wp_get_attachment_image(get_sub_field('icon'), 'full', 0, array('title'=> get_the_title(get_sub_field('icon'))))?></div>		
							<div class='icon-content'><?php the_sub_field('title')?></div>
						</div>
					<?php endwhile?>
				</div>				
			</div>	
		</div>	
		
	<?php elseif( get_row_layout() == 'icons_2' ):?>
		<div class='icons-2-section section section-<?php echo $section;?>'>
			<div class='container'>	
				<h3 class='sub-heading'><?php the_sub_field('heading')?></h3>
				<div class='icon-2-list wowo fadeInRight'>
					<?php while(has_sub_field('icon_2')):?>	
						<div class='ico2'>
							<div class='icon-wrapper'><?php echo wp_get_attachment_image(get_sub_field('icon'), 'full', 0, array('title'=> get_the_title(get_sub_field('icon'))))?></div>		
							<div class='icon-content'><?php the_sub_field('content')?></div>
						</div>
					<?php endwhile?>
				</div>	
				<div class='btn-wrapper'><a class='btn btn-red' href='<?php echo wc_get_page_permalink( 'shop' )?>'>Order Now - Fast delivery</a></div>
			</div>	
		</div>	
		
	<?php elseif( get_row_layout() == 'icons_3' ):?>
		<div class='icons-3-section section section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='icon-3-list wowo fadeInRight'>
					<?php while(has_sub_field('icon_3')):?>	
						<div class='ico3'>
							<div class='icon-wrapper'><?php echo wp_get_attachment_image(get_sub_field('icon'), 'full', 0, array('title'=> get_the_title(get_sub_field('icon'))))?></div>		
							<div class='icon-content'><?php the_sub_field('content')?></div>
						</div>
					<?php endwhile?>
				</div>	
			
			</div>	
		</div>	
		
	<?php elseif( get_row_layout() == 'product_delivery_intro' ):?>
		<div class='product-delivery-content-wrapper'>
			<div class='container'>
				<div class='row'>
					<div class='col-md-7'>
						<div class='entry-content'><?php the_sub_field('left_content')?></div>
					</div>
					<div class='col-md-5'>
						<div class='entry-content small-content'><?php the_sub_field('right_content')?></div>
					</div>
				</div>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'benefit' ):?>
		<div class='benefit-section section section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='benefit-intro entry-content'><?php the_sub_field('intro')?></div>	
				<div class='benefit-list row'>
					<?php while(has_sub_field('benefit')):?>	
						<div class='col-md-6'>
							<div class='benefit'>
								<?php echo wp_get_attachment_image(get_sub_field('icon'), 'full', 0, array('title'=> get_the_title(get_sub_field('icon'))))?>	
								<div class='entry-content'><?php the_sub_field('content')?></div>
							</div>
						</div>
					<?php endwhile?>
				</div>				
			</div>	
		</div>	
		
	<?php elseif( get_row_layout() == 'dosage' ):?>
		<div class='dosage-section section section-<?php echo $section;?>'>
			<div class='container'>		
				<div class=' row'>
					<div class=' col-md-3'>
						<div class='dosage-intro entry-content'><?php the_sub_field('intro')?></div>	
					</div>
					<div class=' col-md-9'>
						<div class='dosage-table row'>
							<?php while(has_sub_field('dosage')):?>	
								<div class='col-md-4 dosage-wrapper'>
									<div class='dosage <?php if(get_sub_field('recommend')) echo 'recommend';?>'>
										<div class='title'><?php the_sub_field('title')?></div>
										<div class='amount'><?php the_sub_field('amount')?></div>
										<div class='entry-content'><?php the_sub_field('content')?></div>
									</div>
								</div>
							<?php endwhile?>
						</div>				
					</div>				
				</div>								
			</div>	
		</div>

	<?php elseif( get_row_layout() == 'tips' ):?>
		<div class='tips-section section section-<?php echo $section;?>'>
			<div class='container'>		
				<div class=' row'>
					<div class=' col-md-8'>
						<div class='entry-content'>
							<h3><?php the_sub_field('content_left_title')?></h3>
							<div class='content-left'><?php the_sub_field('content_left')?></div>
						</div>
					</div>	
					<div class=' col-md-4'>
						<div class='entry-content'>
							<h3><?php the_sub_field('content_right_title')?></h3>
							<div class='content-right'><?php the_sub_field('content_right')?></div>
						</div>
					</div>						
				</div>					
			</div>					
		</div>					
				
	<?php elseif( get_row_layout() == 'how_to_order' ):?>
		<div class='how-to-order-section section section-<?php echo $section;?>'>
			<div class='container px-4'>	
				<div class='how-to-order-intro'><?php the_sub_field('intro')?></div>	
				<div class='how-to-order'>
					<?php $step=1;?>
					<?php while(has_sub_field('how_to_order')):?>	
						<div class='step'>
							<div class='i'><img src='<?php the_sub_field('icon')?>' /></div>
							<div class='content'><span>Step <?php echo $step++?></span><?php the_sub_field('content')?></div>
						</div>
					<?php endwhile?>
				</div>									
			</div>	
		</div>
	
	<?php elseif( get_row_layout() == 'featured_blog' ):?>
		<div class='latest-blog-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container '>
				<div class='entry-content intro-content'><?php the_sub_field('intro_content')?></div>
				
				<div class='row'>
					<?php 
						$args = array( 'post_type' => 'post', 'posts_per_page' =>3); 
						$wp_query = new WP_Query($args);
					?>
					<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<div class='col-lg-4 col-sm-6 post-block-wrapper'>
							<div class='post-block'>
								<?php if(has_post_thumbnail()):?>
									<a href='<?php the_permalink()?>' title='<?php the_title()?>'><div class='image'><?php the_post_thumbnail('full')?></div></a>
								<?php endif ?>
								<div class='content'>
									<a href='<?php the_permalink()?>' title='<?php the_title()?>'><h3><?php the_title()?></h3></a>
									<div class='entry-content'>
										<?php if(get_the_excerpt()){
											the_excerpt();
										}else{											
											$rows = get_field('modules');
											if($rows){
												foreach( $rows as $row ){
													if( $row['acf_fc_layout'] == 'text_-_full_width' ){
														echo force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( $row['content'] ), 20, '' ) ) );
														break;
													}
												}
											}	
										}?>
										
									</div>
									
									<?php $cats = get_the_category(); ?> 
									<div class='cat'>			
										<?php foreach ($cats as $cat):?>
											<a href='<?php echo get_term_link($cat)?>'><?php echo $cat->name?></a><span>, </span>
										<?php endforeach?>
									</div>	
									<time><?php echo get_the_date('F j, Y')?></time>
								</div>
							</div>
						</div>
					<?php endwhile?>
					<?php wp_reset_postdata();?>
					<?php wp_reset_query();?>
				
				</div>
			</div>
		</div>	
		
	<?php elseif( get_row_layout() == 'blog' ):?>
		<div class='all-news-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>				
				<div class='row'>
					<div class='news-category'>
						<?php $terms = get_terms( array(
							'taxonomy' => 'category',
							'hide_empty' => true,
						) );?>
						<header>Category</header>
						<ul>
						<?php foreach($terms as $term):?>
							<li><a href='<?php echo get_term_link($term)?>'><?php echo $term->name?></a></li>
						<?php endforeach?>
						</ul>
					</div>
					<?php 
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						$args = array( 'post_type' => 'post', 'posts_per_page' =>21,'paged' => $paged); 
						$wp_query = new WP_Query($args);
					?>
					<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<div class='col-lg-4 col-sm-6 post-block-wrapper'>
							<div class='post-block'>
								<?php if(has_post_thumbnail()):?>
									<a href='<?php the_permalink()?>' title='<?php the_title()?>'><div class='image'><?php the_post_thumbnail('full')?></div></a>
								<?php endif ?>
								<div class='content'>
									<a href='<?php the_permalink()?>' title='<?php the_title()?>'><h3><?php the_title()?></h3></a>
									<div class='entry-content'>
										<?php if(get_the_excerpt()){
											the_excerpt();
										}else{											
											$rows = get_field('modules');
											if($rows){
												foreach( $rows as $row ){
													if( $row['acf_fc_layout'] == 'text_-_full_width' ){
														echo  wp_trim_words( wp_strip_all_tags( $row['content'] ), 20, '' ) ;
														break;
													}
												}
											}	
										}?>
										
									</div>
									
									<?php $cats = get_the_category(); ?> 
									<div class='cat'>			
										<?php foreach ($cats as $cat):?>
											<a href='<?php echo get_term_link($cat)?>'><?php echo $cat->name?></a><span>, </span>
										<?php endforeach?>
									</div>	
									<time><?php echo get_the_date('F j, Y')?></time>
								</div>
							</div>
						</div>
					<?php endwhile?>
					
				
				</div>			
				<?php if(my_pagination()):?>
					<div class="news-pagination" >
						<?php echo my_pagination(); ?>
					</div>
				<?php endif?>
				<?php wp_reset_postdata();?>
				<?php wp_reset_query();?>
							
			</div>
		</div>
		
	<?php elseif( get_row_layout() == 'reviews' ):?>
		<div class='reviews-section section section-<?php echo $section;?>'>
			<div class='reviews-wrapper  wowo fadeInUp'>			
				<div class=' container '>			
					<?php if(get_sub_field('heading')):?><h3 class='sub-heading'><?php the_sub_field('heading')?></h3><?php endif?>				
					<div class='reviews-slider'>
						<?php 
							$args = array( 'post_type' => 'reviews', 'posts_per_page' =>-1); 				
							$wp_query = new WP_Query($args);
						?>
						<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							<div class='review-wrapper'>
								<div class='review'>
									<div class='row align-items-center'>
										<div class='col-7'>
										<name><?php the_field('name')?></name>
										</div>
										<div class='col-5'>
										<div class='star-rating'><span style='width:<?php echo get_field('rating')*20?>%;'></span></div>
										</div>
									</div>
									<header><?php the_title()?></header>
									<div class='entry-content'><?php the_content()?></div>
								</div>
							</div>
						<?php endwhile ?>
						<?php wp_reset_postdata();?>
						<?php wp_reset_query();?>
					</div>					
				</div>				
			</div>				
		</div>			
		
	<?php elseif( get_row_layout() == 'cta' ):?>
		<div class='cta-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='cta-content'><?php the_sub_field('content')?></div>			
			</div>				
		</div>	
		
	<?php elseif( get_row_layout() == 'cta_2' ):?>
		<div class='cta-2-section section wowo fadeInUp section-<?php echo $section;?>'>
			<div class='container'>	
				<div class='cta-2-content'><?php the_sub_field('content')?></div>			
			</div>				
		</div>	
				
	<?php elseif( get_row_layout() == 'contact' ):?>
		<div class='contact-section section section-<?php echo $section;?>'>
			<div class='container wowo fadeInUp'>			
				<div class='row'>	
					<div class='col-md-4 contact-left '>	
						<div class='entry-content contact-info'>
							<li class='email'><span>Email us</span><a href='mailto:<?php the_sub_field('email')?>'><?php the_sub_field('email')?></a></li>
							<li class='phone'><a><span>Phone/SMS:</span> <?php the_sub_field('phone')?></a></li>
							<li class='address'><span>Address</span><?php the_sub_field('address')?></li>
						</div>
						<div class='map'>
							<?php echo get_sub_field('map')?>
						</div>
					</div>	
					<div class='col-md-8 contact-right'>	
						<div class='form entry-content'><?php the_sub_field('form')?></div>
					</div>	
				</div>	
			</div>				
		</div>
		
	<?php elseif( get_row_layout() == 'faqs' ):?>
		<div class='product-faqs accordion-section section wowo fadeInUp '>
			<div class='container '>					
				<h3 class='sub-heading'><?php the_sub_field('faq_title')?></h3>			
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
	
	<?php $section++;?>
	
<?php endwhile?>
<?php endif?>