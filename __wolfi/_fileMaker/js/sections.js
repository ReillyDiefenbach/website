(function () {
    'use strict';

    window.CarlVon = window.CarlVon || {};

    function initSections(root) {
        const scope = root && root.querySelectorAll ? root : document;

        scope.querySelectorAll('section.content, section.topHead, section.modHead, section.hero')
            .forEach(function (section) {
                if (section.dataset.sectionReady === '1') return;
                section.dataset.sectionReady = '1';
            });
    }

    window.CarlVon.sections = {
        init: initSections
    };

    if (window.CarlVon.ready) {
        window.CarlVon.ready(function () {
            initSections(document);
        });
    } else if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initSections(document);
        }, { once: true });
    } else {
        initSections(document);
    }
})();

