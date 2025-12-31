<?php
/**
 * Plugin Name: News Sitemap for Publishers
 * Plugin URI: https://github.com/alleyinteractive/wp-news-sitemap
 * Description: Extends WordPress's XML sitemaps feature to add a news sitemap for Google, Yahoo, Bing, et al.
 * Version: 1.0.1
 * Author: Matthew Boynes
 * Author URI: https://alley.com
 * Requires at least: 5.5
 * Requires PHP: 8.2
 * Tested up to: 6.9
 * License: GPLv2 or later
 *
 * Text Domain: wp-news-sitemap
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
