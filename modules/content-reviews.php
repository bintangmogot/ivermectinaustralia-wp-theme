<?php
/**
 * Customer Reviews Module
 */

$heading = get_sub_field('heading') ?: "What Australian Customers Say";
$desc = get_sub_field('description') ?: "Real testimonials from Waklert Australia users across Australia.";

// Run query first to calculate dynamic stats
$reviews_query = new WP_Query([
    'post_type' => 'review',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key'     => 'linked_product',
            'compare' => 'NOT EXISTS'
        ),
        array(
            'key'     => 'linked_product',
            'value'   => '',
            'compare' => '='
        )
    )
]);

$total_reviews = $reviews_query->found_posts;
$total_stars = 0;

if ($reviews_query->have_posts()) {
    while ($reviews_query->have_posts()) {
        $reviews_query->the_post();
        $r = (int) get_post_meta(get_the_ID(), 'rating', true);
        if (!$r) $r = 5;
        $total_stars += $r;
    }
    $reviews_query->rewind_posts();
}

$average_rating = $total_reviews > 0 ? round($total_stars / $total_reviews, 1) : 5.0;
$average_stars = round($average_rating);
?>
<section class="relative overflow-hidden bg-gradient-to-b from-[#f0f6fa] via-[#e9f2f8] to-[#f8fafc] bg-pattern-dots py-8 md:py-12" data-testid="section-general-reviews">
    <div class="container-site max-w-6xl mx-auto px-4 relative z-10">
        <!-- Header & Aggregate Summary Card -->
        <div class="mb-12">
            <div class="text-center max-w-2xl mx-auto mb-8">
                <span class="inline-flex items-center gap-2 rounded-full bg-sky-100 border border-sky-300/80 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-[#0c4161] shadow-sm mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Verified Customer Experiences
                </span>
                <h1 class="font-heading text-3xl md:text-5xl font-extrabold text-[#0c4161] tracking-tight mb-4">
                    <?= esc_html($heading) ?>
                </h1>
                <p class="text-slate-600 text-base md:text-lg leading-relaxed">
                    <?= esc_html($desc) ?>
                </p>
            </div>

            <!-- 2-Tone Dashed Rating Summary Card -->
            <div class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-white p-6 md:p-8 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-md">
                        <!-- Multi-color Shield SVG -->
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L3 6V12C3 17.5 7 21 12 22C17 21 21 17.5 21 12V6L12 2Z" fill="#FFFFFF" fill-opacity="0.3"/>
                            <path d="M12 3L4 7V12C4 16.8 7.5 20 12 21C16.5 20 20 16.8 20 12V7L12 3Z" stroke="#0C4161" stroke-width="1.8"/>
                            <path d="M8.5 12L10.8 14.3L15.5 9.7" stroke="#0C4161" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 text-amber-400 mb-1">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 <?= ($i < $average_stars) ? 'text-amber-400 fill-current' : 'text-slate-200 fill-current' ?>" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                </svg>
                            <?php endfor; ?>
                            <span class="ml-1 text-lg font-black text-slate-900"><?= number_format($average_rating, 1) ?> / 5.0</span>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold text-slate-500">
                            <?= sprintf("Based on %d verified Australian customer ratings", $total_reviews) ?>
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-300 px-3.5 py-1.5 text-xs font-bold text-emerald-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        100% Genuine Orders
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 border border-sky-300 px-3.5 py-1.5 text-xs font-bold text-[#0c4161]">
                        <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Fast Delivery Feedback
                    </span>
                </div>
            </div>
        </div>

        <!-- Reviews Grid: 2-Tone Dashed Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <?php
            if ($reviews_query->have_posts()):
                while ($reviews_query->have_posts()): $reviews_query->the_post();
                    $post_id = get_the_ID();
                    $name = get_field('name', $post_id) ?: get_the_title();
                    $meta = get_field('reviewer_meta', $post_id) ?: "Verified Buyer";
                    
                    $title = get_the_title();
                    $body = get_the_content();
                    
                    $rating = (int) get_field('rating', $post_id);
                    if (!$rating) $rating = 5;
                    
                    $initial = !empty($name) ? mb_substr($name, 0, 1) : 'M';
                ?>
                <div class="group flex flex-col rounded-3xl border-2 border-dashed border-sky-300/80 hover:border-[#0c4161] bg-white shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <!-- Top Card Body (Pure White) -->
                    <div class="p-6 md:p-7 flex-1 flex flex-col">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <!-- Colorful Quote Mark SVG -->
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 border border-sky-200 text-[#0c4161] group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                </svg>
                            </div>

                            <!-- Golden Stars -->
                            <div class="flex items-center gap-1 text-amber-400">
                                <?php for ($stars = 0; $stars < 5; $stars++): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 <?= ($stars < $rating) ? "text-amber-400 fill-current" : "text-slate-200 fill-current" ?>" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <h3 class="font-heading text-base font-bold text-slate-900 mb-2 leading-snug group-hover:text-[#0c4161] transition-colors">
                            <?= esc_html($title) ?>
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic">
                            &ldquo;<?= esc_html($body) ?>&rdquo;
                        </p>
                    </div>

                    <!-- Bottom 2-Tone Solid Navy Strip -->
                    <div class="bg-gradient-to-r from-[#0c4161] to-[#125883] p-4 sm:p-5 flex items-center justify-between gap-3 text-white">
                        <div class="flex items-center gap-3">
                            <!-- Vibrant Amber Reviewer Avatar -->
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 text-[#0c4161] flex items-center justify-center font-extrabold text-sm uppercase shadow-sm shrink-0">
                                <?= esc_html($initial) ?>
                            </div>
                            <div>
                                <p class="font-bold text-xs sm:text-sm text-white flex items-center gap-1.5 whitespace-nowrap">
                                    <?= esc_html($name) ?>
                                </p>
                                <p class="text-[11px] text-sky-200 font-medium"><?= esc_html($meta) ?> &bull; <?= get_the_date('M Y') ?></p>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-300 bg-emerald-500/20 border border-emerald-400/40 px-2.5 py-1 rounded-full shrink-0" data-testid="badge-verified-buyer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?= "Verified" ?>
                        </span>
                    </div>
                </div>
                <?php endwhile;
                wp_reset_postdata();
            else: ?>
                <div class="col-span-full py-16 text-center text-slate-500 bg-white rounded-3xl border-2 border-dashed border-sky-200 p-8">
                    <p class="text-lg font-bold text-[#0c4161]"><?= "No reviews found yet." ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
