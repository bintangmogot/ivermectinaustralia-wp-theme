<?php
/**
 * The template for displaying the footer
 */
$footer_desc = get_field('footer_description', 'option');
$footer_address = get_field('location_text', 'option') ?: get_field('footer_address', 'option');
$footer_email = get_field('support_email', 'option') ?: get_field('footer_email', 'option') ?: 'orders@waklertaustralia.com.au';
$footer_contact_heading = get_field('footer_contact_heading', 'option');
$working_days = get_field('working_days', 'option');
$office_hours = get_field('office_hours', 'option');
$payments_heading = get_field('footer_payments_heading', 'option');
$shipping_heading = get_field('footer_shipping_heading', 'option');
$payment_images = get_field('footer_payment_images', 'option');
$shipping_images = get_field('footer_shipping_images', 'option');
$copyright_text = get_field('copyright_text', 'option');
$disclaimer = get_field('disclaimer_en', 'option');
$ssl_text = get_field('ssl_text', 'option');
$dispatch_text = get_field('dispatch_text', 'option');
$checkout_text = get_field('checkout_text', 'option');

$footer_menu_locations = get_nav_menu_locations();
$footer_menus = [];

foreach (['footer_trending', 'footer_quick', 'footer_cities', 'footer_info'] as $location) {
    $menu_id = absint($footer_menu_locations[$location] ?? 0);
    if (!$menu_id) {
        continue;
    }

    $menu = wp_get_nav_menu_object($menu_id);
    $items = wp_get_nav_menu_items($menu_id);
    if (!$menu || empty($items)) {
        continue;
    }

    $heading = preg_replace('/^Footer\s+/i', '', $menu->name);
    $footer_menus[$location] = [
        'id' => $menu_id,
        'heading' => $heading ?: $menu->name,
    ];
}

$render_footer_menu = static function ($location, $wrapper_class = '') use ($footer_menus) {
    if (empty($footer_menus[$location])) {
        return;
    }

    $menu = $footer_menus[$location];
    ?>
    <section class="<?= esc_attr($wrapper_class) ?>">
        <h3 class="mb-5 text-[15px] font-bold text-white" style="color: #fff !important">
            <?= esc_html($menu['heading']) ?>
        </h3>
        <nav aria-label="<?= esc_attr($menu['heading']) ?>">
            <?php wp_nav_menu([
                'menu' => $menu['id'],
                'container' => false,
                'menu_class' => 'space-y-4 text-[13px] font-medium text-white/70 [&_a:hover]:text-accent [&_a]:transition-colors',
                'fallback_cb' => false,
            ]); ?>
        </nav>
    </section>
    <?php
};

