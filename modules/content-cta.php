<?php
/**
 * Module: Call to Action (CTA)
 */

$heading = get_sub_field('heading') ?: get_sub_field('title') ?: "Need Help?";

$is_buyers_guide = is_page('buyers-guide');
$desc = get_sub_field('description') ?: ($is_buyers_guide
    ? "Compare the current product listings and confirm the details shown at checkout before ordering."
    : "We are available all the time to handle any questions, offer advice or reassure you about your purchase.");

$btn = get_sub_field('button_text') ?: ($is_buyers_guide ? "Browse Products" : "WhatsApp Us");
$wa_number = preg_replace('/[^0-9]/', '', get_field('whatsapp_number', 'option') ?: '61488841833');
$btn_url = get_sub_field('button_url') ?: ($is_buyers_guide ? home_url('/shop/') : "https://wa.me/{$wa_number}");

$btn2 = get_sub_field('secondary_button_text') ?: ($is_buyers_guide ? "Contact Us" : "Send an email");
$default_cta_email = get_field('contact_email', 'option') ?: 'orders@waklertaustralia.com.au';
if (strpos($default_cta_email, 'example.com') !== false || strpos($default_cta_email, 'hoppingmad') !== false || strpos($default_cta_email, 'modaaust') !== false) {
    $default_cta_email = 'orders@waklertaustralia.com.au';
}
$btn2_url = get_sub_field('secondary_button_url') ?: ('mailto:' . $default_cta_email);
?>
<section class="py-4 pb-10 bg-white" data-testid="cta-section">
    <div class="container-custom max-w-4xl">
        <div class="relative isolate overflow-hidden rounded-2xl bg-primary p-8 text-center text-white shadow-sm md:p-10 not-prose">
            <?php waklert_output_pattern_rings(); ?>
            <div class="relative z-10">
                <h2 class="font-heading font-black text-2xl md:text-3xl mb-3 text-white">
                    <?= $heading ?>
                </h2>
                <p class="text-white/85 mb-6 max-w-2xl mx-auto font-medium text-sm md:text-base leading-relaxed">
                    <?= $desc ?>
                </p>
                <div class="flex flex-row gap-3 sm:gap-4 justify-center items-center">
                    <a href="<?= esc_url($btn_url) ?>" class="inline-flex items-center justify-center gap-2 bg-white text-primary-dark font-bold px-4 py-2 sm:px-7 sm:py-3 rounded-full hover:bg-accent transition-colors shadow-sm text-xs sm:text-sm whitespace-nowrap">
                        <?= $btn ?>
                    </a>
                    <a href="<?= esc_url($btn2_url) ?>" class="inline-flex items-center justify-center gap-2 border-2 border-white/50 text-white font-bold px-4 py-2 sm:px-7 sm:py-3 rounded-full hover:border-accent hover:bg-accent hover:text-ink transition-colors text-xs sm:text-sm whitespace-nowrap">
                        <?= $btn2 ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
