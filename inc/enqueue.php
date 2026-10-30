<?php
/**
 * Enqueue scripts and styles
 */

function modmy_enqueue_scripts() {
    // 1. Google Fonts (Manrope, Inter & Caveat for cursive Best Seller notes)
    wp_enqueue_style(
        'modmy-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap',
        array(),
        null
    );

    if (function_exists('is_product') && is_product()) {
        wp_enqueue_style(
            'waklert-flaticon-uicons',
            'https://cdn-uicons.flaticon.com/4.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css',
            array(),
            '4.0.0'
        );
    }

    // 2. Main Tailwind CSS
    // Check if main.css exists in assets/css
    $css_path = MODMY_THEME_DIR . '/assets/css/main.css';
    $css_url  = MODMY_THEME_URI . '/assets/css/main.css';
    
    if (file_exists($css_path)) {
        wp_enqueue_style('modmy-style', $css_url, array(), filemtime($css_path));
        
        // Add robust prose table and typography styles as inline safeguard
        $custom_css = "
            .prose table { width: 100%; max-width: 100%; table-layout: auto; text-align: left; margin: 2rem 0; border-collapse: separate; border-spacing: 0; border: 2px dashed rgba(125, 211, 252, 0.85); border-radius: 1.5rem; overflow: hidden; background-color: #ffffff; box-shadow: 0 4px 20px -4px rgba(12, 65, 97, 0.08); font-size: 0.9375rem; line-height: 1.55; }
            .prose .table-wrapper, .prose figure.wp-block-table { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 2rem 0; border: 2px dashed rgba(125, 211, 252, 0.85); border-radius: 1.5rem; background-color: #ffffff; box-shadow: 0 4px 20px -4px rgba(12, 65, 97, 0.08), 0 2px 6px -1px rgba(12, 65, 97, 0.04); }
            .prose .table-wrapper table, .prose figure.wp-block-table table { margin: 0; border: none; border-radius: 0; box-shadow: none; min-width: 100%; }
            .prose thead { background: linear-gradient(135deg, #0c4161 0%, #082c42 100%); border-bottom: 2px solid rgba(12, 65, 97, 0.3) !important; }
            .prose th, .prose thead th, .prose thead td { font-family: var(--font-heading, inherit); font-weight: 800; font-size: 0.9375rem; letter-spacing: 0.02em; color: #ffffff !important; padding: 1.125rem 1.35rem; vertical-align: middle; border-bottom: 2px solid rgba(12, 65, 97, 0.3) !important; border-right: 1px dashed rgba(255, 255, 255, 0.18) !important; border-left: none !important; border-top: none !important; background: transparent; text-align: left; }
            .prose thead th:last-child, .prose thead td:last-child, .prose th:last-child { border-right: none !important; background: linear-gradient(135deg, #09344e 0%, #062235 100%) !important; border-left: 1px dashed rgba(255, 255, 255, 0.2) !important; }
            .prose thead th *, .prose thead td *, .prose th * { color: #ffffff !important; font-family: var(--font-heading, inherit); font-weight: 800; }
            .prose table:not(:has(thead)) tbody tr:first-child td, .prose table:not(:has(thead)) tbody tr:first-child th { background: linear-gradient(135deg, #0c4161 0%, #082c42 100%) !important; font-family: var(--font-heading, inherit); font-weight: 800; font-size: 0.9375rem; letter-spacing: 0.02em; color: #ffffff !important; padding: 1.125rem 1.35rem; vertical-align: middle; border-bottom: 2px solid rgba(12, 65, 97, 0.3) !important; border-right: 1px dashed rgba(255, 255, 255, 0.18) !important; border-left: none !important; border-top: none !important; }
            .prose table:not(:has(thead)) tbody tr:first-child td *, .prose table:not(:has(thead)) tbody tr:first-child th * { color: #ffffff !important; font-family: var(--font-heading, inherit); font-weight: 800; }
            .prose table:not(:has(thead)) tbody tr:first-child td:last-child, .prose table:not(:has(thead)) tbody tr:first-child th:last-child { border-right: none !important; background: linear-gradient(135deg, #09344e 0%, #062235 100%) !important; border-left: 1px dashed rgba(255, 255, 255, 0.2) !important; }
            .prose tbody tr { background-color: #ffffff; transition: background-color 0.15s ease-in-out; }
            .prose tbody tr:nth-child(even) { background-color: #f8fafc; }
            .prose tbody tr:hover { background-color: #f0f7fd !important; }
            .prose tbody td { padding: 1rem 1.35rem; border-bottom: 1px dashed rgba(186, 230, 253, 0.75) !important; border-right: none !important; border-left: none !important; border-top: none !important; vertical-align: middle; color: #334155; font-size: 0.9375rem; line-height: 1.6; background-color: transparent; }
            .prose tbody td:first-child, .prose tbody th:first-child { font-family: var(--font-heading, inherit); font-weight: 800; color: #0c4161 !important; background-color: rgba(240, 246, 250, 0.5); border-right: 1px dashed rgba(186, 230, 253, 0.65) !important; }
            .prose tbody td:first-child *, .prose tbody th:first-child * { color: #0c4161 !important; font-weight: 800; }
            .prose tbody tr:nth-child(even) td:first-child, .prose tbody tr:nth-child(even) th:first-child { background-color: rgba(240, 246, 250, 0.8); }
            .prose tbody tr:hover td:first-child, .prose tbody tr:hover th:first-child { background-color: rgba(224, 242, 254, 0.65) !important; }
            .prose table:not(:has(.waklert-highlight-cell)) tbody tr td:last-child { background-color: rgba(224, 242, 254, 0.35); border-left: 1px dashed rgba(125, 211, 252, 0.75) !important; color: #0c4161 !important; font-weight: 700; }
            .prose table:not(:has(.waklert-highlight-cell)) tbody tr td:last-child * { color: #0c4161 !important; font-weight: 700; }
            .prose table:not(:has(.waklert-highlight-cell)) tbody tr:nth-child(even) td:last-child { background-color: rgba(224, 242, 254, 0.55); }
            .prose table:not(:has(.waklert-highlight-cell)) tbody tr:hover td:last-child { background-color: rgba(186, 230, 253, 0.75) !important; }
            .prose tbody tr:last-child td, .prose tbody tr:last-child th { border-bottom: none !important; }
            .prose th.waklert-highlight-header, .prose thead th.waklert-highlight-header, .prose thead td.waklert-highlight-header { background: linear-gradient(135deg, #09344e 0%, #062235 100%) !important; color: #ffffff !important; font-weight: 800; border-left: 2px dashed rgba(251, 191, 36, 0.65) !important; }
            .prose td.waklert-highlight-cell, .prose tbody tr td.waklert-highlight-cell { background-color: rgba(224, 242, 254, 0.4) !important; font-weight: 700; color: #0c4161 !important; border-left: 1px dashed rgba(125, 211, 252, 0.8) !important; }
            .prose td.waklert-highlight-cell *, .prose tbody tr td.waklert-highlight-cell * { color: #0c4161 !important; font-weight: 700; }
            .prose tbody tr:nth-child(even) td.waklert-highlight-cell { background-color: rgba(224, 242, 254, 0.6) !important; }
            .prose tbody tr:hover td.waklert-highlight-cell { background-color: rgba(186, 230, 253, 0.8) !important; }
            .prose th.is-featured, .prose td.is-featured { background-color: rgba(224, 242, 254, 0.5) !important; color: #0c4161 !important; font-weight: 700; }
            details .prose a, .faq-answer a, details summary + div a { color: var(--color-primary); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; text-decoration-thickness: 1.5px; }
            details .prose a:hover, .faq-answer a:hover, details summary + div a:hover { color: var(--color-primary-dark); text-decoration-thickness: 2px; }
            details .prose p { margin-top: 0.5rem; margin-bottom: 0.5rem; }
            details .prose > :first-child { margin-top: 0; }
            details .prose > :last-child { margin-bottom: 0; }
        ";
        wp_add_inline_style('modmy-style', $custom_css);
    } else {
        // Fallback to style.css if Tailwind hasn't run yet
        wp_enqueue_style('modmy-style-fallback', get_stylesheet_uri(), array(), MODMY_THEME_VERSION);
    }

    // 3. Main JavaScript
    wp_enqueue_script(
        'modmy-main-js',
        MODMY_THEME_URI . '/assets/js/main.js',
        array('jquery'),
        file_exists(MODMY_THEME_DIR . '/assets/js/main.js') ? filemtime(MODMY_THEME_DIR . '/assets/js/main.js') : MODMY_THEME_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'modmy_enqueue_scripts');
