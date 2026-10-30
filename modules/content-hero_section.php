<?php
/**
 * Module: Hero Section
 * Waklert Australia editorial storefront introduction.
 * Features Australian tradie holding Waklert 150 product box.
 * Responsive right-anchor layout with seamless desktop blend.
 * 100% dynamic: admin can customize desktop & mobile images via ACF Pro module,
 * with ultra-optimized WebP fallbacks (35KB desktop, 56KB mobile) for instant loading.
 */

$location      = get_sub_field('location_text') ?: 'Australia-wide delivery';
$heading       = get_sub_field('heading') ?: 'Waklert in Australia, delivered on-time & hassle-free.';
$subtitle      = get_sub_field('subtitle') ?: 'Waklert Australia — Generic Armodafinil';
$description   = get_sub_field('description') ?: 'Waklert Australia supplies genuine, high-grade Armodafinil, including Sun Pharma’s Waklert 150mg and 50mg, without the standard pharmacy markups. Every order is packed discreetly and shipped with full Australia Post tracking to Sydney, Melbourne, Brisbane, Perth, Adelaide, and regional towns nationwide, with quick AUD checkout via PayID and direct EFT.';
$primary_btn   = get_sub_field('primary_button_text') ?: 'View Products';
$primary_link  = get_sub_field('primary_button_link') ?: wc_get_page_permalink('shop');
$secondary_btn = trim((string) get_sub_field('secondary_button_text'));
$secondary_link = trim((string) get_sub_field('secondary_button_link'));

// -----------------------------------------------------------------------------
// Desktop Hero Image Handling
// -----------------------------------------------------------------------------
$desktop_img           = get_sub_field('background_image') ?: get_sub_field('hero_image');
$desktop_attachment_id = 0;
$desktop_img_url       = '';
$desktop_alt           = $heading;

if (is_array($desktop_img)) {
    $desktop_attachment_id = !empty($desktop_img['ID']) ? (int) $desktop_img['ID'] : 0;
    $desktop_img_url       = $desktop_img['url'] ?? '';
    if (!empty($desktop_img['alt'])) {
        $desktop_alt = $desktop_img['alt'];
    }
} elseif (is_numeric($desktop_img) && (int) $desktop_img > 0) {
    $desktop_attachment_id = (int) $desktop_img;
    $desktop_img_url       = wp_get_attachment_image_url($desktop_attachment_id, 'full') ?: '';
    $alt = get_post_meta($desktop_attachment_id, '_wp_attachment_image_alt', true);
    if (!empty($alt)) {
        $desktop_alt = $alt;
    }
} elseif (is_string($desktop_img) && $desktop_img !== '') {
    $desktop_img_url = $desktop_img;
}

$is_custom_desktop = !empty($desktop_img_url) && !str_contains($desktop_img_url, 'kl-skyline');
if (!$is_custom_desktop) {
    $desktop_webp_url = get_theme_file_uri('assets/images/hero-tradie-v2.webp');
    $desktop_png_url  = get_theme_file_uri('assets/images/hero-tradie-v2.png');
}

// -----------------------------------------------------------------------------
// Mobile Hero Image Handling
// -----------------------------------------------------------------------------
$mobile_img           = get_sub_field('mobile_image') ?: get_sub_field('hero_image_mobile');
$mobile_attachment_id = 0;
$mobile_img_url       = '';
$mobile_alt           = $heading;

if (is_array($mobile_img)) {
    $mobile_attachment_id = !empty($mobile_img['ID']) ? (int) $mobile_img['ID'] : 0;
    $mobile_img_url       = $mobile_img['url'] ?? '';
    if (!empty($mobile_img['alt'])) {
        $mobile_alt = $mobile_img['alt'];
    }
} elseif (is_numeric($mobile_img) && (int) $mobile_img > 0) {
    $mobile_attachment_id = (int) $mobile_img;
    $mobile_img_url       = wp_get_attachment_image_url($mobile_attachment_id, 'full') ?: '';
    $alt = get_post_meta($mobile_attachment_id, '_wp_attachment_image_alt', true);
    if (!empty($alt)) {
        $mobile_alt = $alt;
    }
} elseif (is_string($mobile_img) && $mobile_img !== '') {
    $mobile_img_url = $mobile_img;
}

$is_custom_mobile = !empty($mobile_img_url);
if (!$is_custom_mobile) {
    // If admin set a custom desktop image but no mobile image, use the custom desktop image
    if ($is_custom_desktop) {
        $mobile_img_url       = $desktop_img_url;
        $mobile_attachment_id = $desktop_attachment_id;
        $mobile_alt           = $desktop_alt;
        $is_custom_mobile     = true;
    } else {
        $mobile_webp_url = get_theme_file_uri('assets/images/hero-mobile-tradie.webp');
        $mobile_png_url  = get_theme_file_uri('assets/images/hero-mobile-tradie.png');
    }
}
?>

