<?php
/** Module: cta. Sub fields: title, button_text, button_url. */
$heading     = trim((string) (get_sub_field('heading') ?: get_sub_field('title')));
$button_text = trim((string) get_sub_field('button_text'));
$button_url  = trim((string) get_sub_field('button_url'));

if ($heading === '') {
    return;
}
?>
<section class="py-8" data-testid="cta-section">
    <div class="container-site">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-border bg-primary-soft p-6">
            <h2 class="max-w-2xl font-heading text-xl font-extrabold text-primary md:text-2xl"><?= esc_html($heading) ?></h2>
            <?php if ($button_text !== '' && $button_url !== '') : ?><a href="<?= esc_url($button_url) ?>" class="btn-cta"><?= esc_html($button_text) ?></a><?php endif; ?>
        </div>
    </div>
</section>
