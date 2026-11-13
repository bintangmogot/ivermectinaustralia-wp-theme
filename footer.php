<?php
/**
 * Site footer. Every string comes from Theme Settings, menus, or the previous theme's options fields.
 */
$opt = static function ($name) {
    return function_exists('get_field') ? get_field($name, 'option') : '';
};
$img_url = static function ($image) {
    if (is_array($image)) {
        return $image['url'] ?? '';
    }
    return is_numeric($image) ? (string) wp_get_attachment_image_url((int) $image, 'full') : (string) $image;
};

$description   = ivermectin_get_footer_description();
$address       = trim((string) $opt('location_text'));
$email         = trim((string) ($opt('support_email') ?: $opt('footer_email')));
$phone_label   = trim((string) ($opt('whatsapp_button_text') ?: $opt('whatsapp_number')));
$phone_href    = preg_replace('/\D+/', '', (string) $opt('whatsapp_number'));
$working_days  = trim((string) $opt('working_days'));
$office_hours  = trim((string) $opt('office_hours'));
$contact_head  = trim((string) $opt('footer_contact_heading'));
$disclaimer    = trim((string) $opt('disclaimer_en'));
$copyright     = trim((string) $opt('copyright_text'));
$ssl_text      = trim((string) $opt('ssl_text'));
$dispatch_text = trim((string) $opt('dispatch_text'));
$checkout_text = trim((string) $opt('checkout_text'));

$socials = array_filter([
    'Facebook'  => trim((string) $opt('facebook_link')),
    'Instagram' => trim((string) $opt('instagram_link')),
    'X'         => trim((string) $opt('twitter_link')),
    'LinkedIn'  => trim((string) $opt('linkedin_link')),
]);
$social_paths = [
    'Facebook'  => 'M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z',
    'Instagram' => 'M7.8 2h8.4A5.8 5.8 0 0 1 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8A5.8 5.8 0 0 1 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2zm-.2 2A3.6 3.6 0 0 0 4 7.6v8.8A3.6 3.6 0 0 0 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6A3.6 3.6 0 0 0 16.4 4H7.6zm9.65 1.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z',
    'X'         => 'M17.8 3h3.1l-6.8 7.8L22 21h-6.2l-4.9-6.3L5.3 21H2.2l7.3-8.3L2 3h6.4l4.4 5.8L17.8 3zm-1.1 16.2h1.7L7.4 4.7H5.6l11.1 14.5z',
    'LinkedIn'  => 'M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9.75h4v11.5H3V9.75zm6.5 0h3.8v1.6h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1v5.85h-4v-5.2c0-1.24-.02-2.84-1.73-2.84-1.73 0-2 1.35-2 2.75v5.29h-4V9.75z',
];

$locations = get_nav_menu_locations();
$footer_sections = [];
$footer_sources = [
    'footer_menu' => ['footer_trending'],
    'footer_menu_2' => ['footer_quick', 'footer_cities'],
    'footer_menu_3' => ['footer_info'],
];
foreach ($footer_sources as $field => $slots) {
    $groups = ivermectin_parse_footer_links((string) $opt($field));
    foreach ($slots as $index => $location) {
        $menu = !empty($locations[$location]) ? wp_get_nav_menu_object($locations[$location]) : false;
        if ($menu && !is_wp_error($menu)) {
            $footer_sections[] = ['location' => $location, 'title' => $menu->name, 'wide' => count((array) wp_get_nav_menu_items($menu->term_id)) > 9];
        } elseif (isset($groups[$index])) {
            $footer_sections[] = $groups[$index];
        }
    }
    // Preserve any additional saved groups, even when an editor assigns a menu.
    foreach (array_slice($groups, count($slots)) as $group) {
        $footer_sections[] = $group;
    }
}

