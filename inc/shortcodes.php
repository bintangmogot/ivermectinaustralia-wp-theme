<?php
/**
 * Custom Shortcodes
 */

// Add any custom shortcodes here if needed
// e.g. [current_year]
add_shortcode('current_year', function() {
    return date('Y');
});
