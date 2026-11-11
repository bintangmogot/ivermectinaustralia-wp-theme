# Reorder the Shop products

1. Open **Products → All Products** in WordPress.
2. Click **Sorting** above the products table.
3. Drag product rows into the order you want. WooCommerce saves the order
   automatically after each move.
4. Refresh the Shop page to check the result.

The current catalogue setting is **Default sorting (custom ordering + name)**
(`menu_order`). The theme's Shop catalogue uses WooCommerce's product query,
so it respects this saved order. No theme code, ACF migration, product copy,
prices or images need to change to reorder the catalogue.

If a visitor has selected a different sorting option in the URL, open the Shop
without its `orderby` parameter to see the store's default order.

The homepage Featured Products module can have its own selected-product order.
Its manual ACF selection is independent of the Shop catalogue's sorting screen.

Reviewed for Steven's 11 November 2026 Shop-ordering email. Confirmed against
the installed WooCommerce Sorting view and its automatic ordering script.
