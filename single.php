<?php
/**
 * The template for displaying all single posts and attachments
 */

get_header(); ?>

	
	<div id="single-post" >
		<div class="container section">
			<div class='blog-header'>
				<h1 class='heading'><?php the_title()?></h1>
				<div class='blog-header-img'>
				<?php if(has_post_thumbnail()):?>
					<?php the_post_thumbnail('full')?>
				<?php endif?>
				</div>
			</div>
				
			<?php while ( have_posts() ) : the_post(); ?>
				<?php $cats = get_the_category(); ?> 
				<div class='cat'>	
					<header>Category: </header>
					<?php foreach ($cats as $cat):?>
						<a href='<?php echo get_term_link($cat)?>'><?php echo $cat->name?></a><span>, </span>
					<?php endforeach?>
				</div>	
				<?php  $post_tags = get_the_tags(); 
					if ( $post_tags ):?>
						<div class='blog-tags'>
						<header>Tags</header>
						<?php foreach( $post_tags as $tag ): ?>
							<li><a href='<?php echo get_tag_link( $tag->term_id )?>'><?php echo $tag->name ?></a></li>
						<?php endforeach ?>
						</div>
					<?php endif?>
				
			<?php endwhile; ?>
		</div>
		<?php get_template_part( 'modules' ); ?>
		<!--<div class=" entry-content">
			<h3>About the author</h3>
			<p><img class="wp-image-42377 alignleft" src="https://modafinil-australia.com.au/wp-content/themes/theme/images/steven.png" alt="" width="100" height="100" />Steven is a health care pro who knows all there is to know about Modafinil and how it fits into daily life. They make tricky health info easy to understand and actually useful. Always up-to-date and practical, Steven loves helping people get the most out of their wellness choices. Simply put, if it’s Modafinil or brain-boosting stuff, Jardine’s your go-to guide.</p>
		</div>-->
		
		<div class='container'>
			<nav class="navigation post-navigation" role="navigation">
				<div class="nav-links">
					<?php while (have_posts()) : the_post(); ?>
					<div class="nav-previous">
						<span>Previous: </span><?php previous_post_link( '%link', '%title'); ?> 
					</div>
					<div class="nav-next">
						<span>Next: </span><?php next_post_link( '%link', '%title'); ?> 
					</div>
					<?php endwhile ?>
				</div>
			</nav>
		</div>
			
	</div>		
	

<?php get_footer(); ?>
