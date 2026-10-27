<?php

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/**
 * Change Shipping label in Checkout when there is only 1 package
 */
add_filter('woocommerce_shipping_package_name', function ($name, $package_id, $package) {
    $packages = WC()->shipping()->get_packages();
    if (count($packages) === 1) {
        return 'Shipping';
    }
    return 'Shipping ' . ($package_id + 1);
}, 999, 3);


/**
 * Remove Category in the breadcrumb on the product page
 */
add_filter( 'woocommerce_get_breadcrumb', 'custom_breadcrumb', 20, 2 );
function custom_breadcrumb( $crumbs, $breadcrumb ) {

    // only on the single product page
    if ( ! is_product() ) {
        return $crumbs;
    }
    
    // gets the first element of the array "$crumbs"
    $new_crumbs[] = reset( $crumbs );
    // gets the last element of the array "$crumbs"
    $new_crumbs[] = end( $crumbs );

    return $new_crumbs;

}

/**
 * Change the breadcrumb separator
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
function wcc_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' &raquo; ';
	return $defaults;
}

/**
 * Change select option button text
 */
add_filter( 'woocommerce_product_add_to_cart_text', 'change_select_options_button_text', 9999, 2 );
function change_select_options_button_text( $label, $product ) {
   if ( $product->is_type( 'variable' ) ) {
      return 'View Details';
   }
   return $label;
}



/*********************************************
 * Wrap sub-menu and link in custom wrapper
 *********************************************/
class Child_Wrap extends Walker_Nav_Menu{	
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<span class='menu-expand'><i class='icon ion-chevron-down'></i></span><ul class='sub-menu'>\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

/*
* Callback function to filter the MCE settings
*/
function my_mce_before_init_insert_formats( $init_array ) {  

	$style_formats = array(  
		array(  
			'title' => 'Button',  
			'inline' => 'span',  
			'classes' => 'btn',
			'wrapper' => true,
		),
		array(  
			'title' => 'Button Green',  
			'inline' => 'span',  
			'classes' => 'btn btn-green',
			'wrapper' => true,
		),	
		array(  
			'title' => 'Button Red',  
			'inline' => 'span',  
			'classes' => 'btn btn-red',
			'wrapper' => true,
		),
		array(  
			'title' => 'Button Outline',  
			'inline' => 'span',  
			'classes' => 'btn btn-outline',
			'wrapper' => true,
		)
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );  
	
	return $init_array;  
} 

/*===================================== 
 * Attach callback to 'tiny_mce_before_init' 
 ======================================*/
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' ); 

function wpb_mce_buttons_2($buttons) {
	array_unshift($buttons, 'styleselect');
	return $buttons;
}
add_filter('mce_buttons_2', 'wpb_mce_buttons_2');

function my_theme_add_editor_styles() {
    add_editor_style( 'css/custom-editor-style.css' );
}
add_action( 'init', 'my_theme_add_editor_styles' );

/*===================================== 
 * Custom Excerpt 
 ======================================*/
function new_excerpt_more( $more ) {
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');
function custom_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/*===================================== 
 * Pagination 
 ======================================*/
if ( ! function_exists( 'my_pagination' ) ) :
	function my_pagination() {
		global $wp_query;

		$big = 999999999; // need an unlikely integer
		
		return paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'prev_text' => '<i class="icon ion-chevron-left"></i>',
			'next_text' => '<i class="icon ion-chevron-right"></i>',
		) );
	}
endif;

/*********************************************
 * Custom login logo
 *********************************************/
function custom_loginlogo() {
	echo '<style type="text/css">
	h1 a {background-image: url('.get_bloginfo('template_directory').'/images/logo.png) !important;width:100% !important; background-size:    auto 100% !important;height:100px !important }body{}
	</style>';
}
add_action('login_head', 'custom_loginlogo');

/*********************************************
 * Reviews
 *********************************************/
register_post_type('reviews', array(
	'label' => __('Reviews'),
	'singular_label' => __('Reviews'),
	'public' => false,
	'show_ui' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'has_archive' => false,
	'rewrite' => true,
	'query_var' => true,
	'supports' => array('title', 'editor')
));
$labels = array('name' => _x('Reviews Categories', 'taxonomy general name'));
register_taxonomy('reviews_cat', array('reviews'), array(
	'hierarchical' => true,
	'labels' => $labels,
	'show_ui' => true,
	'query_var' => true,
	'show_admin_column' => true,
	'public' => false,
));
register_taxonomy_for_object_type('reviews_cat', 'reviews');

/*********************************************
 * Change buttons in WYSWIG post editor, edit color palette 
 *********************************************/
function change_mce_options( $init ) {
	$default_colours = '"FFFFFF", "White"';
	$custom_colours = '
    "000000", "Color 1",
	"012C70", "Color 2",
	"2AB5A3", "Color 3",
	"29B547", "Color 4",
	"FF831A", "Color 5",
	
    ';
	$init['textcolor_map'] = '['.$default_colours.','.$custom_colours.']';
	$init['textcolor_rows'] = 1; // expand colour grid to 6 rows
	return $init;
}

add_filter('tiny_mce_before_init', 'change_mce_options');


/*********************************************
 * Widgets init
 *********************************************/
function theme_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area' ),
		'id'            => 'header-widget',
		'description'   => __( '' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'theme_widgets_init' );


/*********************************************
 * Theme set up
 *********************************************/
function theme_setup() {
	
	/* add options page */
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page();	
	}

	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	//set_post_thumbnail_size( 825, 510, true );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu' ),
		'mobile' => __( 'Mobile Menu' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
}
add_action( 'after_setup_theme', 'theme_setup' );


/*********************************************
 * Enqueue scripts and styles.
 *********************************************/
function scripts() {

	
	// Load our main stylesheet.
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css');
	wp_enqueue_style( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
	wp_enqueue_style( 'ionicons', 'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
	wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css',array(), filemtime(get_template_directory() . '/style.css'));
	 
	//wp_enqueue_script( 'sidr-script', get_template_directory_uri() . '/js/jquery.sidr.js', array( 'jquery' ), '', true );
	
	wp_enqueue_script( 'slick-script', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), '', true );
	
	wp_enqueue_script( 'functions-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150338', true );

	
}
add_action( 'wp_enqueue_scripts', 'scripts' );


/*********************************************
 * Admin footer modification
 *********************************************/
function remove_footer_admin ()
{
    echo '<span id="footer-thankyou">Developed by <a href="https://hoppingmad.com.au/" target="_blank">Hopping Mad Design</a></span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

