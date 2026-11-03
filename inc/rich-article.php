<?php
/**
 * Long-form article layout for rich_text content with several H2 headings.
 * The editor's HTML is not changed in the database; it is only presented differently:
 * - each H2 starts a chapter, listed in a contents rail;
 * - "Label: text" bullet lists become icon cards, or a spec table when the values are short;
 * - an ordered list becomes a step flow (it is a sequence);
 * - "Pro Tip" paragraphs and blockquotes become a callout;
 * - runs of paragraphs that open with a bold question become an accordion (answers open unless $faq_open is false);
 * - a heading with nothing under it is not shown.
 */

defined('ABSPATH') || exit;

/**
 * @return array{chapters: array<int, array{id: string, title: string, html: string}>}|null
 *         null when the content has fewer than three H2 headings (caller renders plain prose instead).
 */
function ivermectin_build_rich_article(string $content, bool $faq_open = true, bool $trusted = false): ?array {
    if (substr_count(strtolower($content), '<h2') < 3 || !class_exists('DOMDocument')) {
        return null;
    }

    // Content from the editor is filtered; saved legacy page content is shown as it was written.
    $html = $trusted ? wpautop($content) : wp_kses_post(wpautop($content));
    $html = preg_replace('/\sdata-[a-z-]+="[^"]*"/i', '', $html);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8"?><div id="iva-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    $root = $dom->getElementById('iva-root');
    if (!$root) {
        return null;
    }

    ivermectin_unwrap_root($root);

    // Split top-level nodes into chapters at each H2.
    $chapters = [];
    $current  = ['title' => '', 'nodes' => []];
    foreach (iterator_to_array($root->childNodes) as $node) {
        if ($node instanceof DOMElement && strtolower($node->nodeName) === 'h2') {
            $chapters[] = $current;
            $current    = ['title' => trim($node->textContent), 'nodes' => []];
            continue;
        }
        if ($node instanceof DOMText && trim(str_replace("\xC2\xA0", ' ', $node->textContent)) === '') {
            continue;
        }
        if ($node instanceof DOMElement && strtolower($node->nodeName) === 'p' && trim(str_replace("\xC2\xA0", ' ', $node->textContent)) === '') {
            continue;
        }
        $current['nodes'][] = $node;
    }
    $chapters[] = $current;

    $output = [];
    $used   = [];
    foreach ($chapters as $chapter_index => $chapter) {
        if (!$chapter['nodes']) {
            continue;
        }
        $body = ivermectin_rich_article_body($dom, $chapter['nodes'], count($output) === 0, $faq_open);
        if ($chapter['title'] === '') {
            $output[] = ['id' => '', 'title' => '', 'html' => $body];
            continue;
        }
        $id = 'guide-' . sanitize_title($chapter['title']);
        while (isset($used[$id])) {
            $id .= '-' . $chapter_index;
        }
        $used[$id] = true;
        $output[]  = ['id' => $id, 'title' => $chapter['title'], 'html' => $body];
    }

    return $output ? ['chapters' => $output] : null;
}

/**
 * Render one chapter's nodes, grouping FAQ paragraphs and restyling lists and tips.
 */
