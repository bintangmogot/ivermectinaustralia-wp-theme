<?php
/** Module: product_faqs. Sub fields: heading, faq_items (question, answer). */
$heading = trim((string) get_sub_field('heading'));
$items   = array_values(array_filter(get_sub_field('faq_items') ?: [], static function ($item) {
    return !empty($item['question']);
}));
if (!$items) {
    return;
}
?>
<section class="py-8 md:py-10">
    <div class="container-site max-w-3xl">
        <?php if ($heading !== '') : ?><h2 class="mb-5 font-heading text-2xl font-extrabold text-primary md:text-3xl"><?= esc_html($heading) ?></h2><?php endif; ?>
        <?php foreach ($items as $item) : ?>
            <details class="iva-faq">
                <summary><?= esc_html($item['question']) ?></summary>
                <div class="prose max-w-none"><?= wp_kses_post($item['answer'] ?? '') ?></div>
            </details>
        <?php endforeach; ?>
    </div>
</section>
