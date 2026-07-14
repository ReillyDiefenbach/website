(function () {
  window.APP_ROOT = window.APP_ROOT || '/';
  window.BACKBONE = window.BACKBONE || '/_PARAMS.php';
  window.CarlVon = window.CarlVon || {};

  function ready(callback) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', callback, { once: true });
      return;
    }

    callback();
  }

  window.CarlVon.ready = ready;
})();
