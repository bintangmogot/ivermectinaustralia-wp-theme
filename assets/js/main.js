document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuOpen = document.getElementById('mobile-menu-open');
    const menuClose = document.getElementById('mobile-menu-close');
    const menuDrawer = document.getElementById('mobile-menu-drawer');
    const menuOverlay = document.getElementById('mobile-menu-overlay');

    function openMenu() {
        if (!menuDrawer || !menuOverlay) return;
        
        // Show overlay
        menuOverlay.classList.remove('hidden');
        menuOverlay.classList.add('block');
        
        // Slide in drawer
        menuDrawer.classList.remove('-translate-x-full');
        menuDrawer.classList.add('translate-x-0');
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        if (!menuDrawer || !menuOverlay) return;
        
        // Hide overlay
        menuOverlay.classList.add('hidden');
        menuOverlay.classList.remove('block');
        
        // Slide out drawer
        menuDrawer.classList.add('-translate-x-full');
        menuDrawer.classList.remove('translate-x-0');
        
        // Restore body scroll
        document.body.style.overflow = '';
    }

    if (menuOpen) menuOpen.addEventListener('click', openMenu);
    if (menuClose) menuClose.addEventListener('click', closeMenu);
    if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);

    // Mobile Navigation Submenu Accordion
    document.querySelectorAll('.mobile-nav .menu-item-has-children').forEach((item) => {
        const toggleBtn = item.querySelector('.mobile-submenu-toggle');
        const subMenu = item.querySelector('.sub-menu');
        const link = item.querySelector('a');

        if (!subMenu) return;

        function toggleSubmenu(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const isOpen = subMenu.classList.contains('is-open');
            if (isOpen) {
                subMenu.classList.remove('is-open');
                if (toggleBtn) {
                    toggleBtn.classList.remove('is-active');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            } else {
                subMenu.classList.add('is-open');
                if (toggleBtn) {
                    toggleBtn.classList.add('is-active');
                    toggleBtn.setAttribute('aria-expanded', 'true');
                }
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSubmenu);
        }

        // If parent link href is "#" or empty, clicking the link also toggles the submenu
        if (link) {
            const href = link.getAttribute('href');
            if (!href || href === '#' || href === 'javascript:void(0)') {
                link.addEventListener('click', toggleSubmenu);
            }
        }
    });

    document.querySelectorAll('[data-product-description-toggle]').forEach((button) => {
        const description = document.getElementById(button.getAttribute('aria-controls'));
        if (!description) return;

        description.classList.add('line-clamp-2');
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            description.classList.toggle('line-clamp-2', expanded);
            button.setAttribute('aria-expanded', String(!expanded));
            button.textContent = expanded ? 'Read more' : 'Read less';
        });
    });

    // Automatic Table Enhancer for WYSIWYG Content
    document.querySelectorAll('.prose table').forEach((table) => {
        // 1. Promote first row to <thead> if missing
        if (!table.querySelector('thead')) {
            const firstRow = table.querySelector('tbody tr:first-child, tr:first-child');
            if (firstRow) {
                const thead = document.createElement('thead');
                thead.appendChild(firstRow);
                table.insertBefore(thead, table.firstChild);
            }
        }

        // 2. Wrap standalone tables in a responsive scroll container
        if (!table.parentElement.classList.contains('table-wrapper') && !table.closest('figure.wp-block-table')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-wrapper';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        }

        // 3. Highlight featured product column in comparison tables
        const headerCells = table.querySelectorAll('thead th, thead td');
        if (headerCells.length >= 2) {
            let highlightIdx = -1;
            const pageHeading = (document.querySelector('h1')?.textContent || '').trim().toLowerCase();

            // Check if any column header matches the current product title
            if (pageHeading) {
                headerCells.forEach((th, idx) => {
                    const txt = th.textContent.trim().toLowerCase();
                    if (idx > 0 && txt && (pageHeading.includes(txt) || txt.includes(pageHeading.split(' ')[0]))) {
                        highlightIdx = idx;
                    }
                });
            }

            // Fallback: if comparison table (>= 3 columns) and no column matched, highlight the last column
            if (highlightIdx === -1 && headerCells.length >= 3) {
                highlightIdx = headerCells.length - 1;
            }

            if (highlightIdx !== -1) {
                const targetTh = headerCells[highlightIdx];
                targetTh.classList.add('iva-highlight-header');
                table.querySelectorAll(`tbody tr td:nth-child(${highlightIdx + 1}), tbody tr th:nth-child(${highlightIdx + 1})`).forEach((td) => {
                    td.classList.add('iva-highlight-cell');
                });
            }
        }
    });

    // Support Email Click Helper: Copies email address as a reliable backup when mailto link is clicked
    document.querySelectorAll('a[data-support-email]').forEach((link) => {
        link.addEventListener('click', () => {
            const email = link.getAttribute('data-support-email');
            if (!email) return;

            const copyPromise = (navigator.clipboard && navigator.clipboard.writeText)
                ? navigator.clipboard.writeText(email)
                : new Promise((resolve, reject) => {
                    try {
                        const ta = document.createElement('textarea');
                        ta.value = email;
                        ta.style.position = 'fixed';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.focus();
                        ta.select();
                        const success = document.execCommand('copy');
                        document.body.removeChild(ta);
                        success ? resolve() : reject();
                    } catch (err) {
                        reject(err);
                    }
                });

            copyPromise.then(() => {
                let badge = link.parentElement.querySelector('.email-copied-badge');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'email-copied-badge inline-flex items-center text-xs font-bold text-[#f49a5b] bg-black/50 border border-[#f49a5b]/40 px-2.5 py-0.5 rounded-full transition-opacity duration-300 ml-2 shadow-sm';
                    link.parentElement.appendChild(badge);
                }
                badge.textContent = 'Copied ' + email;
                badge.style.opacity = '1';
                setTimeout(() => {
                    badge.style.opacity = '0';
                    setTimeout(() => badge.remove(), 300);
                }, 2500);
            }).catch(() => {});
        });
    });
});