// On homepage, render the Support CTA banner before footer if not already rendered as a page module
if (is_front_page() && empty($GLOBALS['waklert_support_cta_rendered'])) {
    get_template_part('modules/content', 'support_cta');
}
?>
<footer class="relative isolate overflow-hidden bg-ink text-ink-foreground">
    <?php waklert_output_pattern_rings(); ?>

    <?php
    $pre_footer_enabled = get_field('pre_footer_badges_enabled', 'option');
    $pre_footer_badges = get_field('pre_footer_badges', 'option');
    $color_classes = [
        'sky'     => 'text-sky-200',
        'amber'   => 'text-amber-200',
        'emerald' => 'text-emerald-200',
    ];
    ?>
    <?php if (!is_front_page() && !empty($pre_footer_enabled) && !empty($pre_footer_badges) && is_array($pre_footer_badges)): ?>
    <!-- 2-Tone Dashed Inner Page Badge Strip (Editable in WP Admin -> Theme Settings -> Footer) -->
    <div class="relative isolate overflow-hidden bg-gradient-to-r from-[#082a3f] via-[#0c4161] to-[#125883] bg-pattern-dots-dark py-10 text-white border-b-2 border-dashed border-sky-400/30">
        <?php waklert_output_pattern_rings(); ?>
        <div class="container-site relative z-10 grid grid-cols-2 gap-4 md:grid-cols-4 sm:gap-6">
            <?php foreach ($pre_footer_badges as $idx => $badge):
                $badge_title = $badge['title'] ?? '';
                if (trim((string) $badge_title) === '') continue;
                $badge_sub = $badge['subtitle'] ?? '';
                $badge_color_key = $badge['subtitle_color'] ?? 'sky';
                $badge_color_cls = $color_classes[$badge_color_key] ?? 'text-sky-200';
                $badge_icon_type = $badge['icon'] ?? 'truck';
                $badge_custom_icon = $badge['custom_icon'] ?? null;
            ?>
            <div class="group rounded-2xl border-2 border-dashed border-sky-300/40 bg-white/10 p-4 sm:p-5 backdrop-blur-sm flex flex-col items-center text-center gap-3 hover:bg-white/15 hover:-translate-y-1 transition-all">
                <div class="flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-white p-2 sm:p-2.5 shadow-lg shadow-black/25 border-2 border-white/90 group-hover:scale-105 transition-transform duration-300 shrink-0">
                    <?php if ($badge_icon_type === 'custom' && !empty($badge_custom_icon)): 
                        $c_url = is_array($badge_custom_icon) ? ($badge_custom_icon['url'] ?? '') : (is_numeric($badge_custom_icon) ? wp_get_attachment_image_url($badge_custom_icon, 'full') : $badge_custom_icon);
                    ?>
                        <img src="<?= esc_url($c_url) ?>" alt="<?= esc_attr($badge_title) ?>" class="w-10 h-10 sm:w-11 sm:h-11 object-contain shrink-0">
                    <?php elseif ($badge_icon_type === 'shield_pharma'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-10 h-10 sm:w-11 sm:h-11 shrink-0" fill="none" aria-hidden="true">
                            <ellipse cx="32" cy="55" rx="18" ry="2.5" fill="#0C4161" fill-opacity="0.15" />
                            <path d="M32 7L15 13.5V28C15 41.5 22.2 50 32 53.5C41.8 50 49 41.5 49 28V13.5L32 7Z" fill="#D97706" />
                            <path d="M32 10L18 15.5V28C18 39.5 23.8 47 32 50.2C40.2 47 46 39.5 46 28V15.5L32 10Z" fill="#F59E0B" />
                            <path d="M32 12.5L20.5 17V28C20.5 38 25.2 44.5 32 47.5C38.8 44.5 43.5 38 43.5 28V17L32 12.5Z" fill="#FBBF24" />
                            <circle cx="32" cy="30" r="11" fill="#10B981" />
                            <path d="M28 30H36M32 26V34" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" />
                            <circle cx="43" cy="18" r="4" fill="#38BDF8" stroke="#FFFFFF" stroke-width="1.5" />
                            <path d="M41.5 18L42.5 19L44.5 17" stroke="#FFFFFF" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    <?php elseif ($badge_icon_type === 'shield_insurance'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-10 h-10 sm:w-11 sm:h-11 shrink-0" fill="none" aria-hidden="true">
                            <ellipse cx="32" cy="55" rx="18" ry="2.5" fill="#0C4161" fill-opacity="0.15" />
                            <path d="M32 7L16 13.5V27C16 40.5 23 48.5 32 52.5C41 48.5 48 40.5 48 27V13.5L32 7Z" fill="#059669" />
                            <path d="M32 10L18.5 15.5V27C18.5 38.5 24.5 45.8 32 49.2C39.5 45.8 45.5 38.5 45.5 27V15.5L32 10Z" fill="#10B981" />
                            <path d="M32 12.5L21 17V27C21 37 26 43 32 46.2C38 43 43 37 43 27V17L32 12.5Z" fill="#34D399" />
                            <circle cx="32" cy="29" r="10" fill="#065F46" />
                            <path d="M27 29L30.5 32.5L37 25.5" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M24 46L21 53L27 50L32 52L29 46" fill="#F59E0B" stroke="#D97706" stroke-width="1" />
                            <path d="M40 46L43 53L37 50L32 52L35 46" fill="#F59E0B" stroke="#D97706" stroke-width="1" />
                        </svg>
                    <?php elseif ($badge_icon_type === 'parcel'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-10 h-10 sm:w-11 sm:h-11 shrink-0" fill="none" aria-hidden="true">
                            <ellipse cx="32" cy="55" rx="20" ry="2.5" fill="#0C4161" fill-opacity="0.15" />
                            <rect x="13" y="18" width="38" height="30" rx="3" fill="#B45309" />
                            <rect x="15" y="16" width="34" height="29" rx="2.5" fill="#D97706" />
                            <path d="M15 27H49M32 16V45" stroke="#92400E" stroke-width="2.5" />
                            <rect x="18" y="32" width="11" height="8" rx="1.5" fill="#FFFFFF" />
                            <line x1="20" y1="35" x2="27" y2="35" stroke="#64748B" stroke-width="1.2" />
                            <line x1="20" y1="37.5" x2="25" y2="37.5" stroke="#64748B" stroke-width="1.2" />
                            <rect x="35" y="30" width="12" height="11" rx="2.5" fill="#0284C7" />
                            <path d="M38 30V27C38 25.3 39.3 24 41 24C42.7 24 44 25.3 44 27V30" stroke="#F59E0B" stroke-width="2.2" stroke-linecap="round" />
                            <circle cx="41" cy="35.5" r="1.5" fill="#FFFFFF" />
                        </svg>
                    <?php else: // 'truck' or default ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-10 h-10 sm:w-11 sm:h-11 shrink-0" fill="none" aria-hidden="true">
                            <ellipse cx="32" cy="54" rx="22" ry="2.5" fill="#0C4161" fill-opacity="0.15" />
                            <path d="M6 31H12M4 37H10M8 43H14" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round" />
                            <rect x="16" y="16" width="28" height="28" rx="4" fill="#DC2626" />
                            <path d="M44 23H52L58 31V44H44V23Z" fill="#B91C1C" />
                            <path d="M44 23H51.5L56 31H44V23Z" fill="#93C5FD" />
                            <rect x="19" y="20" width="22" height="10" rx="2" fill="#FFFFFF" />
                            <text x="30" y="27.5" font-family="system-ui, -apple-system, sans-serif" font-size="6.5" font-weight="900" fill="#DC2626" text-anchor="middle" letter-spacing="0.5">EXPRESS</text>
                            <circle cx="25" cy="44" r="6.5" fill="#1E293B" />
                            <circle cx="25" cy="44" r="2.8" fill="#F8FAFC" />
                            <circle cx="49" cy="44" r="6.5" fill="#1E293B" />
                            <circle cx="49" cy="44" r="2.8" fill="#F8FAFC" />
                            <circle cx="56" cy="38" r="2" fill="#FBBF24" />
                        </svg>
                    <?php endif; ?>
                </div>
                <span class="text-xs font-extrabold tracking-wider uppercase text-white leading-tight"><?= esc_html($badge_title) ?></span>
                <?php if (!empty($badge_sub)): ?>
                    <span class="text-[11px] <?= esc_attr($badge_color_cls) ?> -mt-1.5"><?= esc_html($badge_sub) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="container-site relative z-10 grid gap-10 py-16 md:grid-cols-2 lg:grid-cols-5">
        <!-- Column 1 -->
        <div class="lg:col-span-2 pr-0 lg:pr-8">
            <?php waklert_the_logo('h-12 sm:h-[53px] w-auto max-w-[264px] sm:max-w-[312px] object-contain', 'footer'); ?>

            <?php if (!empty($footer_desc)): ?>
            <p class="mt-5 text-[13px] leading-relaxed text-ink-foreground/80">
                <?= esc_html($footer_desc) ?>
            </p>
            <?php endif; ?>

            <?php if (!empty($disclaimer)): ?>
            <p class="mt-2 text-[13px] leading-relaxed text-ink-foreground/80">
                <strong class="text-white"><?= "Medical Website Disclaimer:" ?></strong>
                <?= esc_html($disclaimer) ?>
            </p>
            <?php endif; ?>

            <?php if (!empty($ssl_text) || !empty($dispatch_text) || !empty($checkout_text)): ?>
            <div class="mt-5 flex flex-wrap items-center gap-2 pt-1 text-xs">
                <?php if (!empty($ssl_text)): ?>
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-sky-400/30 px-2.5 py-1 text-sky-200">
                    <svg class="w-3.5 h-3.5 text-sky-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span><?= esc_html($ssl_text) ?></span>
                </span>
                <?php endif; ?>
                <?php if (!empty($dispatch_text)): ?>
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-amber-400/30 px-2.5 py-1 text-amber-200">
                    <svg class="w-3.5 h-3.5 text-amber-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="5" width="14" height="11" rx="1.5"/><path d="M15 8h4.5l3.5 4.5v3.5h-8V8z"/><circle cx="6" cy="18" r="2"/><circle cx="18" cy="18" r="2"/></svg>
                    <span><?= esc_html($dispatch_text) ?></span>
                </span>
                <?php endif; ?>
                <?php if (!empty($checkout_text)): ?>
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-emerald-400/30 px-2.5 py-1 text-emerald-200">
                    <svg class="w-3.5 h-3.5 text-emerald-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L4 6v6c0 5.25 3.4 10.15 8 11.35 4.6-1.2 8-6.1 8-11.35V6l-8-4z"/><path d="M9 12l2 2 4-4"/></svg>
                    <span><?= esc_html($checkout_text) ?></span>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($footer_contact_heading)): ?>
            <h3 class="text-[15px] font-bold text-white mb-4 mt-8" style="color: #fff !important">
                <?= esc_html($footer_contact_heading) ?>
            </h3>
            <?php endif; ?>

            <?php
            $whatsapp_raw = get_field('whatsapp_button_url', 'option') ?: get_field('whatsapp_number', 'option');
            $whatsapp     = function_exists('waklert_format_whatsapp_url') ? waklert_format_whatsapp_url($whatsapp_raw) : esc_url($whatsapp_raw ?: home_url('/contact/'));
            $has_contact_details = !empty($footer_address) || !empty($whatsapp_raw) || !empty($footer_email) || !empty($working_days) || !empty($office_hours);
            ?>

            <?php if ($has_contact_details): ?>
            <div class="mt-4 space-y-3.5">
                <?php if (!empty($footer_address)): ?>
                <div class="flex items-start gap-3 text-[13px] text-ink-foreground/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 text-[#FFD700] shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <span><?= esc_html($footer_address) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($whatsapp_raw)): ?>
                <div class="flex items-center gap-3 text-[13px] text-ink-foreground/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#FFD700] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>WhatsApp &rarr;</span>
                    <a href="<?= esc_url($whatsapp) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/40 px-2.5 py-0.5 text-xs font-bold text-emerald-300 hover:bg-emerald-500/30 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.67-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Chat Live</span>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (!empty($footer_email)): ?>
                <div class="flex items-center gap-3 text-[13px] text-ink-foreground/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <a href="mailto:<?= esc_attr($footer_email) ?>" class="hover:text-white transition-colors"><?= esc_html($footer_email) ?></a>
                </div>
                <?php endif; ?>

                <?php if (!empty($working_days)): ?>
                <div class="flex items-center gap-3 text-[13px] text-ink-foreground/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span><strong class="text-white font-medium">Working Days:</strong> <?= esc_html($working_days) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($office_hours)): ?>
                <div class="flex items-center gap-3 text-[13px] text-ink-foreground/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><strong class="text-white font-medium">Office Hours:</strong> <?= esc_html($office_hours) ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($payment_images) && is_array($payment_images)) : ?>
                <?php if (!empty($payments_heading)): ?>
                <h3 class="text-[15px] font-bold text-white mb-3 mt-8" style="color: #fff !important">
                    <?= esc_html($payments_heading) ?>
                </h3>
                <?php endif; ?>
                <div class="w-fit max-w-full rounded-2xl border-2 border-dashed border-white/15 bg-white/5 p-4 flex items-center gap-5 flex-wrap">
                    <?php foreach ($payment_images as $pay) : 
                        $img_src = '';
                        if (!empty($pay['image'])) {
                            if (is_array($pay['image'])) {
                                $img_src = $pay['image']['url'] ?? '';
                            } elseif (is_numeric($pay['image'])) {
                                $img_src = wp_get_attachment_image_url($pay['image'], 'full');
                            } elseif (is_string($pay['image'])) {
                                $img_src = $pay['image'];
                            }
                        }
                        if (!$img_src) continue;
                        $alt_val = !empty($pay['alt_text']) ? $pay['alt_text'] : (is_array($pay['image']) ? ($pay['image']['alt'] ?? $pay['image']['title'] ?? 'Payment Method') : 'Payment Method');
                    ?>
                        <img src="<?= esc_url($img_src) ?>" alt="<?= esc_attr($alt_val) ?>" class="footer-payment-logo object-contain h-8 sm:h-9 w-auto hover:scale-105 transition-transform" style="height: 34px; max-height: 36px; width: auto; max-width: 160px; object-fit: contain;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($shipping_images) && is_array($shipping_images)) : ?>
                <?php if (!empty($shipping_heading)): ?>
                <h3 class="text-[15px] font-bold text-white mb-3 mt-8" style="color: #fff !important">
                    <?= esc_html($shipping_heading) ?>
                </h3>
                <?php endif; ?>
                <div class="w-fit max-w-full rounded-2xl border-2 border-dashed border-white/15 bg-white/5 p-4 flex items-center gap-5 flex-wrap">
                    <?php foreach ($shipping_images as $ship) : 
                        $img_src = '';
                        if (!empty($ship['image'])) {
                            if (is_array($ship['image'])) {
                                $img_src = $ship['image']['url'] ?? '';
                            } elseif (is_numeric($ship['image'])) {
                                $img_src = wp_get_attachment_image_url($ship['image'], 'full');
                            } elseif (is_string($ship['image'])) {
                                $img_src = $ship['image'];
                            }
                        }
                        if (!$img_src) continue;
                        $alt_val = !empty($ship['alt_text']) ? $ship['alt_text'] : (is_array($ship['image']) ? ($ship['image']['alt'] ?? $ship['image']['title'] ?? 'Shipping Partner') : 'Shipping Partner');
                    ?>
                        <img src="<?= esc_url($img_src) ?>" alt="<?= esc_attr($alt_val) ?>" class="footer-shipping-logo object-contain h-10 sm:h-11 w-auto hover:scale-105 transition-transform" style="height: 40px; max-height: 44px; width: auto; max-width: 150px; object-fit: contain;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Column 2 -->
        <?php $render_footer_menu('footer_trending'); ?>

        <!-- Column 3 -->
        <div>
            <?php $render_footer_menu('footer_quick'); ?>
            <?php $render_footer_menu('footer_cities', 'mt-10'); ?>
        </div>

        <!-- Column 4 -->
        <?php $render_footer_menu('footer_info'); ?>
    </div>

    <div class="relative z-10 border-t border-white/10">
        <div class="container-site flex flex-col items-center justify-between gap-3 py-5 text-xs text-ink-foreground/60 sm:flex-row">
            <?php if (!empty($copyright_text)): ?>
                <p><?= wp_kses_post($copyright_text) ?></p>
            <?php else: ?>
                <p>&copy; <?= esc_html(date('Y')) ?> <?= esc_html(get_bloginfo('name') ?: 'Waklert Australia') ?>. All rights reserved.</p>
            <?php endif; ?>
            <?php if (function_exists('is_product') && is_product()): ?>
                <a href="https://www.flaticon.com/uicons" class="transition-colors hover:text-white" target="_blank" rel="noopener noreferrer">UIcons by Flaticon</a>
            <?php endif; ?>
        </div>
    </div>
</footer>

<script>console.log("🚀 InstaWP Auto-Deploy is fully operational!");</script>
<?php wp_footer(); ?>
</body>
</html>
