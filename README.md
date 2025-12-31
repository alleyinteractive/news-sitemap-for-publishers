# News Sitemap for Publishers

Contributors: mboynes, alleyinteractive

Tags: news, sitemap, seo, publishers, google

Stable tag: 1.0.1

Requires at least: 5.5

Tested up to: 6.9

Requires PHP: 8.2

License: GPL v2 or later

Extends WordPress's XML sitemaps feature to add a news sitemap for Google, Yahoo, Bing, et al.

## Description

Extends [WordPress's XML sitemaps feature](https://make.wordpress.org/core/2020/07/22/new-xml-sitemaps-functionality-in-wordpress-5-5/) to add a [news sitemap](https://developers.google.com/search/docs/crawling-indexing/sitemaps/news-sitemap). News sitemaps are used by Google and others to surface recent news articles by publishers.

## Installation

You can install the package via Composer:

```bash
composer require alleyinteractive/wp-news-sitemap
```

## Usage

Activate the plugin in WordPress and flush your rewrite rules (simply navigate to Settings → Permalinks in the WordPress admin to do so).

You can verify that the plugin is working by navigating to `/wp-news-sitemap.xml` on your site. You should also find a new sitemap entry in your site's robots.txt file for this URL.

## Development

To set up a WordPress installation and run the plugin in a local environment, you
can use `wp-env` via the `composer dev` command:

```sh
composer dev
```

The command will start a local WordPress environment with the plugin activated.

## Testing

[![Testing Suite](https://github.com/alleyinteractive/wp-news-sitemap/actions/workflows/all-pr-tests.yml/badge.svg?branch=develop)](https://github.com/alleyinteractive/wp-news-sitemap/actions/workflows/all-pr-tests.yml)

Run `composer test` to run tests against PHPUnit and the PHP code in the plugin.
Unit testing code is written in PSR-4 format and can be found in the `tests`
directory.

## Releasing the Plugin

The plugin uses
[action-release](https://github.com/alleyinteractive/action-release) via a
[built release workflow](./.github/workflows/built-release.yml) to compile and
tag releases. Whenever a new version is detected in the root plugin's headers in
the `wp-news-sitemap.php` file or in the `composer.json` file, the workflow will
automatically build the plugin and tag it with a new version. The built tag will
contain all the required front-end assets the plugin may require. This works
well for publishing to WordPress.org or for submodule-ing.

When you are ready to release a new version of the plugin, you can run
`composer release` to start the process of setting up a new
release. If you want to do this manually you can follow these steps:

1. Change the `Version` in the `wp-news-sitemap.php` file to a new higher-level version.

	```diff
	- * Version: 0.0.0
	+ * Version: 0.0.1
	```

2. Commit your changes and push to the repository.
3. Check the actions tab in the repository to see the progress of the release.
   The action will automatically create a new tag and release for the plugin.
   You are done!

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

This project is actively maintained by [Alley
Interactive](https://github.com/alleyinteractive). Like what you see? [Come work
with us](https://alley.com/careers/).

- [Matthew Boynes](https://github.com/mboynes)

## License

The GNU General Public License (GPL) license. Please see [License File](LICENSE) for more information.
