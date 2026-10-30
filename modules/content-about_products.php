<?php
/**
 * Module: About Products
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

$heading = get_sub_field('heading') ?: 'Our Armodafinil Range';
$desc = get_sub_field('desc') ?: 'The catalogue is limited to armodafinil products. Final product names and availability are confirmed on the product pages.';
$products = get_sub_field('products') ?: [];

if (!$heading && !$desc && !$products) {
    return;
}
?>

<section class="py-6 md:py-10 bg-gradient-to-b from-white via-slate-50/50 to-white">
    <div class="container-site max-w-5xl">
        <div class="text-center max-w-3xl mx-auto mb-8">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                Product Portfolio
            </span>
            <h2 class="mt-2 font-heading font-black text-2xl sm:text-3xl md:text-4xl text-[#0c4161]"><?= esc_html($heading) ?></h2>
            <?php if ($desc): ?>
                <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed whitespace-pre-line"><?= esc_html($desc) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($products): ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($products as $idx => $product): ?>
            <div class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs transition-all duration-200 hover:border-sky-400 hover:shadow-md">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-[#0c4161] font-heading font-black text-xs">
                            <?= sprintf('%02d', $idx + 1) ?>
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">
                            100% Genuine
                        </span>
                    </div>
                    <?php if (!empty($product['title'])): ?>
                        <h3 class="font-heading font-bold text-lg text-slate-900 mb-2"><?= esc_html($product['title']) ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($product['desc'])): ?>
                        <p class="text-sm text-slate-600 leading-relaxed mb-4 whitespace-pre-line"><?= esc_html($product['desc']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="pt-2 border-t border-slate-100 mt-auto">
                    <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0c4161] group-hover:text-amber-600 transition-colors">
                        <span>View in catalogue</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
