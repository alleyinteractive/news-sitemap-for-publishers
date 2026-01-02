<?php
/**
 * News Sitemap for Publishers Tests: Base Test Class
 *
 * @package news-sitemap-for-publishers
 */

namespace Alley\WP\News_Sitemap_For_Publishers\Tests;

use Mantle\Testing\Concerns\Prevent_Remote_Requests;
use Mantle\Testkit\Test_Case as TestkitTest_Case;

/**
 * News Sitemap for Publishers Base Test Case
 */
abstract class TestCase extends TestkitTest_Case {
	use Prevent_Remote_Requests;
}
