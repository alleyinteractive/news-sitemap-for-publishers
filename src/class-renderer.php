<?php
/**
 * News_Sitemap_Renderer class
 *
 * @package wp-news-sitemap
 */

namespace Alley\WP\News_Sitemap;

use SimpleXMLElement;
use WP_Sitemaps_Renderer;

use function do_action;
use function esc_url;
use function esc_xml;
use function get_bloginfo;

/**
 * News_Sitemap_Renderer class.
 */
class Renderer extends WP_Sitemaps_Renderer {

	/**
	 * Adds publication information to a news sitemap entry.
	 *
	 * @param SimpleXMLElement $xml The XML element to which the publication info will be added.
	 * @return SimpleXMLElement
	 */
	protected function add_publication( SimpleXMLElement $xml ): SimpleXMLElement {
		$language = get_bloginfo( 'language' );
		$xml_lang = strstr( $language, '-', true );

		$publication = $xml->addChild( 'news:publication', null, 'news' );
		$publication->addChild( 'news:name', esc_xml( get_bloginfo( 'name' ) ), 'news' );
		$publication->addChild( 'news:language', esc_xml( $xml_lang ?: $language ), 'news' );
		return $xml;
	}

	/**
	 * Gets XML for a sitemap.
	 *
	 * @param array{loc: string, news: ?array{publication_date: ?string, title: ?string}}[] $url_list Array of URLs for a sitemap.
	 * @return string|false A well-formed XML string for a sitemap index. False on error.
	 */
	public function get_sitemap_xml( $url_list ) {
		$urlset = new SimpleXMLElement(
			sprintf(
				'%1$s%2$s%3$s',
				'<?xml version="1.0" encoding="UTF-8" ?>',
				$this->stylesheet,
				'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" />'
			)
		);

		foreach ( $url_list as $url_item ) {
			// We might have added the homepage as a fallback, if so, skip this entry.
			if ( '/' === $url_item['loc'] ) {
				continue;
			}

			$url = $urlset->addChild( 'url' );

			// Add each element as a child node to the <url> entry.
			foreach ( $url_item as $name => $value ) {
				if ( 'loc' === $name ) {
					$url->addChild( $name, esc_url( $value ) );
				} elseif ( 'news' === $name ) {
					$news = $url->addChild( 'news:news', null, 'news' );
					$this->add_publication( $news );
					foreach ( [ 'publication_date', 'title' ] as $key ) {
						if ( isset( $value[ $key ] ) ) {
							$news->addChild( "news:{$key}", esc_xml( $value[ $key ] ), 'news' );
						}
					}
				}
			}
		}

		$xml = $urlset->asXML();
		return $xml ? str_replace( ' xmlns:news="news"', '', $xml ) : false;
	}

	/**
	 * Renders a sitemap.
	 *
	 * @param array{loc: string, news: array{publication_date: string, title: string}}[] $url_list Array of URLs for a sitemap.
	 */
	public function render_sitemap( $url_list ): void {
		/**
		 * Action hook fired before rendering a news sitemap.
		 *
		 * @param array    $url_list Array of URLs for the sitemap.
		 * @param Renderer $renderer The current instance of the Renderer.
		 */
		do_action( 'wp_news_sitemaps_before_render_sitemap', $url_list, $this );

		parent::render_sitemap( $url_list );

		/**
		 * Action hook fired after rendering a news sitemap.
		 *
		 * @param array    $url_list Array of URLs for the sitemap.
		 * @param Renderer $renderer The current instance of the Renderer.
		 */
		do_action( 'wp_news_sitemaps_after_render_sitemap', $url_list, $this );
	}
}
