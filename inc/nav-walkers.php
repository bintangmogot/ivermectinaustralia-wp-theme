<?php
/**
 * Navigation walkers for the desktop menu (hover dropdown) and the mobile drawer (accordion).
 * Menu items come from Appearance > Menus. Nothing is hardcoded here.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Ivermectin_Desktop_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu absolute left-0 top-full z-50 hidden min-w-60 rounded-lg border border-border bg-white p-2 shadow-lg group-hover:block group-focus-within:block">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', (array) $item->classes, true);
        $is_active    = in_array('current-menu-item', (array) $item->classes, true)
            || in_array('current-menu-ancestor', (array) $item->classes, true);

        $link_class = $depth === 0
            ? 'iva-nav-link inline-flex items-center gap-1 px-3 py-2 text-[15px] font-semibold text-foreground hover:text-primary' . ($is_active ? ' is-active' : '')
            : 'block rounded-md px-3 py-2 text-sm font-medium text-foreground hover:bg-primary-soft hover:text-primary';

        $output .= '<li class="' . ($depth === 0 ? 'group relative' : '') . '">';
        $output .= '<a href="' . esc_url($item->url) . '" class="' . esc_attr($link_class) . '"' . ($is_active ? ' aria-current="page"' : '') . '>';
        $output .= esc_html($item->title);
        if ($has_children && $depth === 0) {
            $output .= '<svg class="h-4 w-4 text-primary-light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';
        }
        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

class Ivermectin_Mobile_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu ml-3 border-l border-border pl-3">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', (array) $item->classes, true);
        $classes      = $has_children ? 'menu-item-has-children' : '';

        $output .= '<li class="' . $classes . ' border-b border-border last:border-b-0"><div class="flex items-center">';
        $output .= '<a href="' . esc_url($item->url) . '" class="flex-1 px-1 py-3 text-base font-semibold text-foreground">' . esc_html($item->title) . '</a>';
        if ($has_children) {
            $output .= '<button type="button" class="mobile-submenu-toggle grid h-10 w-10 place-items-center text-primary" aria-label="Toggle submenu">'
                . '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></button>';
        }
        $output .= '</div>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}
