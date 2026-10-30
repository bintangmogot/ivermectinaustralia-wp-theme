<?php
/**
 * Module: Page Hero
 *
 * The posts index uses a centered editorial hero. Other page_hero placements
 * retain the standard theme hero and their optional backend bullet list.
 */
$title = get_sub_field('title') ?: get_the_title();
$subtitle = get_sub_field('subtitle');
$eyebrow = get_sub_field('eyebrow') ?: 'Waklert Australia Insights';
$bullets = get_sub_field('bullets');

if (is_home()) {
    // The posts index can inherit the first article as the global post title.
    // Use a clear archive heading whenever the editor has not supplied one.
    $title = get_sub_field('title') ?: 'Clear guides to armodafinil in Australia';
    $subtitle = get_sub_field('subtitle') ?: 'Browse straightforward articles about product information, ordering and delivery. For advice about your health or medicines, speak with an Australian-registered health professional.';
    $eyebrow = get_sub_field('eyebrow') ?: 'Waklert Australia Insights';
}

if (is_shop()) {
    $shop_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : 0;
    // Allow custom title/subtitle/eyebrow from ACF or fallback to shop page title
    $title = get_sub_field('title') ?: ($shop_page_id ? get_the_title($shop_page_id) : 'Armodafinil Products');
    $subtitle = get_sub_field('subtitle') ?: 'Browse our current range of genuine Armodafinil tablets with fast delivery across Australia.';
    $eyebrow = get_sub_field('eyebrow') ?: 'Full catalogue';
}

