<?php
/**
 * Plugin Name: WP News Sitemap
 * Plugin URI: https://github.com/alleyinteractive/wp-news-sitemap
 * Description: Extends WordPress' XML sitemaps feature to add a news sitemap
 * Version: 1.0.0
 * Author: Matthew Boynes
 * Author URI: https://github.com/alleyinteractive/wp-news-sitemap
 * Requires at least: 6.5
 * Requires PHP: 8.2
 * Tested up to: 6.8
 *
 * Text Domain: wp-news-sitemap
 * Domain Path: /languages/
 *
 * @package wp-news-sitemap
 */

namespace Alley\WP\News_Sitemap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Root directory to this plugin.
 */
define( 'WP_NEWS_SITEMAP_DIR', __DIR__ );

// Load the plugin's files.
require_once __DIR__ . '/src/class-main.php';
require_once __DIR__ . '/src/class-provider.php';
require_once __DIR__ . '/src/class-renderer.php';

// Initialize the plugin.
add_action( 'after_setup_theme', [ Main::class, 'boot' ] );
