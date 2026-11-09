# Content recovery and consolidation

The existing Modules, Page Modules and Product Page Details controls stay available.
The theme does not hide ACF content panels, including panels previously unchecked through
Screen Options on Pages and Products. This does not delete or rewrite the user's preferences.

Frontend requests do not run theme content installers. Authenticated administrators can
initialize missing scalar controls, but ordinary loads no longer replace homepage modules
or copy FAQs between editing systems. A saved empty homepage is treated as an intentional clear.
The full content-seed helper is an explicit developer migration operation, not a page-load hook.

Product specifications remain editable under Products > Edit product > Product Page Details.
Strength uses the WooCommerce product attribute or title. Menus, prices, variations, inventory,
reviews and form definitions remain in their native administration screens.

## Waklert reference principles

Extend existing ACF controls with their stable keys. Keep frontend copy connected to its
saved editor source. Initialize absent values without refilling deliberate clears. Preserve
module order and nested fields. Check actual ACF saving, not only PHP syntax or Git status.
These principles come from the sibling Waklert content-editing guide; no client copy or
database values are imported from that project.

## Migration state

No consolidation has been run. InstaWP is authoritative for Steven's latest content.
The older local database must never be uploaded over it.

Run the read-only inventory on the authoritative site with:

```bash
wp eval-file tools/audit-module-migration.php
```

Consolidation requires a fresh site backup, an explicit layout/field mapping for every active
source, and a comparison of saved values and rendered sections before and after on a copy.
The inventory is not a migration. Adding a new Page Modules row currently changes the
selected frontend source, so do not manually transfer partial page content between parents.
Keep existing fields until the complete destination is verified against the current site.

Recovery checks:

```bash
php tests/editor-preservation.php
php tests/shop-editor-render.php
```

These use disposable local fixtures and restore their temporary changes. They are CLI-only.
