<?php
/** Shared Australia Delivery layout for every city. */
get_header();
echo '<main id="main-content">';
while (have_posts()) {
    the_post();
    get_template_part('template-parts/australia-delivery');
}
echo '</main>';
get_footer();
