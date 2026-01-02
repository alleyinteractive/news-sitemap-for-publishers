<?php
/**
 * Plugin Name: News Sitemap for Publishers
 * Plugin URI: https://github.com/alleyinteractive/news-sitemap-for-publishers
 * Description: Extends WordPress's XML sitemaps feature to add a news sitemap for Google, Yahoo, Bing, et al.
 * Version: 1.2.1
 * Author: Matthew Boynes
 * Author URI: https://alley.com
 * Requires at least: 5.5
 * Requires PHP: 8.2
 * Tested up to: 6.9
 * License: GPLv2 or later
 *
 * Text Domain: news-sitemap-for-publishers
 *
 * @package news-sitemap-for-publishers
 */

namespace Alley\WP\News_Sitemap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Root directory to this plugin.
 */
define( 'NEWS_SITEMAP_FOR_PUBLISHERS_DIR', __DIR__ );

// Load the plugin's files.
require_once __DIR__ . '/src/class-main.php';
require_once __DIR__ . '/src/class-provider.php';
require_once __DIR__ . '/src/class-renderer.php';

// Initialize the plugin.
add_action( 'after_setup_theme', [ Main::class, 'boot' ] );
