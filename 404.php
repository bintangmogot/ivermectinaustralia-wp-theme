<?php
/**
 * 404. The heading and copy come from the Theme Settings "404 Page" fields.
 */
get_header();
$heading = trim((string) get_field('not_found_heading', 'option'));
$text    = trim((string) get_field('not_found_text', 'option'));
$label   = trim((string) get_field('not_found_button_label', 'option'));
?>
<main id="main-content" class="py-16">
    <div class="container-site max-w-2xl text-center">
        <p class="font-heading text-6xl font-extrabold text-primary">404</p>
        <?php if ($heading !== '') : ?><h1 class="mt-3 font-heading text-3xl font-extrabold text-foreground"><?= esc_html($heading) ?></h1><?php endif; ?>
        <?php if ($text !== '') : ?><p class="mt-2 text-muted-foreground"><?= esc_html($text) ?></p><?php endif; ?>
        <?php if ($label !== '') : ?><a href="<?= esc_url(home_url('/')) ?>" class="btn-cta mt-6"><?= esc_html($label) ?></a><?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
