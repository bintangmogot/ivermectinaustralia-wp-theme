<?php
/**
 * Waklert Australia — Multi-Color Flat Vector Icon Library & Smart Resolver
 *
 * Provides 100% editable + semantically aligned colorful icons across all ACF section modules:
 * - why_choose_us (Audiences / Benefits)
 * - trust_strip (Trust Badges)
 * - how_to_order (Step Cards)
 * - about_delivery (Delivery Feature Cards)
 * - about_mission (Mission & Vision Cards)
 * - track_order (Trust Benefit Cards)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Return the list of human-readable icon preset choices for ACF Select fields.
 */
function waklert_get_icon_preset_choices(): array {
    return [
        'auto'               => '✨ Auto-Match from Title & Description (Recommended)',
        'parcel_guarantee'   => '🛡️ Parcel Guarantee / Reship or Refund ("If it doesn\'t land, we fix it")',
        'live_chat_support'  => '💬 Real People on Chat / 24/7 SMS & Support',
        'medical_rx_clarity' => '📋 Straight-Up Medicine Info / Schedule 4 & Rx Clarity',
        'aud_checkout'       => '💳 Simple AUD Checkout / PayID & Secure Payment',
        'express_shipping'   => '🚚 Fast Domestic Express Shipping / Australia Post Van',
        'australia_delivery' => '🇦🇺 Australia-Wide Delivery / Tracked Parcel Map',
        'browse_catalogue'   => '💊 Focused Armodafinil Range / Blister Pack Catalogue',
        'listing_details'    => '🔍 Clear Listing Details / Compare Strengths & Packs',
        'returning_loyalty'  => '⭐ Returning Customers / Fast Reorder & Loyalty',
        'discreet_sealed'    => '📦 Discreet Plain Packaging / Sealed Pharmacy Pack',
        'po_box_delivery'    => '📮 PO Box & Parcel Locker Delivery',
        'no_customs'         => '✅ Smooth Domestic Dispatch / No Customs Hassle',
        'affordable_value'   => '💰 Affordable Direct Pricing / Best Value',
    ];
}

/**
 * Return the library of 64x64 multi-color flat vector SVGs keyed by preset slug.
 */
