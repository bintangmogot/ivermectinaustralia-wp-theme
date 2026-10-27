<?php
/**
 * The template for displaying the header
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg ">
<head>
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-WZLKFPNRZQ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-WZLKFPNRZQ');
</script>
	
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="format-detection" content="telephone=no">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<div class='t1-wrapper'>
		<div class='container'>
			<div class='t1'>
			
				<li>Free Shipping on Orders Over $299 - Australia Wide</li>
			</div>
		</div>
	</div>
	<div class='top-header'>
		<div class='container'>
			<li class="fast-delivery">Free Shipping on Orders Over $299 - Australia Wide</li>
			<div class='mobile mobile-cart-phone-wrapper'>
				
				<?php if( is_active_sidebar( 'header-widget' ) ) : ?>
					<div class="header-widget">
						<?php dynamic_sidebar( 'header-widget' ); ?>
					</div>
				<?php endif; ?>		
				<a class='header-phone' href='tel:0488 841 833'>0488 841 833</a>
			</div>
			
		</div>
	</div>
	<header id="masthead" class="site-header" role="banner">
		<div class='container'>			
			<a class="site-branding " href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title='<?php bloginfo('name') ?>'>
				<img src='<?php bloginfo('template_directory'); ?>/images/logo.png?v2' alt='<?php bloginfo('name') ?>' title='<?php bloginfo('name') ?>'/>
			</a>
			
			<div class='header-right '>
				
				<div id='desktop-menu'>
					<?php wp_nav_menu( array( 'theme_location' => 'primary') );?>
				</div>
				<?php if( is_active_sidebar( 'header-widget' ) ) : ?>
					<div class="header-widget">
						<?php dynamic_sidebar( 'header-widget' ); ?>
					</div>
				<?php endif; ?>
				<a class='header-phone' href='tel:0488 841 833'>0488 841 833</a>
				
				<div id='open-mobile-menu' class='mobile'><i class="icon ion-navicon"></i></div>
			</div>
		</div>
		<div class='mobile-menu-wrapper mobile'>
			<div class='container'>			
				<div class='mobile-menu'>
					<?php wp_nav_menu( array( 'theme_location' => 'mobile','walker' => new Child_Wrap()) );?> 
				</div>		
			</div>	
		</div>	
	</header><!-- .site-header -->		

	<div id="content" class="site-content">
