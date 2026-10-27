<?php
/**
 * Template Name: Shop
 */

get_header(); ?>

	<div class='woo-general-page container'>
		<h1 class='woo-page-title'><?php the_title()?></h1>
		<?php while ( have_posts() ) : the_post();?>
			<?php the_content()?>
		<?php endwhile?>
	</div>
	
	

<?php get_footer(); ?>
