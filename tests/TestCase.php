<?php
/**
 * WP News Sitemap Tests: Base Test Class
 *
 * @package wp-news-sitemap
 */

namespace Alley\WP\News_Sitemap\Tests;

use Mantle\Testing\Concerns\Prevent_Remote_Requests;
use Mantle\Testkit\Test_Case as TestkitTest_Case;

/**
 * WP News Sitemap Base Test Case
 */
abstract class TestCase extends TestkitTest_Case {
	use Prevent_Remote_Requests;
}
