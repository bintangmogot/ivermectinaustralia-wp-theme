<?php
/**
 * Module: faqs. Sub fields: show_eyebrow, eyebrow, heading, faq_items (question, answer),
 * show_full_faq_link, link_text, link_url. Same open two-column cards as the homepage.
 */
$eyebrow   = get_sub_field('show_eyebrow') ? trim((string) get_sub_field('eyebrow')) : '';
$link_text = get_sub_field('show_full_faq_link') ? trim((string) get_sub_field('link_text')) : '';
ivermectin_render_faq_section(
    trim((string) get_sub_field('heading')),
    (array) (get_sub_field('faq_items') ?: []),
    'bg-surface',
    '',
    $eyebrow,
    $link_text,
    trim((string) get_sub_field('link_url'))
);
