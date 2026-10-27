/* global screenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

( function( $ ) {
	
	$( document ).ready( function() {
		$body          = $( document.body );
		$window        = $( window );
		
		wowo();
		//stickyMenu();
		
		$(window).scroll(function() {
			wowo();
			//stickyMenu();
		});
		
		$( '.accordion-title' ).on( 'click touchstart', function() {
			$(this).parent().toggleClass('active');
		});
		
		$('#open-mobile-menu').on('click', function(){
			$(this).toggleClass('active');
			$('.mobile-menu-wrapper').toggleClass('active');
		});
		
		/*$('#open-mobile-menu').sidr({ side: 'right'});
		
		$('#page').on('click', function(){
			$.sidr('close', 'sidr');
		});

		$('.close-menu').on('click', function(){
			$.sidr('close', 'sidr');
		});

		$('#sidr').on( "click  ", function(event) {
			event.stopPropagation();
		});*/

		$( '.menu-expand ' ).on( 'click', function(e) {
			$(this).parent().toggleClass('active');
		});
		
		$('.reviews-slider').slick({
			dots: false,
			arrows: true,
			infinite: true,
			slidesToShow: 2,
			
			prevArrow:'<span class="reviews-slider-prev reviews-slider-arrow"><i class="icon ion-ios-arrow-back"></i></span>',
			nextArrow:'<span class="reviews-slider-next reviews-slider-arrow"><i class="icon ion-ios-arrow-forward"></i></span>',
			autoplay:true,
			draggable: false,
			responsive: [
				{
				  breakpoint: 600,
				  settings: {
					slidesToShow: 1
				  }
				},
			]
		});
		
		$('.featured-product-list ').slick({
			dots: false,
			arrows: true,
			infinite: true,
			slidesToShow:4,
			slide : '.featured-product',
			
			prevArrow:'<span class="featured-product-prev featured-product-arrow"><i class="icon ion-ios-arrow-back"></i></span>',
			nextArrow:'<span class="featured-product-next featured-product-arrow"><i class="icon ion-ios-arrow-forward"></i></span>',
			autoplay:true,
			draggable: false,
			responsive: [
				{
				  breakpoint: 600,
				  settings: "unslick",
				},
			]
		});
		
		/*$( "<div class='bacs-note'>* Use the below BSB & account details to make the transfer. Simply mention your order number in the comment section. <strong>DO NOT</strong> reference anything related to <strong>medicine</strong> or <strong>website</strong> name. Just mention your order number.</div>" ).insertBefore('.woocommerce-order-received .woocommerce-bacs-bank-details');
		
		let account_name = $('.wc-bacs-bank-details-account-name').html();
		if (account_name){
			$('.wc-bacs-bank-details-account-name').html('<label>Account Holder:</label> '+account_name.slice(0, -1));
		}*/
		
		$( '.btn-copy ' ).on( 'click', function(e) {
			let id = $(this).data('id');
			let copyText = document.getElementById("payment-value-"+id);
			

			copyText.select();
			copyText.setSelectionRange(0, 99999);
			navigator.clipboard.writeText(copyText.value);
			$('.copy-tooltip-text').removeClass('active');
			$(this).find('.copy-tooltip-text').addClass('active');
		});
		
		$( '.readmore' ).on( 'click', function(e) {
			$(this).parent().toggleClass('active');
			if ($(this).text()=='Read more >>'){
				$(this).text('Read less >>');
			}else{
				$(this).text('Read more >>');
			}
		});
		
		$('.t1').slick({
			dots: false,
			arrows: false,
			infinite: true,
			slidesToShow: 1,
			autoplay:true,
			autoplaySpeed:100,
			speed:9000,
		});
		
		$( '.wc-bacs-bank-details.order_details .sort_code').insertBefore('.wc-bacs-bank-details.order_details .account_number');
		$(document.body).on('updated_checkout', function() {
			$('#shipping_method #shipping_method_0_free_shipping5').parent().append('<span class="woocommerce-help-tip" data-tip="Orders over $299 get FREE shipping"></span>');
		})
	} );
	
	function wowo() {
		var wTop = $(window).scrollTop(),
			wHeight = $(window).height(),
			wBottom = wTop + wHeight;
		$(".wowo:not(.animated)").each(function() {
			var me = $(this),
				meTop = me.offset().top,
				meHeight = me.innerHeight(),
				meBottom = meTop + meHeight,
				limitTop = wTop - meHeight,
				limitBottom = wBottom + meHeight;
			if (meTop > limitTop && meBottom < limitBottom) {
				me.addClass("animated");
				setTimeout(function() {
					me.removeClass("animated wowo");
				}, 1500);
			}
		});
	};
	
	function stickyMenu(){
		var sh = $(window).scrollTop();
		if (sh > 100 && !$("#masthead").hasClass("smaller")) {
			$("#masthead").addClass("smaller");
		} else if (sh <= 100 && $("#masthead").hasClass("smaller")) {
			$("#masthead").removeClass("smaller");
		}
	}

} )( jQuery );
