<?php
/**
 * News Sitemap Test
 *
 * @package news-sitemap-for-publishers
 */

namespace Alley\WP\News_Sitemap_For_Publishers\Tests\Feature;

use Alley\WP\News_Sitemap_For_Publishers\Main;
use Alley\WP\News_Sitemap_For_Publishers\Tests\TestCase;
use Alley\WP\News_Sitemap_For_Publishers\Tests\Util\ExitException;
use Mantle\Database\Model\Post;

/**
 * Feature tests for the news sitemap output.
 */
class NewsSitemapTest extends TestCase {
	/**
	 * Set up the test environment.
	 *
	 * @throws ExitException Prevent exit calls during sitemap rendering.
	 */
	protected function setUp(): void {
		global $wp_sitemaps, $wp_sitemaps_backup;

		parent::setUp();

		// Load the global from backup, re-register rewrites (see
		// https://github.com/alleyinteractive/mantle-framework/issues/854), and re-initialize this plugin.
		// See bootstrap.php for the other half of this backup/restore.
		$wp_sitemaps = $wp_sitemaps_backup; // phpcs:ignore WordPress.NamingConventions
		$wp_sitemaps->register_rewrites();
		Main::sitemaps_init( $wp_sitemaps );

		// Prevent actual exit calls from core's sitemap rendering during tests.
		add_action( 'news_sitemap_for_publishers_after_render_sitemap', fn () => throw new ExitException() );
	}

	/**
	 * Get the response body of the news sitemap.
	 *
	 * Because core's sitemap rendering calls exit() after outputting the sitemap,
	 * we throw an exception to interrupt that flow. This is a bit of a hack, but it
	 * allows us to run the request as end-to-end and normal as possible.
	 *
	 * @return false|string
	 */
	protected function get_sitemap_response() {
		ob_start();
		try {
			$this->get( Main::$uri );
		} catch ( ExitException $e ) {
			// Flush Mantle's output buffer, which was interrupted by the exception.
			ob_end_flush();
		}
		return ob_get_clean();
	}

	/**
	 * Ensure that posts published within the last 48 hours appear in the news sitemap
	 * while posts older than 48 hours do not.
	 */
	public function test_sitemap_includes_recent_posts_and_excludes_old_posts() {
		$recent_post_1 = Post::factory()->create(
			[ 'post_date' => gmdate( 'Y-m-d H:i:s', time() - DAY_IN_SECONDS ) ]
		);
		$recent_post_2 = Post::factory()->create(
			[ 'post_date' => gmdate( 'Y-m-d H:i:s', time() - ( 47 * HOUR_IN_SECONDS ) ) ]
		);
		$old_post      = Post::factory()->create(
			[ 'post_date' => gmdate( 'Y-m-d H:i:s', time() - ( 4 * DAY_IN_SECONDS ) ) ]
		);

		$body = $this->get_sitemap_response();
		$this->assertStringContainsString( get_permalink( $recent_post_1 ), $body );
		$this->assertStringContainsString( get_permalink( $recent_post_2 ), $body );
		$this->assertStringNotContainsString( get_permalink( $old_post ), $body );
	}

	/**
	 * If there are no posts in the last 48 hours, the sitemap should still load
	 * and the urlset should be empty.
	 */
	public function test_sitemap_loads_with_empty_urlset_when_no_recent_posts(): void {
		Post::factory()->create(
			[ 'post_date' => gmdate( 'Y-m-d H:i:s', time() - ( 72 * HOUR_IN_SECONDS ) ) ]
		);

		$body = $this->get_sitemap_response();

		// Ensure the urlset exists, but no <url> entries are present.
		$this->assertStringContainsString( '<urlset', $body );
		$this->assertStringNotContainsString( '<url>', $body );
	}
}
