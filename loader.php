<?php
/**
 * Plugin Name:       WooCommerce Auto Calculate Total
 * Plugin URI:        
 * Description:       Automatically recalculates taxes and totals when saving admin-created orders.
 * Version:           1.1.2
 * Author:            Josiah
 * Author URI:        
 * Text Domain:       wact
 * Domain Path:       /languages
 */

// Prevent to access the file from outside of WordPress
if (!defined('ABSPATH')) {
	exit;
}

// Add functionality to recalculate taxes and totals for admin orders
add_action('woocommerce_process_shop_order_meta', function ($post_id, $post) {
	if (!is_admin()) return;

	$order = wc_get_order($post_id);
	if (!$order) return;

	// Only act if the order has line items
	$line_items = $order->get_items('line_item');
	if (empty($line_items)) return;

	/**
	 * IMPORTANT:
	 * - WooCommerce calculates taxes based on your settings:
	 *   WooCommerce > Settings > Tax > "Calculate tax based on" (Customer shipping address / Customer billing address / Shop base).
	 * - Ensure the order has the necessary address fields set before calculation,
	 *   otherwise tax may be calculated against the shop base.
	 */

	// Recalculate totals; passing true recalculates taxes too
	$order->calculate_totals(true);
	$order->save();
}, 20, 2);
