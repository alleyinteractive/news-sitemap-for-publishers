<?php
/**
 * News Sitemap for Publishers Tests: Robots.txt Feature Test
 *
 * @package news-sitemap-for-publishers
 */

namespace Alley\WP\News_Sitemap_For_Publishers\Tests\Feature;

use Alley\WP\News_Sitemap_For_Publishers\Main;
use Alley\WP\News_Sitemap_For_Publishers\Tests\TestCase;

/**
 * Tests that robots.txt exposes the news sitemap URL.
 */
class RobotsTxtTest extends TestCase {
	/**
	 * Test that the robots.txt includes the news sitemap URL.
	 */
	public function test_robots_includes_news_sitemap_url() {
		$this->get( '/robots.txt' )
			->assertStatus( 200 )
			->assertSee( 'Sitemap: ' . home_url( Main::$uri ), 1 );
	}
}
