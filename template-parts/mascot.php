<?php
/**
 * Decorative mascot: a scored tablet carrying a parcel. No text, hidden from screen readers.
 * Args: pose ('deliver' stands with the parcel and waves, 'peek' shows the top half waving), class.
 */
defined('ABSPATH') || exit;

$pose  = ($args['pose'] ?? 'deliver') === 'peek' ? 'peek' : 'deliver';
$class = trim('iva-mascot iva-mascot-' . $pose . ' ' . ($args['class'] ?? ''));
$view  = $pose === 'peek' ? '0 0 160 112' : '0 0 160 190';
?>
<svg class="<?= esc_attr($class) ?>" viewBox="<?= esc_attr($view) ?>" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
    <g class="iva-mascot-body">
        <?php if ($pose === 'deliver') : ?>
            <ellipse cx="80" cy="182" rx="38" ry="5" fill="#012c70" opacity="0.08"/>
            <path d="M66 146v26M94 146v26" stroke="#012c70" stroke-width="5" stroke-linecap="round"/>
            <path d="M58 174h12M90 174h12" stroke="#012c70" stroke-width="6" stroke-linecap="round"/>
        <?php endif; ?>

        <ellipse cx="80" cy="92" rx="56" ry="58" fill="#dbe8fb"/>
        <ellipse cx="80" cy="86" rx="56" ry="56" fill="#ffffff" stroke="#c9d9f1" stroke-width="2"/>
        <path d="M44 56c10-12 24-18 36-18" stroke="#eef4fc" stroke-width="6" stroke-linecap="round"/>
        <path d="M80 34v12" stroke="#c9d9f1" stroke-width="3" stroke-linecap="round"/>

        <g class="iva-mascot-eyes">
            <ellipse cx="62" cy="82" rx="5.5" ry="7" fill="#012c70"/>
            <ellipse cx="98" cy="82" rx="5.5" ry="7" fill="#012c70"/>
            <circle cx="64" cy="79" r="1.8" fill="#fff"/>
            <circle cx="100" cy="79" r="1.8" fill="#fff"/>
        </g>
        <ellipse cx="50" cy="98" rx="7" ry="4" fill="#ffd3a1" opacity="0.8"/>
        <ellipse cx="110" cy="98" rx="7" ry="4" fill="#ffd3a1" opacity="0.8"/>
        <path d="M70 100c5 6 15 6 20 0" stroke="#012c70" stroke-width="3.5" stroke-linecap="round"/>

        <g class="iva-mascot-wave">
            <path d="M134 82c10-6 16-16 18-28" stroke="#012c70" stroke-width="5" stroke-linecap="round"/>
            <circle cx="152" cy="50" r="6" fill="#ffffff" stroke="#012c70" stroke-width="3.5"/>
        </g>

        <?php if ($pose === 'deliver') : ?>
            <path d="M26 96c-8 6-12 16-10 26" stroke="#012c70" stroke-width="5" stroke-linecap="round"/>
            <g transform="rotate(-8 22 132)">
                <rect x="2" y="114" width="40" height="32" rx="4" fill="#ffb870"/>
                <rect x="2" y="114" width="40" height="9" rx="3" fill="#ff9f45"/>
                <path d="M22 114v32" stroke="#fff6ea" stroke-width="5"/>
                <circle cx="22" cy="134" r="4" fill="#012c70"/>
            </g>
        <?php else : ?>
            <path d="M26 82c-10-4-16-12-18-22" stroke="#012c70" stroke-width="5" stroke-linecap="round"/>
            <circle cx="8" cy="56" r="6" fill="#ffffff" stroke="#012c70" stroke-width="3.5"/>
        <?php endif; ?>
    </g>
</svg>
