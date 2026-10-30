<?php
/**
 * Module: Contact Form & Info
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$heading = get_sub_field('heading') ?: "Have Questions? We Are Here.";
$desc    = get_sub_field('description');
if (empty($desc) || strpos($desc, 'English and Australia') !== false) {
    $desc = "Our Australian customer care team is here to assist with product information, tracking updates, and order support.";
}

// WhatsApp
$wa_title  = get_sub_field('whatsapp_title') ?: "WhatsApp (Fastest Option)";
$wa_number = get_sub_field('whatsapp_number') ?: "+61 488 841 833";
$wa_url    = get_sub_field('whatsapp_url') ?: (get_field('whatsapp_number', 'option') ?: "https://wa.me/61488841833");
if (strpos($wa_url, 'wa.me/60') !== false) {
    $wa_url = "https://wa.me/61488841833";
}

// Email
$email_title   = get_sub_field('email_title') ?: "Email Support";
$email_address = get_sub_field('contact_email') ?: (get_field('contact_email', 'option') ?: "orders@waklertaustralia.com.au");
if (strpos($email_address, 'example.com') !== false || strpos($email_address, 'hoppingmad') !== false || strpos($email_address, 'modaaust') !== false) {
    $email_address = 'orders@waklertaustralia.com.au';
}
$email_subtext = get_sub_field('email_subtext') ?: "Within 24 hours";

// Operating Hours
$hours_title   = get_sub_field('hours_title') ?: "Operating Hours (AEST)";
$hours_mon_fri = get_sub_field('hours_mon_fri') ?: "8:00 AM – 8:00 PM AEST";
$hours_sat     = get_sub_field('hours_sat') ?: "9:00 AM – 5:00 PM AEST";
$hours_sun     = get_sub_field('hours_sun') ?: "Limited (WhatsApp only)";

// Map & Address
$address_title   = get_sub_field('address_title') ?: "Waklert Australia Support Office";
$address_line_1  = get_sub_field('address_line_1') ?: "50 Holt St";
$address_line_2  = get_sub_field('address_city_state_zip') ?: "Surry Hills NSW 2010";
$address_country = get_sub_field('address_country') ?: "Australia";

// Directions URL (link to open in Google Maps)
$entered_directions = trim((string) (get_sub_field('directions_url') ?: ''));
$default_directions = "https://maps.google.com/?q=" . urlencode($address_line_1 . ', ' . $address_line_2 . ', ' . $address_country);
$directions_url     = $entered_directions ?: $default_directions;

// Official Google Maps Embed URL specifically for 50 Holt St, Surry Hills NSW 2010
$default_map_embed = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3312.729113645068!2d151.2069395!3d-33.8862948!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12ae21d2054155%3A0x88d02fd98fa53a3a!2s50%20Holt%20St%2C%20Surry%20Hills%20NSW%202010!5e0!3m2!1sen!2sau!4v1728725700000!5m2!1sen!2sau";

$entered_embed = trim((string) (get_sub_field('google_maps_url') ?: ''));

if (preg_match('/src=["\']([^"\']+)["\']/', $entered_embed, $iframe_match)) {
    $entered_embed = $iframe_match[1];
}

if (strpos($entered_embed, 'maps.app.goo.gl') !== false || strpos($entered_embed, 'goo.gl/maps') !== false || strpos($entered_embed, '/place/') !== false) {
    if (empty($entered_directions)) {
        $directions_url = $entered_embed;
    }
    $google_maps_url = $default_map_embed;
} elseif (!empty($entered_embed) && strpos($entered_embed, '/maps/embed') !== false) {
    $google_maps_url = $entered_embed;
} else {
    $google_maps_url = $default_map_embed;
}
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
        <!-- Breadcrumbs -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]">Contact Us</span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
                Direct Customer Support
            </span>
        </div>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($heading) ?>
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
            <?= esc_html($desc) ?>
        </p>
    </div>
</header>

<section class="py-7 md:py-10 bg-gradient-to-b from-white via-slate-50/50 to-white">
    <div class="container-site grid gap-8 lg:gap-10 lg:grid-cols-[1fr_1.2fr]">
        
        <!-- Left: Contact Details & Info Cards -->
        <div class="space-y-5">
            
            <!-- WhatsApp Card (Fastest Option) -->
            <a href="<?= esc_url($wa_url) ?>" target="_blank" rel="noopener noreferrer" class="group relative block overflow-hidden rounded-2xl border-2 border-dashed border-emerald-300/80 bg-gradient-to-br from-emerald-50/60 via-white to-emerald-50/30 p-6 shadow-xs transition-all duration-200 hover:border-emerald-400 hover:shadow-md">
                <div class="flex items-start gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-xs group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.67-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="font-heading text-base font-bold text-slate-900"><?= esc_html($wa_title) ?></h2>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Fast Reply
                            </span>
                        </div>
                        <p class="mt-1 font-mono text-base font-bold text-emerald-700"><?= esc_html($wa_number) ?></p>
                        <p class="mt-1 text-xs text-slate-500">Typically replies within 15–30 minutes during business hours.</p>
                    </div>
                    <span class="hidden sm:inline-flex items-center text-xs font-bold text-emerald-600 group-hover:translate-x-1 transition-transform">
                        Chat Now &rarr;
                    </span>
                </div>
            </a>

            <!-- Email Card -->
            <a href="<?= esc_url('mailto:' . $email_address) ?>" class="group relative block overflow-hidden rounded-2xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-sky-50/60 via-white to-sky-50/30 p-6 shadow-xs transition-all duration-200 hover:border-sky-400 hover:shadow-md">
                <div class="flex items-start gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#0c4161] text-white shadow-xs group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <h2 class="font-heading text-base font-bold text-slate-900"><?= esc_html($email_title) ?></h2>
                        <p class="mt-1 font-mono text-sm font-semibold text-[#0c4161] break-all"><?= esc_html($email_address) ?></p>
                        <p class="mt-1 text-xs text-slate-500">Response timeframe: <?= esc_html($email_subtext) ?></p>
                    </div>
                    <span class="hidden sm:inline-flex items-center text-xs font-bold text-sky-700 group-hover:translate-x-1 transition-transform">
                        Send Mail &rarr;
                    </span>
                </div>
            </a>

            <!-- Operating Hours Card -->
            <div class="rounded-2xl border-2 border-dashed border-slate-200/90 bg-white p-6 shadow-xs">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-[#0c4161]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider text-slate-900"><?= esc_html($hours_title) ?></h2>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-600 font-medium">Monday – Friday</span>
                        <span class="font-bold text-slate-900"><?= esc_html($hours_mon_fri) ?></span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-600 font-medium">Saturday</span>
                        <span class="font-bold text-slate-900"><?= esc_html($hours_sat) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600 font-medium">Sunday & Public Holidays</span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800"><?= esc_html($hours_sun) ?></span>
                    </div>
                </div>
            </div>

            <!-- Visit Us / Google Maps Card -->
            <div class="rounded-2xl border-2 border-dashed border-slate-200/90 bg-white overflow-hidden shadow-xs">
                <div class="p-6 space-y-3">
                    <div class="flex items-center gap-2 font-heading font-bold text-base text-[#0c4161]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span><?= esc_html($address_title) ?></span>
                    </div>
                    <?php
                    $address_parts = array_filter(array_map(function($p) {
                        return rtrim(trim((string) $p), ',');
                    }, [$address_line_1, $address_line_2, $address_country]));
                    $one_line_address = implode(', ', $address_parts);
                    ?>
                    <p class="text-sm leading-relaxed text-slate-600"><?= esc_html($one_line_address) ?></p>
                    <div class="pt-1">
                        <a href="<?= esc_url($directions_url) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 border border-sky-200/80 px-3.5 py-1.5 text-xs font-bold text-[#0c4161] hover:bg-sky-100 transition-colors">
                            <span>Open in Google Maps</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
                <?php if ($google_maps_url): ?>
                <div class="relative w-full h-56 border-t border-slate-200 bg-slate-50">
                    <iframe
                        src="<?= esc_url($google_maps_url) ?>"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="<?= esc_attr($address_title . ' - ' . $one_line_address) ?>"
                        class="w-full h-full">
                    </iframe>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right: Message Form Card -->
        <div class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-white p-6 sm:p-8 md:p-10 shadow-xs relative">
            <div class="mb-6">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                    Online Enquiry
                </span>
                <h2 class="mt-2 font-heading text-2xl font-black text-[#0c4161]">Send Us a Message</h2>
                <p class="mt-1 text-sm text-slate-500">Have an enquiry about your order or our armodafinil products? Submit your details and our team will get in touch promptly.</p>
            </div>

            <div class="contact-form-wrapper">
                <?= do_shortcode('[gravityform id="1" title="false" description="false" ajax="true"]') ?>
            </div>
        </div>

    </div>
</section>
