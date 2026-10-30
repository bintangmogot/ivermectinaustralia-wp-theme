<?php
/**
 * Module: FAQs
 */

$heading = get_sub_field('heading') ?: "Frequently Asked Questions";
?>
<section class="section-padding bg-white" data-testid="faq-strip">
    <div class="container-custom max-w-3xl">
        <div class="text-center mb-10">
            <h2 class="font-heading text-2xl md:text-4xl font-black text-ink mb-3">
                <?= $heading ?>
            </h2>
        </div>

        <div class="space-y-4">
            <?php 
            if(have_rows('faq_items')): 
                while(have_rows('faq_items')): the_row();
            ?>
            <details open class="group border border-stone-200 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md open:bg-primary-softer/50 open:border-primary/40">
                <summary class="flex items-center justify-between gap-4 p-5 cursor-pointer list-none rounded-xl transition-colors hover:text-primary-dark active:bg-primary-softer/60">
                    <h3 class="font-heading font-bold text-ink text-sm">
                        <?= get_sub_field('question') ?>
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary flex-shrink-0 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                <div class="px-5 pb-5 text-sm text-muted-foreground leading-relaxed border-t border-stone-100 pt-4 prose prose-sm max-w-none [&_a]:text-primary [&_a]:underline [&_a]:font-semibold hover:[&_a]:text-primary-dark">
                    <?= wp_kses_post(wpautop(get_sub_field('answer'))) ?>
                </div>
            </details>
            <?php 
                endwhile;
            else:
                // Fallback default FAQs
                $defaults = [
                    ['What is Modanil 200mg used for?', 'Apakah kegunaan Modanil 200mg?', 'Modanil 200 mg is useful for the improvement of narcolepsy, obstructive sleep apnea, or shift work sleep disorder.', 'Modanil 200 mg berguna untuk pembaikan narkolepsi, apnea tidur obstruktif, atau gangguan tidur kerja syif.'],
                    ['How does Modanil 200mg work?', 'Bagaimanakah Modanil 200mg berfungsi?', 'Modanil works by affecting certain chemicals in the brain that regulate the sleep-wake cycle, helping you stay awake and alert.', 'Modanil berfungsi dengan mempengaruhi bahan kimia tertentu di dalam otak yang mengawal selitaran tidur-jaga, membantu anda kekal berjaga dan peka.'],
                    ['How should I take Modanil 200mg?', 'Bagaimanakah cara saya mengambil Modanil 200mg?', 'Take one tablet in the morning with a glass of water. Do not exceed the recommended dose.', 'Ambil sebiji tablet pada waktu pagi dengan segelas air. Jangan melebihi dos yang disyorkan.'],
                    ['Can I take Modanil with food?', 'Bolehkah saya mengambil Modanil dengan makanan?', 'Yes, it can be taken with or without food.', 'Ya, ia boleh diambil dengan atau tanpa makanan.'],
                    ['What should I do if I miss a dose?', 'Apakah yang harus saya lakukan jika terlepas dos?', 'If you miss a dose, take it as soon as you remember. If it is close to your next dose, skip the missed dose. Do not take a double dose.', 'Jika anda terlepas dos, ambil sebaik sahaja anda teringat. Jika ia hampir dengan dos seterusnya, abaikan dos yang terlepas. Jangan ambil dos berganda.'],
                    ['How long does Modanil 200mg last?', 'Berapa lamakah Modanil 200mg bertahan?', 'The effects typically last between 12 to 15 hours.', 'Kesannya biasanya bertahan antara 12 hingga 15 jam.'],
                    ['What are the common side effects of Modanil 200mg?', 'Apakah kesan sampingan biasa Modanil 200mg?', 'Common side effects include headache, nausea, nervousness, dizziness, or difficulty sleeping.', 'Kesan sampingan biasa termasuk sakit kepala, loya, gementar, pening, atau kesukaran tidur.']
                ];
                foreach($defaults as $f):
            ?>
            <details open class="group border border-stone-200 rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md open:bg-primary-softer/50 open:border-primary/40">
                <summary class="flex items-center justify-between gap-4 p-5 cursor-pointer list-none rounded-xl transition-colors hover:text-primary-dark active:bg-primary-softer/60">
                    <h3 class="font-heading font-bold text-ink text-sm">
                        <?= $f[0] ?>
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary flex-shrink-0 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                <div class="px-5 pb-5 text-sm text-muted-foreground leading-relaxed border-t border-stone-100 pt-4 prose prose-sm max-w-none [&_a]:text-primary [&_a]:underline [&_a]:font-semibold hover:[&_a]:text-primary-dark">
                    <?= wp_kses_post(wpautop($f[2])) ?>
                </div>
            </details>
            <?php 
                endforeach;
            endif; 
            ?>
        </div>

    </div>
</section>
