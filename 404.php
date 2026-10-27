<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>

	<div class="container section">
		

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentyfifteen' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php _e( 'It looks like nothing was found at this location. ', 'twentyfifteen' ); ?></p>

				
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

		
	</div><!-- .content-area -->

<?php get_footer(); ?>
