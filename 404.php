<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

<div class="section-padding bg-background min-h-[70vh] flex flex-col justify-center items-center text-center">
    <div class="container-site max-w-2xl">
        <h1 class="font-heading text-6xl md:text-8xl font-black text-primary mb-6">404</h1>
        <h2 class="font-heading text-2xl md:text-3xl font-bold text-foreground mb-4">
            <?= "Oops! Page not found" ?>
        </h2>
        <p class="text-muted-foreground mb-8">
            <?= "The page you are looking for might have been removed, had its name changed, or is temporarily unavailable." ?>
        </p>
        <a href="<?= home_url('/') ?>" class="inline-flex items-center gap-2 rounded-full bg-primary px-8 py-4 text-sm font-bold uppercase tracking-wider text-primary-foreground shadow-pill transition-colors hover:bg-primary-dark">
            <?= "Back to Homepage" ?>
        </a>
    </div>
</div>

<?php
get_footer();