function waklert_get_colorful_icon_svgs(): array {
    return [
        // 1. Parcel Guarantee / Reship or Refund ("If it doesn't land, we fix it")
        'parcel_guarantee' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="8" y="14" width="38" height="34" rx="5" fill="#F59E0B"/>'
            . '<path d="M8 23h38v6H8z" fill="#D97706" opacity="0.45"/>'
            . '<rect x="23" y="14" width="8" height="16" fill="#FEF3C7"/>'
            . '<rect x="13" y="36" width="12" height="6" rx="1.5" fill="#FFFBEB"/>'
            . '<path d="M44 24l14 5v11c0 9.5-6.2 16.5-14 19-7.8-2.5-14-9.5-14-19V29l14-5z" fill="#10B981" stroke="#fff" stroke-width="2.5"/>'
            . '<path d="M38 41.5l4.2 4.2 8.3-8.5" stroke="#fff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>'
            . '</svg>',

        // 2. Real People on Chat / 24/7 SMS & Support ("Real people on chat", "Practical support", "Get order support")
        'live_chat_support' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="6" y="10" width="38" height="28" rx="8" fill="#0EA5E9"/>'
            . '<path d="M14 38v8l9-8h-9z" fill="#0EA5E9"/>'
            . '<circle cx="17" cy="24" r="2.8" fill="#fff"/>'
            . '<circle cx="25" cy="24" r="2.8" fill="#fff"/>'
            . '<circle cx="33" cy="24" r="2.8" fill="#fff"/>'
            . '<rect x="24" y="26" width="34" height="24" rx="8" fill="#10B981" stroke="#fff" stroke-width="2.5"/>'
            . '<path d="M50 50v7l-8-7h8z" fill="#10B981"/>'
            . '<path d="M33 36h16M33 42h10" stroke="#fff" stroke-width="3" stroke-linecap="round"/>'
            . '<circle cx="52" cy="14" r="6" fill="#F59E0B" stroke="#fff" stroke-width="2"/>'
            . '</svg>',

        // 3. Straight-Up Medicine Info / Schedule 4 & Rx Clarity ("Straight up about what we sell", "Prescription Customers", "Clear boundaries")
        'medical_rx_clarity' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="12" y="10" width="36" height="46" rx="5" fill="#0284C7"/>'
            . '<rect x="16" y="16" width="28" height="36" rx="3" fill="#F0F9FF"/>'
            . '<rect x="22" y="6" width="16" height="8" rx="3" fill="#F59E0B"/>'
            . '<circle cx="30" cy="26" r="6" fill="#EF4444"/>'
            . '<path d="M30 23v6M27 26h6" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/>'
            . '<path d="M21 37h18M21 43h12" stroke="#0284C7" stroke-width="2.8" stroke-linecap="round"/>'
            . '<circle cx="47" cy="45" r="11" fill="#10B981" stroke="#fff" stroke-width="2.5"/>'
            . '<path d="M42.5 45l3.2 3.2 6.3-6.5" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>'
            . '</svg>',

        // 4. Simple AUD Checkout / PayID & Secure Payment ("Simple AUD checkout", "Secure checkout", "Confirm Your Order")
        'aud_checkout' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="6" y="14" width="44" height="30" rx="5" fill="#0C4161"/>'
            . '<rect x="6" y="21" width="44" height="7" fill="#0EA5E9"/>'
            . '<rect x="12" y="33" width="10" height="6" rx="1.5" fill="#FBBF24"/>'
            . '<text x="27" y="38" fill="#E0F2FE" font-size="8" font-weight="900" font-family="sans-serif">AUD</text>'
            . '<circle cx="46" cy="42" r="13" fill="#10B981" stroke="#fff" stroke-width="2.5"/>'
            . '<text x="46" y="47" text-anchor="middle" fill="#fff" font-size="15" font-weight="900" font-family="sans-serif">$</text>'
            . '</svg>',

        // 5. Fast Domestic Express Shipping / Australia Post Van ("Fast, Local Domestic Shipping", "Track Delivery")
        'express_shipping' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<path d="M6 20h32v24H6z" fill="#EF4444" rx="3"/>'
            . '<path d="M38 26h12l8 10v8H38V26z" fill="#F59E0B"/>'
            . '<path d="M41 29h8l5 6H41v-6z" fill="#E0F2FE"/>'
            . '<circle cx="18" cy="46" r="6" fill="#1E293B"/>'
            . '<circle cx="18" cy="46" r="2.5" fill="#E2E8F0"/>'
            . '<circle cx="48" cy="46" r="6" fill="#1E293B"/>'
            . '<circle cx="48" cy="46" r="2.5" fill="#E2E8F0"/>'
            . '<text x="22" y="35" text-anchor="middle" fill="#fff" font-size="9" font-weight="900" font-family="sans-serif" font-style="italic">FAST</text>'
            . '<path d="M2 26h6M0 32h6" stroke="#FCA5A5" stroke-width="2.5" stroke-linecap="round"/>'
            . '</svg>',

        // 6. Australia-Wide Delivery / Map & Location Pin ("Australia-wide delivery", "Australia-wide Customers", "Delivery details at checkout")
        'australia_delivery' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<circle cx="32" cy="34" r="22" fill="#E0F2FE" stroke="#38BDF8" stroke-width="2.5"/>'
            . '<path d="M18 32c3-5 10-7 16-5 5 1.5 11 0 13 5 2 4-2 11-7 13-6 2-11-1-16-2-4-1-8-6-6-11z" fill="#10B981"/>'
            . '<path d="M32 8c-7.2 0-13 5.6-13 12.6C19 29.8 32 42 32 42s13-12.2 13-21.4C45 13.6 39.2 8 32 8z" fill="#EF4444" stroke="#fff" stroke-width="2"/>'
            . '<circle cx="32" cy="20" r="4.5" fill="#FEF08A"/>'
            . '</svg>',

        // 7. Focused Armodafinil Range / Blister Pack Catalogue ("Focused range", "A focused product category", "Browse the listings", "Review the Range")
        'browse_catalogue' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="8" y="10" width="36" height="44" rx="6" fill="#0EA5E9"/>'
            . '<rect x="12" y="14" width="28" height="36" rx="4" fill="#E0F2FE"/>'
            . '<rect x="16" y="18" width="8" height="12" rx="4" fill="#0284C7"/>'
            . '<rect x="28" y="18" width="8" height="12" rx="4" fill="#F59E0B"/>'
            . '<rect x="16" y="34" width="8" height="12" rx="4" fill="#10B981"/>'
            . '<rect x="28" y="34" width="8" height="12" rx="4" fill="#0284C7"/>'
            . '<circle cx="46" cy="44" r="11" fill="#F59E0B" stroke="#fff" stroke-width="2.5"/>'
            . '<path d="M42 44l3 3 5.5-5.5" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>'
            . '</svg>',

        // 8. Clear Listing Details / Compare Strengths & Packs ("Clear listing details", "Product details by listing", "Compare listing details", "New Customers")
        'listing_details' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="10" y="8" width="34" height="44" rx="5" fill="#38BDF8"/>'
            . '<rect x="14" y="13" width="26" height="34" rx="3" fill="#fff"/>'
            . '<path d="M18 21h18M18 28h18M18 35h11" stroke="#0284C7" stroke-width="3" stroke-linecap="round"/>'
            . '<circle cx="44" cy="42" r="11" fill="#F59E0B" stroke="#fff" stroke-width="2.5"/>'
            . '<circle cx="44" cy="42" r="5" fill="#FEF3C7"/>'
            . '<path d="M52 50l6 6" stroke="#D97706" stroke-width="4" stroke-linecap="round"/>'
            . '</svg>',

        // 9. Returning Customers / Fast Reorder & Loyalty ("Returning Customers")
        'returning_loyalty' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<circle cx="32" cy="32" r="22" fill="#0EA5E9"/>'
            . '<path d="M32 16l4.3 8.7 9.6 1.4-7 6.8 1.6 9.6L32 38l-8.5 4.5 1.6-9.6-7-6.8 9.6-1.4L32 16z" fill="#FBBF24" stroke="#fff" stroke-width="1.5"/>'
            . '<circle cx="48" cy="48" r="10" fill="#10B981" stroke="#fff" stroke-width="2"/>'
            . '<path d="M44 48l3 3 5-5.5" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            . '</svg>',

        // 10. Discreet Plain Packaging / Sealed Pharmacy Pack ("Discreet packaging", "Sealed product packaging")
        'discreet_sealed' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="10" y="14" width="38" height="34" rx="5" fill="#D97706"/>'
            . '<path d="M10 24h38v5H10z" fill="#B45309" opacity="0.5"/>'
            . '<rect x="25" y="14" width="8" height="15" fill="#FDE68A"/>'
            . '<circle cx="46" cy="44" r="12" fill="#0284C7" stroke="#fff" stroke-width="2.5"/>'
            . '<rect x="41" y="43" width="10" height="8" rx="2" fill="#FBBF24"/>'
            . '<path d="M43 43v-3a3 3 0 016 0v3" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>'
            . '</svg>',

        // 11. PO Box & Parcel Locker Delivery ("PO Box Delivery")
        'po_box_delivery' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<rect x="28" y="38" width="8" height="18" fill="#94A3B8"/>'
            . '<path d="M12 26c0-8.8 7.2-16 16-16h12c8.8 0 16 7.2 16 16v14H12V26z" fill="#EF4444"/>'
            . '<path d="M12 26c0-8.8 7.2-16 16-16h4c8.8 0 16 7.2 16 16v14H12V26z" fill="#DC2626"/>'
            . '<rect x="18" y="24" width="14" height="4" rx="2" fill="#1E293B"/>'
            . '<path d="M42 18v12" stroke="#FBBF24" stroke-width="3.5" stroke-linecap="round"/>'
            . '<path d="M42 18h8l-2 4 2 4h-8" fill="#FBBF24"/>'
            . '</svg>',

        // 12. Smooth Domestic Dispatch / No Customs Hassle ("No Customs Issues")
        'no_customs' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<circle cx="28" cy="16" r="8" fill="#FDBA74"/>'
            . '<path d="M18 14c0-5.5 4.5-10 10-10s10 4.5 10 10H18z" fill="#0284C7"/>'
            . '<path d="M26 10h14v4H26z" fill="#0369A1"/>'
            . '<path d="M14 48c0-9 6-16 14-16s14 7 14 16H14z" fill="#0EA5E9"/>'
            . '<rect x="30" y="30" width="22" height="18" rx="2" fill="#D97706"/>'
            . '<path d="M30 36h22" stroke="#FDE68A" stroke-width="2.5"/>'
            . '<circle cx="48" cy="44" r="9" fill="#10B981" stroke="#fff" stroke-width="2"/>'
            . '<path d="M44.5 44l2.5 2.5 5-5" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>'
            . '</svg>',

        // 13. Affordable Direct Pricing / Best Value ("Affordable Prices")
        'affordable_value' => '<svg viewBox="0 0 64 64" class="h-11 w-11" fill="none" xmlns="http://www.w3.org/2000/svg">'
            . '<circle cx="28" cy="24" r="14" fill="#FBBF24"/>'
            . '<circle cx="28" cy="24" r="10.5" fill="#F59E0B"/>'
            . '<text x="28" y="29" text-anchor="middle" fill="#FEF3C7" font-size="15" font-weight="900" font-family="sans-serif">$</text>'
            . '<path d="M36 32l10-10m0 0h-7m7 0v7" stroke="#EF4444" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>'
            . '<path d="M10 44c4-3 10-3 15-1l13 3c4 1 7-1 8-3 1-2-1-4-4-4h-9" stroke="#FDBA74" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>'
            . '<rect x="6" y="40" width="8" height="14" rx="2" fill="#0284C7"/>'
            . '</svg>',
    ];
}

