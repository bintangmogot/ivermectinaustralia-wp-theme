<?php
/** Module: product_tabs. Sub fields: heading, tabs (title, content). */
$heading = trim((string) get_sub_field('heading'));
$tabs    = array_values(array_filter(get_sub_field('tabs') ?: [], static function ($tab) {
    return !empty($tab['title']) && trim(strip_tags((string) ($tab['content'] ?? ''))) !== '';
}));
if (!$tabs) {
    return;
}
$uid = 'ivatabs-' . wp_unique_id();
?>
<section class="iva-section-tight bg-white">
    <div class="container-site max-w-5xl" data-iva-tabs>
        <?php if ($heading !== '') : ?><h2 class="iva-h3 mb-5"><?= esc_html($heading) ?></h2><?php endif; ?>
        <?php if (count($tabs) > 1) : ?>
            <div class="iva-tablist" role="tablist">
                <?php foreach ($tabs as $i => $tab) : ?>
                    <button type="button" role="tab" id="<?= esc_attr($uid . '-t' . $i) ?>" class="iva-tab<?= $i === 0 ? ' is-active' : '' ?>"
                        aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="<?= esc_attr($uid . '-p' . $i) ?>"><?= esc_html($tab['title']) ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php foreach ($tabs as $i => $tab) : ?>
            <div class="iva-panel iva-tabpanel prose iva-prose max-w-none" role="tabpanel" id="<?= esc_attr($uid . '-p' . $i) ?>"
                aria-labelledby="<?= esc_attr($uid . '-t' . $i) ?>"<?= $i === 0 ? '' : ' hidden' ?>>
                <?php if (count($tabs) === 1) : ?><h2 class="iva-h3"><?= esc_html($tab['title']) ?></h2><?php endif; ?>
                <?= wp_kses_post(apply_filters('the_content', (string) $tab['content'])) ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
