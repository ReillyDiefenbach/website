(function () {
    function bindPrintControls(root) {
        var scope = root || document;

        scope.querySelectorAll('[data-print-page]').forEach(function (button) {
            if (button.dataset.printBound === '1') {
                return;
            }

            button.dataset.printBound = '1';
            button.addEventListener('click', function () {
                window.print();
            });
        });
    }

    window.CarlVon = window.CarlVon || {};
    window.CarlVon.bindPrintControls = bindPrintControls;

    window.CarlVon.ready(function () {
        bindPrintControls(document);
    });
})();
