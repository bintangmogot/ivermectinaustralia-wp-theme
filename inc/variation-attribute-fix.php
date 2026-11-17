<?php
/**
 * One-time repair: variations saved under an old attribute name.
 *
 * The product attribute was renamed (for example "Quantity" became "Quantity (prices in AUD)"),
 * but the variations still hold their value under the old key (attribute_quantity). WooCommerce
 * then sees every variation as "any quantity" and always picks the first one, so every quantity
 * showed the price of the smallest pack, and the quantity buttons on product cards showed the long product title (for example Wormentel Duo 156mg). This copies the old value to the current attribute key.
 * It only fills attributes that are empty and runs once.
 */
defined( 'ABSPATH' ) || exit;

function ivermectin_repair_variation_attributes() {
	if ( '1' === get_option( 'ivermectin_variation_attribute_repair_done' ) || ! function_exists( 'wc_get_products' ) ) {
		return;
	}

	$products = wc_get_products(
		array(
			'type'   => 'variable',
			'limit'  => -1,
			'status' => array( 'publish', 'draft', 'private' ),
		)
	);

	foreach ( $products as $product ) {
		$current_keys = array();
		foreach ( $product->get_attributes() as $attribute ) {
			if ( $attribute->get_variation() ) {
				$current_keys[ 'attribute_' . sanitize_title( $attribute->get_name() ) ] = array_map( 'strval', (array) $attribute->get_options() );
			}
		}
		if ( ! $current_keys ) {
			continue;
		}

		$changed = false;
		foreach ( $product->get_children() as $variation_id ) {
			foreach ( $current_keys as $key => $options ) {
				if ( '' !== (string) get_post_meta( $variation_id, $key, true ) ) {
					continue;
				}
				foreach ( get_post_meta( $variation_id ) as $meta_key => $values ) {
					if ( 0 !== strpos( $meta_key, 'attribute_' ) || isset( $current_keys[ $meta_key ] ) ) {
						continue;
					}
					$value = isset( $values[0] ) ? (string) $values[0] : '';
					if ( '' !== $value && in_array( $value, $options, true ) ) {
						update_post_meta( $variation_id, $key, $value );
						$changed = true;
						break;
					}
				}
			}
		}

		if ( $changed ) {
			wc_delete_product_transients( $product->get_id() );
		}
	}

	update_option( 'ivermectin_variation_attribute_repair_done', '1', false );
}
add_action( 'init', 'ivermectin_repair_variation_attributes', 99 );
