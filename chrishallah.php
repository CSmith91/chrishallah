<?php
/**
 * Plugin Name:       Chrishallah
 * Description:       Shows prayer times local to Lambeth.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Chris Smith
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chrishallah
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

// Include prayer times logic
require_once plugin_dir_path( __FILE__ ) . 'inc/prayer-times.php';

// Initialise the plugin 
function create_block_chrishallah_block_init() {
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) { // Function introduced in WordPress 6.8.
		wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	} else {
		if ( function_exists( 'wp_register_block_metadata_collection' ) ) { // Function introduced in WordPress 6.7.
			wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		}
		$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
		foreach ( array_keys( $manifest_data ) as $block_type ) {
			register_block_type( __DIR__ . "/build/{$block_type}" );
		}
	}
}
add_action( 'init', 'create_block_chrishallah_block_init' );

// Load JavaScript for the countdown and CSS
function enqueue_prayer_assets() {
    if ( ! is_admin() ) { // Load only on frontend

        // Enqueue JavaScript
        wp_enqueue_script(
            'prayer-timer', 
            plugin_dir_url(__FILE__) . 'assets/js/prayer-timer.js', 
            array(), // Remove jQuery if not needed
            null, 
            true
        );

        // Enqueue CSS
        wp_enqueue_style(
            'prayer-times-style', 
            plugin_dir_url(__FILE__) . 'assets/css/styles.css', 
            array(), 
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_prayer_assets');

