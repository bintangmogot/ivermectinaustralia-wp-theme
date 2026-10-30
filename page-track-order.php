<?php
/**
 * Template Name: Track Order
 * Description: Order tracking page. Renders the track_order module.
 */
get_header();
?>
<main id="main-content" class="site-main bg-white">
<?php
if (!ivermectin_render_modules()) {
    get_template_part('modules/content', 'track_order');
}
?>
</main>
<?php get_footer(); ?>
