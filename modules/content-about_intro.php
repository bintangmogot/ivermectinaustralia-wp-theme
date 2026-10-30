<?php
/**
 * Module: About Intro / Hero
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$tag = get_sub_field('tag') ?: 'About Waklert Australia';
$heading = get_sub_field('heading') ?: "Australia's Focused Minds. Your Reliable Supplier.";
$desc = get_sub_field('desc') ?: "Waklert Australia was founded to provide Australian professionals, shift workers, and students with reliable access to genuine pharmaceutical-grade Armodafinil.";
?>

<!-- 2-Tone Patterned Header -->
<header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
        <!-- Breadcrumb Pill -->
        <div class="mb-3">
            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                <span class="text-sky-300 font-normal">/</span>
                <span aria-current="page" class="font-extrabold text-[#0c4161]">About Us</span>
            </nav>
        </div>

        <!-- Eyebrow Badge -->
        <?php if ($tag): ?>
        <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                <?= esc_html($tag) ?>
            </span>
        </div>
        <?php endif; ?>

        <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
            <?= esc_html($heading) ?>
        </h1>

        <?php if ($desc): ?>
        <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600 whitespace-pre-line">
            <?= esc_html($desc) ?>
        </p>
        <?php endif; ?>
    </div>
</header>
