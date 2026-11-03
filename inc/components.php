<?php
/**
 * Shared page building blocks used by templates and the legacy content adapters:
 * option labels, the page hero, leading heading extraction, pagination and the blog post card.
 * Every string comes from Theme Settings ("Page Labels" tab) or from the page's own content.
 */

defined('ABSPATH') || exit;

/** Text option from Theme Settings. */
function ivermectin_label(string $name): string {
    return function_exists('get_field') ? trim((string) get_field($name, 'option')) : '';
}

/** Replace {placeholders} in a label, e.g. ivermectin_fill($label, ['count' => 15]). */
function ivermectin_fill(string $label, array $values): string {
    $search = $replace = [];
    foreach ($values as $key => $value) {
        $search[]  = '{' . $key . '}';
        $replace[] = (string) $value;
    }
    return str_replace($search, $replace, $label);
}

/**
 * Light page header: breadcrumb, H1 and an optional lead.
 *
 * @param array $args {
 *   @type string $title   H1 text (defaults to the page title)
 *   @type string $lead    Short text under the H1 (HTML allowed)
 *   @type string $meta    Extra HTML under the lead, e.g. category and date for articles
 *   @type array  $crumbs  [[label, url], ...] before the current page. Default: Home.
 *   @type bool   $mascot  Show the peeking mascot. Default: the "Show the mascot in page headers" setting.
 * }
 */
function ivermectin_page_hero(array $args = []): void {
    $title = trim((string) ($args['title'] ?? get_the_title()));
    $lead  = trim((string) ($args['lead'] ?? ''));
    $meta  = (string) ($args['meta'] ?? '');
    if ($title === '') {
        return;
    }
    $home_label = ivermectin_label('breadcrumb_home_label');
    $crumbs     = $args['crumbs'] ?? ($home_label !== '' ? [[$home_label, home_url('/')]] : []);
    $mascot     = $args['mascot'] ?? (get_field('page_hero_mascot', 'option') !== false && get_field('page_hero_mascot', 'option') !== '0');
    ?>
    <header class="iva-pagehero relative isolate overflow-hidden">
        <div class="container-site relative">
            <?php if ($crumbs || $title !== '') : ?>
                <nav aria-label="<?= esc_attr($home_label !== '' ? $home_label : $title) ?>" class="iva-crumbs" data-iva-hero>
                    <?php foreach ($crumbs as [$crumb_label, $crumb_url]) : ?>
                        <a href="<?= esc_url($crumb_url) ?>"><?= esc_html($crumb_label) ?></a><span class="iva-crumbs-sep" aria-hidden="true"></span>
                    <?php endforeach; ?>
                    <span aria-current="page"><?= esc_html(wp_trim_words($title, 8, '')) ?></span>
                </nav>
            <?php endif; ?>
            <h1 class="iva-pagehero-title" data-iva-hero><?= esc_html($title) ?></h1>
            <?php if ($lead !== '') : ?><div class="iva-pagehero-lead" data-iva-hero><?= wp_kses_post($lead) ?></div><?php endif; ?>
            <?php if ($meta !== '') : ?><div class="iva-pagehero-meta" data-iva-hero><?= $meta ?></div><?php endif; ?>
        </div>
        <?php if ($mascot) { get_template_part('template-parts/mascot', null, ['pose' => 'peek', 'class' => 'iva-pagehero-mascot']); } ?>
    </header>
    <?php
}

/**
 * Content pasted from other tools often sits in nested wrapper elements. When the only thing in a root is one
 * wrapper (article, div, section), lift its children up so headings and paragraphs are top level. Display only.
 */
function ivermectin_unwrap_root(DOMElement $root): void {
    // Whitespace and elements with no text and no media count as empty.
    $blank = static fn(DOMNode $node): bool => trim(str_replace("\xC2\xA0", ' ', $node->textContent)) === ''
        && !($node instanceof DOMElement && ($node->getElementsByTagName('img')->length || $node->getElementsByTagName('iframe')->length || $node->getElementsByTagName('video')->length || in_array(strtolower($node->nodeName), ['img', 'iframe', 'video', 'hr'], true)));
    for ($depth = 0; $depth < 8; $depth++) {
        $meaningful = array_values(array_filter(iterator_to_array($root->childNodes), static fn($node) => !$blank($node)));
        if (count($meaningful) !== 1 || !$meaningful[0] instanceof DOMElement || !in_array(strtolower($meaningful[0]->nodeName), ['article', 'div', 'section', 'main'], true)) {
            return;
        }
        $wrapper = $meaningful[0];
        while ($wrapper->firstChild) {
            $root->insertBefore($wrapper->firstChild, $wrapper);
        }
        $root->removeChild($wrapper);
    }
}

