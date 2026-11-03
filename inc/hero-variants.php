<?php
/**
 * Page header variants. Each kind of page gets its own header so the inner pages do not all look alike:
 * contact (quick contact buttons), reviews (score card), faq (jump chips), document (policies),
 * route (delivery route line), search (article search), dark (articles), shop (strength capsules),
 * steps (cart and checkout tracker) and place (city pages).
 * All text comes from the page's own content, Theme Settings labels, or live data (reviews, products).
 */

defined('ABSPATH') || exit;

/** Pick the variant for a page from the layouts in its legacy content. */
function ivermectin_detect_hero_variant(array $rows): string {
    $layouts = array_column($rows, 'layout');
    if (in_array('review_page', $layouts, true)) {
        return 'reviews';
    }
    if (in_array('contact', $layouts, true)) {
        return 'contact';
    }
    if (in_array('blog', $layouts, true)) {
        return 'search';
    }
    // Delivery pages: how to order and tracking. Filter ivermectin_route_page_slugs to add more.
    $route_slugs = (array) apply_filters('ivermectin_route_page_slugs', ['how-to-order', 'tracking']);
    if (is_singular() && in_array(get_post_field('post_name', get_queried_object_id()), $route_slugs, true)) {
        return 'route';
    }
    if (count(array_filter($rows, static fn($row) => $row['layout'] === 'faqs')) >= 2) {
        return 'faq';
    }
    if (in_array('feature_product', $layouts, true) || in_array('icons_3', $layouts, true)) {
        return 'place';
    }
    $text_only = !array_diff($layouts, ['breadcrumb', 'text_-_full_width']);
    $headings  = 0;
    foreach ($rows as $row) {
        $headings += preg_match_all('/<h2\b/i', (string) ($row['content'] ?? ''));
    }
    if ($text_only && $headings >= 3) {
        return 'document';
    }
    return 'default';
}