/**
 * Determine the best matching icon preset slug based on a card's title and description.
 */
function waklert_detect_icon_preset_from_text(string $title = '', string $desc = '', string $default_preset = 'parcel_guarantee'): string {
    $t = strtolower(trim($title));
    $haystack = strtolower(trim($title . ' ' . $desc));
    if ($haystack === '') {
        return $default_preset;
    }

    // --- PHASE 1: High-priority matches on the CARD TITLE ($t) ---
    if ($t !== '') {
        if (str_contains($t, "doesn't land") || str_contains($t, "doesnt land") || str_contains($t, 'doesn’t land') || str_contains($t, 'fix it') || str_contains($t, 'reship') || str_contains($t, 'refund') || str_contains($t, 'guarantee')) {
            return 'parcel_guarantee';
        }
        if (str_contains($t, 'real people') || str_contains($t, 'on chat') || str_contains($t, 'support') || str_contains($t, 'question') || str_contains($t, 'whatsapp') || str_contains($t, 'sms')) {
            return 'live_chat_support';
        }
        if (str_contains($t, 'straight up') || str_contains($t, 'candy coating') || str_contains($t, 'schedule 4') || str_contains($t, 'prescription') || str_contains($t, 'clear boundaries') || str_contains($t, 'information first')) {
            return 'medical_rx_clarity';
        }
        if (str_contains($t, 'checkout') || str_contains($t, 'payid') || str_contains($t, 'payment') || str_contains($t, 'confirm your order')) {
            if (str_contains($t, 'delivery')) {
                return 'express_shipping';
            }
            return 'aud_checkout';
        }
        if (str_contains($t, 'returning') || str_contains($t, 'repeat') || str_contains($t, 'loyalty')) {
            return 'returning_loyalty';
        }
        if (str_contains($t, 'sealed')) {
            return 'browse_catalogue';
        }
        if (str_contains($t, 'discreet')) {
            return 'discreet_sealed';
        }
        if (str_contains($t, 'po box') || str_contains($t, 'parcel locker')) {
            return 'po_box_delivery';
        }
        if (str_contains($t, 'no customs') || str_contains($t, 'customs')) {
            return 'no_customs';
        }
        if (str_contains($t, 'affordable') || str_contains($t, 'price') || str_contains($t, 'value')) {
            return 'affordable_value';
        }
        if (str_contains($t, 'browse') || str_contains($t, 'review the range') || str_contains($t, 'focused range') || str_contains($t, 'focused product') || str_contains($t, 'our approach')) {
            return 'browse_catalogue';
        }
        if (str_contains($t, 'compare') || str_contains($t, 'listing detail') || str_contains($t, 'by listing') || str_contains($t, 'new customer')) {
            return 'listing_details';
        }
        if (str_contains($t, 'australia-wide') || str_contains($t, 'destination')) {
            return 'australia_delivery';
        }
        if (str_contains($t, 'track') || str_contains($t, 'shipping') || str_contains($t, 'delivery') || str_contains($t, 'dispatch')) {
            return 'express_shipping';
        }
    }

    // --- PHASE 2: Fallback matches on combined Title + Description ($haystack) ---
    if (
        str_contains($haystack, "doesn't land") ||
        str_contains($haystack, "doesnt land") ||
        str_contains($haystack, 'doesn’t land') ||
        str_contains($haystack, 'we fix it') ||
        str_contains($haystack, 'reship') ||
        str_contains($haystack, 'refund') ||
        str_contains($haystack, 'guarantee') ||
        str_contains($haystack, 'stalls') ||
        str_contains($haystack, 'goes missing')
    ) {
        return 'parcel_guarantee';
    }

    if (
        str_contains($haystack, 'real people') ||
        str_contains($haystack, 'on chat') ||
        str_contains($haystack, 'whatsapp') ||
        str_contains($haystack, 'sms') ||
        str_contains($haystack, '7 days a week') ||
        str_contains($haystack, '24/7')
    ) {
        return 'live_chat_support';
    }

    if (
        str_contains($haystack, 'straight up') ||
        str_contains($haystack, 'candy coating') ||
        str_contains($haystack, 'schedule 4') ||
        str_contains($haystack, 'prescription') ||
        str_contains($haystack, 'doctor') ||
        str_contains($haystack, 'pharmacist') ||
        str_contains($haystack, 'medical advice') ||
        str_contains($haystack, 'clear boundaries')
    ) {
        return 'medical_rx_clarity';
    }

    if (
        str_contains($haystack, 'aud checkout') ||
        str_contains($haystack, 'payid') ||
        str_contains($haystack, 'bank transfer') ||
        str_contains($haystack, 'secure checkout') ||
        str_contains($haystack, 'encrypted') ||
        str_contains($haystack, 'payment') ||
        str_contains($haystack, 'confirm your order') ||
        str_contains($haystack, 'pay securely')
    ) {
        return 'aud_checkout';
    }

    if (
        str_contains($haystack, 'returning customer') ||
        str_contains($haystack, 'repeat') ||
        str_contains($haystack, 'familiar with the product')
    ) {
        return 'returning_loyalty';
    }

    if (
        str_contains($haystack, 'discreet') ||
        str_contains($haystack, 'sealed') ||
        str_contains($haystack, 'unmarked') ||
        str_contains($haystack, 'plain pack') ||
        str_contains($haystack, 'blister pack')
    ) {
        return 'discreet_sealed';
    }

    if (
        str_contains($haystack, 'po box') ||
        str_contains($haystack, 'parcel locker') ||
        str_contains($haystack, 'mailbox')
    ) {
        return 'po_box_delivery';
    }

    if (
        str_contains($haystack, 'no customs') ||
        str_contains($haystack, 'customs')
    ) {
        return 'no_customs';
    }

    if (
        str_contains($haystack, 'affordable') ||
        str_contains($haystack, 'lowest price') ||
        str_contains($haystack, 'best value')
    ) {
        return 'affordable_value';
    }

    if (
        str_contains($haystack, 'listing detail') ||
        str_contains($haystack, 'compare') ||
        str_contains($haystack, 'by listing') ||
        str_contains($haystack, 'new customer') ||
        str_contains($haystack, 'stated strength')
    ) {
        return 'listing_details';
    }

    if (
        str_contains($haystack, 'focused range') ||
        str_contains($haystack, 'focused product') ||
        str_contains($haystack, 'browse') ||
        str_contains($haystack, 'review the range') ||
        str_contains($haystack, 'catalogue')
    ) {
        return 'browse_catalogue';
    }

    if (
        str_contains($haystack, 'australia-wide') ||
        str_contains($haystack, 'australian states') ||
        str_contains($haystack, 'metro and regional') ||
        str_contains($haystack, 'territories')
    ) {
        return 'australia_delivery';
    }

    if (
        str_contains($haystack, 'shipping') ||
        str_contains($haystack, 'delivery') ||
        str_contains($haystack, 'dispatch') ||
        str_contains($haystack, 'australia post') ||
        str_contains($haystack, 'track')
    ) {
        return 'express_shipping';
    }

    return $default_preset;
}