/**
 * Split HTML into a leading H1, the paragraph right after it, and the rest.
 * Content is never saved back; this only decides how it is laid out.
 *
 * @return array{title: string, lead: string, rest: string}
 */
function ivermectin_split_leading_heading(string $html): array {
    $result = ['title' => '', 'lead' => '', 'rest' => $html];
    if (!class_exists('DOMDocument') || stripos($html, '<h1') === false) {
        return $result;
    }
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8"?><div id="iva-split">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    $root = $dom->getElementById('iva-split');
    if (!$root) {
        return $result;
    }
    ivermectin_unwrap_root($root);
    $blank = static fn(DOMNode $node): bool => $node instanceof DOMText && trim(str_replace("\xC2\xA0", ' ', $node->textContent)) === '';
    $nodes = array_values(array_filter(iterator_to_array($root->childNodes), static fn($node) => !$blank($node)));
    // Leading empty paragraphs and wrappers are skipped when looking for the heading.
    $index = 0;
    while (isset($nodes[$index]) && $nodes[$index] instanceof DOMElement && trim(str_replace("\xC2\xA0", ' ', $nodes[$index]->textContent)) === '' && !$nodes[$index]->getElementsByTagName('img')->length) {
        $index++;
    }
    $first = $nodes[$index] ?? null;
    if (!$first instanceof DOMElement || strtolower($first->nodeName) !== 'h1') {
        return $result;
    }
    $result['title'] = trim(preg_replace('/\s+/', ' ', $first->textContent));
    $next = $nodes[$index + 1] ?? null;
    $take = $index + 1;
    // A second H1 repeating the same words is the same heading pasted twice.
    while ($next instanceof DOMElement && strtolower($next->nodeName) === 'h1' && trim(preg_replace('/\s+/', ' ', $next->textContent)) === $result['title']) {
        $take++;
        $next = $nodes[$take] ?? null;
    }
    if ($next instanceof DOMElement && strtolower($next->nodeName) === 'p' && mb_strlen(trim($next->textContent)) <= 400) {
        $result['lead'] = trim($dom->saveHTML($next));
        $take++;
    }
    $rest = '';
    foreach (array_slice($nodes, $take) as $node) {
        $rest .= $dom->saveHTML($node);
    }
    $result['rest'] = $rest;
    return $result;
}

/** Keep one H1 per page: any H1 left in article content becomes an H2. */
function ivermectin_demote_h1(string $html): string {
    return preg_replace(['/<h1(\s|>)/i', '/<\/h1>/i'], ['<h2$1', '</h2>'], $html);
}

/** Numbered pagination for a custom query, with Previous and Next from Theme Settings. */
function ivermectin_pagination(WP_Query $query): string {
    $total = (int) $query->max_num_pages;
    if ($total < 2) {
        return '';
    }
    $current = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
    $links   = paginate_links([
        'base'      => trailingslashit(get_permalink() ?: home_url('/')) . 'page/%#%/',
        'format'    => '',
        'current'   => $current,
        'total'     => $total,
        'type'      => 'array',
        'mid_size'  => 1,
        'prev_text' => ivermectin_label('pagination_previous_label') ?: '&lsaquo;',
        'next_text' => ivermectin_label('pagination_next_label') ?: '&rsaquo;',
    ]);
    return $links ? '<nav class="iva-pager" aria-label="' . esc_attr(ivermectin_label('pagination_label')) . '"><ul><li>' . implode('</li><li>', $links) . '</li></ul></nav>' : '';
}

