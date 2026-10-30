<?php
/**
 * Custom Navigation Walkers for Waklert Australia
 * Provides vibrant, 2-tone dashed mega-dropdown and active menu items.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper to fetch product metadata for rich navigation cards
 */
function waklert_get_product_menu_meta($title, $url = '') {
    $meta = [
        'badge'          => '',
        'badge_bg'       => '',
        'is_best_seller' => false,
        'brand'          => 'Pharmaceutical Grade',
        'price'          => 'From $125',
        'icon_color'     => '#0284C7',
        'dot_color'      => '#F59E0B',
    ];

    $lower = strtolower($title);
    if (strpos($lower, 'waklert 150') !== false) {
        $meta['badge']          = 'Best Seller';
        $meta['is_best_seller'] = true;
        $meta['brand']          = 'Sun Pharma • 150mg';
        $meta['price']          = 'From $150 ($3/tab)';
        $meta['icon_color']     = '#0C4161';
        $meta['dot_color']      = '#F59E0B';
    } elseif (strpos($lower, 'waklert 50') !== false) {
        $meta['brand']      = 'Sun Pharma • 50mg';
        $meta['price']      = 'From $95';
        $meta['icon_color'] = '#10B981';
        $meta['dot_color']  = '#0C4161';
    } elseif (strpos($lower, 'artvigil') !== false) {
        $meta['brand']      = 'HAB Pharma • 150mg';
        $meta['price']      = 'From $125';
        $meta['icon_color'] = '#0284C7';
        $meta['dot_color']  = '#6366F1';
    } elseif (strpos($lower, 'armodacharge') !== false) {
        $meta['brand']      = 'Centurion Labs • 150mg';
        $meta['price']      = 'From $125';
        $meta['icon_color'] = '#8B5CF6';
        $meta['dot_color']  = '#38BDF8';
    } elseif (strpos($lower, 'armod 150') !== false) {
        $meta['brand']      = 'Signature Brand • 150mg';
        $meta['price']      = 'From $220';
        $meta['icon_color'] = '#D97706';
        $meta['dot_color']  = '#0C4161';
    } elseif (strpos($lower, 'armod 50') !== false) {
        $meta['brand']      = 'Signature Brand • 50mg';
        $meta['price']      = 'From $180';
        $meta['icon_color'] = '#0D9488';
        $meta['dot_color']  = '#F59E0B';
    }

    return $meta;
}

/**
 * Helper to fetch product thumbnail image URL for rich navigation cards
 */
function waklert_get_product_menu_image($title, $url = '', $object_id = 0) {
    $img_url = '';
    if ($object_id) {
        $img_url = get_the_post_thumbnail_url($object_id, 'woocommerce_thumbnail') ?: get_the_post_thumbnail_url($object_id, 'thumbnail');
    }
    if (!$img_url && $url) {
        $post_id = url_to_postid($url);
        if ($post_id) {
            $img_url = get_the_post_thumbnail_url($post_id, 'woocommerce_thumbnail') ?: get_the_post_thumbnail_url($post_id, 'thumbnail');
        }
    }
    if (!$img_url) {
        $lower = strtolower($title);
        $fallback_ids = [
            'waklert 150'  => 1174,
            'waklert 50'   => 1179,
            'artvigil'     => 1175,
            'armodacharge' => 1178,
            'armod 150'    => 1176,
            'armod 50'     => 1177,
        ];
        foreach ($fallback_ids as $key => $id) {
            if (strpos($lower, $key) !== false) {
                $img_url = get_the_post_thumbnail_url($id, 'woocommerce_thumbnail') ?: get_the_post_thumbnail_url($id, 'thumbnail');
                break;
            }
        }
    }
    return $img_url;
}

/**
 * Desktop Navigation Walker
 */
