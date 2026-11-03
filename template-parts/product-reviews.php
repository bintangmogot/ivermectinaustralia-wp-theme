<?php
/**
 * Product reviews summary, review cards, and submission form.
 *
 * @var array $args Values passed by get_template_part().
 */

$args = isset($args) && is_array($args) ? $args : [];
// Every label comes from Product Presentation settings; {placeholders} are filled in below.
$t = static fn(string $key, array $values = []): string => ivermectin_fill(ivermectin_label('product_reviews_' . $key), $values);
$product_id = absint($args['product_id'] ?? get_the_ID());
$product_name = (string) ($args['product_name'] ?? get_the_title($product_id));
$reviews = get_posts([
    'post_type' => 'reviews',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => [[
        'key' => 'linked_product',
        'value' => $product_id,
        'compare' => '=',
    ]],
]);
$review_count = count($reviews);
$rating_count = 0;
$rating_total = 0;
$review_ratings = [];
$rating_counts = array_fill_keys([5, 4, 3, 2, 1], 0);

foreach ($reviews as $review) {
    $rating_value = function_exists('get_field')
        ? get_field('rating', $review->ID)
        : get_post_meta($review->ID, 'rating', true);
    $review_ratings[$review->ID] = $rating_value;

    if (is_numeric($rating_value) && (float) $rating_value >= 1 && (float) $rating_value <= 5) {
        $rating_counts[(int) round((float) $rating_value)]++;
        $rating_count++;
        $rating_total += (float) $rating_value;
    }
}

$average_rating = $rating_count > 0 ? round($rating_total / $rating_count, 1) : null;
$rounded_rating = $average_rating !== null ? (int) round($average_rating) : 0;

$rating_percentages = [];
foreach ($rating_counts as $star => $count) {
    $rating_percentages[$star] = $rating_count > 0 ? (int) round(($count / $rating_count) * 100) : 0;
}

$rating_label = $t('label_default');
if ($average_rating !== null) {
    if ((float) $average_rating >= 4.5) {
        $rating_label = $t('label_excellent');
    } elseif ((float) $average_rating >= 4.0) {
        $rating_label = $t('label_great');
    } elseif ((float) $average_rating >= 3.0) {
        $rating_label = $t('label_average');
    } else {
        $rating_label = $t('label_poor');
    }
}
?>

