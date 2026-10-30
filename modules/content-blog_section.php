<?php
/**
 * Module: Blog Section
 * Fully editable via ACF Pro flexible content module.
 */

$row = function_exists('get_row') ? get_row() : [];
if (!is_array($row)) {
    $row = [];
}

// Field values with robust fallback to $row array and default content
$eyebrow = get_sub_field('eyebrow');
if (empty($eyebrow) && isset($row['eyebrow'])) {
    $eyebrow = $row['eyebrow'];
}
if ($eyebrow === null || $eyebrow === '') {
    $eyebrow = 'Knowledge & Resources';
}

$title = get_sub_field('title');
if (empty($title) && isset($row['title'])) {
    $title = $row['title'];
}
if (empty($title)) {
    $title = get_sub_field('heading') ?: ($row['heading'] ?? 'Latest Articles & Guides');
}

$subtitle = get_sub_field('subtitle');
if (empty($subtitle) && isset($row['subtitle'])) {
    $subtitle = $row['subtitle'];
}
if (empty($subtitle)) {
    $subtitle = get_sub_field('description') ?: ($row['description'] ?? 'Helpful insights, dosage guides, and research on Armodafinil and cognitive performance.');
}

$posts_count = get_sub_field('posts_count');
if (empty($posts_count) && isset($row['posts_count'])) {
    $posts_count = $row['posts_count'];
}
if (!$posts_count) {
    $posts_count = is_front_page() ? 3 : 9;
}
$posts_count = (int) $posts_count;

$show_view_all = true;
if (isset($row['show_view_all'])) {
    $show_view_all = !empty($row['show_view_all']);
} elseif (get_sub_field('show_view_all') !== null && get_sub_field('show_view_all') !== false) {
    $show_view_all = !empty(get_sub_field('show_view_all'));
}

// If we are already on the blog page or archive, hide "view all" button to prevent self-linking
if (is_home() || is_archive() || (is_page() && get_the_ID() === (int) get_option('page_for_posts')) || (is_page() && basename(get_permalink()) === 'blog')) {
    $show_view_all = false;
}

$button_text = !empty(get_sub_field('button_text')) 
    ? get_sub_field('button_text') 
    : (!empty($row['button_text']) ? $row['button_text'] : 'View All Articles');

$button_url = !empty(get_sub_field('button_url')) 
    ? get_sub_field('button_url') 
    : (!empty($row['button_url']) ? $row['button_url'] : '');

if (empty($button_url)) {
    $blog_page_id = get_option('page_for_posts');
    $button_url = $blog_page_id ? get_permalink($blog_page_id) : home_url('/blog');
}

// Check for admin-chosen posts (via ACF relationship field)
$selected_posts = get_sub_field('selected_posts');
if (empty($selected_posts) && isset($row['selected_posts'])) {
    $selected_posts = $row['selected_posts'];
}
if (empty($selected_posts)) {
    $selected_posts = get_sub_field('posts') ?: ($row['posts'] ?? null);
}

$articles = [];
if (!empty($selected_posts) && is_array($selected_posts)) {
    foreach ($selected_posts as $item) {
        $p = is_numeric($item) ? get_post($item) : $item;
        if ($p && is_object($p) && isset($p->ID) && $p->post_status === 'publish') {
            $articles[] = $p;
        }
    }
}

