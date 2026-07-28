(function () {
    'use strict';

    window.CarlVon = window.CarlVon || {};

    const storageKey = 'carlvon-switched';
    const allowedValues = new Set(['personal', 'business']);
    let resizeFrame = 0;
    let resizeObserver = null;
    let sectionTransitionId = 0;

    function normalize(value) {
        return allowedValues.has(value) ? value : null;
    }

    function getSwitchers(root = document) {
        const scope = root instanceof Element || root instanceof Document ? root : document;
        return [
            ...(scope.matches?.('.switcher') ? [scope] : []),
            ...Array.from(scope.querySelectorAll('.switcher'))
        ];
    }

    function getStateRoots() {
        const roots = Array.from(document.querySelectorAll('[data-switcher]'))
            .filter(root => !root.parentElement?.closest('[data-switcher]'));

        return roots.length ? roots : [document.documentElement];
    }

    function ensureSlider(switcher) {
        let slider = switcher.querySelector(':scope > .switcher__slider');

        if (!slider) {
            slider = document.createElement('span');
            slider.className = 'switcher__slider';
            slider.setAttribute('aria-hidden', 'true');
            switcher.prepend(slider);
        }

        return slider;
    }

    function position(switcher) {
        const active = switcher.querySelector(':scope > [data-switch].active');
        if (!active) return;

        switcher.style.setProperty('--switcher-x', `${active.offsetLeft}px`);
        switcher.style.setProperty('--switcher-width', `${active.offsetWidth}px`);
        switcher.dataset.switcherReady = 'true';
    }

    function positionAll() {
        resizeFrame = 0;
        getSwitchers(document).forEach(position);
    }

    function requestPosition() {
        if (resizeFrame) return;
        resizeFrame = window.requestAnimationFrame(positionAll);
    }

    function getSections() {
        return Array.from(document.querySelectorAll('main[data-section]'))
            .filter(section => normalize(section.dataset.section));
    }

    function setSectionVisibility(section, visible) {
        section.classList.remove('is-fading-out', 'is-fading-in');
        section.hidden = !visible;
        section.setAttribute('aria-hidden', String(!visible));
    }

    function waitForFadeOut(section) {
        return new Promise(resolve => {
            let complete = false;
            const finish = () => {
                if (complete) return;
                complete = true;
                section.removeEventListener('transitionend', onTransitionEnd);
                window.clearTimeout(timeout);
                resolve();
            };
            const onTransitionEnd = event => {
                if (event.target === section && event.propertyName === 'opacity') finish();
            };
            const timeout = window.setTimeout(finish, 320);

            section.addEventListener('transitionend', onTransitionEnd);
        });
    }

    async function switchSections(value, animate) {
        const sections = getSections();
        if (!sections.length) return;

        const transitionId = ++sectionTransitionId;
        const targets = sections.filter(section => section.dataset.section === value);
        if (!targets.length) return;

        if (!animate || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            sections.forEach(section => setSectionVisibility(section, targets.includes(section)));
            return;
        }

        const outgoing = sections.filter(section => !section.hidden && !targets.includes(section));
        outgoing.forEach(section => section.classList.add('is-fading-out'));

        if (outgoing.length) {
            await Promise.all(outgoing.map(waitForFadeOut));
            if (transitionId !== sectionTransitionId) return;
            outgoing.forEach(section => setSectionVisibility(section, false));
        }

        targets.forEach(section => {
            section.hidden = false;
            section.setAttribute('aria-hidden', 'false');
            section.classList.remove('is-fading-out');
            section.classList.add('is-fading-in');
        });

        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => {
                if (transitionId !== sectionTransitionId) return;
                targets.forEach(section => section.classList.remove('is-fading-in'));
            });
        });
    }

    function setValue(value, options = {}) {
        const nextValue = normalize(value);
        if (!nextValue) return false;

        const stateRoots = getStateRoots();
        const previousValue = normalize(stateRoots[0]?.dataset.switched);

        if (!stateRoots.includes(document.documentElement)) {
            delete document.documentElement.dataset.switched;
        }

        stateRoots.forEach(root => {
            root.dataset.switched = nextValue;
        });

        switchSections(nextValue, options.animate !== false && previousValue !== nextValue);

        getSwitchers(document).forEach(switcher => {
            switcher.querySelectorAll(':scope > [data-switch]').forEach(option => {
                const active = option.dataset.switch === nextValue;
                option.classList.toggle('active', active);
                option.setAttribute('aria-pressed', String(active));
            });
        });

        if (options.persist !== false) {
            try {
                window.localStorage.setItem(storageKey, nextValue);
            } catch (error) {
                // Der globale data-Zustand funktioniert auch ohne Storage.
            }
        }

        requestPosition();

        if (options.emit !== false && previousValue !== nextValue) {
            document.dispatchEvent(new CustomEvent('carlvonswitchchange', {
                detail: { value: nextValue, previousValue }
            }));
        }

        return true;
    }

    function getInitialValue(switchers) {
        for (const root of getStateRoots()) {
            const rootValue = normalize(root.dataset.switched);
            if (rootValue) return rootValue;
        }

        try {
            const storedValue = normalize(window.localStorage.getItem(storageKey));
            if (storedValue) return storedValue;
        } catch (error) {
            // Fallback auf Markup oder personal.
        }

        for (const switcher of switchers) {
            const defaultValue = normalize(switcher.dataset.switchDefault);
            const activeValue = normalize(switcher.querySelector(':scope > [data-switch].active')?.dataset.switch);
            const firstValue = normalize(switcher.querySelector(':scope > [data-switch]')?.dataset.switch);
            if (defaultValue || activeValue || firstValue) return defaultValue || activeValue || firstValue;
        }

        return 'personal';
    }

    function bindOption(option) {
        if (option.dataset.switchBound === 'true') return;
        option.dataset.switchBound = 'true';

        if (option.tagName !== 'BUTTON') {
            option.setAttribute('role', 'button');
            option.setAttribute('tabindex', option.getAttribute('tabindex') || '0');
        }

        const activate = () => setValue(option.dataset.switch);
        option.addEventListener('click', activate);
        option.addEventListener('keydown', event => {
            if (option.tagName === 'BUTTON') return;
            if (event.key !== 'Enter' && event.key !== ' ') return;
            event.preventDefault();
            activate();
        });
    }

    function init(root = document) {
        const switchers = getSwitchers(root);
        if (!switchers.length) return;

        if (!resizeObserver && 'ResizeObserver' in window) {
            resizeObserver = new ResizeObserver(requestPosition);
        }

        switchers.forEach(switcher => {
            ensureSlider(switcher);
            switcher.setAttribute('role', 'group');
            switcher.setAttribute('aria-label', switcher.getAttribute('aria-label') || 'Choose view');
            switcher.querySelectorAll(':scope > [data-switch]').forEach(option => {
                bindOption(option);
                resizeObserver?.observe(option);
            });
            resizeObserver?.observe(switcher);
        });

        setValue(getInitialValue(switchers), { persist: false, emit: false, animate: false });
        document.fonts?.ready.then(requestPosition);
    }

    window.addEventListener('resize', requestPosition);
    window.CarlVon.switcher = { init, set: setValue };
})();
