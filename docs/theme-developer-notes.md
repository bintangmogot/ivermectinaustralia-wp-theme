# Ivermectin Australia theme notes

Classic PHP theme, ACF Pro Flexible Content, WooCommerce, Tailwind CSS 4.

## Existing site content is never touched

The theme does not write to the database on load: no option, post, meta or product updates. It only reads.

- New pages use the `page_modules` flexible field (`modules/content-{layout}.php`).
- Pages built with the previous theme keep their `modules` field. When a page has no `page_modules`, `ivermectin_render_modules()` renders the old layouts from `legacy/modules.php`, scoped under `.iva-legacy`.
- Reviews use the previous theme's `reviews` post type and `reviews_cat` taxonomy, with its `rating` and `name` fields.
- Old options still work: `footer_text`, `footer_menu`, `footer_menu_2`, `footer_menu_3`, `payid`. They stay editable under Legacy Options.
- Old product fields are still read: `short_description`, `price_per_unit`, `shop_page_text`, `text_under_product_image`, `faqs`, `faq_title`, `extra_tabs`.
- `images/` stays at the theme root in case existing content links to it.

## Content comes from ACF

No copy, phone, email, address, price or bank detail lives in PHP. Theme Settings (Theme Settings in WP Admin) holds the header, footer, contact details, checkout wording, thank you page and 404 text. Their ACF defaults are the previous site's text, so nothing is blank before the first save.

Bank details on the thank you page come from the WooCommerce BACS settings. The PayID comes from Theme Settings (or the old `payid` option).

## Adding a module

1. Add the layout to `page_modules` in ACF (snake_case name). ACF writes `acf-json/group_page_modules.json`.
2. Create `modules/content-{layout}.php`. Read with `get_sub_field()`, escape on output, return early when fields are empty. No fallback copy.
3. `npm run build`, commit `assets/css/main.css`.

## Design

Tokens live in `assets/css/source.css` (navy `#012C70`, blue `#0A68D4`, orange `#FF831A`, red only as a small accent). Fonts: Schibsted Grotesk and Public Sans. Cards use the ledger style: `.lcard`, `.lcard-tab`, `.lcard-foot`. Icons are colorful flat SVGs from `inc/icon-library.php` (`ivermectin_icon('slug')`). No emojis.

## Checks before every push

```bash
for f in $(git ls-files '*.php'); do php -l "$f"; done
grep -rnP '[\x{2013}\x{2014}]|â€' --include='*.php' --include='*.json' --include='*.css' --include='*.js' . --exclude-dir=node_modules --exclude-dir=legacy --exclude=main.css
grep -rn 'A\$' woocommerce/ modules/ template-parts/
npm run build
```
