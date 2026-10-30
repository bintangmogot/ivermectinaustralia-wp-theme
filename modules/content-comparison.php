<?php
/**
 * Module: Comparison Table
 * Follows the colorful card design rules:
 * - Multi-color vibrant vector icons for each compared feature
 * - 2-tone card structure (Waklert highlighted column in navy/amber + white rows)
 * - Dashed borders
 * - Patterned gradient background
 * - Lift & hover animations
 */

$tag = get_sub_field('tag') ?: "Know Before You Order";
$heading = get_sub_field('heading') ?: "Waklert vs Coffee vs Energy Drinks";
$desc = get_sub_field('description') ?: "See why thousands of Australian shift workers and professionals choose Waklert over high-sugar stimulants.";

// Feature row colorful micro-icons
$feature_icons = [
    'duration' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none"><circle cx="12" cy="12" r="9" fill="#0284C7" fill-opacity="0.15" stroke="#0284C7" stroke-width="2"/><path d="M12 7V12L15 15" stroke="#F59E0B" stroke-width="2.5" stroke-linecap="round"/></svg>',
    'crash'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none"><circle cx="12" cy="12" r="9" fill="#EF4444" fill-opacity="0.15" stroke="#DC2626" stroke-width="2"/><path d="M13 3L6 14H12L11 21L18 10H12L13 3Z" fill="#F59E0B" stroke="#D97706" stroke-width="1.2" stroke-linejoin="round"/></svg>',
    'focus'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none"><circle cx="12" cy="12" r="9" fill="#10B981" fill-opacity="0.15" stroke="#10B981" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="#34D399"/><circle cx="12" cy="12" r="1.5" fill="#FFFFFF"/></svg>',
    'safety'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none"><path d="M12 3L4 7V13C4 18 7.5 21.5 12 23C16.5 21.5 20 18 20 13V7L12 3Z" fill="#0C4161" fill-opacity="0.15" stroke="#0C4161" stroke-width="2"/><path d="M9 12L11 14L15 10" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    'sleep'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" fill="#F59E0B" fill-opacity="0.2" stroke="#D97706" stroke-width="2"/><circle cx="16" cy="7" r="1" fill="#F59E0B"/><circle cx="19" cy="10" r="1.2" fill="#F59E0B"/></svg>',
    'cost'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" fill="none"><circle cx="12" cy="12" r="9" fill="#FBBF24" fill-opacity="0.25" stroke="#D97706" stroke-width="2"/><text x="12" y="16" font-family="system-ui, sans-serif" font-size="11" font-weight="900" fill="#78350F" text-anchor="middle">$</text></svg>',
];

$rows = [];
if (have_rows('rows')) {
    while (have_rows('rows')) {
        the_row();
        $rows[] = [
            'feature'  => get_sub_field('feature'),
            'modafinil' => get_sub_field('modafinil'),
            'coffee'   => get_sub_field('coffee'),
            'energy'   => get_sub_field('energy'),
        ];
    }
}

