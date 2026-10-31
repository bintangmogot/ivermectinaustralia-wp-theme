<?php
/**
 * Site header: top bar, sticky header, mobile drawer.
 * Every string comes from Theme Settings or the menus.
 */
$google_tag_id = trim((string) get_field('google_tag_id', 'option'));

$raw_phone     = trim((string) (get_field('header_phone_number', 'option') ?: get_field('whatsapp_number', 'option')));
$phone_digits  = preg_replace('/\D+/', '', $raw_phone);
$phone_href    = $phone_digits !== '' ? 'tel:' . $phone_digits : '';
$phone_label   = trim((string) get_field('whatsapp_button_text', 'option')) ?: $raw_phone;
$phone_url_opt = trim((string) get_field('whatsapp_button_url', 'option'));
if ($phone_url_opt !== '' && stripos($phone_url_opt, 'wa.me') === false) {
    $phone_href = $phone_url_opt;
}
$topbar_left   = trim((string) get_field('topbar_left', 'option'));
$topbar_center = trim((string) get_field('topbar_center', 'option'));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="overflow-x-hidden">
<head>
    <?php if ($google_tag_id !== '') : ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc_attr($google_tag_id) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', <?= wp_json_encode($google_tag_id) ?>);
    </script>
    <?php endif; ?>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-background text-foreground antialiased'); ?>>
<?php wp_body_open(); ?>

<?php if ($topbar_left !== '' || $topbar_center !== '' || $phone_label !== '') : ?>
<div class="bg-ink text-ink-foreground text-[13px]">
    <div class="container-site flex min-h-9 items-center justify-between gap-3 py-1.5 font-medium">
        <?php if ($topbar_left !== '') : ?>
            <p class="flex min-w-0 items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-accent" aria-hidden="true"></span><span class="leading-snug"><?= esc_html($topbar_left) ?></span></p>
        <?php endif; ?>
        <?php if ($topbar_center !== '') : ?>
            <p class="hidden items-center gap-2 md:flex"><?= ivermectin_icon('express_shipping', 'h-4 w-4') ?><span><?= esc_html($topbar_center) ?></span></p>
        <?php endif; ?>
        <?php if ($phone_href !== '' && $phone_label !== '') : ?>
            <a href="<?= esc_attr($phone_href) ?>" class="inline-flex shrink-0 items-center gap-1.5 font-semibold md:hidden"><?= ivermectin_icon('live_chat_support', 'h-4 w-4') ?><span><?= esc_html($phone_label) ?></span></a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<header class="sticky top-0 z-50 w-full border-b border-border bg-white">
    <div class="container-site flex h-[68px] items-center justify-between gap-4 lg:h-[76px]">
        <button type="button" aria-label="Menu" id="mobile-menu-open" class="grid h-10 w-10 place-items-center rounded-lg border border-border text-primary lg:hidden">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>

        <div class="absolute left-1/2 -translate-x-1/2 lg:static lg:translate-x-0">
            <?php ivermectin_the_logo('h-11 w-auto object-contain', 'header'); ?>
        </div>

        <nav class="desktop-nav hidden lg:flex lg:flex-1 lg:justify-center" aria-label="Primary">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex items-center gap-1',
                'fallback_cb'    => false,
                'walker'         => new Ivermectin_Desktop_Nav_Walker(),
            ]);
            ?>
        </nav>

        <div class="ml-auto flex items-center gap-2 sm:gap-3 lg:ml-0">
            <?php if ($phone_href !== '' && $phone_label !== '') : ?>
                <a href="<?= esc_attr($phone_href) ?>" class="hidden items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-primary-dark md:inline-flex">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
                    <span><?= esc_html($phone_label) ?></span>
                </a>
            <?php endif; ?>
            <?php if (shortcode_exists('xoo_wsc_cart')) : ?>
                <?= do_shortcode('[xoo_wsc_cart]') ?>
            <?php elseif (function_exists('wc_get_cart_url')) : ?>
                <a href="<?= esc_url(wc_get_cart_url()) ?>" aria-label="<?= esc_attr__('Cart', 'ivermectin-australia') ?>" class="relative grid h-10 w-10 place-items-center rounded-lg border border-border text-primary hover:bg-primary-softer">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
                    <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    <?php if ($cart_count > 0) : ?>
                        <span class="absolute -right-1.5 -top-1.5 grid h-5 min-w-5 place-items-center rounded-full bg-accent-dark px-1 text-xs font-bold text-white"><?= esc_html((string) $cart_count) ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div id="mobile-menu-overlay" class="fixed inset-0 z-[99998] hidden bg-ink/60 lg:hidden" aria-hidden="true"></div>

<div id="mobile-menu-drawer" class="fixed inset-y-0 left-0 z-[99999] h-[100dvh] w-[88%] max-w-sm -translate-x-full transform overflow-y-auto bg-white transition-transform duration-200 lg:hidden">
    <div class="flex h-[68px] items-center justify-between border-b border-border px-5">
        <?php ivermectin_the_logo('h-10 w-auto object-contain', 'mobile'); ?>
        <button type="button" aria-label="Close menu" id="mobile-menu-close" class="grid h-10 w-10 place-items-center rounded-lg border border-border text-primary">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </div>
    <nav class="mobile-nav px-5 py-2" aria-label="Mobile">
        <?php
        wp_nav_menu([
            'theme_location' => has_nav_menu('mobile') ? 'mobile' : 'primary',
            'container'      => false,
            'menu_class'     => 'flex flex-col',
            'fallback_cb'    => false,
            'walker'         => new Ivermectin_Mobile_Nav_Walker(),
        ]);
        ?>
    </nav>
    <?php if ($phone_href !== '' && $phone_label !== '') : ?>
        <div class="px-5 pb-6 pt-2">
            <a href="<?= esc_attr($phone_href) ?>" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-3 text-sm font-bold text-white"><?= esc_html($phone_label) ?></a>
        </div>
    <?php endif; ?>
</div>