// Fallback: If no posts selected manually, query latest posts based on posts_count
if (empty($articles)) {
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $posts_count,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $blog_query = new WP_Query($args);
    $articles = $blog_query->posts;
}
?>
<section class="section-padding bg-gradient-to-b from-[#f0f6fa] via-[#e9f2f8] to-[#f8fafc] bg-pattern-dots border-t-2 border-dashed border-sky-200/80 relative isolate overflow-hidden" data-testid="blog-section">
    <div class="container-custom relative z-10">
        <!-- Section Header -->
        <?php if (!empty($title) || !empty($eyebrow)): ?>
        <div class="text-center max-w-3xl mx-auto mb-10 md:mb-12">
            <?php if (!empty($eyebrow)): ?>
            <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-900 border border-amber-300 text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse-subtle"></span>
                <?= esc_html($eyebrow) ?>
            </span>
            <?php endif; ?>

            <?php if (!empty($title)): ?>
            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl font-black text-ink tracking-tight mb-3">
                <?= esc_html($title) ?>
            </h2>
            <?php endif; ?>

            <?php if (!empty($subtitle)): ?>
            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mx-auto">
                <?= esc_html($subtitle) ?>
            </p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Cards Grid -->
        <?php if (!empty($articles)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <?php foreach ($articles as $article): 
                $post_id = $article->ID;
                $permalink = get_permalink($post_id);
                $categories = get_the_category($post_id);
                $cat_label = (!empty($categories) && $categories[0]->name !== 'Uncategorized') 
                    ? $categories[0]->name 
                    : 'Armodafinil Guide';

                // Reading time calculation (~200 wpm)
                $plain_text = strip_tags($article->post_content);
                $word_count = str_word_count($plain_text);
                $read_time = max(1, (int) ceil($word_count / 200));

                // Intro / Excerpt calculation
                $intro_val = '';
                if (function_exists('get_field')) {
                    $modules_data = get_field('modules', $post_id);
                    if (is_array($modules_data)) {
                        foreach ($modules_data as $mod) {
                            if (!empty($mod['intro'])) {
                                $intro_val = $mod['intro'];
                                break;
                            }
                        }
                    }
                    if (empty($intro_val)) {
                        $intro_val = get_field('intro', $post_id);
                    }
                }

                $excerpt = !empty(trim($article->post_excerpt)) 
                    ? $article->post_excerpt 
                    : (!empty($intro_val) ? $intro_val : wp_trim_words($plain_text, 22, '...'));
            ?>
            <article class="group flex flex-col h-full bg-white border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-out">
                <!-- Card Thumbnail / Visual Area -->
                <a href="<?= esc_url($permalink) ?>" class="block relative aspect-[16/10] overflow-hidden bg-slate-900 focus:outline-hidden" tabindex="-1" aria-hidden="true">
                    <?php if (has_post_thumbnail($post_id)): ?>
                        <?= get_the_post_thumbnail($post_id, 'large', [
                            'class' => 'w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
                            'loading' => 'lazy',
                            'alt' => esc_attr($article->post_title)
                        ]); ?>
                    <?php else: ?>
                        <!-- Branded Fallback Visual -->
                        <div class="w-full h-full bg-gradient-to-br from-[#081729] via-[#0d2a4a] to-[#1a4066] flex flex-col items-center justify-center p-6 text-white text-center relative overflow-hidden transition-transform duration-500 ease-out group-hover:scale-105">
                            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
                            <div class="absolute -left-6 -bottom-6 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-3 backdrop-blur-xs border border-white/15 text-white/90 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold tracking-widest uppercase text-white/80">Waklert Knowledge</span>
                        </div>
                    <?php endif; ?>

                    <!-- Floating Category Badge -->
                    <div class="absolute top-3.5 left-3.5 z-10">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase bg-amber-400 text-slate-950 shadow-md border border-amber-500/30">
                            ★ <?= esc_html($cat_label) ?>
                        </span>
                    </div>
                </a>

                <!-- Card Body -->
                <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between bg-white">
                    <div>
                        <!-- Meta: Date & Read Time with Colorful SVG Icons -->
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-3 font-semibold">
                            <time datetime="<?= get_the_date('c', $post_id) ?>" class="inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-sky-600">
                                    <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                                </svg>
                                <?= get_the_date('M j, Y', $post_id) ?>
                            </time>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-amber-500">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                </svg>
                                <?= esc_html($read_time) ?> min read
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="font-heading font-black text-xl sm:text-2xl text-ink group-hover:text-[#0c4161] transition-colors line-clamp-2 mb-3 leading-snug">
                            <a href="<?= esc_url($permalink) ?>">
                                <?= esc_html($article->post_title) ?>
                            </a>
                        </h3>

                        <!-- Excerpt -->
                        <p class="text-sm text-slate-600 line-clamp-3 mb-4 leading-relaxed">
                            <?= esc_html($excerpt) ?>
                        </p>
                    </div>
                </div>

                <!-- Two-Tone Footer Block -->
                <a href="<?= esc_url($permalink) ?>" class="bg-[#f0f6fa] group-hover:bg-[#0c4161] p-3 px-4 sm:p-4 sm:px-6 border-t-2 border-dashed border-sky-200/80 flex items-center justify-between transition-colors duration-300 text-xs sm:text-sm font-black text-[#0c4161] group-hover:text-white mt-auto">
                    <span>Read Full Guide</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4 transform group-hover:translate-x-1.5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12 bg-white rounded-2xl border-2 border-dashed border-sky-300/80 p-8">
            <p class="text-slate-500 font-medium">No articles published yet.</p>
        </div>
        <?php endif; ?>

        <!-- View All Button -->
        <?php if ($show_view_all && !empty($button_text) && !empty($button_url)): ?>
        <div class="mt-10 md:mt-12 text-center">
            <a href="<?= esc_url($button_url) ?>" class="inline-flex items-center gap-2 border-2 border-dashed border-sky-300/90 bg-white hover:bg-[#0c4161] text-[#0c4161] hover:text-white font-extrabold px-5 py-2.5 sm:px-8 sm:py-3.5 rounded-full transition-all duration-300 uppercase tracking-wider sm:tracking-widest text-xs shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <span><?= esc_html($button_text) ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
