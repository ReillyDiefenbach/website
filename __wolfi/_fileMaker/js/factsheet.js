(function () {
  function bindFactsheet(root) {
    var scope = root || document;

    scope.querySelectorAll('.factsheet').forEach(function (factsheet) {
      if (factsheet.dataset.factsheetBound === '1') {
        return;
      }

      factsheet.dataset.factsheetBound = '1';
    });
  }

  window.CarlVon = window.CarlVon || {};
  window.CarlVon.bindFactsheet = bindFactsheet;

  window.CarlVon.ready(function () {
    bindFactsheet(document);
  });
})();