<section data-testid="hero-section" class="relative isolate overflow-hidden bg-[#0C4161] text-primary-foreground">
    <!-- MOBILE ONLY: Top Image (< md) -->
    <div class="relative block md:hidden w-full overflow-hidden bg-[#0C4161]">
        <?php if ($is_custom_mobile): ?>
            <img 
                src="<?= esc_url($mobile_img_url) ?>" 
                <?php if ($mobile_attachment_id && function_exists('wp_get_attachment_image_srcset')): 
                    $m_srcset = wp_get_attachment_image_srcset($mobile_attachment_id, 'full');
                    if ($m_srcset): ?>
                        srcset="<?= esc_attr($m_srcset) ?>"
                        sizes="(max-width: 768px) 100vw, 736px"
                    <?php endif; ?>
                <?php endif; ?>
                alt="<?= esc_attr($mobile_alt) ?>" 
                class="w-full h-auto object-cover max-h-[440px] object-top"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            />
        <?php else: ?>
            <picture>
                <source srcset="<?= esc_url($mobile_webp_url) ?>" type="image/webp">
                <img 
                    src="<?= esc_url($mobile_png_url) ?>" 
                    alt="<?= esc_attr($heading) ?>" 
                    class="w-full h-auto object-cover max-h-[440px] object-top"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                />
            </picture>
        <?php endif; ?>
        <!-- Gradient transition between image and content (< md) -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-36 sm:h-44 bg-gradient-to-t from-[#0C4161] via-[#0C4161]/80 to-transparent z-10" aria-hidden="true"></div>
    </div>

    <!-- MAIN HERO CONTENT CONTAINER (with pattern rings, dots & text) -->
    <div class="relative md:min-h-[580px] lg:min-h-[640px] md:flex md:items-center -mt-12 sm:-mt-16 md:mt-0 z-20">
        <!-- Background dot pattern (hidden on mobile, visible on md+) -->
        <div class="pointer-events-none absolute inset-0 hidden md:block bg-pattern-dots-dark z-0" aria-hidden="true"></div>

        <?php waklert_output_pattern_rings('hidden md:block'); ?>

        <!-- TABLET & DESKTOP ONLY: Background Right-Anchored Image (>= md) -->
        <div class="pointer-events-none absolute inset-0 z-0 overflow-hidden hidden md:block">
            <?php if ($is_custom_desktop): ?>
                <img 
                    src="<?= esc_url($desktop_img_url) ?>" 
                    <?php if ($desktop_attachment_id && function_exists('wp_get_attachment_image_srcset')): 
                        $d_srcset = wp_get_attachment_image_srcset($desktop_attachment_id, 'full');
                        if ($d_srcset): ?>
                            srcset="<?= esc_attr($d_srcset) ?>"
                            sizes="(max-width: 1024px) 100vw, 1024px"
                        <?php endif; ?>
                    <?php endif; ?>
                    alt="<?= esc_attr($desktop_alt) ?>" 
                    class="h-full w-full object-cover"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                />
            <?php else: ?>
                <picture>
                    <source srcset="<?= esc_url($desktop_webp_url) ?>" type="image/webp">
                    <img 
                        src="<?= esc_url($desktop_png_url) ?>" 
                        alt="<?= esc_attr($heading) ?>" 
                        class="h-full w-full object-cover"
                        loading="eager"
                        fetchpriority="high"
                        decoding="async"
                    />
                </picture>
            <?php endif; ?>
            <!-- Tablet backdrop overlay (md to lg) for text contrast, hidden on desktop lg+ -->
            <div class="absolute inset-0 bg-[#0C4161]/75 lg:hidden" aria-hidden="true"></div>
        </div>

        <!-- Left Column Content -->
        <div class="container-site relative z-10 w-full pt-2 pb-8 sm:pt-3 sm:pb-10 md:py-12 lg:py-14">
            <div class="max-w-lg lg:max-w-[460px] xl:max-w-[530px]">
                <?php if ($location): ?>
                <span class="mb-5 md:mb-6 inline-flex items-center gap-2 md:gap-2.5 rounded-full border border-white/25 bg-white/10 px-4.5 py-2 md:px-5 md:py-2.5 text-xs sm:text-sm md:text-[0.9375rem] font-bold uppercase tracking-[0.16em] text-white backdrop-blur-md shadow-sm">
                    <svg class="h-4 w-4 md:h-4.5 md:w-4.5 text-amber-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                        <path d="M15 18H9"/>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.24-4.05a1 1 0 0 0-.78-.33H14v10"/>
                        <circle cx="17" cy="18" r="2"/>
                        <circle cx="7" cy="18" r="2"/>
                    </svg>
                    <span><?= esc_html($location) ?></span>
                </span>
                <?php endif; ?>

                <h1 class="mb-4 md:mb-5 font-heading text-3xl sm:text-4xl md:text-[2.75rem] lg:text-[3.125rem] xl:text-[3.4375rem] font-extrabold leading-[1.1] md:leading-[1.08] tracking-tight text-white drop-shadow-sm">
                    <?= esc_html($heading) ?>
                </h1>

                <?php if ($subtitle): ?>
                <p class="mb-5 md:mb-6 font-heading text-xl sm:text-2xl md:text-[1.625rem] lg:text-[1.875rem] font-semibold leading-snug text-amber-300/95">
                    <?= esc_html($subtitle) ?>
                </p>
                <?php endif; ?>

                <?php if ($description): ?>
                <p class="mb-7 md:mb-8 text-base sm:text-lg md:text-[1.25rem] lg:text-[1.35rem] leading-relaxed text-white/85">
                    <?= esc_html($description) ?>
                </p>
                <?php endif; ?>

                <div class="flex flex-wrap gap-2.5 sm:gap-3 md:gap-3.5 mb-8">
                    <a href="<?= esc_url($primary_link) ?>" class="group inline-flex items-center gap-2 md:gap-2.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 px-5 py-2.5 sm:px-7 sm:py-3.5 md:px-8 md:py-4 text-xs sm:text-base md:text-[1.0625rem] font-extrabold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/30 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
                        <span><?= esc_html($primary_btn) ?></span>
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-4 sm:w-4 md:h-5 md:w-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                    <?php if ($secondary_btn !== '' && $secondary_link !== ''): ?>
                        <a href="<?= esc_url($secondary_link) ?>" class="inline-flex items-center rounded-full border border-white/30 bg-white/10 px-4 py-2.5 sm:px-6 sm:py-3.5 md:px-7 md:py-4 text-xs sm:text-base md:text-[1.0625rem] font-bold uppercase tracking-wide text-white transition hover:bg-white/20 hover:-translate-y-0.5">
                            <?= esc_html($secondary_btn) ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- 3 Colorful Micro Feature Badges (Dashed Border + Dual Tone + Colorful Vector Icons) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2">
                    <div class="group flex items-center gap-2.5 rounded-xl border border-dashed border-white/30 bg-white/10 hover:bg-white/15 backdrop-blur-sm p-2.5 px-3 transition-all duration-300 hover:-translate-y-0.5 hover:border-amber-400/80">
                        <div class="w-8 h-8 rounded-lg bg-white/90 p-1 flex items-center justify-center shrink-0 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <!-- Colorful Truck Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5" fill="none">
                                <rect x="2" y="8" width="18" height="15" rx="2" fill="#EF4444"/>
                                <path d="M20 12H26L29 16V23H20V12Z" fill="#DC2626"/>
                                <path d="M21 14H25L27.5 17H21V14Z" fill="#93C5FD"/>
                                <circle cx="8" cy="23" r="3" fill="#1E293B"/>
                                <circle cx="8" cy="23" r="1.2" fill="#F8FAFC"/>
                                <circle cx="24" cy="23" r="3" fill="#1E293B"/>
                                <circle cx="24" cy="23" r="1.2" fill="#F8FAFC"/>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <span class="block text-[11px] font-extrabold text-white">Express Delivery</span>
                            <span class="block text-[10px] text-white/75 font-medium">Domestic AU Post</span>
                        </div>
                    </div>

                    <div class="group flex items-center gap-2.5 rounded-xl border border-dashed border-white/30 bg-white/10 hover:bg-white/15 backdrop-blur-sm p-2.5 px-3 transition-all duration-300 hover:-translate-y-0.5 hover:border-amber-400/80">
                        <div class="w-8 h-8 rounded-lg bg-white/90 p-1 flex items-center justify-center shrink-0 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <!-- Colorful Shield / Pharma Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5" fill="none">
                                <path d="M16 3L6 7V15C6 22 10.5 27 16 29C21.5 27 26 22 26 15V7L16 3Z" fill="#F59E0B"/>
                                <path d="M16 5.5L8 9V15C8 20.8 11.6 25 16 26.8C20.4 25 24 20.8 24 15V9L16 5.5Z" fill="#FBBF24"/>
                                <circle cx="16" cy="16" r="6" fill="#10B981"/>
                                <path d="M13.5 16L15.2 17.8L18.8 14.2" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <span class="block text-[11px] font-extrabold text-white">Genuine Quality</span>
                            <span class="block text-[10px] text-white/75 font-medium">Sun Pharma Grade</span>
                        </div>
                    </div>

                    <div class="group flex items-center gap-2.5 rounded-xl border border-dashed border-white/30 bg-white/10 hover:bg-white/15 backdrop-blur-sm p-2.5 px-3 transition-all duration-300 hover:-translate-y-0.5 hover:border-amber-400/80">
                        <div class="w-8 h-8 rounded-lg bg-white/90 p-1 flex items-center justify-center shrink-0 shadow-xs transition-transform duration-300 group-hover:scale-110">
                            <!-- Colorful Lock / Privacy Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5" fill="none">
                                <rect x="6" y="13" width="20" height="15" rx="3" fill="#0C4161"/>
                                <path d="M10 13V9C10 5.686 12.686 3 16 3C19.314 3 22 5.686 22 9V13" stroke="#F59E0B" stroke-width="3" stroke-linecap="round"/>
                                <circle cx="16" cy="20" r="2.5" fill="#10B981"/>
                                <path d="M16 22.5V25" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <span class="block text-[11px] font-extrabold text-white">Discreet Packaging</span>
                            <span class="block text-[10px] text-white/75 font-medium">100% Privacy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
