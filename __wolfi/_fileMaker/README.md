# CarlVon CDN Builder

Edit source files here:

- `scss/main.scss` controls the SCSS include order.
- `scss/nav_menu.scss` contains the normal navigation styles.
- `scss/spy.scss` may override shared navigation classes inside `.spy-group`.
- `scss/factsheet.scss` contains factsheet-specific styles.
- `js/main.js` starts the JavaScript bundle.
- `js/nav_menu.js`, `js/spy.js`, and `js/factsheet.js` are appended in this order.

Generated files are written to:

- `_cdn/carlvon.css`
- `_cdn/carlvon.min.css`
- `_cdn/carlvon.js`
- `_cdn/carlvon.min.js`

Recommended non-AJAX hook in `index.php`:

```php
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    require_once __DIR__ . '/__wolfi/_fileMaker/makeCDN.php';
    makeCDN_ifChanged();
}
```

`makeCDN_ifChanged()` compares source hashes and only rewrites `_cdn` when a source file changed.
