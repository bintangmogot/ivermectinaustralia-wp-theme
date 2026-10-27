<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 */
?>

	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class='container'>
			<div class='row'>
				<div class='col-md-5'>	
					<div class='footer-text entry-content'><?php the_field('footer_text','options')?></div>
					<div class='footer-contact'>
						<h3>Customer Support & Order Resolutions</h3>				
						<li><a class='address'>Corporate Registry: 50 Holt St, Surry Hills, 2010, NSW</a></li>
						<li><a class='phone' href='tel:0488 841 833'>Direct Helpline: 0488 841 833</a></li>		
						<li><a class='email' href='mailto:orders@ivermectin-australia.com.au'>Email Notifications: orders@ivermectin-australia.com.au</a></li>
					</div>
					<div class='footer-text'>
						<h4>Payments Accepted</h4>
						<img  src='<?php bloginfo('template_directory'); ?>/images/payment.png'/>
						<h4>Shipping Partner</h4>
						<img  src='<?php bloginfo('template_directory'); ?>/images/shipping.png'/>
					</div>
					<div id='footer-social' class='social'>
						<a href='https://www.linkedin.com/company/ivermectin-australia/' target='_blank'>
							<img src='<?php bloginfo('template_directory'); ?>/images/icon-linkedin.svg'/>
						</a>
						<a href='https://www.facebook.com/profile.php?id=61594064199783' target='_blank'>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free v7.3.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5l0-170.3-52.8 0 0-78.2 52.8 0 0-33.7c0-87.1 39.4-127.5 125-127.5 16.2 0 44.2 3.2 55.7 6.4l0 70.8c-6-.6-16.5-1-29.6-1-42 0-58.2 15.9-58.2 57.2l0 27.8 83.6 0-14.4 78.2-69.3 0 0 175.9C413.8 494.8 512 386.9 512 256z"/></svg>
						</a>							
					</div>
				</div>
				<div class='col-md-7'>	
					<div class='row'>
						<div class='col-md-4'>
							<div class='footer-menu'><?php the_field('footer_menu','options')?></div>
						</div>
						<div class='col-md-4'>
							<div class='footer-menu'><?php the_field('footer_menu_2','options')?></div>
						</div>
						<div class='col-md-4'>
							<div class='footer-menu'><?php the_field('footer_menu_3','options')?></div>
						</div>				
					</div>				
				</div>				
			</div>
		</div>
		<div class='container site-info'>
			<div class='row'>
				<div class='col-md-8'>
					<div id="copyright">
						&copy <?php echo esc_attr( date( 'Y' ) )?> Ivermectin Australia. All rights reserved.
					</div>
				</div>  
				<div class='col-md-4'>
					
				</div>
			</div>
		</div>
	</footer><!-- .site-footer -->
	
	

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
