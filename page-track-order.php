<?php
/**
 * Template Name: Track Order
 * Description: Dedicated Australia Post Order Tracking page for Waklert Australia.
 * Renders the 'track_order' module from ACF Flexible Content.
 */

get_header();

echo '<main id="main-content" class="site-main bg-white">';

if (have_rows('modules')) {
    while (have_rows('modules')) {
        the_row();
        $layout = get_row_layout();
        get_template_part('modules/content', $layout);
    }
} else {
    get_template_part('modules/content', 'track_order');
}

echo '</main>';

get_footer();
