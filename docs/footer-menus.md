# Footer menus

Edit the four footer columns in **Appearance → Menus**. Select **Best Sellers**
to add products, change labels, remove links or reorder them. Open the Products
panel, select the products, choose **Add to Menu**, then **Save Menu**. If the
Products panel is hidden, enable it in Screen Options.

The initial transfer copies existing links as custom links. The Best Sellers
links can then be converted to native product menu items while retaining their
labels and order. Product items use the products' current permalinks, which may
correct outdated custom URLs. Menu names provide the footer headings.

For an existing site, take a current database backup, then run these commands
from the active theme directory. The preview is read-only:

```sh
wp eval-file tools/transfer-footer-menus.php preview
wp --user=admin-ivermectin eval-file tools/transfer-footer-menus.php apply
wp eval-file tools/transfer-footer-menus.php preview
```

The transfer reads the current site's legacy footer fields; it does not copy
content from another installation. It leaves the original fields untouched,
does not overwrite assigned menu locations and stops on naming or mapping
conflicts. Until a location has an assigned menu, its saved legacy group is
shown. If creation fails, inspect any newly created unassigned menu before
retrying. The transfer does not run automatically on page loads.

After transferring the menus, review the product matches before converting:

```sh
wp eval-file tools/convert-footer-products.php preview
wp --user=admin-ivermectin eval-file tools/convert-footer-products.php apply
wp eval-file tools/convert-footer-products.php preview
```

The conversion stops if a product is missing, unpublished or ambiguous. It does
not publish products or run automatically. Review the preview on each site,
since its products and their status may differ from another installation.
