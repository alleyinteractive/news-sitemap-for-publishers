<?php
/**
 * WP News Sitemap Tests: Bootstrap
 *
 * @package wp-news-sitemap
 */

/**
 * Visit {@see https://mantle.alley.com/testing/test-framework.html} to learn more.
 */
\Mantle\Testing\manager()
	// Rsync the plugin to plugins/wp-news-sitemap when testing.
	->maybe_rsync_plugin()
	// Load the main file of the plugin.
	->loaded( fn () => require_once __DIR__ . '/../wp-news-sitemap.php' )
	->install();
