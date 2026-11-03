<?php
/**
 * Renders the previous theme's "modules" content in the current design.
 *
 * The saved content and field values are never changed: each layout is read with the same sub fields
 * and only laid out differently. A page that uses a layout without an adapter here falls back to the
 * old markup (legacy/modules.php), so nothing is ever dropped.
 */

defined('ABSPATH') || exit;

/** Layouts with an adapter below. */
function ivermectin_legacy_supported_layouts(): array {
    return ['breadcrumb', 'text_-_full_width', 'intro_black_background', 'faqs', 'contact', 'review_page', 'reviews', 'blog', 'feature_product', 'icons_3', 'how_to_order'];
}

/**
 * Read the "modules" rows of a post into plain arrays. Returns null when the post has none or uses an unsupported layout.
 */
function ivermectin_legacy_rows($post_id = false): ?array {
    if (!have_rows('modules', $post_id)) {
        return null;
    }
    $rows = [];
    while (have_rows('modules', $post_id)) {
        the_row();
        $layout = (string) get_row_layout();
        if (!in_array($layout, ivermectin_legacy_supported_layouts(), true)) {
            return null;
        }
        $row = ['layout' => $layout];
        switch ($layout) {
            case 'text_-_full_width':
            case 'intro_black_background':
                $row['content'] = (string) get_sub_field('content');
                break;
            case 'faqs':
                $row['title'] = trim((string) get_sub_field('faq_title'));
                $row['items'] = array_values(array_filter((array) get_sub_field('faqs'), static fn($item) => trim((string) ($item['question'] ?? '')) !== ''));
                break;
            case 'contact':
                foreach (['email', 'phone', 'address', 'map', 'form'] as $name) {
                    $row[$name] = (string) get_sub_field($name);
                }
                break;
            case 'reviews':
                $row['heading'] = trim((string) get_sub_field('heading'));
                break;
            case 'feature_product':
                $row['heading']  = trim((string) get_sub_field('heading'));
                $row['products'] = array_filter((array) get_sub_field('feature_product'));
                break;
            case 'icons_3':
                $row['items'] = (array) get_sub_field('icon_3');
                break;
            case 'how_to_order':
                $row['intro'] = (string) get_sub_field('intro');
                $row['steps'] = (array) get_sub_field('how_to_order');
                break;
        }
        $rows[] = $row;
    }
    return $rows;
}

/**
 * Render a post's legacy modules. Returns false when the old renderer should be used instead.
 *
 * @param array $opts {
 *   @type array|false $hero  false for no page header; an array overrides title, lead, meta, crumbs.
 * }
 */
function ivermectin_render_legacy_modules($post_id = false, array $opts = []): bool {
    $rows = ivermectin_legacy_rows($post_id);
    if ($rows === null) {
        return false;
    }
    ivermectin_render_legacy_rows($rows, $opts);
    return true;
}

function ivermectin_render_legacy_rows(array $rows, array $opts = []): void {
    $hero = $opts['hero'] ?? [];
    $hero_html_taken = false;

    // The first text or intro block usually opens with the page's H1: it moves into the page header.
    if (is_array($hero)) {
        foreach ($rows as $index => $row) {
            if (!in_array($row['layout'], ['text_-_full_width', 'intro_black_background'], true)) {
                continue;
            }
            if (empty($hero['title'])) {
                $split = ivermectin_split_leading_heading($row['content']);
                if ($split['title'] !== '') {
                    $hero['title']          = $split['title'];
                    $hero['lead']           = $hero['lead'] ?? $split['lead'];
                    $rows[$index]['content'] = $split['rest'];
                    $hero_html_taken         = true;
                }
            }
            break;
        }
        // The reviews page has no heading in its content: it uses the Reviews page heading label.
        if (empty($hero['title']) && !$hero_html_taken) {
            if (in_array('review_page', array_column($rows, 'layout'), true)) {
                $hero['title'] = ivermectin_label('reviews_page_heading') ?: get_the_title();
            }
        }
        ivermectin_page_hero($hero);
    }

    foreach ($rows as $index => $row) {
        $fn = 'ivermectin_legacy_render_' . preg_replace('/[^a-z0-9]+/', '_', $row['layout']);
        if (function_exists($fn)) {
            $fn($row, $index);
        }
    }
}