/** Opening words of a post: its Excerpt, otherwise its content or the first text block of the previous theme's modules. */
function ivermectin_post_card_excerpt(WP_Post $article, int $words): string {
    if (has_excerpt($article)) {
        return wp_trim_words(get_the_excerpt($article), $words);
    }
    $text = wp_strip_all_tags(strip_shortcodes($article->post_content));
    if (trim($text) === '') {
        foreach ((array) get_post_meta($article->ID, 'modules', true) as $index => $layout) {
            $block = (string) get_post_meta($article->ID, "modules_{$index}_content", true);
            if (is_string($layout) && str_contains($layout, 'text') && trim(wp_strip_all_tags($block)) !== '') {
                $text = wp_strip_all_tags(strip_shortcodes($block));
                break;
            }
        }
    }
    return wp_trim_words($text, $words);
}

/**
 * Blog card: inset photo with the category chip, title, excerpt, date and an arrow link.
 * $variant: 'large' (featured), 'side' (small beside a featured card) or 'grid'.
 */
function ivermectin_render_post_card(WP_Post $article, string $variant = 'grid'): void {
    $link       = get_permalink($article);
    $categories = get_the_category($article->ID);
    $category   = $categories && $categories[0]->slug !== 'uncategorized' ? $categories[0]->name : '';
    $excerpt    = ivermectin_post_card_excerpt($article, $variant === 'large' ? 34 : 18);
    $title      = get_the_title($article);
    ?>
    <article class="iva-post iva-post-<?= esc_attr($variant) ?> group">
        <?php if (has_post_thumbnail($article)) : ?>
            <a href="<?= esc_url($link) ?>" class="iva-post-media" tabindex="-1" aria-hidden="true">
                <?= get_the_post_thumbnail($article, $variant === 'side' ? 'medium' : 'medium_large', ['class' => 'h-full w-full object-cover', 'alt' => '']) ?>
                <?php if ($category !== '') : ?><span class="iva-post-chip"><?= esc_html($category) ?></span><?php endif; ?>
            </a>
        <?php endif; ?>
        <div class="iva-post-body">
            <h3 class="iva-post-title"><a href="<?= esc_url($link) ?>"><span><?= esc_html($title) ?></span></a></h3>
            <?php if ($excerpt !== '') : ?><p class="iva-post-excerpt"><?= esc_html($excerpt) ?></p><?php endif; ?>
            <div class="iva-post-foot">
                <time class="inline-flex items-center gap-2" datetime="<?= esc_attr(get_the_date('c', $article)) ?>">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15" rx="3"/><path d="M3.5 10h17M8 3v4M16 3v4"/></svg>
                    <?= esc_html(get_the_date('', $article)) ?>
                </time>
                <a href="<?= esc_url($link) ?>" class="iva-post-arrow" aria-label="<?= esc_attr($title) ?>">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>
                </a>
            </div>
        </div>
    </article>
    <?php
}

/** Five-star row for a rating (0-5). */
function ivermectin_star_row(float $rating): string {
    $filled = (int) round($rating);
    return '<span class="iva-stars" aria-hidden="true">' . str_repeat('&#9733;', $filled) . '<span class="text-border">' . str_repeat('&#9733;', max(0, 5 - $filled)) . '</span></span>';
}

/** Page numbers for the main query (archives, search), styled like ivermectin_pagination(). */
function ivermectin_main_pagination(): string {
    $links = paginate_links([
        'type'      => 'array',
        'mid_size'  => 1,
        'prev_text' => ivermectin_label('pagination_previous_label') ?: '&lsaquo;',
        'next_text' => ivermectin_label('pagination_next_label') ?: '&rsaquo;',
    ]);
    return $links ? '<nav class="iva-pager" aria-label="' . esc_attr(ivermectin_label('pagination_label')) . '"><ul><li>' . implode('</li><li>', $links) . '</li></ul></nav>' : '';
}

/** Minutes to read a post (about 200 words a minute): its content plus the text blocks of the previous theme's modules. */
function ivermectin_reading_minutes(int $post_id): int {
    $text = wp_strip_all_tags(get_post_field('post_content', $post_id));
    foreach ((array) get_post_meta($post_id, 'modules', true) as $index => $layout) {
        if (is_string($layout) && str_contains($layout, 'text')) {
            $text .= ' ' . wp_strip_all_tags((string) get_post_meta($post_id, "modules_{$index}_content", true));
        }
    }
    $words = str_word_count($text);
    return $words > 0 ? max(1, (int) ceil($words / 200)) : 0;
}
