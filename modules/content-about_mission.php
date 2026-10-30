<?php
/**
 * Module: About Mission & Vision
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$m_head   = get_sub_field('mission_heading') ?: 'Our Approach';
$m_desc   = get_sub_field('mission_desc') ?: 'We organise armodafinil product information so customers can compare brands, strengths and batch details with clarity.';
$m_preset = (string) (get_sub_field('mission_icon_preset') ?: 'browse_catalogue');
$m_image  = get_sub_field('mission_icon_image');

$v_head   = get_sub_field('vision_heading') ?: 'Information First';
$v_desc   = get_sub_field('vision_desc') ?: 'We use plain English, state clearly that armodafinil is prescription-only in Australia, and encourage customers to speak to a doctor before ordering.';
$v_preset = (string) (get_sub_field('vision_icon_preset') ?: 'medical_rx_clarity');
$v_image  = get_sub_field('vision_icon_image');

if (!$m_head && !$m_desc && !$v_head && !$v_desc) {
    return;
}

$m_icon = waklert_render_card_icon([
    'icon_image'     => $m_image,
    'icon_preset'    => $m_preset,
    'title'          => $m_head,
    'desc'           => $m_desc,
    'default_preset' => 'browse_catalogue',
    'size_class'     => 'h-10 w-10 shrink-0',
]);

$v_icon = waklert_render_card_icon([
    'icon_image'     => $v_image,
    'icon_preset'    => $v_preset,
    'title'          => $v_head,
    'desc'           => $v_desc,
    'default_preset' => 'medical_rx_clarity',
    'size_class'     => 'h-10 w-10 shrink-0',
]);
?>

<section class="py-6 md:py-10 bg-white">
    <div class="container-site max-w-5xl">
        <div class="grid md:grid-cols-2 gap-6 lg:gap-8 items-stretch">
            
            <!-- Mission / Approach Card -->
            <div class="relative overflow-hidden rounded-2xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-sky-50/50 via-white to-sky-50/20 p-6 sm:p-8 shadow-xs hover:border-sky-400 hover:shadow-md transition-all">
                <div class="flex items-center gap-3.5 mb-4">
                    <span class="flex h-13 w-13 shrink-0 items-center justify-center rounded-xl bg-white border border-sky-100 shadow-xs">
                        <?= $m_icon ?>
                    </span>
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-[#0c4161]/70">Core Purpose</span>
                        <h2 class="font-heading font-black text-xl sm:text-2xl text-[#0c4161] m-0"><?= esc_html($m_head) ?></h2>
                    </div>
                </div>
                <?php if ($m_desc): ?>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line m-0">
                        <?= esc_html($m_desc) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Vision / Information First Card -->
            <div class="relative overflow-hidden rounded-2xl border-2 border-dashed border-amber-300/80 bg-gradient-to-br from-amber-50/50 via-white to-amber-50/20 p-6 sm:p-8 shadow-xs hover:border-amber-400 hover:shadow-md transition-all">
                <div class="flex items-center gap-3.5 mb-4">
                    <span class="flex h-13 w-13 shrink-0 items-center justify-center rounded-xl bg-white border border-amber-100 shadow-xs">
                        <?= $v_icon ?>
                    </span>
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-amber-700">Medical Clarity</span>
                        <h2 class="font-heading font-black text-xl sm:text-2xl text-slate-900 m-0"><?= esc_html($v_head) ?></h2>
                    </div>
                </div>
                <?php if ($v_desc): ?>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line m-0">
                        <?= esc_html($v_desc) ?>
                    </p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
