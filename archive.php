<?php
/**
 * The template for displaying archive pages
 */

get_header(); ?>

	<div class='all-news-section section wowo fadeInUp'>
		<div class='container '>
			<div class='entry-content intro-content'>
				<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
			</div>
			<?php if ( have_posts() ) : ?>
				<div class='row'>
				<?php while ( have_posts() ) : the_post(); ?>
					<div class='col-lg-4 col-sm-6 post-block-wrapper'>
						<div class='post-block'>
							<?php if(has_post_thumbnail()):?>
								<a href='<?php the_permalink()?>' title='<?php the_title()?>'><div class='image'><?php the_post_thumbnail('full')?></div></a>
							<?php endif ?>
							<div class='content'>
								<a href='<?php the_permalink()?>' title='<?php the_title()?>'><h3><?php the_title()?></h3></a>
								<div class='entry-content'><?php the_excerpt()?></div>
								<time><?php echo get_the_date('F j, Y')?></time>
							</div>
						</div>
					</div>
				<?php endwhile?>
				</div>
			<?php else:?>
				<?php get_template_part( 'content', 'none' );?>
			<?php endif?>
		</div>
	</div>

<?php get_footer(); ?>
