/**
 * Homepage motion with GSAP and ScrollTrigger.
 * Everything uses gsap.from(), so content is fully visible when GSAP is missing or motion is reduced.
 */
(() => {
    if (!window.gsap || !window.ScrollTrigger) return;
    const { gsap, ScrollTrigger } = window;
    gsap.registerPlugin(ScrollTrigger);

    const media = gsap.matchMedia();

    media.add('(prefers-reduced-motion: no-preference)', () => {
        const ease = 'power3.out';

        // Hero: one load sequence. Text lines rise in turn, the photo unmasks, the badge slides, the mascot hops in.
        const heroLines = gsap.utils.toArray('[data-iva-hero]');
        const heroVisual = document.querySelector('[data-iva-hero-visual]');
        if (heroLines.length || heroVisual) {
            const intro = gsap.timeline({ defaults: { ease } });
            if (heroLines.length) intro.from(heroLines, { y: 28, autoAlpha: 0, duration: 0.8, stagger: 0.09 });
            if (heroVisual) {
                const frame = heroVisual.querySelector('.iva-hero-frame');
                const badge = heroVisual.querySelector('.iva-float-badge');
                const mascot = heroVisual.querySelector('.iva-hero-mascot');
                if (frame) intro.from(frame, { clipPath: 'inset(12% 12% 12% 12% round 2rem)', scale: 0.94, autoAlpha: 0, duration: 1.1, clearProps: 'transform,clipPath' }, 0.15);
                if (badge) intro.from(badge, { x: 40, autoAlpha: 0, duration: 0.7 }, 0.7);
                if (mascot) intro.from(mascot, { y: 70, rotate: -12, autoAlpha: 0, duration: 0.9, ease: 'back.out(1.8)' }, 0.85);

                // The badge drifts on its own; the photo stays in place.
                if (badge) gsap.to(badge, { y: -8, duration: 2.8, ease: 'sine.inOut', yoyo: true, repeat: -1, delay: 1.6 });
                if (mascot) {
                    gsap.to(mascot, {
                        rotate: 10,
                        y: -24,
                        ease: 'none',
                        scrollTrigger: { trigger: heroVisual, start: 'top top', end: 'bottom top', scrub: true },
                    });
                }
            }
        }

        // Section headings: tag, heading and lead rise in turn.
        gsap.utils.toArray('[data-iva-head]').forEach((head) => {
            gsap.from(head.children, {
                y: 32,
                autoAlpha: 0,
                duration: 0.8,
                stagger: 0.1,
                ease,
                scrollTrigger: { trigger: head, start: 'top 85%', once: true },
            });
        });

        // Card groups: children enter one after another.
        gsap.utils.toArray('[data-iva-stagger]').forEach((group) => {
            gsap.from(group.children, {
                y: 40,
                autoAlpha: 0,
                duration: 0.75,
                stagger: 0.08,
                ease,
                clearProps: 'transform',
                scrollTrigger: { trigger: group, start: 'top 85%', once: true },
            });
        });

        // How to order: cards pop in, the dashed line draws across, the numbers bounce.
        // Transforms are cleared afterwards so the cards do not form their own layers and the line stays behind the numbers.
        gsap.utils.toArray('[data-iva-steps]').forEach((steps) => {
            const timeline = gsap.timeline({ defaults: { ease }, scrollTrigger: { trigger: steps, start: 'top 78%', once: true } });
            timeline.from(steps.querySelectorAll('.iva-step'), { y: 50, autoAlpha: 0, duration: 0.8, stagger: 0.15, clearProps: 'transform' });
            const line = steps.querySelector('.iva-steps-line');
            if (line) timeline.from(line, { scaleX: 0, duration: 1.1, ease: 'power2.inOut' }, 0.3);
            timeline.from(steps.querySelectorAll('.iva-step-num'), { scale: 0.4, duration: 0.6, stagger: 0.2, ease: 'back.out(2.2)', clearProps: 'transform' }, 0.35);
        });

        // Photos drift against the scroll.
        gsap.utils.toArray('[data-iva-parallax] img').forEach((image) => {
            gsap.fromTo(image, { yPercent: -6 }, {
                yPercent: 6,
                ease: 'none',
                scrollTrigger: { trigger: image.parentElement, start: 'top bottom', end: 'bottom top', scrub: true },
            });
        });
        gsap.utils.toArray('[data-iva-parallax-soft]').forEach((block) => {
            gsap.from(block, {
                y: 60,
                autoAlpha: 0,
                duration: 1,
                ease,
                scrollTrigger: { trigger: block, start: 'top 85%', once: true },
            });
        });

        // Review score counts up once.
        gsap.utils.toArray('[data-iva-count]').forEach((element) => {
            const target = parseFloat(element.dataset.ivaCount);
            if (!target) return;
            const decimals = (element.dataset.ivaCount.split('.')[1] || '').length;
            const counter = { value: 0 };
            gsap.to(counter, {
                value: target,
                duration: 1.2,
                ease: 'power2.out',
                scrollTrigger: { trigger: element, start: 'top 90%', once: true },
                onUpdate: () => { element.textContent = counter.value.toFixed(decimals); },
            });
        });

        // Support block scales up from slightly smaller, then the mascot pops over the edge and waves.
        gsap.utils.toArray('[data-iva-support]').forEach((block) => {
            const timeline = gsap.timeline({ scrollTrigger: { trigger: block, start: 'top 80%', once: true } });
            timeline.from(block, { y: 50, scale: 0.97, autoAlpha: 0, duration: 0.9, ease });
            const mascot = block.querySelector('.iva-support-mascot');
            if (mascot) timeline.from(mascot, { yPercent: 70, autoAlpha: 0, duration: 0.7, ease: 'back.out(2)' }, '-=0.25');
        });

        // Product photos lean toward the pointer.
        gsap.utils.toArray('.iva-prow').forEach((card) => {
            const image = card.querySelector('.iva-prow-media img');
            if (!image) return;
            const moveX = gsap.quickTo(image, 'x', { duration: 0.5, ease: 'power3' });
            const moveY = gsap.quickTo(image, 'y', { duration: 0.5, ease: 'power3' });
            card.addEventListener('pointermove', (event) => {
                const box = card.getBoundingClientRect();
                moveX(((event.clientX - box.left) / box.width - 0.5) * 14);
                moveY(((event.clientY - box.top) / box.height - 0.5) * 10);
            });
            card.addEventListener('pointerleave', () => { moveX(0); moveY(0); });
        });
    });

    // Images that load late change the page height; recalculate trigger positions once everything is in.
    window.addEventListener('load', () => ScrollTrigger.refresh());
})();
