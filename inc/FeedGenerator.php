<?php # -*- coding: utf-8 -*-

namespace Inpsyde\MultisiteFeed;

use Inpsyde\MultisiteFeed\Cache\CacheHandler;

/**
 * Class FeedGenerator
 *
 * @package Inpsyde\MultisiteFeed
 */
class FeedGenerator {

	/**
	 * @var CacheHandler
	 */
	private $cache;
	/**
	 * @var DataStorage
	 */
	private $settings;
	/**
	 * @var Renderer
	 */
	private $renderer;
	/**
	 * @var FeedItemProvider
	 */
	private $item_provider;

	/**
	 * FeedGenerator constructor.
	 *
	 * @param DataStorage      $settings
	 * @param FeedItemProvider $item_provider
	 * @param Renderer         $renderer
	 * @param CacheHandler     $cache
	 */
	public function __construct(
		DataStorage $settings,
		FeedItemProvider $item_provider,
		Renderer $renderer,
		CacheHandler $cache
	) {

		$this->settings      = $settings;
		$this->item_provider = $item_provider;
		$this->cache         = $cache;
		$this->renderer      = $renderer;
	}

	/**
	 * Print out feed XML. Use cache if available.
	 *
	 * @return void
	 */
	public function display_feed() {

		$cache_key = $this->get_cache_key();
		$out       = false;

		// Deactivate Caching for Debugging
		if ( ! defined( 'WP_DEBUG' )
		     || defined( 'WP_DEBUG' ) && WP_DEBUG
		     || ( 0 !== $this->settings->get( OptionsKeys::CACHE_EXPIRY ) )
		) {
			$out = $this->cache->get( $cache_key );
		}

		if ( ! $out ) {
			$feed_items = $this->item_provider->get_items();
			$out        = $this->renderer->render( $feed_items );
			$this->cache->set( $cache_key, $out );
		}

		header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ),
			true );
		echo $out;
	}

	private function get_cache_key() {

		return 'inpsyde_multisite_feed_cache';
	}

}