<?php
/**
 * Terms of Service Module
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$title = get_sub_field('title') ?: "Terms & Conditions";
$subtitle = get_sub_field('subtitle') ?: "By accessing this website and placing an order, you agree to the conditions and compliance guidelines below.";

$default_badges = [
    'Age 18+ Verification',
    'Therapeutic Goods Act 1989 Compliance',
    'Personal Importation Scheme Only'
];

$default_sections = [
    [
        'title' => '1. Eligibility & Age Restriction',
        'content' => 'You must be at least 18 years of age to access this site and place an order. By completing an order, you confirm that products ordered are strictly for your personal use and not for unauthorized commercial resale.',
    ],
    [
        'title' => '2. Medical Disclaimer & Prescriptions',
        'content' => 'All content on this site is provided for general informational purposes only. Armodafinil is a Schedule 4 prescription medication in Australia regulated under the Therapeutic Goods Act 1989. We are not licensed medical providers. Always consult a registered medical practitioner before commencing treatment.',
    ],
    [
        'title' => '3. Orders & Pricing in AUD',
        'content' => 'All prices are displayed in Australian Dollars (AUD) and include standard express logistics options. We reserve the right to decline or cancel any order suspected of fraudulent activity, automated abuse, or regulatory non-compliance.',
    ],
    [
        'title' => '4. Shipping & Delivery Logistics',
        'content' => 'Deliveries are fulfilled via priority parcel channels with Australia Post tracking. Standard metro delivery takes 5–10 business days. Regional and remote destinations take 7–14 business days. Free shipping applies to bulk multi-pack orders.',
    ],
    [
        'title' => '5. Customer Delivery Responsibilities',
        'content' => 'Customers are responsible for providing an accurate and complete postal address at checkout. Orders that fail to deliver due to incorrect address information provided by the customer require reshipment handling.',
    ],
    [
        'title' => '6. Limitation of Liability',
        'content' => 'Waklert Australia accepts no liability for personal adverse reactions or consequential losses resulting from product usage. Our financial liability is strictly limited to the actual purchase amount paid for the specific order.',
    ],
    [
        'title' => '7. Terms Modifications',
        'content' => 'We reserve the right to revise or update these terms at any time. Continued use of the website following published updates constitutes acceptance of the modified terms.',
    ]
];
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
        <!-- Breadcrumbs -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]">Terms &amp; Conditions</span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                User Agreement &amp; Compliance
            </span>
        </div>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($title) ?>
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
            <?= esc_html($subtitle) ?>
        </p>

        <!-- Compliance Badges -->
        <div class="mt-6 flex flex-wrap justify-center gap-2.5">
            <?php if (have_rows('badges')): ?>
                <?php while (have_rows('badges')): the_row(); 
                    $badge_label = get_sub_field('badge');
                    $badge_label = str_ireplace('Poisons Act 1952', 'Therapeutic Goods Act 1989', $badge_label);
                ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 border border-dashed border-sky-300 px-3.5 py-1.5 text-xs font-bold text-[#0c4161] shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <?= esc_html($badge_label) ?>
                </span>
                <?php endwhile; ?>
            <?php else: ?>
                <?php foreach ($default_badges as $b): ?>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 border border-dashed border-sky-300 px-3.5 py-1.5 text-xs font-bold text-[#0c4161] shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <?= esc_html($b) ?>
                </span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</header>

<section class="py-7 md:py-10 bg-gradient-to-b from-white via-slate-50/50 to-white">
    <div class="container-site max-w-4xl space-y-6">
        <?php if (have_rows('sections')): ?>
            <?php $s_idx = 0; while (have_rows('sections')): the_row(); $s_idx++;
                $s_title = get_sub_field('title');
                $s_content = get_sub_field('content');
            ?>
            <article class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-8 shadow-xs transition-all hover:border-sky-300">
                <div class="flex items-center gap-2 mb-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-sky-100 font-mono text-xs font-bold text-[#0c4161]">
                        <?= sprintf('%02d', $s_idx) ?>
                    </span>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-[#0c4161] m-0">
                        <?= esc_html($s_title) ?>
                    </h2>
                </div>
                <div class="prose max-w-none text-slate-600 text-sm sm:text-base leading-relaxed mt-3">
                    <?= wp_kses_post(wpautop($s_content)) ?>
                </div>
            </article>
            <?php endwhile; ?>
        <?php else: ?>
            <?php foreach ($default_sections as $s_idx => $sec): ?>
            <article class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-8 shadow-xs transition-all hover:border-sky-300">
                <div class="flex items-center gap-2 mb-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-sky-100 font-mono text-xs font-bold text-[#0c4161]">
                        <?= sprintf('%02d', $s_idx + 1) ?>
                    </span>
                    <h2 class="font-heading font-black text-lg sm:text-xl text-[#0c4161] m-0">
                        <?= esc_html($sec['title']) ?>
                    </h2>
                </div>
                <div class="prose max-w-none text-slate-600 text-sm sm:text-base leading-relaxed mt-3">
                    <?= wp_kses_post(wpautop($sec['content'])) ?>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Contact Callout -->
        <?php
        $hide_contact = get_sub_field('hide_contact_callout');
        if (!$hide_contact):
            $contact_heading = get_sub_field('contact_heading') ?: "Have Questions About Our Terms?";
            $contact_desc = get_sub_field('contact_description') ?: "Our customer care team is available via WhatsApp or email for any pre-order clarifications.";
            $contact_btn = get_sub_field('contact_btn_label') ?: "Chat on WhatsApp";
            $contact_wa = get_sub_field('whatsapp_number') ?: "+61 488 841 833";
            $wa_url = function_exists('waklert_format_whatsapp_url') ? waklert_format_whatsapp_url($contact_wa) : 'https://wa.me/61488841833';
        ?>
        <div class="mt-10 rounded-3xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-[#f0f6fa] via-white to-sky-50/40 p-8 sm:p-10 shadow-xs text-center" data-testid="terms-contact-callout">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                Customer Care
            </span>
            <h2 class="mt-2.5 font-heading font-black text-xl sm:text-2xl text-[#0c4161]">
                <?= esc_html($contact_heading) ?>
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2 mb-6 max-w-lg mx-auto leading-relaxed">
                <?= esc_html($contact_desc) ?>
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="<?= esc_url($wa_url) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 text-sm shadow-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.67-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <?= esc_html($contact_btn) ?>
                </a>
                <a href="<?= esc_url(home_url('/contact/')) ?>" class="inline-flex items-center gap-1.5 rounded-full bg-white hover:bg-slate-50 text-[#0c4161] border border-sky-300 font-bold px-6 py-3 text-sm shadow-xs transition-colors">
                    Send Email &rarr;
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