function ivermectin_rich_article_body(DOMDocument $dom, array $nodes, bool $is_first, bool $faq_open = true): string {
    $out       = '';
    $faq       = [];
    $lead_done = !$is_first;

    $flush_faq = static function () use (&$faq, &$out, $faq_open): void {
        if (!$faq) {
            return;
        }
        $out .= '<div class="iva-qa" data-iva-stagger>';
        foreach ($faq as $item) {
            $out .= '<details class="iva-qa-item"' . ($faq_open ? ' open' : '') . '><summary><span>' . esc_html($item['q']) . '</span><span class="iva-qa-icon" aria-hidden="true"></span></summary><div class="iva-qa-answer">' . $item['a'] . '</div></details>';
        }
        $out .= '</div>';
        $faq = [];
    };

    foreach ($nodes as $node) {
        if (!$node instanceof DOMElement) {
            $flush_faq();
            $out .= '<p>' . esc_html(trim($node->textContent)) . '</p>';
            continue;
        }
        $tag = strtolower($node->nodeName);

        if ($tag === 'p') {
            [$label, $rest] = ivermectin_rich_article_split_label($dom, $node);
            if ($label !== '' && substr($label, -1) === '?') {
                $faq[] = ['q' => $label, 'a' => $rest];
                continue;
            }
            $flush_faq();
            if ($label !== '' && preg_match('/\btips?\b/i', $label)) {
                $out .= ivermectin_rich_article_tip(rtrim($label, ':'), $rest);
                continue;
            }
            if (!$lead_done) {
                $lead_done = true;
                $out .= '<p class="iva-guide-lead">' . ivermectin_rich_article_inner($dom, $node) . '</p>';
                continue;
            }
            $out .= $dom->saveHTML($node);
            continue;
        }

        $flush_faq();

        if ($tag === 'blockquote') {
            $inner = $node->getElementsByTagName('p')->item(0);
            [$label, $rest] = $inner ? ivermectin_rich_article_split_label($dom, $inner) : ['', ''];
            $out .= $label !== '' ? ivermectin_rich_article_tip(rtrim($label, ':'), $rest) : '<aside class="iva-tip"><div>' . ivermectin_rich_article_inner($dom, $node) . '</div></aside>';
            continue;
        }

        if ($tag === 'ul' || $tag === 'ol') {
            $out .= ivermectin_rich_article_list($dom, $node, $tag === 'ol');
            continue;
        }

        $out .= $dom->saveHTML($node);
    }
    $flush_faq();

    return $out;
}

/**
 * A paragraph that opens with bold text: returns [bold text, the rest as HTML]. Otherwise ['', ''].
 */
function ivermectin_rich_article_split_label(DOMDocument $dom, DOMElement $paragraph): array {
    $first = $paragraph->firstChild;
    while ($first instanceof DOMText && trim($first->textContent) === '') {
        $first = $first->nextSibling;
    }
    if (!$first instanceof DOMElement || !in_array(strtolower($first->nodeName), ['b', 'strong'], true)) {
        return ['', ''];
    }
    $label = trim($first->textContent);
    $rest  = '';
    for ($sibling = $first->nextSibling; $sibling; $sibling = $sibling->nextSibling) {
        $rest .= $dom->saveHTML($sibling);
    }
    return [$label, trim($rest)];
}

function ivermectin_rich_article_inner(DOMDocument $dom, DOMElement $element): string {
    $html = '';
    foreach ($element->childNodes as $child) {
        $html .= $dom->saveHTML($child);
    }
    return trim($html);
}

function ivermectin_rich_article_tip(string $label, string $text): string {
    return '<aside class="iva-tip" data-iva-stagger><span class="iva-tip-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3.6 10.8c.7.5 1.1 1.3 1.1 2.2h5c0-.9.4-1.7 1.1-2.2A6 6 0 0 0 12 3Z"/></svg></span><div><p class="iva-tip-label">' . esc_html($label) . '</p><p>' . $text . '</p></div></aside>';
}

/**
 * Lists: labelled items become cards or spec rows, ordered lists become steps, the rest a check list.
 */