if (is_home()) :
?>
<section class="relative overflow-hidden border-b-2 border-dashed border-sky-200/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-foreground" data-testid="blog-hero">
    <div class="container-site relative z-10 py-7 md:py-10 lg:py-12">
        <div class="grid gap-7 border-b-2 border-dashed border-sky-200/80 pb-8 md:grid-cols-[1.1fr_.9fr] md:items-end md:gap-12 md:pb-10">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-sky-100 border border-sky-300/80 px-3.5 py-1 text-xs font-extrabold uppercase tracking-[0.18em] text-[#0c4161] shadow-sm">
                    <span aria-hidden="true" class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <?php echo esc_html($eyebrow); ?>
                </span>
                <h1 class="mt-4 max-w-3xl font-heading text-4xl font-extrabold leading-[1.05] tracking-tight text-[#0c4161] md:text-5xl lg:text-6xl">
                    <?php echo esc_html($title); ?>
                </h1>
            </div>
            <div class="max-w-2xl md:justify-self-end">
                <?php if ($subtitle) : ?>
                    <p class="text-base leading-relaxed text-slate-600 md:text-lg">
                        <?php echo esc_html($subtitle); ?>
                    </p>
                <?php endif; ?>
                <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-3">
                    <a href="#blog-articles" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-2.5 text-sm font-extrabold uppercase tracking-wide text-white shadow-md transition-all hover:from-amber-600 hover:to-amber-700 hover:shadow-lg hover:-translate-y-0.5">
                        Browse articles
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                    <a href="<?php echo esc_url(home_url('/faq/')); ?>" class="text-sm font-bold text-[#0c4161] transition hover:text-amber-600">
                        Read the FAQs &rarr;
                    </a>
                </div>
            </div>
        </div>

        <?php
        $blog_topics = [
            ['Product Information', 'Dosages, effects & user guides', 'text-sky-600'],
            ['Ordering & Delivery', 'Express shipping & AU tracking', 'text-amber-600'],
            ['Australian Context', 'Domestic insights & safe storage', 'text-emerald-600'],
        ];
        ?>
        <div class="grid gap-4 py-8 sm:grid-cols-3 sm:gap-6">
            <?php foreach ($blog_topics as $index => $topic) : ?>
                <div class="group rounded-2xl border-2 border-dashed border-sky-300/80 bg-white p-5 shadow-sm hover:border-[#0c4161] hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-xs font-black text-[#0c4161] border border-sky-200">
                            <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                        </span>
                        <h2 class="font-heading font-bold text-slate-900 group-hover:text-[#0c4161] transition-colors"><?php echo esc_html($topic[0]); ?></h2>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed"><?php echo esc_html($topic[1]); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="pt-2 text-xs leading-relaxed text-slate-500">Articles do not replace advice from a qualified health professional.</p>
    </div>
</section>
<?php elseif (is_shop()) : ?>
<section class="relative isolate overflow-hidden bg-gradient-to-br from-[#082a3f] via-[#0c4161] to-[#125883] bg-pattern-dots-dark text-white text-center border-b-2 border-dashed border-sky-400/30" data-testid="shop-hero">
    <?php waklert_output_pattern_rings(); ?>
    <div class="container-site relative z-10 mx-auto max-w-4xl py-8 md:py-12 px-4">
        <!-- Eyebrow Badge -->
        <span class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/15 px-4 py-1.5 text-xs font-extrabold uppercase tracking-widest text-amber-300 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            <?php echo esc_html($eyebrow); ?>
        </span>

        <!-- Main Title -->
        <h1 class="mx-auto mt-4 font-heading text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
            <?php echo esc_html($title); ?>
        </h1>

        <!-- Subtitle -->
        <?php if ($subtitle) : ?>
            <p class="mx-auto mt-4 max-w-2xl text-base sm:text-lg leading-relaxed text-sky-100/90 font-medium">
                <?php echo esc_html($subtitle); ?>
            </p>
        <?php endif; ?>

        <!-- 3 Two-Tone Dashed Micro Trust Cards -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3.5 max-w-3xl mx-auto text-left">
            <!-- Trust Card 1 -->
            <div class="rounded-2xl border-2 border-dashed border-sky-300/40 bg-white/10 p-3.5 backdrop-blur-sm flex items-center gap-3 hover:bg-white/15 transition-all">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-400/20 text-amber-300 border border-amber-300/40">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L4 6v6c0 5.25 3.4 10.15 8 11.35 4.6-1.2 8-6.1 8-11.35V6l-8-4z" fill="#F59E0B" fill-opacity="0.3" stroke="#F59E0B" stroke-width="1.8"/>
                        <path d="M9 12l2 2 4-4" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white leading-snug">100% Genuine</h4>
                    <p class="text-[11px] text-sky-200">Sun Pharma certified batches</p>
                </div>
            </div>

            <!-- Trust Card 2 -->
            <div class="rounded-2xl border-2 border-dashed border-sky-300/40 bg-white/10 p-3.5 backdrop-blur-sm flex items-center gap-3 hover:bg-white/15 transition-all">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-400/20 text-sky-300 border border-sky-300/40">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="1" y="5" width="14" height="11" rx="1.5" fill="#38BDF8" fill-opacity="0.3" stroke="#38BDF8" stroke-width="1.8"/>
                        <path d="M15 8h4.5l3.5 4.5v3.5h-8V8z" fill="#F59E0B" stroke="#F59E0B" stroke-width="1.8"/>
                        <circle cx="6" cy="18" r="2.2" fill="#FFFFFF" stroke="#38BDF8" stroke-width="1.5"/>
                        <circle cx="18" cy="18" r="2.2" fill="#FFFFFF" stroke="#F59E0B" stroke-width="1.5"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white leading-snug">Express AU Post</h4>
                    <p class="text-[11px] text-sky-200">Fast Australia-wide dispatch</p>
                </div>
            </div>

            <!-- Trust Card 3 -->
            <div class="rounded-2xl border-2 border-dashed border-sky-300/40 bg-white/10 p-3.5 backdrop-blur-sm flex items-center gap-3 hover:bg-white/15 transition-all">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-400/20 text-emerald-300 border border-emerald-300/40">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="11" width="18" height="11" rx="2" fill="#34D399" fill-opacity="0.3" stroke="#34D399" stroke-width="1.8"/>
                        <path d="M7 11V7a5 5 0 0110 0v4" stroke="#FFFFFF" stroke-width="1.8"/>
                        <circle cx="12" cy="16" r="1.5" fill="#34D399"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white leading-snug">Discreet Parcel</h4>
                    <p class="text-[11px] text-sky-200">100% plain packaging</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else : ?>
<section class="relative isolate overflow-hidden bg-gradient-to-br from-[#082a3f] via-[#0c4161] to-[#125883] bg-pattern-dots-dark text-white border-b-2 border-dashed border-sky-400/30" data-testid="page-hero">
    <?php waklert_output_pattern_rings(); ?>
    <div class="container-site relative z-10 py-8 md:py-12">
        <h1 class="font-heading text-3xl font-extrabold tracking-tight md:text-[2.75rem] md:leading-[1.1] text-white">
            <?php echo esc_html($title); ?>
        </h1>

        <?php if ($subtitle) : ?>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-sky-100/90 font-medium">
                <?php echo esc_html($subtitle); ?>
            </p>
        <?php endif; ?>

        <?php if ($bullets) : ?>
            <ul class="mt-6 flex flex-wrap gap-3">
                <?php foreach ($bullets as $bullet) :
                    if (empty($bullet['bullet'])) continue;
                ?>
                    <li class="inline-flex items-center gap-2 rounded-full border border-dashed border-sky-300/40 bg-white/10 px-4 py-1.5 text-xs sm:text-sm font-semibold text-white backdrop-blur-sm hover:bg-white/20 transition-all">
                        <svg aria-hidden="true" class="h-4 w-4 text-amber-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span><?php echo esc_html($bullet['bullet']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
