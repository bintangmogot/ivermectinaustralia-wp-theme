<?php
/**
 * Module: City Section (Delivered Everywhere in Australia)
 * Standalone text-based cities showcase styled in Waklert Australia's signature theme.
 * Supports both 'navy' (Pacific Navy & Amber) and 'light' (2-Tone Sky & White) brand styles.
 * Features 2-tone dashed styling, pattern dots, glowing amber indicators, and unlinked location badges.
 */

$theme_style = get_sub_field('theme_style') ?: 'navy';
$is_navy     = ($theme_style !== 'light');

$tag = get_sub_field('tag');
if ($tag === null || $tag === '') {
    $tag = 'SHIPPING NATIONWIDE';
}

$heading = get_sub_field('title');
if ($heading === null || $heading === '') {
    $heading = 'Delivered Everywhere in Australia';
}

$desc = get_sub_field('description');
if ($desc === null || $desc === '') {
    $desc = 'Metro cities, regional towns, or remote FIFO camps — we ship to your address.';
}

// 1. Check quick_cities textarea
$quick_cities = trim((string) get_sub_field('quick_cities'));
$cities_list = [];

if (!empty($quick_cities)) {
    $lines = preg_split('/\r\n|\r|\n/', $quick_cities);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;
        $has_check = false;
        if (str_contains($line, '✓')) {
            $has_check = true;
            $line = trim(str_replace('✓', '', $line));
        }
        $cities_list[] = [
            'name' => $line,
            'checkmark' => $has_check,
        ];
    }
}

// 2. Check repeater field
if (empty($cities_list) && have_rows('cities_list')) {
    while (have_rows('cities_list')) {
        the_row();
        $name = trim((string) get_sub_field('city_name'));
        if ($name === '') continue;
        $has_check = (bool) get_sub_field('has_checkmark');
        if (str_contains($name, '✓')) {
            $has_check = true;
            $name = trim(str_replace('✓', '', $name));
        }
        $cities_list[] = [
            'name' => $name,
            'checkmark' => $has_check,
        ];
    }
}

// 3. Fallback to default 8 Australian delivery cities
if (empty($cities_list)) {
    $cities_list = [
        ['name' => 'Sydney', 'checkmark' => true],
        ['name' => 'Melbourne', 'checkmark' => true],
        ['name' => 'Adelaide', 'checkmark' => true],
        ['name' => 'Darwin', 'checkmark' => true],
        ['name' => 'Brisbane', 'checkmark' => true],
        ['name' => 'Gold Coast', 'checkmark' => true],
        ['name' => 'Hobart', 'checkmark' => true],
        ['name' => 'Canberra', 'checkmark' => true],
    ];
}

// 4. Ensure legacy Perth references are mapped to Canberra
foreach ($cities_list as &$c_item) {
    if (stripos($c_item['name'], 'Perth') !== false) {
        $c_item['name'] = 'Canberra';
    }
}
unset($c_item);
?>

<?php if ($is_navy): ?>
<!-- Pacific Navy Dark Brand Theme -->
<section class="section-padding relative isolate overflow-hidden bg-gradient-to-r from-[#082a3f] via-[#0c4161] to-[#125883] bg-pattern-dots-dark text-white border-y-2 border-dashed border-sky-400/40" data-testid="city-section">
    <!-- Orbit Background Rings -->
    <?php if (function_exists('waklert_output_pattern_rings')) { waklert_output_pattern_rings(); } ?>

    <!-- Ambient Center Spotlight -->
    <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(ellipse_70%_50%_at_50%_0%,rgba(56,189,248,0.15),transparent_70%)]" aria-hidden="true"></div>

    <div class="container-custom relative z-10 max-w-5xl mx-auto px-4 sm:px-6">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10 md:mb-12">
            <?php if (!empty($tag)): ?>
            <div class="inline-flex items-center rounded-full bg-amber-400/15 border border-amber-300/35 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-amber-300 mb-4 sm:mb-5">
                <?= esc_html($tag) ?>
            </div>
            <?php endif; ?>

            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl lg:text-[42px] font-black text-white tracking-tight leading-tight mb-3 sm:mb-4">
                <?= esc_html($heading) ?>
            </h2>

            <?php if (!empty($desc)): ?>
            <p class="text-sm sm:text-base text-sky-100/85 max-w-xl mx-auto leading-relaxed">
                <?= esc_html($desc) ?>
            </p>
            <?php endif; ?>
        </div>

        <!-- City Pills Container -->
        <div class="max-w-4xl mx-auto flex flex-wrap justify-center items-center gap-2.5 sm:gap-3 md:gap-3.5">
            <?php foreach ($cities_list as $city): ?>
            <div class="inline-flex items-center bg-white/10 hover:bg-white/15 border border-white/20 hover:border-amber-400/50 text-white rounded-full px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm md:text-[15px] font-semibold tracking-wide backdrop-blur-sm transition-colors duration-200 cursor-text select-text">
                <span class="text-white"><?= esc_html($city['name']) ?></span>
                <?php if (!empty($city['checkmark'])): ?>
                <span class="text-amber-300 font-bold ml-1.5" aria-hidden="true">✓</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php else: ?>
<!-- 2-Tone Light Pattern Brand Theme -->
<section class="section-padding relative isolate overflow-hidden bg-gradient-to-b from-[#f0f6fa] via-[#e9f2f8] to-[#f8fafc] bg-pattern-dots border-y-2 border-dashed border-sky-200/80 text-foreground" data-testid="city-section">
    <div class="container-custom relative z-10 max-w-5xl mx-auto px-4 sm:px-6">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10 md:mb-12">
            <?php if (!empty($tag)): ?>
            <span class="inline-flex items-center bg-sky-100 text-[#0c4161] border border-sky-300 text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-3">
                <?= esc_html($tag) ?>
            </span>
            <?php endif; ?>

            <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl lg:text-[42px] font-black text-ink tracking-tight leading-tight mb-3 sm:mb-4">
                <?= esc_html($heading) ?>
            </h2>

            <?php if (!empty($desc)): ?>
            <p class="text-sm sm:text-base text-muted-foreground max-w-xl mx-auto leading-relaxed">
                <?= esc_html($desc) ?>
            </p>
            <?php endif; ?>
        </div>

        <!-- City Pills Container -->
        <div class="max-w-4xl mx-auto flex flex-wrap justify-center items-center gap-2.5 sm:gap-3 md:gap-3.5">
            <?php foreach ($cities_list as $city): ?>
            <div class="inline-flex items-center bg-white hover:bg-sky-50 border border-sky-200 hover:border-[#0c4161] text-[#0c4161] rounded-full px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm md:text-[15px] font-semibold tracking-wide shadow-2xs transition-colors duration-200 cursor-text select-text">
                <span class="text-slate-800 hover:text-[#0c4161]"><?= esc_html($city['name']) ?></span>
                <?php if (!empty($city['checkmark'])): ?>
                <span class="text-emerald-600 font-bold ml-1.5" aria-hidden="true">✓</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