function ivermectin_rich_article_list(DOMDocument $dom, DOMElement $list, bool $ordered): string {
    $items = [];
    foreach ($list->childNodes as $li) {
        if (!$li instanceof DOMElement || strtolower($li->nodeName) !== 'li') {
            continue;
        }
        $paragraph = $li->getElementsByTagName('p')->item(0);
        $source    = $paragraph ?: $li;
        [$label, $rest] = ivermectin_rich_article_split_label($dom, $source);
        $items[] = [
            'label' => rtrim($label, ': '),
            'text'  => $label !== '' ? preg_replace('/^:\s*/', '', $rest) : ivermectin_rich_article_inner($dom, $source),
        ];
    }
    if (!$items) {
        return '';
    }
    $labelled = count(array_filter($items, static fn($item) => $item['label'] !== '')) === count($items);

    if ($ordered) {
        $html = '<ol class="iva-flow" data-iva-stagger>';
        foreach ($items as $index => $item) {
            $html .= '<li><span class="iva-flow-num" aria-hidden="true">' . ($index + 1) . '</span>';
            $html .= $item['label'] !== '' ? '<p class="iva-flow-title">' . esc_html($item['label']) . '</p><p>' . $item['text'] . '</p>' : '<p>' . $item['text'] . '</p>';
            $html .= '</li>';
        }
        return $html . '</ol>';
    }

    if (!$labelled) {
        $html = '<ul class="iva-checks">';
        foreach ($items as $item) {
            $html .= '<li>' . $item['text'] . '</li>';
        }
        return $html . '</ul>';
    }

    $lengths = array_map(static fn($item) => mb_strlen(wp_strip_all_tags($item['text'])), $items);
    sort($lengths);
    $median = $lengths[(int) floor((count($lengths) - 1) / 2)];

    if ($median <= 45) {
        $html = '<dl class="iva-specs" data-iva-stagger>';
        foreach ($items as $item) {
            $html .= '<div><dt>' . esc_html($item['label']) . '</dt><dd>' . $item['text'] . '</dd></div>';
        }
        return $html . '</dl>';
    }

    // Mid-length points read as light icon rows; long ones get full cards.
    if ($median <= 90) {
        $html = '<ul class="iva-rows" data-iva-stagger>';
        foreach ($items as $item) {
            $html .= '<li><span class="iva-icon-tile iva-row-icon">'
                . ivermectin_render_card_icon(['title' => $item['label'], 'desc' => wp_strip_all_tags($item['text']), 'size_class' => 'h-7 w-7'])
                . '</span><span><span class="iva-row-title">' . esc_html($item['label']) . '</span><span class="iva-row-text">' . $item['text'] . '</span></span></li>';
        }
        return $html . '</ul>';
    }

    $columns = count($items) === 3 ? ' iva-points-3' : '';
    $html    = '<div class="iva-points' . $columns . '" data-iva-stagger>';
    foreach ($items as $item) {
        $html .= '<div class="iva-point"><span class="iva-icon-tile iva-point-icon">'
            . ivermectin_render_card_icon(['title' => $item['label'], 'desc' => wp_strip_all_tags($item['text']), 'size_class' => 'h-8 w-8'])
            . '</span><p class="iva-point-title">' . esc_html($item['label']) . '</p><p class="iva-point-text">' . $item['text'] . '</p></div>';
    }
    return $html . '</div>';
}

/**
 * A text section: guide layout (contents rail, chapters) for long content with three or more H2 headings,
 * otherwise a prose section. $trusted skips the editor filter for saved page content.
 */
function ivermectin_render_article_section(string $content, bool $faq_open = true, string $width = 'default', bool $trusted = false): void {
    $article = ivermectin_build_rich_article($content, $faq_open, $trusted);
    $max_width_class = match ($width) {
        'narrow' => 'max-w-3xl',
        'full' => 'max-w-none',
        default => 'max-w-6xl',
    };
    $toc = $article ? array_values(array_filter($article['chapters'], static fn($chapter) => $chapter['id'] !== '')) : [];
    ?>
    <?php $short = !$article && mb_strlen(trim(wp_strip_all_tags($content))) < 900; ?>
    <section class="<?= $short ? 'iva-section-tight' : 'iva-section' ?> iva-guide bg-white">
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
                <div class="prose prose-slate iva-prose max-w-none"><?= $trusted ? $content : wp_kses_post($content) ?></div>
            </div>
        <?php endif; ?>
    </section>
    <?php
}
