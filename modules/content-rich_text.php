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

$width = get_sub_field('width') ?: 'default';
ivermectin_render_article_section((string) $content, (bool) get_sub_field('faq_open'), (string) $width);