// Quantity buttons: select one, the price in the same card follows it.
document.addEventListener('click', (event) => {
    const button = event.target.closest('.iva-qbtn');
    if (!button) return;
    const card = button.closest('[data-iva-qcard]');
    if (!card) return;
    card.querySelectorAll('.iva-qbtn').forEach((other) => other.setAttribute('aria-pressed', 'false'));
    button.setAttribute('aria-pressed', 'true');
    const output = card.querySelector('[data-iva-price]');
    if (output && button.dataset.ivaAmount) {
        output.innerHTML = button.dataset.ivaAmount;
        output.classList.remove('is-changed');
        void output.offsetWidth;
        output.classList.add('is-changed');
    }
    const cartLink = card.querySelector('[data-iva-cart]');
    if (cartLink && button.dataset.ivaCartUrl) cartLink.href = button.dataset.ivaCartUrl;
    const unit = card.querySelector('[data-iva-unit-out]');
    if (unit) unit.innerHTML = button.dataset.ivaUnit || '';
});

// Previous theme's FAQ accordions (content stored in the old "modules" field).
document.addEventListener('click', (event) => {
    const title = event.target.closest('.iva-legacy .accordion-title');
    if (!title) return;
    const content = title.parentElement.querySelector('.accordion-content');
    if (content) content.style.display = content.style.display === 'block' ? 'none' : 'block';
});

// Copy buttons on the thank you page.
document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-iva-copy]');
    if (!button) return;
    const value = button.parentElement.querySelector('[data-iva-copy-value]');
    if (!value) return;

    const text = value.textContent.trim();
    const copyWithFallback = () => {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.setAttribute('aria-hidden', 'true');
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        textarea.setSelectionRange(0, textarea.value.length);

        let copied = false;
        try {
            copied = document.execCommand('copy');
        } catch (error) {}

        textarea.remove();
        return copied;
    };

    let copyPromise;
    try {
        copyPromise = navigator.clipboard && typeof navigator.clipboard.writeText === 'function'
            ? navigator.clipboard.writeText(text).then(() => true).catch(copyWithFallback)
            : Promise.resolve(copyWithFallback());
    } catch (error) {
        copyPromise = Promise.resolve(copyWithFallback());
    }

    copyPromise.then((copied) => {
        if (!copied) return;
        const label = button.textContent;
        button.textContent = button.dataset.ivaCopied || label;
        setTimeout(() => { button.textContent = label; }, 1200);
    });
});

// Arrow buttons that scroll a horizontal card row by one card.
document.addEventListener('click', (event) => {
    const arrow = event.target.closest('[data-iva-scroll]');
    if (!arrow) return;
    const row = document.getElementById(arrow.dataset.ivaScroll);
    if (!row) return;
    const card = row.firstElementChild;
    const gap = parseFloat(getComputedStyle(row).columnGap) || 16;
    const step = card ? card.getBoundingClientRect().width + gap : row.clientWidth;
    row.scrollBy({ left: step * Number(arrow.dataset.dir || 1), behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
});


// Guide contents rail: mark the chapter being read; on phones keep the active chip in view.
(() => {
    const toc = document.querySelector('[data-iva-toc]');
    if (!toc || !('IntersectionObserver' in window)) return;
    const links = [...toc.querySelectorAll('a[href^="#"]')];
    const chapters = links.map((link) => document.getElementById(link.getAttribute('href').slice(1))).filter(Boolean);
    const setActive = (id) => {
        links.forEach((link) => {
            const active = link.getAttribute('href') === '#' + id;
            link.setAttribute('aria-current', active ? 'true' : 'false');
            const list = link.closest('ol');
            if (active && list.scrollWidth > list.clientWidth) {
                list.scrollTo({ left: link.offsetLeft - 16, behavior: 'smooth' });
            }
        });
    };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => { if (entry.isIntersecting) setActive(entry.target.id); });
    }, { rootMargin: '-30% 0px -60% 0px' });
    chapters.forEach((chapter) => observer.observe(chapter));
})();