/** Hero arguments (extra, side, decor, icon) for a variant. $ctx may hold 'rows'. */
function ivermectin_hero_variant_args(string $variant, array $ctx = []): array {
    $rows = (array) ($ctx['rows'] ?? []);
    $args = ['variant' => $variant];

    switch ($variant) {
        case 'contact':
            foreach ($rows as $row) {
                if ($row['layout'] !== 'contact') {
                    continue;
                }
                $actions = [];
                if (trim($row['phone']) !== '') {
                    $actions[] = ['href' => ivermectin_tel_url($row['phone']), 'text' => $row['phone'], 'icon' => 'live_chat_support'];
                }
                if (trim($row['email']) !== '') {
                    $actions[] = ['href' => 'mailto:' . $row['email'], 'text' => $row['email'], 'icon' => 'email_support'];
                }
                $html = '';
                foreach ($actions as $action) {
                    $html .= '<a href="' . esc_url($action['href']) . '" class="iva-hero-action"><span class="iva-icon-tile grid h-11 w-11 shrink-0 place-items-center rounded-xl">' . ivermectin_icon($action['icon'], 'h-7 w-7') . '</span><span class="min-w-0 break-words">' . esc_html(wp_strip_all_tags($action['text'])) . '</span></a>';
                }
                $args['extra'] = $html !== '' ? '<div class="iva-hero-actions">' . $html . '</div>' : '';
                break;
            }
            break;

        case 'reviews':
            $stats = ivermectin_review_stats();
            if ($stats['count']) {
                $args['side'] = '<div class="iva-hero-score"><p class="iva-hero-score-num" data-iva-count="' . esc_attr(number_format($stats['average'], 1)) . '">' . esc_html(number_format($stats['average'], 1)) . '</p>'
                    . ivermectin_star_row($stats['average'])
                    . '<p class="iva-hero-score-text">' . esc_html(ivermectin_fill(ivermectin_label('reviews_page_summary'), ['score' => number_format($stats['average'], 1), 'count' => $stats['count']])) . '</p></div>';
            }
            break;

        case 'faq':
            $chips = '';
            foreach ($rows as $index => $row) {
                if ($row['layout'] === 'faqs' && $row['title'] !== '' && $row['items']) {
                    $chips .= '<a href="#' . esc_attr(ivermectin_faq_anchor($index)) . '" class="iva-chip">' . esc_html($row['title']) . '</a>';
                }
            }
            $args['extra'] = $chips !== '' ? '<div class="iva-hero-chips">' . $chips . '</div>' : '';
            break;

        case 'document':
            $args['icon'] = '<span class="iva-icon-tile grid h-16 w-16 place-items-center rounded-2xl">' . ivermectin_icon('shield_insurance', 'h-10 w-10') . '</span>';
            $sections = 0;
            foreach ($rows as $row) {
                $sections += preg_match_all('/<h2\b/i', (string) ($row['content'] ?? ''));
            }
            $label = ivermectin_label('policy_sections_label');
            if ($label !== '' && $sections > 0) {
                $args['meta'] = '<span class="iva-meta-chip">' . esc_html(ivermectin_fill($label, ['count' => $sections])) . '</span>';
            }
            break;

        case 'route':
            $args['decor'] = '<svg class="iva-route" viewBox="0 0 640 140" fill="none" aria-hidden="true" focusable="false">'
                . '<path class="iva-route-line" d="M0 112C90 112 110 40 210 40S330 118 430 96 560 22 640 34" stroke="#8db4ea" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="3 11"/>'
                . '<circle cx="210" cy="40" r="7" fill="#fff" stroke="#012c70" stroke-width="3"/>'
                . '<circle cx="430" cy="96" r="7" fill="#fff" stroke="#012c70" stroke-width="3"/>'
                . '<g transform="translate(578 4)"><path d="M18 0C8.6 0 1 7.6 1 17c0 12.5 17 29 17 29s17-16.5 17-29C35 7.6 27.4 0 18 0z" fill="#d4012f"/><circle cx="18" cy="17" r="6.5" fill="#fff"/></g>'
                . '</svg>';
            break;

        case 'search':
            $placeholder = ivermectin_label('blog_search_placeholder');
            $button      = ivermectin_label('blog_search_button');
            $args['extra'] = '<form role="search" method="get" action="' . esc_url(home_url('/')) . '" class="iva-hero-search">'
                . '<input type="search" name="s" value="' . esc_attr(get_search_query()) . '" placeholder="' . esc_attr($placeholder) . '" aria-label="' . esc_attr($placeholder) . '">'
                . '<input type="hidden" name="post_type" value="post">'
                . '<button type="submit">' . esc_html($button) . '</button></form>';
            break;

        case 'shop':
            if (class_exists('WooCommerce') && function_exists('ivermectin_get_product_strength')) {
                $html = '';
                foreach (wc_get_products(['limit' => 24, 'status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC']) as $product) {
                    if (!preg_match('/^\s*([\d.]+)\s*([a-z]+)\s*$/i', ivermectin_get_product_strength($product), $parts)) {
                        continue;
                    }
                    $html .= '<a href="' . esc_url($product->get_permalink()) . '" class="iva-capsule iva-capsule-link" aria-label="' . esc_attr($product->get_name()) . '"><span class="iva-capsule-dose">' . esc_html($parts[1]) . '</span><span class="iva-capsule-unit">' . esc_html($parts[2]) . '</span></a>';
                }
                $args['extra'] = $html !== '' ? '<div class="iva-hero-capsules">' . $html . '</div>' : '';
            }
            break;

        case 'steps':
            $steps   = [ivermectin_label('checkout_step_cart'), ivermectin_label('checkout_step_checkout'), ivermectin_label('checkout_step_done')];
            $current = 0;
            if (function_exists('is_order_received_page') && is_order_received_page()) {
                $current = 3;
            } elseif (function_exists('is_checkout') && is_checkout()) {
                $current = 2;
            } elseif (function_exists('is_cart') && is_cart()) {
                $current = 1;
            }
            if ($current > 0) {
                $html = '';
                foreach ($steps as $index => $step) {
                    $number = $index + 1;
                    $state  = $number < $current ? 'done' : ($number === $current ? 'current' : 'todo');
                    $html  .= '<li class="iva-stepper-' . $state . '"' . ($number === $current ? ' aria-current="step"' : '') . '><span class="iva-stepper-dot">' . ($state === 'done' ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7"/></svg>' : esc_html((string) $number)) . '</span><span>' . esc_html($step) . '</span></li>';
                }
                $args['extra'] = '<ol class="iva-stepper">' . $html . '</ol>';
            }
            break;

        case 'place':
            $args['side'] = '<div class="iva-place" aria-hidden="true"><span class="iva-place-ring"></span><span class="iva-place-ring iva-place-ring-2"></span><span class="iva-place-pin">' . ivermectin_icon('location_pin', 'h-14 w-14') . '</span></div>';
            break;
    }

    return $args;
}

/** Anchor id of the FAQ block at a position in a page's modules. */
function ivermectin_faq_anchor(int $index): string {
    return 'faq-' . ($index + 1);
}
