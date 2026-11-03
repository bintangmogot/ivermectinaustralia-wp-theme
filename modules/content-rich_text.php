<?php
/**
 * Module: rich_text. Sub fields: content, width (narrow, default, full), image.
 * Long content with three or more H2 headings renders as a guide: a contents rail beside the chapters,
 * with lists, tips and questions restyled (see inc/rich-article.php); faq_open shows the answers open.
 * Shorter content renders as plain prose.
 * The image field is kept in admin and not shown.
 */

$content = get_sub_field('content');
if (!$content) {
    return;
}

$article = ivermectin_build_rich_article((string) $content, (bool) get_sub_field('faq_open'));
$width   = get_sub_field('width') ?: 'default';
$max_width_class = match ($width) {
    'narrow' => 'max-w-3xl',
    'full' => 'max-w-none',
    default => 'max-w-6xl',
};
$toc = $article ? array_values(array_filter($article['chapters'], static fn($chapter) => $chapter['id'] !== '')) : [];
?>
<section class="iva-section iva-guide bg-white">
    <?php if ($article) : ?>
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-[15rem_minmax(0,1fr)] lg:gap-16 xl:grid-cols-[17rem_minmax(0,1fr)]">
            <?php if (count($toc) > 1) : ?>
                <nav class="iva-toc" data-iva-toc>
                    <ol>
                        <?php foreach ($toc as $chapter) : ?>
                            <li><a href="#<?= esc_attr($chapter['id']) ?>"><?= esc_html($chapter['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            <?php endif; ?>

            <div class="iva-guide-body">
                <?php foreach ($article['chapters'] as $chapter) : ?>
                    <article class="iva-chapter"<?= $chapter['id'] !== '' ? ' id="' . esc_attr($chapter['id']) . '"' : '' ?>>
                        <?php if ($chapter['title'] !== '') : ?><h2 class="iva-chapter-title"><?= esc_html($chapter['title']) ?></h2><?php endif; ?>
                        <div class="iva-chapter-content"><?= $chapter['html'] ?></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="container-site <?= esc_attr($max_width_class) ?>">
            <div class="prose prose-slate max-w-none">
                <?= wp_kses_post($content) ?>
            </div>
        </div>
    <?php endif; ?>
</section>
