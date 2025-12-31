<?php
/**
 * Main class
 *
 * @package news-sitemap-for-publishers
 */

namespace Alley\WP\News_Sitemap;

use WP_Rewrite;
use WP_Sitemaps;

use function add_action;
use function add_filter;
use function esc_url;
use function home_url;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class.
 */
class Main {
	/**
	 * Sitemap provider key.
	 *
	 * @var string
	 */
	public static string $key = 'news';

	/**
	 * Sitemap URI.
	 *
	 * @var string
	 */
	public static string $uri = 'wp-news-sitemap.xml';

	/**
	 * Boot the plugin.
	 */
	public static function boot(): void {
		add_action( 'wp_sitemaps_init', [ self::class, 'sitemaps_init' ] );
	}

	/**
	 * Modify the WP_Sitemaps object after it is initialized.
	 *
	 * @param WP_Sitemaps $wp_sitemaps Sitemaps object.
	 * @return void
	 */
	public static function sitemaps_init( $wp_sitemaps ): void {
		$wp_sitemaps->registry->add_provider( self::$key, new Provider() );

		// Intercept the request to change the renderer if the request is for a news sitemap.
		add_action( 'template_redirect', [ self::class, 'set_news_renderer' ], 5 );

		// Register robots.txt modifications.
		add_filter( 'robots_txt', [ self::class, 'add_robots' ], 0, 2 ); // phpcs:ignore WordPressVIPMinimum.Hooks

		// Register routes for providers.
		add_rewrite_rule(
			'^' . addcslashes( self::$uri, '.' ) . '$',
			'index.php?sitemap=' . self::$key,
			'top'
		);
	}

	/**
	 * Set the sitemap renderer if the current query is for a news sitemap.
	 *
	 * @return void
	 */
	public static function set_news_renderer(): void {
		$type = sanitize_text_field( get_query_var( 'sitemap' ) ); // @phpstan-ignore argument.type
		if ( $type === self::$key ) {
			$wp_sitemaps           = wp_sitemaps_get_server();
			$wp_sitemaps->renderer = new Renderer();
		}
	}

	/**
	 * Adds the news sitemap to robots.txt.
	 *
	 * @param string $output    robots.txt output.
	 * @param bool   $is_public Whether the site is public.
	 * @return string The robots.txt output.
	 */
	public static function add_robots( $output, $is_public ) {
		if ( $is_public ) {
			$output .= "\nSitemap: " . esc_url( self::get_sitemap_url() ) . "\n";
		}

		return $output;
	}

	/**
	 * Builds the URL for the sitemap.
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress rewrite component.
	 *
	 * @return string The sitemap URL.
	 */
	public static function get_sitemap_url() {
		global $wp_rewrite;

		if ( $wp_rewrite instanceof \WP_Rewrite && ! $wp_rewrite->using_permalinks() ) {
			return home_url( '/?sitemap=' . self::$key );
		}

		return home_url( self::$uri );
	}
}