$pre_footer_enabled = $opt('pre_footer_badges_enabled');
$pre_footer_badges  = $opt('pre_footer_badges');
?>
<?php
if (is_front_page() && empty($GLOBALS['ivermectin_support_cta_rendered'])) {
    get_template_part('modules/content', 'support_cta');
}
?>
<footer class="relative isolate overflow-hidden bg-ink text-ink-foreground">
    <?php ivermectin_output_pattern_rings(); ?>

    <?php if (!is_front_page() && !empty($pre_footer_enabled) && is_array($pre_footer_badges)) : ?>
    <div class="relative z-10 border-b border-white/10">
        <div class="container-site grid grid-cols-2 gap-4 py-6 md:grid-cols-4">
            <?php foreach ($pre_footer_badges as $badge) :
                $badge_title = trim((string) ($badge['title'] ?? ''));
                if ($badge_title === '') { continue; }
                $badge_icon = ($badge['icon'] ?? '') === 'custom'
                    ? ($img_url($badge['custom_icon'] ?? '') ? '<img src="' . esc_url($img_url($badge['custom_icon'])) . '" alt="" class="h-10 w-10 object-contain">' : '')
                    : ivermectin_icon((string) ($badge['icon'] ?? ''), 'h-10 w-10');
                ?>
                <div class="flex items-center gap-3">
                    <?php if ($badge_icon !== '') : ?><span class="grid h-12 w-12 shrink-0 place-items-center rounded-lg bg-white"><?= $badge_icon ?></span><?php endif; ?>
                    <div>
                        <p class="text-sm font-bold text-white"><?= esc_html($badge_title) ?></p>
                        <?php if (!empty($badge['subtitle'])) : ?><p class="text-xs text-white/70"><?= esc_html($badge['subtitle']) ?></p><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="container-site relative z-10 py-12 md:py-16">
        <div class="iva-footer-grid grid grid-cols-2 gap-x-6 gap-y-10 xl:grid-cols-[minmax(22rem,2.3fr)_minmax(0,1.25fr)_minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)] xl:gap-x-10">
            <div>
                <?php ivermectin_the_logo('h-11 w-auto object-contain', 'footer'); ?>
                <?php if (trim($description) !== '') : ?><div class="iva-footer-description mt-4 text-sm leading-relaxed text-white/80"><?= wp_kses_post(wpautop($description)) ?></div><?php endif; ?>
                <?php if ($contact_head !== '') : ?><p class="mt-6 font-heading text-base font-bold text-white"><?= esc_html($contact_head) ?></p><?php endif; ?>
                <?php if ($address !== '' || $email !== '' || $phone_label !== '') : ?>
                    <ul class="mt-3 space-y-3 text-sm text-white/80">
                        <?php if ($address !== '') : ?><li class="flex items-start gap-3"><?= ivermectin_icon('australia_delivery', 'h-6 w-6') ?><span><?= esc_html($address) ?></span></li><?php endif; ?>
                        <?php if ($phone_label !== '') : ?><li class="flex items-start gap-3"><?= ivermectin_icon('live_chat_support', 'h-6 w-6') ?><?php if ($phone_href !== '') : ?><a href="<?= esc_url(ivermectin_tel_url($phone_href)) ?>" class="hover:text-white"><?= esc_html($phone_label) ?></a><?php else : ?><span><?= esc_html($phone_label) ?></span><?php endif; ?></li><?php endif; ?>
                        <?php if ($email !== '') : ?><li class="flex items-start gap-3"><?= ivermectin_icon('email_support', 'h-6 w-6') ?><a href="mailto:<?= esc_attr($email) ?>" class="[overflow-wrap:anywhere] hover:text-white"><?= esc_html($email) ?></a></li><?php endif; ?>
                        <?php if ($working_days !== '' || $office_hours !== '') : ?><li class="flex items-start gap-3"><?= ivermectin_icon('clock_hours', 'h-6 w-6') ?><span><?= esc_html(trim($working_days . ' ' . $office_hours)) ?></span></li><?php endif; ?>
                    </ul>
                <?php endif; ?>
                <?php if ($socials) : ?>
                    <ul class="mt-6 flex gap-2">
                        <?php foreach ($socials as $label => $url) : ?>
                            <li><a href="<?= esc_url($url) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= esc_attr($label) ?>" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition-colors hover:bg-white hover:text-primary"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="<?= esc_attr($social_paths[$label]) ?>"/></svg></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <?php foreach ($footer_sections as $group) : ?>
                <nav aria-label="<?= esc_attr($group['title']) ?>"<?= (!empty($group['wide']) || count($group['links'] ?? []) > 9) ? ' class="iva-footer-wide"' : '' ?>>
                    <?php if ($group['title'] !== '') : ?><h4 class="iva-footer-head"><?= esc_html($group['title']) ?></h4><?php endif; ?>
                    <?php if (isset($group['location'])) : ?>
                        <?php wp_nav_menu(['theme_location' => $group['location'], 'container' => false, 'menu_class' => 'iva-footer-links iva-footer-menu', 'fallback_cb' => false, 'depth' => 1]); ?>
                    <?php else : ?>
                    <ul class="iva-footer-links">
                        <?php foreach ($group['links'] as $link) : ?>
                            <li><a href="<?= esc_url($link['href']) ?>"<?= $link['target'] !== '' ? ' target="' . esc_attr($link['target']) . '"' : '' ?><?= $link['rel'] !== '' ? ' rel="' . esc_attr($link['rel']) . '"' : '' ?>><?= esc_html($link['label']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </nav>
            <?php endforeach; ?>
        </div>

        <?php
        $logo_groups = [
            [trim((string) $opt('footer_payments_heading')), $opt('footer_payment_images')],
            [trim((string) $opt('footer_shipping_heading')), $opt('footer_shipping_images')],
        ];
        $has_logos = false;
        foreach ($logo_groups as [, $group_images]) {
            $has_logos = $has_logos || (!empty($group_images) && is_array($group_images));
        }
        $chips = array_filter([$ssl_text, $dispatch_text, $checkout_text]);
        ?>
        <?php if ($has_logos || $chips) : ?>
        <div class="mt-10 flex flex-wrap items-center gap-x-10 gap-y-5 border-t border-white/10 pt-8">
            <?php foreach ($logo_groups as [$group_heading, $group_images]) :
                if (empty($group_images) || !is_array($group_images)) { continue; } ?>
                <div>
                    <?php if ($group_heading !== '') : ?><p class="mb-2 text-sm font-semibold text-white/70"><?= esc_html($group_heading) ?></p><?php endif; ?>
                    <ul class="flex flex-wrap items-center gap-2">
                        <?php foreach ($group_images as $row) : $url = $img_url($row['image'] ?? ''); if ($url === '') { continue; } ?>
                            <li class="rounded-lg bg-white px-3.5 py-2"><img src="<?= esc_url($url) ?>" alt="<?= esc_attr($row['alt_text'] ?? '') ?>" class="h-6 w-auto object-contain" loading="lazy"></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
            <?php if ($chips) : ?>
                <ul class="flex flex-wrap gap-2 lg:ml-auto">
                    <?php foreach ($chips as $chip) : ?><li class="rounded-full border border-white/20 px-3.5 py-1.5 text-xs font-semibold text-white/85"><?= esc_html($chip) ?></li><?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($description !== '') : ?>
            <div class="iva-footer-about mt-8 border-t border-white/10 pt-8 text-sm leading-relaxed text-white/60"><?= wp_kses_post($description) ?></div>
        <?php endif; ?>

        <?php if ($disclaimer !== '' || $copyright !== '') : ?>
        <div class="mt-8 space-y-2 border-t border-white/10 pt-6 text-xs leading-relaxed text-white/55">
            <?php if ($disclaimer !== '') : ?><p><?= esc_html($disclaimer) ?></p><?php endif; ?>
            <?php if ($copyright !== '') : ?><p>&copy; <?= esc_html(gmdate('Y')) ?> <?= esc_html($copyright) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
