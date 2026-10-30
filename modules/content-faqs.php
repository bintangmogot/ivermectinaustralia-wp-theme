<?php
/**
 * Module: faqs. Sub fields: show_eyebrow, eyebrow, heading, faq_items (question, answer),
 * show_full_faq_link, link_text, link_url.
 */
$eyebrow  = get_sub_field('show_eyebrow') ? trim((string) get_sub_field('eyebrow')) : '';
$heading  = trim((string) get_sub_field('heading'));
$items    = array_values(array_filter(get_sub_field('faq_items') ?: [], static function ($item) {
    return !empty($item['question']);
}));
$link_text = get_sub_field('show_full_faq_link') ? trim((string) get_sub_field('link_text')) : '';
$link_url  = trim((string) get_sub_field('link_url'));

if (!$items) {
    return;
}
?>
<section class="py-8 md:py-10">
    <div class="container-site max-w-3xl">
        <?php if ($eyebrow !== '' || $heading !== '') : ?>
            <div class="mb-5">
                <?php if ($eyebrow !== '') : ?><p class="iva-eyebrow text-link"><?= esc_html($eyebrow) ?></p><?php endif; ?>
                <?php if ($heading !== '') : ?><h2 class="mt-2 font-heading text-2xl font-extrabold text-primary md:text-3xl"><?= esc_html($heading) ?></h2><?php endif; ?>
            </div>
        <?php endif; ?>
        <?php foreach ($items as $index => $item) : ?>
            <details class="iva-faq" <?= $index === 0 ? 'open' : '' ?>>
                <summary><?= esc_html($item['question']) ?></summary>
                <div class="prose max-w-none"><?= wp_kses_post($item['answer'] ?? '') ?></div>
            </details>
        <?php endforeach; ?>
        <?php if ($link_text !== '' && $link_url !== '') : ?>
            <p class="mt-4"><a href="<?= esc_url($link_url) ?>" class="font-bold text-link hover:underline"><?= esc_html($link_text) ?></a></p>
        <?php endif; ?>
    </div>
</section>
