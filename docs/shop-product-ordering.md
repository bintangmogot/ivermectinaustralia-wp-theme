# Reorder the Shop products

1. Open **Products → Re-Order** in WordPress (Post Types Order plugin).
2. Drag the product rows into the preferred order, then click **Update**.
3. Refresh the Shop page to check the result.

The Shop follows saved `menu_order` ascending, with newest first when positions
tie, matching Re-Order. WooCommerce still excludes unpublished and hidden products.
Old sorting URL parameters do not override the Shop order. Search and category
archives retain their existing ordering.

The separate WooCommerce **Sorting** tab is removed while Post Types Order is
available. The frontend sorting dropdown remains removed. No saved product,
ACF value, content, image, price or store setting is changed by this integration.

The homepage Featured Products module can have its own selected-product order.
Its manual ACF selection is independent of the Shop catalogue's sorting screen.

Use Re-Order as the single product-ordering interface.
