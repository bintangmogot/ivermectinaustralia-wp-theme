<?php
/**
 * Privacy policy module.
 * Waklert Australia 2-tone Dashed & Patterned Theme
 *
 * Policy copy and contact details are maintained in the WordPress page editor.
 */
$title = get_sub_field('title') ?: get_the_title();
$intro = get_sub_field('subtitle');
$eyebrow = get_sub_field('eyebrow') ?: 'Privacy & Data Protection';
$updated = get_sub_field('updated');
$sections = get_sub_field('sections') ?: [];
$contact_email = sanitize_email(get_sub_field('contact_email'));
$contact_heading = get_sub_field('contact_heading') ?: 'Questions about your privacy?';
$contact_description = get_sub_field('contact_description') ?: 'Contact our team if you have questions regarding your personal information or data privacy.';
$section_ids = [];

foreach ($sections as $index => $section) {
    $section_ids[] = 'policy-section-' . ($index + 1) . '-' . sanitize_title($section['title'] ?? '');
}
?>

<div class="privacy-policy-wrapper">
    <!-- 2-Tone Patterned Header -->
    <header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
        <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
            <!-- Breadcrumbs -->
            <div class="mb-3">
                <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                    <span class="text-sky-300 font-normal">/</span>
                    <span aria-current="page" class="max-w-[12rem] sm:max-w-xs md:max-w-md truncate font-extrabold text-[#0c4161]"><?php echo esc_html($title); ?></span>
                </nav>
            </div>

            <!-- Eyebrow Badge -->
            <div class="mb-2.5">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                    <?php echo esc_html($eyebrow); ?>
                </span>
            </div>

            <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
                <?php echo esc_html($title); ?>
            </h1>

            <?php if ($intro) : ?>
                <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
                    <?php echo esc_html($intro); ?>
                </p>
            <?php endif; ?>

            <?php if ($updated) : ?>
                <div class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-white/80 border border-slate-200 px-3 py-1 text-xs font-medium text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    <span>Last updated <?php echo esc_html($updated); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($sections) : ?>
        <div class="container-site grid max-w-6xl gap-8 lg:gap-12 py-7 md:py-10 lg:grid-cols-[16rem_minmax(0,1fr)]">
            <!-- Sidebar Table of Contents -->
            <aside class="hidden lg:block">
                <div class="sticky top-28 space-y-4">
                    <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-4 shadow-xs">
                        <div class="flex items-center gap-2 mb-3 px-2">
                            <span class="h-2 w-2 rounded-full bg-[#0c4161]"></span>
                            <p class="text-xs font-black uppercase tracking-widest text-[#0c4161]">On this page</p>
                        </div>
                        <nav aria-label="Privacy policy sections">
                            <ol class="space-y-1">
                                <?php foreach ($sections as $index => $section) :
                                    $section_id = $section_ids[$index];
                                ?>
                                    <li>
                                        <a href="#<?php echo esc_attr($section_id); ?>"
                                           data-policy-target="<?php echo esc_attr($section_id); ?>"
                                           class="flex items-center gap-2.5 rounded-xl border border-transparent px-3 py-2 text-xs font-semibold leading-snug text-slate-600 transition-all hover:bg-sky-50 hover:text-[#0c4161] hover:border-sky-200">
                                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-slate-100 font-mono text-[10px] font-bold text-slate-500">
                                                <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                                            </span>
                                            <span class="truncate"><?php echo esc_html($section['title'] ?? ''); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                    </div>

                    <?php if ($contact_email) : ?>
                        <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-gradient-to-br from-sky-50/50 to-white p-4 shadow-xs text-center">
                            <p class="text-xs font-black uppercase tracking-wider text-[#0c4161] mb-1">Privacy Enquiry</p>
                            <p class="text-xs text-slate-500 mb-3">Questions regarding your personal information?</p>
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="inline-flex w-full items-center justify-center gap-1.5 rounded-full bg-[#0c4161] hover:bg-[#082c42] px-3.5 py-2 text-xs font-bold text-white transition-colors">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                                Contact Privacy Officer
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- Policy Sections (Single Unified Box) -->
            <div class="min-w-0 space-y-8">
                <article class="rounded-2xl sm:rounded-3xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-10 shadow-xs divide-y divide-dashed divide-sky-200/80">
                    <?php foreach ($sections as $index => $section) :
                        $section_title = $section['title'] ?? '';
                        $section_content = $section['content'] ?? '';
                        $section_id = $section_ids[$index];
                    ?>
                        <section id="<?php echo esc_attr($section_id); ?>" aria-labelledby="<?php echo esc_attr($section_id); ?>-title" class="scroll-mt-28 <?php echo $index > 0 ? 'pt-8 sm:pt-10' : ''; ?> <?php echo $index < count($sections) - 1 ? 'pb-8 sm:pb-10' : ''; ?>">
                            <div class="mb-2">
                                <span class="inline-flex items-center justify-center rounded-md bg-sky-100 px-2 py-0.5 font-mono text-[11px] font-extrabold text-[#0c4161]">
                                    <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                                </span>
                            </div>
                            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="font-heading text-xl sm:text-2xl font-black leading-tight text-[#0c4161]">
                                <?php echo esc_html($section_title); ?>
                            </h2>
                            <div class="prose max-w-none text-slate-600 mt-4 text-sm sm:text-base leading-relaxed">
                                <?php echo wp_kses_post(wpautop($section_content)); ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </article>

                <?php if ($contact_email) : ?>
                    <section aria-labelledby="privacy-contact-title" class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-[#f0f6fa] via-white to-sky-50/40 p-6 sm:p-8 md:p-10 shadow-xs">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="max-w-xl">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                                    Privacy Support
                                </span>
                                <h2 id="privacy-contact-title" class="mt-2 font-heading text-xl sm:text-2xl font-black text-[#0c4161]">
                                    <?php echo esc_html($contact_heading); ?>
                                </h2>
                                <?php if ($contact_description) : ?>
                                    <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-600">
                                        <?php echo esc_html($contact_description); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-full bg-[#0c4161] hover:bg-[#082c42] px-6 py-3 text-sm font-bold text-white shadow-sm transition-colors">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                                Email Privacy Officer
                            </a>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const links = Array.from(document.querySelectorAll('[data-policy-target]'));
    if (!links.length) return;

    const sections = links
        .map(function (link) { return document.getElementById(link.dataset.policyTarget); })
        .filter(Boolean);

    function updateActiveSection() {
        let activeId = sections[0] ? sections[0].id : '';
        const threshold = window.scrollY + 180;

        sections.forEach(function (section) {
            if (section.getBoundingClientRect().top + window.scrollY <= threshold) {
                activeId = section.id;
            }
        });

        links.forEach(function (link) {
            const active = link.dataset.policyTarget === activeId;
            link.classList.toggle('border-sky-300', active);
            link.classList.toggle('bg-sky-100/90', active);
            link.classList.toggle('text-[#0c4161]', active);
            link.classList.toggle('font-bold', active);
            link.classList.toggle('border-transparent', !active);
        });
    }

    window.addEventListener('scroll', updateActiveSection, { passive: true });
    updateActiveSection();
});
</script>
