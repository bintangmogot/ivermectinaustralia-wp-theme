<?php
/**
 * Module: Terms Contact Callout / Support Callout
 * Dedicated card matching the Terms & Conditions WhatsApp callout
 */

$heading = get_sub_field('heading') ?: get_sub_field('title') ?: "Have questions about our terms?";
$description = get_sub_field('description') ?: get_sub_field('desc') ?: "Contact our legal support team via WhatsApp for any clarifications before placing your order.";
$btn_label = get_sub_field('btn_label') ?: get_sub_field('button_label') ?: "Support WhatsApp";
$raw_number = get_sub_field('whatsapp_number') ?: get_sub_field('chat_btn_url');
$wa_url = function_exists('waklert_format_whatsapp_url') ? waklert_format_whatsapp_url($raw_number) : 'https://wa.me/61488841833';
?>
<section class="section-padding bg-white" data-testid="terms-contact-callout">
    <div class="container-custom max-w-3xl">
        <div class="bg-slate-900 text-white rounded-2xl p-8 text-center shadow-lg">
            <h2 class="font-heading font-extrabold text-2xl mb-3">
                <?= esc_html($heading) ?>
            </h2>
            <p class="text-slate-300 text-sm mb-6 max-w-lg mx-auto leading-relaxed">
                <?= esc_html($description) ?>
            </p>
            <a href="<?= esc_url($wa_url) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-accent text-accent-foreground font-bold px-7 py-3.5 rounded-full hover:brightness-105 transition-colors shadow-md text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                </svg>
                <?= esc_html($btn_label) ?>
            </a>
        </div>
    </div>
</section>
