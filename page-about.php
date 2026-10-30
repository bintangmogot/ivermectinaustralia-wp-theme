<?php
/**
 * Template Name: About Us
 * Description: Custom About page layout for Waklert Australia (2-tone Dashed & Patterned Theme)
 */

get_header();
?>

<main>
    <!-- 2-Tone Patterned Hero Section -->
    <header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9" data-testid="about-hero">
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
            <div class="mb-2.5">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                    Australia's Focus Destination
                </span>
            </div>

            <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
                Australia's Sharpest Minds.<br class="hidden md:block"> Your Reliable Supplier.
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-sm md:text-base leading-relaxed text-slate-600">
                We built Waklert Australia because Australian professionals, FIFO shift workers, and students deserve an Armodafinil supplier that actually understands the Australian lifestyle and delivers reliably to every postcode.
            </p>
        </div>
    </header>

    <!-- Origin Section -->
    <section class="py-8 md:py-12 bg-white" data-testid="about-origin">
        <div class="container-site max-w-5xl">
            <div class="grid md:grid-cols-5 gap-8 lg:gap-12 items-center">
                <div class="md:col-span-3 space-y-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                        Our Origin
                    </span>
                    <h2 class="font-heading text-2xl sm:text-3xl font-black text-[#0c4161]">Born From Australia's Unique Working Demands</h2>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                        Australia runs on high-intensity schedules: rotating FIFO rosters that flip circadian body clocks every fortnight, intense university exam periods, long interstate freight hauls, and hospital night shifts stretching well past sunrise.
                    </p>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                        We saw colleagues struggle with fatigue, order from questionable international dropshippers, and wait weeks for delayed parcels. So we established Waklert Australia — stocking solely genuine pharmaceutical-grade blister packs (Sun Pharma & HAB Pharma) with express delivery across Sydney, Melbourne, Brisbane, Perth, Adelaide, Hobart, and remote mining communities.
                    </p>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                        Today, we have proudly supplied over <strong class="text-[#0c4161]">4,000+ Australian customers</strong> with reliable, confidential logistics and direct customer support.
                    </p>
                </div>
                <div class="md:col-span-2 space-y-3.5">
                    <div class="rounded-2xl border-2 border-dashed border-sky-200/90 bg-gradient-to-br from-sky-50/60 to-white p-5 shadow-xs">
                        <p class="font-heading text-3xl font-black text-[#0c4161]">60,000+</p>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">FIFO workers in Australia's mining sector</p>
                    </div>
                    <div class="rounded-2xl border-2 border-dashed border-amber-200/90 bg-gradient-to-br from-amber-50/60 to-white p-5 shadow-xs">
                        <p class="font-heading text-3xl font-black text-amber-600">A$300B+</p>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Australian mining industry annual revenue</p>
                    </div>
                    <div class="rounded-2xl border-2 border-dashed border-emerald-200/90 bg-gradient-to-br from-emerald-50/60 to-white p-5 shadow-xs">
                        <p class="font-heading text-3xl font-black text-emerald-600">100%</p>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Genuine pharmaceutical-grade tablets</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Four Principles -->
    <section class="py-8 md:py-12 bg-gradient-to-b from-slate-50/60 via-white to-slate-50/40" data-testid="about-principles">
        <div class="container-site max-w-5xl">
            <div class="text-center mb-10">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161]">
                    What We Stand For
                </span>
                <h2 class="mt-2 font-heading text-2xl sm:text-3xl md:text-4xl font-black text-[#0c4161]">Four Principles That Guide Everything</h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-5 lg:gap-6">
                <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs hover:border-sky-400 hover:shadow-md transition-all">
                    <span class="font-heading text-2xl font-black text-[#0c4161]">01</span>
                    <h3 class="font-heading font-bold text-slate-900 text-lg mt-2 mb-2">Authenticity First</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">Every tablet comes in sealed blister packaging from WHO-GMP certified manufacturers — Sun Pharma and HAB Pharma. Zero unlabelled generics, zero fakes.</p>
                </div>
                <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs hover:border-sky-400 hover:shadow-md transition-all">
                    <span class="font-heading text-2xl font-black text-[#0c4161]">02</span>
                    <h3 class="font-heading font-bold text-slate-900 text-lg mt-2 mb-2">Australian Logistics Speed</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">We know Australia is vast. That is why shipments are handled via priority logistics. Metro addresses receive orders in 5–10 business days.</p>
                </div>
                <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs hover:border-sky-400 hover:shadow-md transition-all">
                    <span class="font-heading text-2xl font-black text-[#0c4161]">03</span>
                    <h3 class="font-heading font-bold text-slate-900 text-lg mt-2 mb-2">Radical Honesty &amp; Transparency</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">We do not market Armodafinil as a miracle cure. It is an effective focus tool for appropriate candidates. We actively recommend consulting your GP.</p>
                </div>
                <div class="rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-6 shadow-xs hover:border-sky-400 hover:shadow-md transition-all">
                    <span class="font-heading text-2xl font-black text-[#0c4161]">04</span>
                    <h3 class="font-heading font-bold text-slate-900 text-lg mt-2 mb-2">Responsive Australian Support</h3>
                    <p class="text-sm text-slate-600 leading-relaxed m-0">Reach us through WhatsApp or email and real team members answer your queries quickly. No automated deflection or lost emails.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Support CTA Section -->
    <section class="py-8 md:py-10 bg-white">
        <div class="container-site max-w-4xl text-center">
            <div class="rounded-3xl border-2 border-dashed border-sky-300/80 bg-gradient-to-br from-[#f0f6fa] via-white to-sky-50/30 p-8 sm:p-10 shadow-xs">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161]">
                    Have Questions?
                </span>
                <h2 class="mt-3 font-heading text-2xl sm:text-3xl font-black text-[#0c4161]">Need Help Choosing or Tracking?</h2>
                <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-xl mx-auto">Our Australian support team is ready to answer questions about dosages, dispatch times, and order delivery.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="<?= esc_url(home_url('/shop/')) ?>" class="inline-flex items-center gap-2 rounded-full bg-[#0c4161] hover:bg-[#082c42] text-white px-6 py-3 text-sm font-bold shadow-sm transition-colors">
                        Browse Catalogue
                    </a>
                    <a href="<?= esc_url(home_url('/contact/')) ?>" class="inline-flex items-center gap-2 rounded-full bg-white hover:bg-slate-50 text-[#0c4161] border border-sky-200 px-6 py-3 text-sm font-bold shadow-xs transition-colors">
                        Contact Support &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
