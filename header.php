<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="overflow-x-hidden">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-D0FP1NRE56"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-D0FP1NRE56');
    </script>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php if (is_single() || is_page()) : ?>
        <?php
        global $post;
        $og_url = get_permalink();
        $og_title = get_the_title();
        $og_description = get_the_excerpt();
        $og_image = get_the_post_thumbnail_url($post->ID, 'full');
        if (!$og_image) {
            $og_image = get_template_directory_uri() . '/screenshot.jpg';
        }
        ?>
        <meta property="og:title" content="<?php echo esc_attr($og_title); ?>" />
        <meta property="og:description" content="<?php echo esc_attr($og_description); ?>" />
        <meta property="og:url" content="<?php echo esc_url($og_url); ?>" />
        <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
        <meta property="og:type" content="article" />
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr($og_description); ?>">
        <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    <?php else : ?>
        <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>" />
        <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>" />
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>" />
        <meta property="og:type" content="website" />
    <?php endif; ?>
    <?php wp_head(); ?>
    <?php if (!has_site_icon()) : ?>
        <link rel="icon" href="<?= esc_url(get_template_directory_uri() . '/assets/images/waklert-site-icon.png') ?>" type="image/png">
        <link rel="apple-touch-icon" href="<?= esc_url(get_template_directory_uri() . '/assets/images/waklert-site-icon.png') ?>">
    <?php endif; ?>
</head>

<body <?php body_class('bg-background text-foreground antialiased selection:bg-primary-soft selection:text-primary-dark'); ?>>
<?php wp_body_open(); ?>

<!-- Trust Bar -->
<div class="relative overflow-hidden w-full bg-gradient-to-r from-[#082a3f] via-[#0c4161] to-[#0f4f75] bg-pattern-dots-dark border-b border-sky-400/20 text-white text-[13px]" data-testid="trust-bar">
    <div class="container-site relative z-10 flex h-9 items-center justify-between gap-2 px-4 font-medium tracking-wide sm:gap-4">
        <?php $topbar_left = get_field('topbar_left', 'option'); ?>
        <?php if (!empty($topbar_left)): ?>
        <p class="min-w-0 flex-1 truncate text-[10px] opacity-95 sm:flex-none sm:overflow-visible sm:text-[13px] sm:whitespace-nowrap flex items-center gap-1.5">
            <!-- Colorful Delivery Van SVG -->
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="1" y="5" width="14" height="11" rx="1.5" fill="#38BDF8" fill-opacity="0.3" stroke="#38BDF8" stroke-width="1.5"/>
                <path d="M15 8h4.5l3.5 4.5v3.5h-8V8z" fill="#F59E0B" stroke="#F59E0B" stroke-width="1.5"/>
                <circle cx="6" cy="18" r="2.5" fill="#FFFFFF" stroke="#0C4161" stroke-width="1.5"/>
                <circle cx="18" cy="18" r="2.5" fill="#FFFFFF" stroke="#F59E0B" stroke-width="1.5"/>
                <circle cx="6" cy="18" r="1" fill="#0C4161"/>
                <circle cx="18" cy="18" r="1" fill="#F59E0B"/>
                <path d="M17 11h3" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            <span><?php echo esc_html($topbar_left); ?></span>
        </p>
        <?php endif; ?>

        <?php $topbar_center = get_field('topbar_center', 'option'); ?>
        <?php if (!empty($topbar_center)): ?>
        <p class="hidden md:flex items-center gap-1.5 opacity-95 text-amber-300 font-semibold">
            <!-- Colorful Star SVG -->
            <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            <span><?php echo esc_html($topbar_center); ?></span>
        </p>
        <?php endif; ?>

        <?php 
        $raw_phone = trim((string) (get_field('header_phone_number', 'option') ?: get_field('whatsapp_number', 'option')));
        $display_phone = '';
        $tel_phone = '';
        if ($raw_phone !== '') {
            $clean_digits = preg_replace('/\D+/', '', $raw_phone);
            if (str_starts_with($clean_digits, '61') && strlen($clean_digits) >= 10) {
                $clean_digits = '0' . substr($clean_digits, 2);
            }
            if (strlen($clean_digits) === 10 && str_starts_with($clean_digits, '0')) {
                $display_phone = substr($clean_digits, 0, 4) . ' ' . substr($clean_digits, 4, 3) . ' ' . substr($clean_digits, 7, 3);
            } elseif (strlen($clean_digits) === 9 && str_starts_with($clean_digits, '4')) {
                $clean_digits = '0' . $clean_digits;
                $display_phone = substr($clean_digits, 0, 4) . ' ' . substr($clean_digits, 4, 3) . ' ' . substr($clean_digits, 7, 3);
            } else {
                $display_phone = trim((string) preg_replace('/^\+?61\s*/', '0', $raw_phone));
            }
            $tel_phone = 'tel:' . preg_replace('/\D+/', '', $display_phone ?: $raw_phone);
        }

        $header_btn_text = trim((string) get_field('whatsapp_button_text', 'option'));
        if ($header_btn_text === '' || stripos($header_btn_text, 'whatsapp') !== false) {
            $header_btn_text = $display_phone;
        }

        $header_btn_url_raw = trim((string) get_field('whatsapp_button_url', 'option'));
        if ($header_btn_url_raw !== '' && stripos($header_btn_url_raw, 'wa.me') === false) {
            if (preg_match('#^(tel:|sms:|mailto:|https?://|/|#)#i', $header_btn_url_raw)) {
                $header_btn_href = $header_btn_url_raw;
            } else {
                $header_btn_href = 'tel:' . preg_replace('/\D+/', '', $header_btn_url_raw);
            }
        } else {
            $header_btn_href = $tel_phone;
        }
        ?>
        <?php if ($display_phone !== ''): ?>
        <div class="flex shrink-0 items-center md:hidden">
            <a href="<?php echo esc_attr($tel_phone); ?>" aria-label="Call <?php echo esc_attr($display_phone); ?>" class="inline-flex shrink-0 items-center gap-1.5 text-[10px] text-white/90 transition-colors hover:text-white sm:text-[13px] font-semibold bg-white/10 hover:bg-white/20 px-2.5 py-1 rounded-full border border-white/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-300"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 8 7.94"/><path d="M14.05 6A5 5 0 0 1 18 10"/></svg>
                <span class="whitespace-nowrap"><?php echo esc_html($display_phone); ?></span>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Header -->
