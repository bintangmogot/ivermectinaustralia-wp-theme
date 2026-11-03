<?php
/**
 * Section heading used by the homepage modules: optional pill tag, large heading and lead.
 * Every value comes from the module's ACF fields. Empty values render nothing.
 * $tone 'dark' is for headings on the navy band; $align 'left' sits the heading beside other content.
 */

defined('ABSPATH') || exit;

function ivermectin_section_head(string $tag, string $heading, string $description = '', string $tone = 'light', string $align = 'center', string $class = ''): void {
    $tag         = trim($tag);
    $heading     = trim($heading);
    $description = trim($description);
    if ($tag === '' && $heading === '' && $description === '') {
        return;
    }
    $on_dark = $tone === 'dark';
    $classes = trim('iva-head ' . ($align === 'left' ? '' : 'iva-head-center ') . $class);
    ?>
    <header class="<?= esc_attr($classes) ?>" data-iva-head>
        <?php if ($tag !== '') : ?><p class="iva-pill-tag <?= $on_dark ? 'iva-pill-tag-dark' : '' ?>"><?= esc_html($tag) ?></p><?php endif; ?>
        <?php if ($heading !== '') : ?><h2 class="iva-h2 <?= $on_dark ? 'text-white' : 'text-primary' ?>"><?= esc_html($heading) ?></h2><?php endif; ?>
        <?php if ($description !== '') : ?><p class="iva-lead <?= $on_dark ? 'text-white/70' : 'text-muted-foreground' ?>"><?= esc_html($description) ?></p><?php endif; ?>
    </header>
    <?php
}
