<?php
/**
 * 404. The heading, copy and button label come from the Theme Settings "404 Page" fields.
 */
get_header();
$heading = trim((string) get_field('not_found_heading', 'option'));
$text    = trim((string) get_field('not_found_text', 'option'));
$label   = trim((string) get_field('not_found_button_label', 'option'));
?>
<main id="main-content">
    <section class="iva-pagehero iva-notfound">
        <div class="container-site grid items-center gap-10 py-6 md:py-14 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <p class="iva-pill-tag" data-iva-hero>404</p>
                <?php if ($heading !== '') : ?><h1 class="iva-display mt-6 text-primary" data-iva-hero><?= esc_html($heading) ?></h1><?php endif; ?>
                <?php if ($text !== '') : ?><p class="iva-lead mt-4 max-w-xl text-muted-foreground" data-iva-hero><?= esc_html($text) ?></p><?php endif; ?>
                <?php if ($label !== '') : ?><a href="<?= esc_url(home_url('/')) ?>" class="btn-cta iva-btn-lift mt-8 rounded-full px-7 py-3.5 text-base" data-iva-hero><?= esc_html($label) ?></a><?php endif; ?>
            </div>
            <div class="mx-auto w-48 lg:w-64" data-iva-hero>
                <?php get_template_part('template-parts/mascot', null, ['pose' => 'deliver', 'class' => 'h-auto w-full']); ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