/**
 * Render an icon for any ACF card/badge/step row.
 *
 * Priority order:
 * 1. Custom uploaded image (`icon_image` array/URL/ID) -> renders `<img>`
 * 2. Explicit `icon_preset` selected in ACF (when not empty and not 'auto') -> renders preset SVG
 * 3. Custom colorful `<svg>` pasted in `icon_svg` (ignoring legacy monochrome `stroke="currentColor"` wireframes) -> renders SVG
 * 4. Smart semantic detection from `$title` + `$desc` -> renders matching colorful preset SVG
 *
 * @param array  $args {
 *   @type mixed  $icon_image      ACF image array, attachment ID, or URL.
 *   @type string $icon_preset     ACF select value (e.g. 'auto', 'parcel_guarantee', etc.).
 *   @type string $icon_svg        ACF textarea raw SVG markup.
 *   @type string $title           Card title or label for semantic matching and alt text.
 *   @type string $desc            Card description for semantic matching.
 *   @type string $default_preset  Fallback preset key if text is empty.
 *   @type string $size_class      Tailwind size classes for the SVG/img (default: 'h-11 w-11').
 * }
 * @return string HTML markup for the icon.
 */
function waklert_render_card_icon(array $args = []): string {
    $icon_image     = $args['icon_image'] ?? null;
    $icon_preset    = isset($args['icon_preset']) ? trim((string) $args['icon_preset']) : 'auto';
    $icon_svg       = isset($args['icon_svg']) ? trim((string) $args['icon_svg']) : '';
    $title          = isset($args['title']) ? trim((string) $args['title']) : '';
    $desc           = isset($args['desc']) ? trim((string) $args['desc']) : '';
    $default_preset = isset($args['default_preset']) ? trim((string) $args['default_preset']) : 'parcel_guarantee';
    $size_class     = isset($args['size_class']) ? trim((string) $args['size_class']) : 'h-11 w-11';

    // 1. Custom Uploaded Image (PNG / SVG / WebP from Flaticon, SVGRepo, etc.)
    $img_url = '';
    $img_alt = $title !== '' ? $title : 'Section Icon';
    if (is_array($icon_image) && !empty($icon_image['url'])) {
        $img_url = (string) $icon_image['url'];
        if (!empty($icon_image['alt'])) {
            $img_alt = (string) $icon_image['alt'];
        }
    } elseif (is_numeric($icon_image) && (int) $icon_image > 0) {
        $resolved = wp_get_attachment_image_url((int) $icon_image, 'full');
        if ($resolved) {
            $img_url = $resolved;
        }
    } elseif (is_string($icon_image) && filter_var($icon_image, FILTER_VALIDATE_URL)) {
        $img_url = $icon_image;
    }

    if ($img_url !== '') {
        return sprintf(
            '<img src="%s" alt="%s" class="%s object-contain select-none" loading="lazy" decoding="async" />',
            esc_url($img_url),
            esc_attr($img_alt),
            esc_attr($size_class)
        );
    }

    $library = waklert_get_colorful_icon_svgs();

    // 2. Explicit Preset Chosen in WP Admin (not 'auto')
    if ($icon_preset !== '' && $icon_preset !== 'auto' && isset($library[$icon_preset])) {
        return str_replace('class="h-11 w-11"', 'class="' . esc_attr($size_class) . '"', $library[$icon_preset]);
    }

    // 3. Custom Colorful SVG Code Pasted in `icon_svg` (ignore legacy monochrome wireframe strokes)
    if (
        $icon_svg !== '' &&
        stripos($icon_svg, '<svg') !== false &&
        stripos($icon_svg, 'stroke="currentColor"') === false
    ) {
        return $icon_svg;
    }

    // 4. Smart Semantic Match from Title + Description
    $matched_key = waklert_detect_icon_preset_from_text($title, $desc, $default_preset);
    $svg = $library[$matched_key] ?? $library['parcel_guarantee'];

    return str_replace('class="h-11 w-11"', 'class="' . esc_attr($size_class) . '"', $svg);
}
