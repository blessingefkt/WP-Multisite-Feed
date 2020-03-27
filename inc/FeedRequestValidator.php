<?php # -*- coding: utf-8 -*-

namespace Inpsyde\MultisiteFeed;

class FeedRequestValidator implements RequestValidator
{

	/**
	 * @var DataStorage
	 */
	private $settings;

	/**
	 * FeedRequestValidator constructor.
	 *
	 * @param DataStorage $settings
	 */
	public function __construct(DataStorage $settings)
	{
		$this->settings = $settings;
	}

	/**
	 * Check if a multifeed URL is requested
	 *
	 * @return bool
	 */
	public function validate()
	{
		$slug = $this->settings->get(OptionsKeys::URL_SLUG, OptionDefaults::URL_SLUG);
		$postId = get_the_ID();
		if ($postId > 0) {
			$multifeedValue = get_post_meta($postId, 'multifeed', true);
			return $multifeedValue == $slug;
		}
		return false;
	}
}