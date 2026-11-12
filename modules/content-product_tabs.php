<?php
/** Module: product_tabs. Sub field: tabs (tab_title, tab_content). */
$tabs = (array) (get_sub_field('tabs') ?: []);
if (!$tabs) { return; }
?>
<section class="iva-section-tight bg-white">
    <div class="container-site max-w-5xl space-y-5">
        <?php foreach ($tabs as $tab) : if (empty($tab['tab_content'])) { continue; } ?>
            <div class="iva-panel">
                <?php if (!empty($tab['tab_title'])) : ?><h2 class="iva-h3"><?= esc_html($tab['tab_title']) ?></h2><?php endif; ?>
                <div class="prose iva-prose mt-4 max-w-none"><?= wp_kses_post($tab['tab_content']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
