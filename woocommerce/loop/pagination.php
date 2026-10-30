<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}
?>
<nav class="woocommerce-pagination mt-12 flex justify-center" aria-label="<?php esc_attr_e( 'Product Pagination', 'woocommerce' ); ?>">
	<?php
	$pages = paginate_links(
		apply_filters(
			'woocommerce_pagination_args',
			array( // WPCS: XSS ok.
				'base'      => $base,
				'format'    => $format,
				'add_args'  => false,
				'current'   => max( 1, $current ),
				'total'     => $total,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'type'      => 'array',
				'end_size'  => 3,
				'mid_size'  => 3,
			)
		)
	);

	if ( is_array( $pages ) ) {
		echo '<ul class="flex flex-wrap items-center gap-2">';
		foreach ( $pages as $page ) {
            // Check if current
            $is_current = strpos($page, 'current') !== false;
            
            // Clean up the WordPress anchor classes to use Tailwind
            $page = str_replace("page-numbers", "page-numbers flex items-center justify-center w-10 h-10 rounded-md font-bold text-sm transition-colors", $page);
            
            if ($is_current) {
                // Style for current page
                $page = str_replace("current", "current bg-primary text-primary-foreground pointer-events-none", $page);
            } else {
                // Style for inactive links
                $page = preg_replace('/class="(.*?)"/', 'class="$1 bg-card border border-border text-card-foreground hover:bg-primary-soft hover:text-primary-dark hover:border-primary-soft"', $page);
            }
            
            // For the dots
            if (strpos($page, 'dots') !== false) {
                $page = str_replace("hover:bg-primary-soft hover:text-primary-dark hover:border-primary-soft border border-border bg-card", "bg-transparent text-muted-foreground font-normal", $page);
            }

			echo '<li>' . wp_kses_post( $page ) . '</li>';
		}
		echo '</ul>';
	}
	?>
</nav>