function ivermectin_legacy_render_breadcrumb(array $row, int $index): void {
    // The page header carries the breadcrumb.
}

/** Long content with several H2 headings becomes a guide with a contents rail; shorter content is prose. */
function ivermectin_legacy_render_text_full_width(array $row, int $index): void {
    $content = trim($row['content']);
    if ($content === '' || trim(wp_strip_all_tags(str_replace('&nbsp;', ' ', $content))) === '' && stripos($content, '<img') === false && stripos($content, '<iframe') === false) {
        return;
    }
    ivermectin_render_article_section(ivermectin_demote_h1($content), true, 'default', true);
}

function ivermectin_legacy_render_intro_black_background(array $row, int $index): void {
    ivermectin_legacy_render_text_full_width($row, $index);
}

/** Questions as two columns of open cards. The schema.org markup of the previous theme is kept. */
function ivermectin_legacy_render_faqs(array $row, int $index): void {
    if (!$row['items']) {
        return;
    }
    ?>
    <section class="iva-section iva-faqs bg-surface">
        <div class="container-site">
            <?php ivermectin_section_head('', $row['title'], '', 'light', 'left'); ?>
            <div class="iva-qa-grid" data-iva-stagger>
                <?php foreach ($row['items'] as $item) : ?>
                    <details class="iva-qa-item" open itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                        <summary><span itemprop="name"><?= wp_kses_post($item['question']) ?></span><span class="iva-qa-icon" aria-hidden="true"></span></summary>
                        <div class="iva-qa-answer entry-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><div itemprop="text"><?= $item['answer'] ?></div></div>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

/** Australian number to a tel: link with +61. */
function ivermectin_tel_url(string $raw): string {
    $digits = preg_replace('/\D+/', '', $raw);
    if ($digits === '') {
        return '';
    }
    if (str_starts_with($digits, '0')) {
        $digits = '61' . substr($digits, 1);
    }
    return 'tel:+' . $digits;
}

/** Contact details beside the form, map underneath. Labels come from Theme Settings. */
function ivermectin_legacy_render_contact(array $row, int $index): void {
    $details = [
        ['label' => ivermectin_label('contact_label_email'), 'value' => $row['email'], 'href' => $row['email'] !== '' ? 'mailto:' . $row['email'] : '', 'icon' => 'email_support'],
        ['label' => ivermectin_label('contact_label_phone'), 'value' => $row['phone'], 'href' => ivermectin_tel_url($row['phone']), 'icon' => 'live_chat_support'],
        ['label' => ivermectin_label('contact_label_address'), 'value' => $row['address'], 'href' => '', 'icon' => 'location_pin'],
    ];
    ?>
    <section class="iva-section iva-contact bg-surface">
        <div class="container-site grid gap-6 lg:grid-cols-[5fr_7fr] lg:gap-8">
            <div class="grid content-start gap-4" data-iva-stagger>
                <?php foreach ($details as $detail) : if (trim($detail['value']) === '') { continue; } ?>
                    <div class="iva-contact-card">
                        <span class="iva-icon-tile grid h-12 w-12 shrink-0 place-items-center rounded-2xl"><?= ivermectin_icon($detail['icon'], 'h-8 w-8') ?></span>
                        <div class="min-w-0">
                            <?php if ($detail['label'] !== '') : ?><p class="text-sm font-semibold text-muted-foreground"><?= esc_html($detail['label']) ?></p><?php endif; ?>
                            <?php if ($detail['href'] !== '') : ?>
                                <a href="<?= esc_url($detail['href']) ?>" class="mt-0.5 block break-words font-heading text-lg font-bold text-primary hover:text-link"><?= esc_html($detail['value']) ?></a>
                            <?php else : ?>
                                <div class="mt-0.5 font-heading text-lg font-bold leading-snug text-primary [&_p]:m-0 [&_p+p]:mt-1 [&_p+p]:text-base [&_p+p]:font-semibold [&_p+p]:text-foreground/80"><?= wp_kses_post($detail['value']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (trim($row['map']) !== '') : ?><div class="iva-contact-map"><?= $row['map'] ?></div><?php endif; ?>
            </div>
            <?php if (trim($row['form']) !== '') : ?>
                <div class="iva-form-card entry-content" data-iva-reveal-item><?= $row['form'] ?></div>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

/** Review score and breakdown from the real Reviews posts. */
function ivermectin_review_stats(): array {
    $stats = ['count' => 0, 'average' => 0.0, 'breakdown' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]];
    $sum   = 0;
    foreach (get_posts(['post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids']) as $review_id) {
        $rating = (int) round((float) get_field('rating', $review_id));
        if ($rating >= 1 && $rating <= 5) {
            $stats['count']++;
            $stats['breakdown'][$rating]++;
            $sum += $rating;
        }
    }
    $stats['average'] = $stats['count'] ? $sum / $stats['count'] : 0.0;
    return $stats;
}

/** One review card (used on the reviews page and in the homepage-style carousel). */
function ivermectin_render_review_card(WP_Post $review): void {
    $rating = (int) get_field('rating', $review->ID);
    $name   = trim((string) get_field('name', $review->ID));
    ?>
    <figure class="iva-review-card iva-review-card-grid">
        <?php if ($rating >= 1) : ?><span class="iva-stars text-lg leading-none" aria-hidden="true"><?= str_repeat('&#9733;', $rating) ?><span class="text-border"><?= str_repeat('&#9733;', 5 - $rating) ?></span></span><?php endif; ?>
        <?php if ($review->post_title !== '') : ?><p class="mt-5 font-heading text-xl font-bold leading-snug text-primary"><?= esc_html($review->post_title) ?></p><?php endif; ?>
        <blockquote class="mt-3 text-[1.0625rem] leading-relaxed text-muted-foreground"><?= esc_html(wp_strip_all_tags($review->post_content)) ?></blockquote>
        <figcaption class="mt-auto pt-6">
            <?php if ($name !== '') : ?><span class="block font-semibold text-primary"><?= esc_html($name) ?></span><?php endif; ?>
            <span class="block text-sm text-muted-foreground"><?= esc_html(get_the_date('', $review)) ?></span>
        </figcaption>
    </figure>
    <?php
}

/** Reviews page: score panel beside a paged grid of reviews, then the review form. */
function ivermectin_legacy_render_review_page(array $row, int $index): void {
    $stats  = ivermectin_review_stats();
    $paged  = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
    $query  = new WP_Query(['post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => $paged]);
    $form_heading = ivermectin_label('reviews_form_heading');
    $has_form     = shortcode_exists('gravityform');
    ?>
    <section class="iva-section iva-reviews-page bg-surface">
        <div class="container-site grid gap-8 lg:grid-cols-[20rem_minmax(0,1fr)] lg:items-start">
            <?php if ($stats['count']) : ?>
                <aside class="iva-score lg:sticky lg:top-28" data-iva-reveal-item>
                    <div class="flex items-center gap-4">
                        <p class="font-heading text-6xl font-extrabold leading-none text-primary" data-iva-count="<?= esc_attr(number_format($stats['average'], 1)) ?>"><?= esc_html(number_format($stats['average'], 1)) ?></p>
                        <div><?= ivermectin_star_row($stats['average']) ?></div>
                    </div>
                    <p class="mt-3 text-[0.9375rem] text-muted-foreground"><?= esc_html(ivermectin_fill(ivermectin_label('reviews_page_summary'), ['score' => number_format($stats['average'], 1), 'count' => $stats['count']])) ?></p>
                    <ul class="mt-5 space-y-2">
                        <?php foreach ($stats['breakdown'] as $star => $count) : $share = round($count / $stats['count'] * 100); ?>
                            <li class="grid grid-cols-[2.25rem_1fr_2rem] items-center gap-2 text-sm">
                                <span class="font-bold text-primary"><?= esc_html((string) $star) ?> <span class="text-accent" aria-hidden="true">&#9733;</span></span>
                                <span class="h-2 overflow-hidden rounded-full bg-primary-soft"><span class="iva-bar block h-full rounded-full bg-primary" style="--w: <?= esc_attr((string) $share) ?>%"></span></span>
                                <span class="text-right text-muted-foreground"><?= esc_html((string) $count) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ($has_form && $form_heading !== '') : ?><a href="#review-form" class="btn-cta mt-6 w-full rounded-full"><?= esc_html($form_heading) ?></a><?php endif; ?>
                </aside>
            <?php endif; ?>

            <div class="min-w-0">
                <div class="grid gap-5 md:grid-cols-2" data-iva-stagger>
                    <?php foreach ($query->posts as $review) { ivermectin_render_review_card($review); } ?>
                </div>
                <?= ivermectin_pagination($query) ?>
            </div>
        </div>
    </section>
    <?php if ($has_form && $form_heading !== '') : ?>
        <section class="iva-section bg-white" id="review-form">
            <div class="container-site max-w-3xl">
                <?php ivermectin_section_head('', $form_heading, '', 'light', 'center'); ?>
                <div class="iva-form-card entry-content"><?= do_shortcode('[gravityform id="1" title="false" description="false" ajax="false"]') ?></div>
            </div>
        </section>
    <?php endif;
    wp_reset_postdata();
}

/** Latest reviews strip (the "reviews" layout on city pages). */
function ivermectin_legacy_render_reviews(array $row, int $index): void {
    $reviews = get_posts(['post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => 3]);
    if (!$reviews) {
        return;
    }
    ?>
    <section class="iva-section bg-surface">
        <div class="container-site">
            <?php ivermectin_section_head('', $row['heading'], ''); ?>
            <div class="grid gap-5 md:grid-cols-3" data-iva-stagger>
                <?php foreach ($reviews as $review) { ivermectin_render_review_card($review); } ?>
            </div>
        </div>
    </section>
    <?php
}

/** Articles page: category chips, a featured trio on page one, then a grid. */
function ivermectin_legacy_render_blog(array $row, int $index): void {
    $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
    $query = new WP_Query(['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => $paged]);
    if (!$query->have_posts()) {
        return;
    }
    $posts = $query->posts;
    ?>
    <section class="iva-section iva-blog-index bg-surface">
        <div class="container-site">
            <?php ivermectin_render_category_chips(); ?>
            <?php if ($paged === 1 && count($posts) >= 3) : ?>
                <div class="iva-posts-featured" data-iva-stagger>
                    <?php ivermectin_render_post_card($posts[0], 'large'); ?>
                    <div class="grid gap-5">
                        <?php ivermectin_render_post_card($posts[1], 'side'); ?>
                        <?php ivermectin_render_post_card($posts[2], 'side'); ?>
                    </div>
                </div>
                <?php $posts = array_slice($posts, 3); ?>
            <?php endif; ?>
            <?php if ($posts) : ?>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 <?= $paged === 1 ? 'mt-6' : '' ?>" data-iva-stagger>
                    <?php foreach ($posts as $post_item) { ivermectin_render_post_card($post_item, 'grid'); } ?>
                </div>
            <?php endif; ?>
            <?= ivermectin_pagination($query) ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
}

/** Row of category links above article lists. */
function ivermectin_render_category_chips(): void {
    $terms = get_terms(['taxonomy' => 'category', 'hide_empty' => true]);
    if (is_wp_error($terms) || !$terms) {
        return;
    }
    $blog_page_id = (int) get_option('page_for_posts');
    $all_url      = $blog_page_id ? get_permalink($blog_page_id) : (get_permalink() ?: home_url('/'));
    $current      = is_category() ? get_queried_object_id() : 0;
    $all_label    = ivermectin_label('blog_all_label');
    ?>
    <nav class="iva-chips" aria-label="<?= esc_attr(ivermectin_label('blog_categories_label')) ?>">
        <?php if ($all_label !== '') : ?><a href="<?= esc_url($all_url) ?>" class="iva-chip" <?= $current ? '' : 'aria-current="true"' ?>><?= esc_html($all_label) ?></a><?php endif; ?>
        <?php foreach ($terms as $term) : if ($term->slug === 'uncategorized') { continue; } ?>
            <a href="<?= esc_url(get_term_link($term)) ?>" class="iva-chip" <?= $current === $term->term_id ? 'aria-current="true"' : '' ?>><?= esc_html($term->name) ?></a>
        <?php endforeach; ?>
    </nav>
    <?php
}

/** Featured products as the same cards used on the homepage. */
function ivermectin_legacy_render_feature_product(array $row, int $index): void {
    if (!class_exists('WooCommerce') || !$row['products']) {
        return;
    }
    ?>
    <section class="iva-section bg-white">
        <div class="container-site">
            <?php ivermectin_section_head('', $row['heading'], ''); ?>
            <div class="grid grid-cols-2 gap-2.5 sm:gap-4 md:gap-6" data-iva-stagger>
                <?php foreach ($row['products'] as $item) {
                    $product_id = is_numeric($item) ? (int) $item : (is_object($item) ? (int) $item->ID : 0);
                    $product    = $product_id ? wc_get_product($product_id) : null;
                    if ($product) {
                        get_template_part('template-parts/product-row-card', null, ['product' => $product]);
                    }
                } ?>
            </div>
        </div>
    </section>
    <?php
}

/** Icon and text cards (city pages). */
function ivermectin_legacy_render_icons_3(array $row, int $index): void {
    $items = array_values(array_filter($row['items'], static fn($item) => trim(wp_strip_all_tags((string) ($item['content'] ?? ''))) !== ''));
    if (!$items) {
        return;
    }
    ?>
    <section class="iva-section bg-white">
        <div class="container-site grid gap-5 sm:grid-cols-2 lg:grid-cols-4" data-iva-stagger>
            <?php foreach ($items as $item) : ?>
                <div class="iva-point">
                    <?php if (!empty($item['icon'])) : ?><span class="iva-icon-tile iva-point-icon"><?= wp_get_attachment_image((int) $item['icon'], 'thumbnail', false, ['class' => 'h-8 w-8 object-contain', 'alt' => '']) ?></span><?php endif; ?>
                    <div class="iva-point-text !mt-4"><?= $item['content'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/** Numbered steps with an intro (city pages). */
function ivermectin_legacy_render_how_to_order(array $row, int $index): void {
    $steps = array_values(array_filter($row['steps'], static fn($step) => trim(wp_strip_all_tags((string) ($step['content'] ?? ''))) !== ''));
    if (!$steps) {
        return;
    }
    ?>
    <section class="iva-section iva-steps bg-surface">
        <div class="container-site">
            <?php if (trim($row['intro']) !== '') : ?><div class="iva-head iva-head-center prose max-w-none" data-iva-head><?= $row['intro'] ?></div><?php endif; ?>
            <ol class="iva-steps-list mx-auto grid max-w-5xl gap-5 <?= esc_attr(['md:grid-cols-1', 'md:grid-cols-2', 'md:grid-cols-3'][min(3, count($steps)) - 1]) ?>" data-iva-stagger>
                <?php foreach ($steps as $number => $step) : ?>
                    <li class="iva-step">
                        <span class="iva-step-num"><?= esc_html(str_pad((string) ($number + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                        <?php if (!empty($step['icon'])) : ?><span class="iva-icon-tile mx-auto mt-6 grid h-14 w-14 place-items-center rounded-2xl"><img src="<?= esc_url(is_array($step['icon']) ? ($step['icon']['url'] ?? '') : $step['icon']) ?>" alt="" class="h-9 w-9 object-contain"></span><?php endif; ?>
                        <div class="iva-body mt-4 text-left"><?= $step['content'] ?></div>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>
    <?php
}
