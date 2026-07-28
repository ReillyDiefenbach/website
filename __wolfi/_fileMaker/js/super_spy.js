(function () {
    'use strict';

    window.CarlVon = window.CarlVon || {};

    let cleanup = null;

    function getHeaderOffset() {
        const header = document.querySelector('header, #header');
        return (header?.getBoundingClientRect().height || 72) + 20;
    }

    function getPageRoot(root) {
        if (root instanceof Element && root.matches('#middle')) return root;
        return root.querySelector?.('#middle') || root;
    }

    function getSections(root) {
        const pageRoot = getPageRoot(root);
        if (
            pageRoot.matches?.('[data-legal-page]')
            || pageRoot.querySelector?.('[data-legal-page]')
        ) return [];

        const directSections = pageRoot.querySelectorAll?.(':scope > section.content') || [];
        const sections = directSections.length
            ? Array.from(directSections)
            : Array.from(pageRoot.querySelectorAll?.('section.content') || []);

        return sections.filter(section => !section.parentElement?.closest('section.content'));
    }

    function getLabel(section, index) {
        const heading = section.querySelector('h1, h2, h3, h4');
        return section.dataset.superSpyLabel
            || section.getAttribute('aria-label')
            || heading?.textContent?.trim()
            || `Abschnitt ${index + 1}`;
    }

    function destroy() {
        if (cleanup) cleanup();
        cleanup = null;
        document.querySelectorAll('.super_spy[data-generated="super-spy"]').forEach(nav => nav.remove());
    }

    function init(root = document) {
        const scope = root instanceof Element || root instanceof Document ? root : document;
        const sections = getSections(scope);

        destroy();
        if (sections.length < 2) return;

        const nav = document.createElement('nav');
        nav.className = 'super_spy';
        nav.dataset.generated = 'super-spy';
        nav.setAttribute('aria-label', 'Seitennavigation');

        const items = sections.map((section, index) => {
            const label = getLabel(section, index);
            const button = document.createElement('button');
            const track = document.createElement('span');
            const fill = document.createElement('span');

            button.className = 'super_spy__item';
            button.type = 'button';
            button.dataset.label = label;
            button.setAttribute('aria-label', label);
            track.className = 'super_spy__track';
            track.setAttribute('aria-hidden', 'true');
            fill.className = 'super_spy__fill';
            track.appendChild(fill);
            button.appendChild(track);
            nav.appendChild(button);

            button.addEventListener('click', () => {
                const top = section.getBoundingClientRect().top + window.scrollY - getHeaderOffset();
                window.scrollTo({
                    top: Math.max(0, top),
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'
                });
            });

            return { section, button };
        });

        document.body.appendChild(nav);

        let frame = 0;

        const measure = () => {
            items.forEach(({ section, button }) => {
                button.style.setProperty('--super-spy-size', Math.max(1, section.getBoundingClientRect().height));
            });
        };

        const update = () => {
            frame = 0;
            const viewportTop = window.scrollY + getHeaderOffset();
            const viewportBottom = window.scrollY + window.innerHeight;
            const readingLine = viewportTop + Math.max(0, viewportBottom - viewportTop) * .35;
            let activeIndex = 0;
            let largestVisibleArea = -1;

            items.forEach(({ section }, index) => {
                const sectionTop = section.getBoundingClientRect().top + window.scrollY;
                const sectionBottom = sectionTop + section.offsetHeight;
                const visibleArea = Math.max(
                    0,
                    Math.min(sectionBottom, viewportBottom) - Math.max(sectionTop, viewportTop)
                );

                if (visibleArea > largestVisibleArea) {
                    largestVisibleArea = visibleArea;
                    activeIndex = index;
                }

                if (sectionTop <= readingLine && sectionBottom > readingLine) {
                    activeIndex = index;
                }
            });

            items.forEach(({ section, button }, index) => {
                const sectionTop = section.getBoundingClientRect().top + window.scrollY;
                const sectionHeight = Math.max(1, section.offsetHeight);
                const sectionBottom = sectionTop + sectionHeight;
                const visibleTop = Math.max(sectionTop, viewportTop);
                const visibleBottom = Math.min(sectionBottom, viewportBottom);
                const visibleArea = Math.max(0, visibleBottom - visibleTop);
                const fillTop = visibleArea > 0 ? (visibleTop - sectionTop) / sectionHeight : 0;
                const fillHeight = visibleArea / sectionHeight;

                button.classList.toggle('is-active', index === activeIndex);
                button.classList.toggle('is-visible', visibleArea > 0);
                button.setAttribute('aria-current', index === activeIndex ? 'location' : 'false');
                button.style.setProperty('--super-spy-fill-top', `${(fillTop * 100).toFixed(4)}%`);
                button.style.setProperty('--super-spy-fill-height', `${(fillHeight * 100).toFixed(4)}%`);
            });
        };

        const requestUpdate = () => {
            if (frame) return;
            frame = window.requestAnimationFrame(update);
        };

        const onResize = () => {
            measure();
            requestUpdate();
        };

        const resizeObserver = 'ResizeObserver' in window
            ? new ResizeObserver(onResize)
            : null;

        items.forEach(({ section }) => resizeObserver?.observe(section));
        window.addEventListener('scroll', requestUpdate, { passive: true });
        window.addEventListener('resize', onResize);
        measure();
        update();

        cleanup = () => {
            window.removeEventListener('scroll', requestUpdate);
            window.removeEventListener('resize', onResize);
            resizeObserver?.disconnect();
            if (frame) window.cancelAnimationFrame(frame);
            nav.remove();
        };
    }

    window.CarlVon.superSpy = { init, destroy };
})();