<header class="sticky top-0 z-50 w-full border-b-2 border-dashed border-sky-200/80 bg-white/95 backdrop-blur-md shadow-sm transition-all">
    <div class="container-site relative flex h-[68px] sm:h-[72px] items-center justify-between gap-4">

        <!-- Mobile Menu Toggle -->
        <div class="flex items-center lg:hidden">
            <button type="button" aria-label="Menu" id="mobile-menu-open" class="rounded-xl p-2.5 -ml-1 text-[#0c4161] bg-sky-50 border-2 border-dashed border-sky-300/80 hover:bg-sky-100 hover:border-[#0c4161] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Logo -->
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 lg:static lg:translate-x-0 lg:translate-y-0 transition-transform duration-300 hover:scale-[1.02]">
            <?php waklert_the_logo('h-12 sm:h-[53px] w-auto max-w-[264px] sm:max-w-[312px] object-contain', 'header'); ?>
        </div>

        <!-- Desktop Nav -->
        <nav class="hidden items-center gap-1 lg:flex lg:flex-1 lg:justify-center desktop-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex items-center gap-1.5 font-semibold text-slate-700',
                'fallback_cb'    => false,
                'walker'         => class_exists('Waklert_Desktop_Nav_Walker') ? new Waklert_Desktop_Nav_Walker() : false,
            ]);
            ?>
        </nav>

        <!-- Right Actions -->
        <div class="flex items-center gap-2 sm:gap-3 ml-auto lg:ml-0">
            <?php if ($header_btn_text !== '' && $header_btn_href !== ''): ?>
            <!-- Header Phone Number Button (Hidden on mobile; Solid Blue Background & White Icon on desktop) -->
            <a href="<?= esc_attr($header_btn_href) ?>" aria-label="Call <?= esc_attr($header_btn_text) ?>" class="hidden md:inline-flex items-center gap-2 sm:gap-2.5 px-3.5 sm:px-4 py-1.5 rounded-full bg-[#0c4161] hover:bg-[#082a3f] text-white transition-all group shadow-sm">
                <span class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-full bg-white/15 text-white transition-colors group-hover:bg-white/25">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 sm:h-4 sm:w-4 text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </span>
                <span class="text-base sm:text-lg lg:text-xl font-black font-heading tracking-tight text-white whitespace-nowrap"><?= esc_html($header_btn_text) ?></span>
            </a>
            <?php endif; ?>

            <?= do_shortcode('[xoo_wsc_cart]') ?>
        </div>
    </div>
