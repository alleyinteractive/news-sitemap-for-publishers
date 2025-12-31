<?php
/**
 * News_Provider class
 *
 * @package wp-news-sitemap
 */

namespace Alley\WP\News_Sitemap;

use WP_Query;
use WP_Sitemaps_Provider;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * News class.
 */
class Provider extends WP_Sitemaps_Provider {
	/**
	 * Gets a URL list for a sitemap.
	 *
	 * @param int    $page_num       Page of results. Ignored.
	 * @param string $object_subtype Optional. Object subtype name. Default empty. Ignored.
	 * @return array{loc: string, news: array{publication_date: string, title: string}}[]
	 */
	public function get_url_list( $page_num, $object_subtype = '' ) {
		$urls         = [];
		$latest_posts = new WP_Query( [
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'date_query'             => [
				[
					'after'     => '-2 days',
					'inclusive' => true,
				],
			],
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		] );

		while ( $latest_posts->have_posts() ) {
			$latest_posts->the_post();
			$urls[] = [
				'loc'  => (string) get_permalink(),
				'news' => [
					'publication_date' => (string) get_the_date( DATE_ATOM ),
					'title'            => get_the_title(),
				],
			];
		}

		// If there are no recent articles, include the homepage to avoid a 404. This gets removed later.
		if ( empty( $urls ) ) {
			$urls[] = [
				'loc'  => '/',
				'news' => [
					'publication_date' => gmdate( DATE_ATOM ),
					'title'            => get_bloginfo( 'name' ),
				],
			];
		}

		return $urls;
	}

	/**
	 * Gets the max number of pages available for the sitemap, which in this case is always 1.
	 *
	 * @param string $object_subtype Optional. Object subtype. Default empty.
	 * @return int Total number of pages.
	 */
	public function get_max_num_pages( $object_subtype = '' ) {
		return 1;
	}

	/**
	 * Retrieves the full URL for a sitemap.
	 *
	 * In the case of the news sitemap, there is only one sitemap, so the name and page parameters are ignored.
	 *
	 * @param string $name The sitemap name.
	 * @param int    $page The page of the sitemap.
	 * @return string The sitemap URL.
	 */
	public function get_sitemap_url( $name, $page ) {
		return Main::get_sitemap_url();
	}
}
