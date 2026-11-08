<?php
/** Module: product_faqs. Sub fields: heading, faq_items (question, answer). Same open two-column cards as the homepage. */
ivermectin_render_faq_section(
    trim((string) get_sub_field('heading')),
    (array) (get_sub_field('faq_items') ?: []),
    'bg-surface'
);
