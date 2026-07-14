(function () {
  function bindNavMenu(root) {
    var scope = root || document;

    scope.querySelectorAll('.bigMenu__close').forEach(function (button) {
      if (button.dataset.navCloseBound === '1') {
        return;
      }

      button.dataset.navCloseBound = '1';
      button.addEventListener('click', function () {
        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
      });
    });

    scope.querySelectorAll('.nav-menu [data-link]').forEach(function (link) {
      if (link.dataset.navMenuBound === '1') {
        return;
      }

      link.dataset.navMenuBound = '1';
    });
  }

  window.CarlVon = window.CarlVon || {};
  window.CarlVon.bindNavMenu = bindNavMenu;

  window.CarlVon.ready(function () {
    bindNavMenu(document);
  });
})();
