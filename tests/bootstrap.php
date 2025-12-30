<?php
/**
 * WP News Sitemap Tests: Bootstrap
 *
 * @package wp-news-sitemap
 */

/**
 * Visit {@see https://mantle.alley.com/testing/test-framework.html} to learn more.
 */

use Alley\WP\News_Sitemap\Main;

\Mantle\Testing\manager()
	// Rsync the plugin to plugins/wp-news-sitemap when testing.
	->maybe_rsync_plugin()
	// Load the main file of the plugin.
	->loaded( fn () => require_once __DIR__ . '/../wp-news-sitemap.php' )
	->init( function () {
		global $wp_sitemaps_backup;

		/*
		 * Backup the global sitemaps server for restoration in tests.
		 * Mantle resets the global on tearDown, but this orphans its hooked methods. We need one stable instance.
		 * See `NewsSitemapTest::setUp()` for an example where and how this is used. Also see this Mantle issue
		 * https://github.com/alleyinteractive/mantle-framework/issues/853.
		 */
		$wp_sitemaps        = wp_sitemaps_get_server();
		$wp_sitemaps_backup = $wp_sitemaps; // phpcs:ignore WordPress.NamingConventions
	} )
	->install();