<section id="product-reviews" class="iva-section scroll-mt-24 bg-surface" data-testid="section-reviews">
    <div class="container-site max-w-6xl">
        <header class="mb-10 flex flex-col items-center justify-center text-center">
            <span class="iva-pill-tag mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-accent" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.2 6.8 1-4.9 4.8L18 21l-6-3.2L5.9 21l1.2-7-5-4.8 6.9-1L12 2Z"/></svg>
                <?= esc_html($t('eyebrow')) ?>
            </span>
            <h2 class="iva-h2 mb-2 text-primary"><?= esc_html($t('heading')) ?></h2>
            
        </header>

        <?php if ($review_count > 0 && $rating_count > 0): ?>
            <div class="mb-8 flex flex-col gap-8 rounded-[1.75rem] border border-border bg-white p-6 md:flex-row md:gap-16 md:p-10">
                <div class="flex shrink-0 flex-col justify-center">
                    <div class="mb-2 text-sm font-semibold text-slate-500"><?= esc_html($t('label_default')) ?></div>
                    <div class="mb-3 text-5xl font-extrabold text-slate-900"><?= esc_html(number_format((float) $average_rating, 1)) ?></div>
                    <div class="mb-3 flex gap-0.5" aria-label="<?= esc_attr($t('stars_aria', ['rating' => number_format((float) $average_rating, 1)])) ?>">
                        <?php for ($star = 1; $star <= 5; $star++): ?>
                            <span class="flex h-6 w-6 items-center justify-center rounded-sm <?= $star <= $rounded_rating ? 'bg-primary' : 'bg-slate-200' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.2 6.8 1-4.9 4.8L18 21l-6-3.2L5.9 21l1.2-7-5-4.8 6.9-1L12 2Z"/></svg>
                            </span>
                        <?php endfor; ?>
                    </div>
                    <div class="mb-1 font-bold text-slate-900"><?= esc_html($rating_label) ?></div>
                    <div class="text-xs font-medium text-slate-500"><?= esc_html($t('based_on', ['count' => number_format_i18n($review_count)])) ?></div>
                </div>

                <div class="flex w-full max-w-lg flex-1 flex-col justify-center gap-3">
                    <?php foreach ([5, 4, 3, 2, 1] as $star): ?>
                        <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
                            <span class="w-10 whitespace-nowrap"><?= esc_html($t('star_bar_label', ['star' => $star])) ?></span>
                            <span class="h-2.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                                <span class="block h-full rounded-full bg-primary" style="width: <?= esc_attr($rating_percentages[$star]) ?>%"></span>
                            </span>
                            <span class="w-10 text-right"><?= esc_html($rating_percentages[$star]) ?>%</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($review_count > 1): ?>
            <div class="mb-4 flex justify-end gap-2 pr-2">
                <button type="button" class="iva-review-prev flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition-colors hover:border-primary-light hover:text-primary-dark" aria-label="<?= esc_attr($t('prev_label')) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                </button>
                <button type="button" class="iva-review-next flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition-colors hover:border-primary-light hover:text-primary-dark" aria-label="<?= esc_attr($t('next_label')) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        <?php endif; ?>

        <?php if ($reviews): ?>
            <div class="iva-review-slider mb-8 flex snap-x snap-mandatory gap-6 overflow-x-auto pb-8" aria-label="<?= esc_attr($t('slider_label')) ?>">
                <?php foreach ($reviews as $index => $review):
                    $post_id = $review->ID;
                    $title = get_the_title($post_id);
                    $body = get_post_field('post_content', $post_id);
                    $reviewer = function_exists('get_field') ? get_field('name', $post_id) : '';
                    $reviewer = $reviewer ?: $review->post_title;
                    $reviewer_meta = function_exists('get_field') ? get_field('reviewer_meta', $post_id) : '';
                    $rating_value = $review_ratings[$post_id] ?? null;
                    $name_parts = preg_split('/\s+/', trim((string) $reviewer)) ?: [];
                    $first_initial = !empty($name_parts[0])
                        ? (function_exists('mb_substr') ? mb_substr($name_parts[0], 0, 1) : substr($name_parts[0], 0, 1))
                        : 'W';
                    $last_name = count($name_parts) > 1 ? end($name_parts) : '';
                    $last_initial = $last_name !== ''
                        ? (function_exists('mb_substr') ? mb_substr($last_name, 0, 1) : substr($last_name, 0, 1))
                        : '';
                    $initials = strtoupper($first_initial . $last_initial);
                    $is_verified = !$reviewer_meta || stripos((string) $reviewer_meta, 'verified') !== false;
                ?>
                    <article class="w-full shrink-0 snap-start rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:w-[350px]" data-testid="review-<?= esc_attr($index) ?>">
                        <?php if (is_numeric($rating_value) && (float) $rating_value >= 1 && (float) $rating_value <= 5): ?>
                            <div class="mb-4 flex gap-0.5" aria-label="<?= esc_attr($t('stars_aria', ['rating' => $rating_value])) ?>">
                                <?php for ($star = 1; $star <= 5; $star++): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?= $star <= round((float) $rating_value) ? 'text-accent' : 'text-slate-200' ?>" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.2 6.8 1-4.9 4.8L18 21l-6-3.2L5.9 21l1.2-7-5-4.8 6.9-1L12 2Z"/></svg>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>

                        <h3 class="mb-2 text-[15px] font-bold leading-snug text-slate-900"><?= esc_html($title) ?></h3>
                        <div class="mb-6 line-clamp-4 flex-1 text-[13px] leading-relaxed text-slate-600"><?= wp_kses_post($body) ?></div>

                        <div class="mt-auto flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-softer text-[11px] font-bold uppercase tracking-wider text-primary-dark"><?= esc_html($initials) ?></span>
                                <span class="flex min-w-0 flex-col">
                                    <span class="truncate text-[13px] font-bold leading-tight text-slate-900"><?= esc_html($reviewer) ?></span>
                                    <span class="mt-0.5 text-[10px] font-medium text-slate-400"><?= esc_html(get_the_date('j F Y', $post_id)) ?></span>
                                </span>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1 rounded bg-primary-softer px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-primary-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                <?= esc_html($is_verified ? $t('badge_verified') : $t('badge_review')) ?>
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="mb-8 rounded-2xl border border-slate-300 bg-white p-8 text-center md:p-12">
                <p class="font-medium text-slate-500"><?= esc_html(ivermectin_get_product_presentation_option('product_no_reviews_text')) ?></p>
            </div>
        <?php endif; ?>

        <div class="relative mx-auto mt-8 max-w-2xl overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-5 flex items-center gap-3 border-b border-slate-100 pb-5">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-softer text-primary-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </span>
                <h3 class="font-bold text-slate-900"><?= esc_html($t('form_heading')) ?> <span class="font-normal text-slate-400"><?= esc_html($t('form_for', ['product' => $product_name])) ?></span></h3>
            </div>

            <form id="iva-product-review-form" class="space-y-4" data-endpoint="<?= esc_url(admin_url('admin-ajax.php')) ?>" data-messages="<?= esc_attr(wp_json_encode(['pickRating' => $t('msg_pick_rating'), 'submitting' => $t('msg_submitting'), 'failed' => $t('msg_failed'), 'network' => $t('msg_network')])) ?>">
                <input type="hidden" name="action" value="ivermectin_submit_product_review">
                <input type="hidden" name="product_id" value="<?= esc_attr($product_id) ?>">
                <input type="hidden" name="rating" value="0" required>
                <?php wp_nonce_field('ivermectin_submit_product_review', 'review_nonce'); ?>

                <div>
                    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-slate-700"><?= esc_html($t('form_rating_label')) ?></label>
                    <div class="flex items-center gap-1" role="group" aria-label="<?= esc_attr($t('form_rating_aria')) ?>">
                        <?php for ($star = 1; $star <= 5; $star++): ?>
                            <button type="button" class="iva-review-star text-slate-300 transition-colors hover:text-accent" data-rating="<?= esc_attr($star) ?>" aria-label="<?= esc_attr($t('form_star_aria', ['star' => $star])) ?>" aria-pressed="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.2 6.8 1-4.9 4.8L18 21l-6-3.2L5.9 21l1.2-7-5-4.8 6.9-1L12 2Z"/></svg>
                            </button>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700"><?= esc_html($t('form_name_label')) ?>
                        <input type="text" name="reviewer_name" required autocomplete="name" placeholder="<?= esc_attr($t('form_name_placeholder')) ?>" class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-normal normal-case tracking-normal text-slate-900 placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700"><?= esc_html($t('form_email_label')) ?>
                        <input type="email" name="reviewer_email" required autocomplete="email" placeholder="<?= esc_attr($t('form_email_placeholder')) ?>" class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-normal normal-case tracking-normal text-slate-900 placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </label>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700"><?= esc_html($t('form_title_label')) ?>
                        <input type="text" name="review_title" required placeholder="<?= esc_attr($t('form_title_placeholder')) ?>" class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-normal normal-case tracking-normal text-slate-900 placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </label>
                </div>

                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700"><?= esc_html($t('form_review_label')) ?>
                    <textarea name="review_content" required rows="4" placeholder="<?= esc_attr($t('form_review_placeholder')) ?>" class="mt-1.5 w-full resize-y rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-normal normal-case tracking-normal text-slate-900 placeholder:text-slate-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                </label>

                <div class="flex flex-col items-center justify-between gap-4 pt-2 sm:flex-row">
                    <p class="w-full text-left text-[10px] text-slate-400 sm:w-auto"><?= esc_html($t('form_required_note')) ?></p>
                    <div class="flex w-full flex-col items-end sm:w-auto">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 sm:px-8 sm:py-3.5 text-xs sm:text-sm font-bold text-primary-foreground transition-colors hover:bg-primary-dark focus:outline-none focus:ring-4 focus:ring-primary/20 disabled:cursor-wait disabled:opacity-70 sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7ZM22 2 11 13"/></svg>
                            <?= esc_html($t('form_submit')) ?>
                        </button>
                        <span class="mt-2 text-[10px] font-medium text-slate-400"><?= esc_html($t('form_moderated')) ?></span>
                    </div>
                </div>

                <div id="iva-review-form-message" class="hidden rounded-lg p-4 text-sm font-bold" role="status" aria-live="polite"></div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const section = document.querySelector('[data-testid="section-reviews"]');
    if (!section) return;

    const slider = section.querySelector('.iva-review-slider');
    const firstCard = slider ? slider.querySelector('article') : null;
    const slideBy = firstCard ? firstCard.getBoundingClientRect().width + 24 : 374;

    section.querySelector('.iva-review-prev')?.addEventListener('click', function () {
        slider?.scrollBy({ left: -slideBy, behavior: 'smooth' });
    });
    section.querySelector('.iva-review-next')?.addEventListener('click', function () {
        slider?.scrollBy({ left: slideBy, behavior: 'smooth' });
    });

    const form = section.querySelector('#iva-product-review-form');
    if (!form) return;
    const messages = JSON.parse(form.dataset.messages || '{}');

    const ratingInput = form.querySelector('[name="rating"]');
    const stars = Array.from(form.querySelectorAll('.iva-review-star'));
    const message = form.querySelector('#iva-review-form-message');

    function setRating(value) {
        ratingInput.value = value;
        stars.forEach(function (star) {
            const isActive = Number(star.dataset.rating) <= Number(value);
            star.classList.toggle('text-accent', isActive);
            star.classList.toggle('text-slate-300', !isActive);
            star.setAttribute('aria-pressed', isActive && Number(star.dataset.rating) === Number(value) ? 'true' : 'false');
        });
    }

    stars.forEach(function (star) {
        star.addEventListener('click', function () {
            setRating(star.dataset.rating);
        });
        star.addEventListener('mouseenter', function () {
            stars.forEach(function (item) {
                item.classList.toggle('text-accent', Number(item.dataset.rating) <= Number(star.dataset.rating));
            });
        });
        star.addEventListener('mouseleave', function () {
            setRating(ratingInput.value);
        });
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        message.classList.add('hidden');

        if (!Number(ratingInput.value)) {
            message.textContent = messages.pickRating;
            message.className = 'rounded-lg bg-red-50 p-4 text-sm font-bold text-red-700';
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const originalButton = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.textContent = messages.submitting;

        try {
            const response = await fetch(form.dataset.endpoint, {
                method: 'POST',
                credentials: 'same-origin',
                body: new FormData(form),
            });
            const result = await response.json();
            const success = Boolean(result.success);
            message.textContent = success
                ? result.data.message
                : (result.data?.message || messages.failed);
            message.className = success
                ? 'rounded-lg bg-primary-softer p-4 text-sm font-bold text-primary-dark'
                : 'rounded-lg bg-red-50 p-4 text-sm font-bold text-red-700';

            if (success) {
                form.reset();
                setRating(0);
            }
        } catch (error) {
            message.textContent = messages.network;
            message.className = 'rounded-lg bg-red-50 p-4 text-sm font-bold text-red-700';
        } finally {
            submitButton.innerHTML = originalButton;
            submitButton.disabled = false;
        }
    });
});
</script>
