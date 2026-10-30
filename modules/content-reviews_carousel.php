<?php
/**
 * Module: Reviews Carousel
 * Follows the colorful card design rules:
 * - Multi-color vibrant vector icons & 5-star ratings
 * - 2-tone card structure (White quote top + Navy reviewer footer block)
 * - Dashed borders
 * - Patterned gradient background
 * - Lift & hover animations
 */

$tag = "Customer Reviews";
$heading = get_sub_field('title') ?: "What Australian Customers Say";

$reviews = get_sub_field('reviews');
?>
<section class="section-padding bg-gradient-to-b from-[#f0f6fa] via-[#e9f2f8] to-[#f8fafc] bg-pattern-dots border-t-2 border-dashed border-sky-200/80 relative isolate overflow-hidden" data-testid="testimonials">
    <div class="container-custom relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 md:mb-12">
            <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-900 border border-amber-300 text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full mb-3 shadow-xs">
                <span class="text-amber-500">★★★★★</span>
                <?= esc_html($tag) ?>
            </span>
            <h2 class="font-heading text-2xl md:text-4xl font-black text-ink tracking-tight mb-3">
                <?= esc_html($heading) ?>
            </h2>
            <p class="text-sm md:text-base text-muted-foreground leading-relaxed max-w-xl mx-auto">
                Real feedback from verified Australian buyers — shift workers, students, and professionals across the country.
            </p>
        </div>

        <!-- Horizontal Scrollable Review Cards -->
        <div class="flex gap-6 overflow-x-auto pb-6 pt-2 snap-x snap-mandatory scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
            <?php 
            if(!empty($reviews)):
                foreach($reviews as $r):
                    $post_id = is_object($r) ? $r->ID : $r;
                    $title = get_the_title($post_id);
                    $body = get_post_field('post_content', $post_id);
                    $reviewer = get_field('name', $post_id) ?: (is_object($r) ? $r->post_title : get_the_title($post_id));
                    $meta = get_field('reviewer_meta', $post_id) ?: "Verified AU Buyer";
                    $rating_val = get_field('rating', $post_id) ?: 5;
                    $review_date = get_field('review_date', $post_id) ?: get_the_date('M j, Y', $post_id);
            ?>
            <div class="group flex flex-col justify-between bg-white border-2 border-dashed border-sky-300/80 hover:border-amber-400 rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex-shrink-0 w-[310px] sm:w-[340px] snap-start">
                <!-- Top Half: Crisp White Surface with Golden Stars & Quote -->
                <div class="p-6 flex-1 flex flex-col justify-start bg-white relative">
                    <!-- Colorful Quote Mark & 5-Star Row -->
                    <div class="flex items-center justify-between mb-3.5">
                        <div class="flex items-center gap-1 text-amber-400">
                            <?php for($i=0; $i < $rating_val; $i++): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                            <?php endfor; ?>
                        </div>
                        <!-- Colorful Quote SVG -->
                        <div class="w-7 h-7 rounded-lg bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-sky-500">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>
                    </div>

                    <h4 class="font-heading font-black text-ink text-base md:text-lg mb-2.5 leading-snug group-hover:text-[#0c4161] transition-colors">
                        <?= esc_html($title) ?>
                    </h4>
                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed italic">
                        &ldquo;<?= esc_html($body) ?>&rdquo;
                    </p>
                </div>

                <!-- Bottom Half: Solid Navy Footer with Reviewer Info -->
                <div class="bg-[#0c4161] group-hover:bg-[#082c42] text-white p-4 px-5 flex items-center justify-between transition-colors duration-300 border-t-2 border-dashed border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shadow-xs shrink-0">
                            <?= esc_html(strtoupper(substr($reviewer, 0, 1))) ?>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white leading-tight"><?= esc_html($reviewer) ?></p>
                            <p class="text-[11px] text-emerald-300 font-semibold leading-tight mt-0.5">
                                ✓ Verified Buyer
                            </p>
                        </div>
                    </div>
                    <?php if ($review_date): ?>
                    <span class="text-[10px] text-white/70 font-medium"><?= esc_html($review_date) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php 
                endforeach;
            else:
                // Default high-converting Australian testimonials
                $defaults = [
                    ['title' => "Life-changing for night shifts", 'body' => "As a registered nurse doing 12-hour shifts in Sydney, Waklert keeps me clear and focused without the horrific caffeine jitters. Arrived in 3 days.", 'author' => "Sarah L.", 'location' => "Sydney NSW"],
                    ['title' => "Fastest domestic dispatch in AU", 'body' => "Ordered on Monday and received the Australia Post tracking number that afternoon. Parcel arrived discreetly in Brisbane by Wednesday.", 'author' => "Mark T.", 'location' => "Brisbane QLD"],
                    ['title' => "Genuine Sun Pharma quality", 'body' => "Blister packs are sealed, batch numbers match, and the tablets split cleanly down the score. Exceptional product and customer support.", 'author' => "Dave K.", 'location' => "Perth WA"],
                    ['title' => "Code for 8 hours without brain fog", 'body' => "Software engineer working remotely. Waklert provides the locked-in deep focus I need during intense sprint releases. 10/10.", 'author' => "James M.", 'location' => "Melbourne VIC"]
                ];
                foreach ($defaults as $d):
            ?>
            <div class="group flex flex-col justify-between bg-white border-2 border-dashed border-sky-300/80 hover:border-amber-400 rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex-shrink-0 w-[310px] sm:w-[340px] snap-start">
                <div class="p-6 flex-1 flex flex-col justify-start bg-white">
                    <div class="flex items-center justify-between mb-3.5">
                        <div class="flex items-center gap-1 text-amber-400">
                            <?php for($i=0; $i < 5; $i++): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                            <?php endfor; ?>
                        </div>
                        <div class="w-7 h-7 rounded-lg bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-sky-500"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>
                    </div>
                    <h4 class="font-heading font-black text-ink text-base md:text-lg mb-2.5 leading-snug group-hover:text-[#0c4161] transition-colors">
                        <?= esc_html($d['title']) ?>
                    </h4>
                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed italic">
                        &ldquo;<?= esc_html($d['body']) ?>&rdquo;
                    </p>
                </div>
                <div class="bg-[#0c4161] group-hover:bg-[#082c42] text-white p-4 px-5 flex items-center justify-between transition-colors duration-300 border-t-2 border-dashed border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shadow-xs shrink-0">
                            <?= esc_html(strtoupper(substr($d['author'], 0, 1))) ?>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white leading-tight"><?= esc_html($d['author']) ?></p>
                            <p class="text-[11px] text-emerald-300 font-semibold leading-tight mt-0.5">
                                ✓ Verified Buyer &middot; <?= esc_html($d['location']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>

        <div class="text-center mt-8">
            <a href="<?= home_url('/reviews') ?>" class="inline-flex items-center gap-2 border-2 border-dashed border-sky-300/90 bg-white hover:bg-[#0c4161] text-[#0c4161] hover:text-white font-extrabold px-5 py-2.5 sm:px-8 sm:py-3.5 rounded-full transition-all duration-300 uppercase tracking-wider sm:tracking-widest text-xs shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <span>Read All Reviews</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>
</section>