</header>

<!-- Mobile Nav Overlay (Placed outside <header> to avoid backdrop-blur/sticky containing block clipping) -->
<div id="mobile-menu-overlay" class="fixed inset-0 z-[99998] hidden bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity" aria-hidden="true"></div>

<!-- Mobile Nav Drawer -->
<div id="mobile-menu-drawer" class="fixed inset-y-0 left-0 z-[99999] w-[88%] max-w-sm h-screen h-[100dvh] transform overflow-y-auto bg-gradient-to-b from-[#f8fafc] via-[#f0f6fa] to-[#eef5fa] bg-pattern-dots transition-transform duration-300 ease-in-out lg:hidden -translate-x-full shadow-2xl flex flex-col justify-between">
    <div class="shrink-0">
        <div class="flex h-[68px] items-center justify-between border-b-2 border-dashed border-sky-200/80 bg-white/90 px-6 backdrop-blur">
            <?php waklert_the_logo('h-11 w-auto max-w-[228px] object-contain', 'mobile'); ?>
            <button type="button" aria-label="Close menu" id="mobile-menu-close" class="rounded-xl p-2 text-[#0c4161] bg-sky-50 border border-dashed border-sky-300 hover:bg-sky-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <nav class="flex flex-col border-b-2 border-dashed border-sky-200/80 py-3 px-4 mobile-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'mobile',
                'container'      => false,
                'menu_class'     => 'flex flex-col divide-y divide-dashed divide-sky-200/80',
                'fallback_cb'    => function() {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex flex-col divide-y divide-dashed divide-sky-200/80',
                        'fallback_cb'    => false,
                        'walker'         => class_exists('Waklert_Mobile_Nav_Walker') ? new Waklert_Mobile_Nav_Walker() : false,
                    ]);
                },
                'walker'         => class_exists('Waklert_Mobile_Nav_Walker') ? new Waklert_Mobile_Nav_Walker() : false,
            ]);
            ?>
        </nav>

        <?php if ($header_btn_text !== '' && $header_btn_href !== ''): ?>
        <div class="px-6 pt-5 pb-2">
            <a href="<?= esc_attr($header_btn_href) ?>" class="flex w-full items-center justify-center gap-2.5 rounded-full bg-[#0c4161] px-4 py-2.5 sm:px-5 sm:py-3.5 text-sm sm:text-base font-black text-white shadow-md transition-all hover:shadow-lg hover:bg-[#082a3f]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-white"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </span>
                <span><?= esc_html($header_btn_text) ?></span>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- 2-Tone Dashed Help Card in Mobile Drawer -->
    <div class="mx-6 mb-8 mt-4 shrink-0 rounded-3xl border-2 border-dashed border-sky-300/80 bg-white overflow-hidden shadow-sm transition-all hover:shadow-md">
        <div class="p-5">
            <div class="flex items-center gap-3 mb-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-200">
                    <!-- Colorful Help Life Ring SVG -->
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="#F59E0B" stroke-width="2"/>
                        <circle cx="12" cy="12" r="4" stroke="#0C4161" stroke-width="2"/>
                        <path d="M4.93 4.93L8.5 8.5M15.5 15.5L19.07 19.07M4.93 19.07L8.5 15.5M15.5 8.5L19.07 4.93" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-600">Support Desk</span>
                    <h3 class="text-sm font-extrabold text-[#0c4161]">Need a hand?</h3>
                </div>
            </div>
            <p class="text-xs leading-relaxed text-slate-600">Find clear information about products, ordering and express Australia delivery.</p>
        </div>
        <a href="<?= esc_url(home_url('/faq/')) ?>" class="flex items-center justify-between bg-gradient-to-r from-[#0c4161] to-[#125883] px-4 py-2.5 sm:px-5 sm:py-3 text-xs font-bold text-white transition-colors hover:from-[#09324c] hover:to-[#0c4161]">
            <span>Visit the FAQs</span>
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
        </a>
    </div>
</div>
