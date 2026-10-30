<?php
/**
 * Template Name: HTML Sitemap
 * Description: A responsive, editable sitemap for Waklert Australia (2-tone Dashed & Patterned Theme)
 */

get_header();

$sitemap_title = get_field('sitemap_title') ?: get_the_title();
$sitemap_intro = get_field('sitemap_intro') ?: 'Browse the main pages, armodafinil products, Australian delivery hubs, and support information on this site.';
$custom_columns = get_field('sitemap_columns');

$columns = [];
if (is_array($custom_columns) && $custom_columns) {
    foreach ($custom_columns as $column) {
        $links = [];
        foreach (($column['links'] ?? []) as $row) {
            $link = $row['link'] ?? null;
            if (is_array($link) && !empty($link['url']) && !empty($link['title'])) {
                $links[] = [
                    'title' => $link['title'],
                    'url' => $link['url'],
                    'target' => $link['target'] ?? '',
                ];
            }
        }
        if (!empty($column['column_title']) && $links) {
            $columns[] = ['title' => $column['column_title'], 'links' => $links];
        }
    }
}

if (!$columns) {
    $main_pages = [];
    $support_pages = [];
    $policy_pages = [];
    $support_terms = ['about', 'contact', 'faq', 'support', 'track', 'guide', 'review'];
    $policy_terms = ['privacy', 'terms', 'refund', 'policy', 'disclaimer'];

    foreach (get_pages(['sort_column' => 'menu_order,post_title']) as $page) {
        if ((int) $page->ID === (int) get_option('page_on_front') || $page->post_name === 'sitemap') {
            continue;
        }

        $haystack = strtolower($page->post_title . ' ' . $page->post_name);
        if (array_filter($support_terms, static fn($term) => str_contains($haystack, $term))) {
            $support_pages[] = $page;
        } elseif (array_filter($policy_terms, static fn($term) => str_contains($haystack, $term))) {
            $policy_pages[] = $page;
        } else {
            $main_pages[] = $page;
        }
    }

    $columns[] = ['title' => 'Main Pages', 'links' => array_map(static fn($page) => [
        'title' => $page->post_title,
        'url' => get_permalink($page),
    ], $main_pages)];

    $product_links = [];
    if (function_exists('wc_get_products')) {
        foreach (wc_get_products(['status' => 'publish', 'limit' => 100, 'orderby' => 'menu_order title', 'order' => 'ASC']) as $product) {
            $product_links[] = ['title' => $product->get_name(), 'url' => $product->get_permalink()];
        }
    }
    if (!$product_links) {
        $product_links[] = ['title' => 'Shop All Products', 'url' => home_url('/shop/')];
    }
    $columns[] = ['title' => 'Armodafinil Products', 'links' => $product_links];

    $columns[] = ['title' => 'Help & Support', 'links' => array_map(static fn($page) => [
        'title' => $page->post_title,
        'url' => get_permalink($page),
    ], $support_pages)];

    $columns[] = ['title' => 'Policies & Disclaimers', 'links' => array_map(static fn($page) => [
        'title' => $page->post_title,
        'url' => get_permalink($page),
    ], $policy_pages)];
}

$delivery_links = [];
foreach (get_posts(['post_type' => 'city', 'post_status' => 'publish', 'posts_per_page' => 12, 'orderby' => 'menu_order title', 'order' => 'ASC']) as $city_page) {
    $delivery_links[] = ['title' => get_field('city_name', $city_page->ID) ?: $city_page->post_title, 'url' => get_permalink($city_page)];
}
if ($delivery_links) {
    $columns[] = ['title' => 'Australia Delivery', 'links' => $delivery_links];
}
?>

<main>
    <!-- 2-Tone Patterned Header -->
    <header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
        <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
            <!-- Breadcrumbs -->
            <div class="mb-3">
                <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                    <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                    <span class="text-sky-300 font-normal">/</span>
                    <span aria-current="page" class="font-extrabold text-[#0c4161]">Sitemap</span>
                </nav>
            </div>

            <!-- Eyebrow Badge -->
            <div class="mb-2.5">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                    Site Directory
                </span>
            </div>

            <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
                <?= esc_html($sitemap_title) ?>
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
                <?= esc_html($sitemap_intro) ?>
            </p>
        </div>
    </header>

    <section class="py-7 md:py-10 bg-gradient-to-b from-white via-slate-50/50 to-white">
        <div class="container-site grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($columns as $column): ?>
            <section class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs transition-all hover:border-sky-400 hover:shadow-md">
                <h2 class="mb-4 border-b border-slate-100 pb-3 font-heading text-base font-black text-[#0c4161] uppercase tracking-wider">
                    <?= esc_html($column['title']) ?>
                </h2>
                <?php if (!empty($column['links'])): ?>
                <ul class="space-y-2.5">
                    <?php foreach ($column['links'] as $link): ?>
                    <li>
                        <a href="<?= esc_url($link['url']) ?>" <?= !empty($link['target']) ? 'target="' . esc_attr($link['target']) . '" rel="noopener noreferrer"' : '' ?> class="group flex items-center gap-2 text-sm text-slate-600 hover:text-[#0c4161] font-medium transition-all">
                            <span class="h-1.5 w-1.5 rounded-full bg-sky-300 group-hover:bg-[#0c4161] transition-colors"></span>
                            <span class="group-hover:translate-x-0.5 transition-transform"><?= esc_html($link['title']) ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-xs text-slate-400">No links in this category.</p>
                <?php endif; ?>
            </section>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
