(function () {
  function setActiveSpyItem(id) {
    document.querySelectorAll('.spy-group .nav-menu__item').forEach(function (item) {
      item.classList.toggle('is-active', item.getAttribute('href') === '#' + id);
    });
  }

  function bindSpy(root) {
    var scope = root || document;

    scope.querySelectorAll('.spy-group').forEach(function (group) {
      if (group.dataset.spyBound === '1') {
        return;
      }

      group.dataset.spyBound = '1';
    });
  }

  window.CarlVon = window.CarlVon || {};
  window.CarlVon.bindSpy = bindSpy;
  window.CarlVon.setActiveSpyItem = setActiveSpyItem;

  window.CarlVon.ready(function () {
    bindSpy(document);
  });
})();