class Waklert_Desktop_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            // Dropdown wrapper with 2-tone dashed container and hover bridge
            $output .= '<div class="sub-menu-dropdown-wrapper absolute top-full left-[-80px] pt-2.5 w-[625px] max-w-[92vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-200 z-50 pointer-events-none group-hover:pointer-events-auto filter drop-shadow-2xl">';
            $output .= '<div class="rounded-2xl border-2 border-dashed border-sky-300/90 bg-white overflow-hidden shadow-2xl">';
            
            // Dropdown Header Strip
            $output .= '<div class="bg-gradient-to-r from-[#0c4161] via-[#0e4a70] to-[#125883] px-4 py-2.5 flex items-center justify-between border-b border-sky-400/20">';
            $output .= '<div class="flex items-center gap-2">';
            $output .= '<span class="flex h-5 w-5 items-center justify-center rounded-lg bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs font-black">★</span>';
            $output .= '<span class="text-xs font-black uppercase tracking-wider text-white">Genuine Armodafinil Catalogue</span>';
            $output .= '</div>';
            $output .= '<span class="text-[11px] font-bold text-sky-200 bg-white/10 px-2.5 py-0.5 rounded-full border border-sky-300/30">AU Express 7-10 Days</span>';
            $output .= '</div>';

            // 2-Column Product Grid
            $output .= '<ul class="sub-menu grid grid-cols-2 gap-2.5 p-3.5 bg-gradient-to-b from-[#f8fafc] to-[#ffffff] bg-pattern-dots">';
        } else {
            $output .= '<ul class="sub-menu pl-4 space-y-1">';
        }
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= '</ul>';

            // Dropdown Footer Strip
            $shop_url = esc_url(home_url('/shop/'));
            $output .= '<div class="bg-gradient-to-r from-sky-50 via-slate-50 to-amber-50/60 px-4 py-3 border-t-2 border-dashed border-sky-200/80 flex items-center justify-between text-xs">';
            $output .= '<div class="flex items-center gap-1.5 font-bold text-slate-700">';
            $output .= '<svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>';
            $output .= '<span>Free Express Delivery on $299+</span>';
            $output .= '</div>';
            $output .= '<a href="' . $shop_url . '" class="inline-flex items-center gap-1.5 font-black text-[#0c4161] hover:text-amber-600 bg-white hover:bg-sky-50 px-3 py-1.5 rounded-full border-2 border-dashed border-sky-300 shadow-sm transition-all hover:scale-105">';
            $output .= '<span>Explore All Products</span>';
            $output .= '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>';
            $output .= '</a>';
            $output .= '</div>';

            $output .= '</div></div>';
        } else {
            $output .= '</ul>';
        }
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        $url = !empty($item->url) ? esc_url($item->url) : '#';
        $title = esc_html($item->title);

        $is_shop_link = (strpos($url, '/shop/') !== false);
        $is_shop_active = $is_shop_link && (function_exists('is_shop') && is_shop() || function_exists('is_product') && is_product());

        $is_active = in_array('current-menu-item', $classes)
                  || in_array('current-menu-ancestor', $classes)
                  || in_array('current-page-ancestor', $classes)
                  || $item->current
                  || $is_shop_active;

        if ($depth === 0) {
            $li_classes = 'list-none relative group ' . implode(' ', $classes);
            $output .= '<li class="' . esc_attr($li_classes) . '">';

            if ($is_active) {
                // Active 2-Tone Nav Pill
                $link_class = 'inline-flex items-center gap-2 px-4 py-1.5 text-[15.5px] xl:text-[16.5px] font-extrabold rounded-full bg-gradient-to-r from-[#0c4161] via-[#0e4a70] to-[#125883] text-white shadow-md shadow-[#0c4161]/25 border-2 border-dashed border-amber-400 outline-none focus:outline-none focus:ring-0 transition-all duration-200';
                $dot_indicator = '';
                $chev_color = 'text-amber-300';
            } else {
                // Inactive Nav Link
                $link_class = 'inline-flex items-center gap-1.5 px-4 py-1.5 text-[15.5px] xl:text-[16.5px] font-bold rounded-full text-slate-700 hover:text-[#0c4161] hover:bg-sky-50/90 border-2 border-dashed border-transparent hover:border-sky-300/80 outline-none focus:outline-none focus:ring-0 transition-all duration-200 hover:-translate-y-0.5';
                $dot_indicator = '';
                $chev_color = 'text-slate-400 group-hover:text-[#0c4161]';
            }

            $chevron = '';
            if ($has_children) {
                $chevron = '<svg class="w-3.5 h-3.5 ' . $chev_color . ' transition-transform duration-200 group-hover:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>';
            }

            $output .= '<a href="' . $url . '" class="' . $link_class . '">';
            $output .= $dot_indicator . '<span>' . $title . '</span>' . $chevron;
            $output .= '</a>';
        } else {
            // Submenu Item (Product Micro-Card inside dropdown)
            $meta = waklert_get_product_menu_meta($title, $url, $item->object_id);
            $img_url = waklert_get_product_menu_image($title, $url, $item->object_id);

            $output .= '<li class="list-none">';
            $output .= '<a href="' . $url . '" class="group/pcard flex items-center gap-3 p-2.5 rounded-xl border-2 border-dashed border-sky-200/80 hover:border-[#0c4161] bg-white hover:bg-sky-50/70 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">';
            
            // Product Image Box (exact same place with zoom hover)
            $output .= '<div class="w-12 h-12 rounded-xl bg-white border-2 border-dashed border-sky-200/90 p-1 flex items-center justify-center shrink-0 group-hover/pcard:scale-105 group-hover/pcard:border-[#0c4161] group-hover/pcard:shadow-sm transition-all overflow-hidden">';
            if ($img_url) {
                $output .= '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '" class="w-full h-full object-contain transition-transform duration-300 group-hover/pcard:scale-110" loading="lazy" />';
            } else {
                $output .= '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                $output .= '<rect x="3" y="7" width="18" height="10" rx="5" fill="' . $meta['icon_color'] . '" fill-opacity="0.18" stroke="' . $meta['icon_color'] . '" stroke-width="1.8"/>';
                $output .= '<path d="M12 7V17" stroke="#0C4161" stroke-width="1.8" stroke-dasharray="2 2"/>';
                $output .= '<circle cx="7.5" cy="12" r="1.5" fill="' . $meta['dot_color'] . '"/>';
                $output .= '<circle cx="16.5" cy="12" r="1.5" fill="' . $meta['icon_color'] . '"/>';
                $output .= '</svg>';
            }
            $output .= '</div>';

            // Text Info
            $output .= '<div class="min-w-0 flex-1">';
            $output .= '<div class="flex items-center justify-between gap-1">';
            $output .= '<h4 class="text-[13px] font-extrabold text-[#0c4161] whitespace-nowrap group-hover/pcard:text-[#082a3f]">' . $title . '</h4>';
            if (!empty($meta['is_best_seller'])) {
                $output .= '<span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-300/80 shrink-0 shadow-2xs">';
                $output .= '<svg class="w-3 h-3 text-amber-600 shrink-0 animate-point-to-product" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>';
                $output .= '<span class="font-caveat font-bold text-[13px] leading-none text-amber-950 tracking-normal select-none">Best Seller</span>';
                $output .= '</span>';
            }
            $output .= '</div>';
            $output .= '<p class="text-[11px] text-slate-500 font-medium truncate">' . $meta['brand'] . '</p>';
            $output .= '<div class="mt-1 flex items-center justify-between">';
            $output .= '<span class="text-[10.5px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">' . $meta['price'] . '</span>';
            $output .= '<span class="text-[11px] font-bold text-slate-400 group-hover/pcard:text-[#0c4161] group-hover/pcard:translate-x-0.5 transition-all">→</span>';
            $output .= '</div>';
            $output .= '</div>';

            $output .= '</a>';
            $output .= '</li>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

/**
 * Mobile Navigation Walker
 */
class Waklert_Mobile_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu is-open pl-2 pr-1 pt-2 pb-2.5 space-y-2 transition-all">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);
        $url = !empty($item->url) ? esc_url($item->url) : '#';
        $title = esc_html($item->title);

        $is_shop_link = (strpos($url, '/shop/') !== false);
        $is_shop_active = $is_shop_link && (function_exists('is_shop') && is_shop() || function_exists('is_product') && is_product());

        $is_active = in_array('current-menu-item', $classes)
                  || in_array('current-menu-ancestor', $classes)
                  || in_array('current-page-ancestor', $classes)
                  || $item->current
                  || $is_shop_active;

        if ($depth === 0) {
            $output .= '<li class="list-none w-full py-1.5 ' . implode(' ', $classes) . '">';
            $output .= '<div class="flex items-center justify-between gap-1.5 w-full">';

            if ($is_active) {
                // Active 2-Tone Mobile Pill
                $link_class = 'flex-1 flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-gradient-to-r from-[#0c4161] to-[#125883] text-white font-extrabold text-[15px] border-2 border-dashed border-amber-400 shadow-sm';
                $indicator = '';
            } else {
                // Inactive Mobile Link
                $link_class = 'flex-1 flex items-center gap-2 px-4 py-3 rounded-2xl text-slate-800 hover:text-[#0c4161] hover:bg-sky-50 font-bold text-[15px] border-2 border-dashed border-transparent hover:border-sky-200 transition-all';
                $indicator = '';
            }

            $output .= '<a href="' . $url . '" class="' . $link_class . '">';
            $output .= $indicator . '<span>' . $title . '</span>';
            $output .= '</a>';

            if ($has_children) {
                $output .= '<button type="button" class="mobile-submenu-toggle is-active flex items-center justify-center w-11 h-11 rounded-2xl bg-white border-2 border-dashed border-sky-300 text-[#0c4161] hover:bg-sky-50 transition-colors shrink-0" aria-expanded="true" aria-label="Toggle submenu">';
                $output .= '<svg class="w-4.5 h-4.5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>';
                $output .= '</button>';
            }

            $output .= '</div>';
        } else {
            // Submenu Item in mobile drawer
            $meta = waklert_get_product_menu_meta($title, $url, $item->object_id);
            $img_url = waklert_get_product_menu_image($title, $url, $item->object_id);

            $output .= '<li class="list-none w-full">';
            $output .= '<a href="' . $url . '" class="flex items-center gap-2.5 p-2.5 rounded-xl border-2 border-dashed border-sky-200/90 hover:border-[#0c4161] bg-white transition-all">';
            
            // Product Image Box (exact same place)
            $output .= '<div class="w-11 h-11 rounded-xl bg-white border-2 border-dashed border-sky-200/90 p-1 flex items-center justify-center shrink-0 overflow-hidden">';
            if ($img_url) {
                $output .= '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '" class="w-full h-full object-contain" loading="lazy" />';
            } else {
                $output .= '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                $output .= '<rect x="3" y="7" width="18" height="10" rx="5" fill="' . $meta['icon_color'] . '" fill-opacity="0.18" stroke="' . $meta['icon_color'] . '" stroke-width="1.8"/>';
                $output .= '<path d="M12 7V17" stroke="#0C4161" stroke-width="1.8" stroke-dasharray="2 2"/>';
                $output .= '<circle cx="7.5" cy="12" r="1.5" fill="' . $meta['dot_color'] . '"/>';
                $output .= '<circle cx="16.5" cy="12" r="1.5" fill="' . $meta['icon_color'] . '"/>';
                $output .= '</svg>';
            }
            $output .= '</div>';

            // Text
            $output .= '<div class="min-w-0 flex-1">';
            $output .= '<div class="flex items-center justify-between gap-1">';
            $output .= '<span class="text-xs font-bold text-[#0c4161] truncate">' . $title . '</span>';
            if (!empty($meta['is_best_seller'])) {
                $output .= '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-300/80 shrink-0 shadow-2xs">';
                $output .= '<svg class="w-3 h-3 text-amber-600 shrink-0 animate-point-to-product" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>';
                $output .= '<span class="font-caveat font-bold text-[13px] leading-none text-amber-950 tracking-normal select-none">Best Seller</span>';
                $output .= '</span>';
            }
            $output .= '</div>';
            $output .= '<div class="flex items-center justify-between text-[10px] text-slate-500 font-medium">';
            $output .= '<span class="truncate">' . $meta['brand'] . '</span>';
            $output .= '<span class="font-bold text-emerald-700 bg-emerald-50 px-1 rounded">' . $meta['price'] . '</span>';
            $output .= '</div>';
            $output .= '</div>';

            $output .= '</a>';
            $output .= '</li>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}