if (empty($rows)) {
    $rows = [
        ['feature' => 'Effect Duration', 'modafinil' => '10-15 hours', 'coffee' => '2-4 hours', 'energy' => '1-3 hours', 'key' => 'duration'],
        ['feature' => 'Crash Effect', 'modafinil' => 'Smooth / None', 'coffee' => 'Moderate dip', 'energy' => 'Severe crash', 'key' => 'crash'],
        ['feature' => 'Focus Quality', 'modafinil' => 'Deep, calm focus', 'coffee' => 'Mild alertness', 'energy' => 'Jittery spikes', 'key' => 'focus'],
        ['feature' => 'Habit Risk', 'modafinil' => 'Very low', 'coffee' => 'Moderate caffeine cycle', 'energy' => 'High sugar / caffeine', 'key' => 'safety'],
        ['feature' => 'Sleep Disruption', 'modafinil' => 'Low (morning dose)', 'coffee' => 'Moderate', 'energy' => 'High', 'key' => 'sleep'],
        ['feature' => 'Cost per Day', 'modafinil' => '~AUD $3 - $5', 'coffee' => '~AUD $5 - $9', 'energy' => '~AUD $4 - $7', 'key' => 'cost'],
    ];
}
?>
<section class="section-padding bg-gradient-to-b from-[#f8fafc] via-[#eff6fb] to-[#f8fafc] bg-pattern-dots border-t-2 border-dashed border-sky-200/80 relative isolate overflow-hidden" data-testid="comparison-table">
    <div class="container-custom max-w-5xl relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 md:mb-12">
            <span class="inline-flex items-center bg-amber-100 text-amber-900 border border-amber-300 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-3 shadow-2xs">
                <?= esc_html($tag) ?>
            </span>
            <h2 class="font-heading text-2xl md:text-4xl font-black text-ink tracking-tight mb-3">
                <?= esc_html($heading) ?>
            </h2>
            <p class="text-sm md:text-base text-muted-foreground leading-relaxed max-w-xl mx-auto">
                <?= esc_html($desc) ?>
            </p>
        </div>

        <!-- Comparison Table Card with Dashed Border and Two-Tone Structure -->
        <div class="bg-white border-2 border-dashed border-sky-300/80 rounded-3xl overflow-hidden shadow-xl hover:border-[#0c4161] transition-all duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-5 px-5 md:px-6 bg-[#0c4161] text-white font-heading font-black text-sm uppercase tracking-wider w-[34%]">
                                Key Attribute
                            </th>
                            <!-- Highlighted Waklert Column -->
                            <th class="py-5 px-5 md:px-6 bg-[#082c42] text-white font-heading font-black text-center text-sm md:text-base border-x-2 border-dashed border-amber-400/60 relative w-[26%]">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-[10px] font-black uppercase tracking-widest mb-1 shadow-xs">
                                    ★ RECOMMENDED
                                </div>
                                <span class="block text-white text-lg">Waklert 150</span>
                            </th>
                            <th class="py-5 px-4 md:px-6 bg-[#0c4161] text-white/90 font-heading font-bold text-center text-sm uppercase tracking-wider w-[20%]">
                                Coffee
                            </th>
                            <th class="py-5 px-4 md:px-6 bg-[#0c4161] text-white/90 font-heading font-bold text-center text-sm uppercase tracking-wider w-[20%]">
                                Energy Drinks
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-100">
                        <?php 
                        $icon_keys = ['duration', 'crash', 'focus', 'safety', 'sleep', 'cost'];
                        foreach ($rows as $index => $r): 
                            $icon_key = $r['key'] ?? ($icon_keys[$index % 6]);
                            $icon_svg = $feature_icons[$icon_key] ?? $feature_icons['duration'];
                        ?>
                        <tr class="group hover:bg-sky-50/50 transition-colors duration-200">
                            <!-- Feature Name with Colorful Icon -->
                            <td class="py-4 px-5 md:px-6 font-heading font-bold text-ink">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-sky-50 border border-sky-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                        <?= $icon_svg ?>
                                    </div>
                                    <span class="text-sm md:text-[15px]"><?= esc_html($r['feature']) ?></span>
                                </div>
                            </td>

                            <!-- Waklert Value (Clean Pill without bullet dot) -->
                            <td class="py-4 px-5 md:px-6 text-center bg-sky-50/70 border-x-2 border-dashed border-sky-200/80 font-extrabold text-[#0c4161]">
                                <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-300/80 text-xs font-bold shadow-2xs">
                                    <?= esc_html($r['modafinil']) ?>
                                </span>
                            </td>

                            <!-- Coffee Value -->
                            <td class="py-4 px-4 md:px-6 text-center text-slate-600 font-medium text-xs md:text-sm">
                                <?= esc_html($r['coffee']) ?>
                            </td>

                            <!-- Energy Drink Value -->
                            <td class="py-4 px-4 md:px-6 text-center text-slate-500 font-medium text-xs md:text-sm">
                                <?= esc_html($r['energy']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Table Footer with Australia Note -->
            <div class="p-4 px-6 bg-slate-50 border-t-2 border-dashed border-sky-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                <span class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span>Armodafinil offers sustained wakefulness without sugar crashes or high liquid intake.</span>
                </span>
                <span class="font-bold text-[#0c4161]">100% Genuine Sun Pharma Batch Tested</span>
            </div>
        </div>
    </div>
</section>
