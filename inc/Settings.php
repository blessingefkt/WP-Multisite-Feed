<?php # -*- coding: utf-8 -*-

namespace Inpsyde\MultisiteFeed;

class Settings implements DataStorage
{

	const OPTION_KEY = 'inpsyde_multisitefeed';

	/**
	 * Convenience wrapper to access plugin options.
	 *
	 * @param string $name    option name
	 * @param mixed  $default fallback value if option does not exist
	 *
	 * @return mixed
	 */
	public function get($name, $default = null)
	{

		$options = $this->getOptions();
		return isset($options[$name]) ? $options[$name] : $default;
	}

	public function set($key, $value)
	{
		$options = $this->getOptions();
		$options[$key] = $value;
		return $this->updateOptions($options);
	}

	public function merge(array $values)
	{
		$options = $this->getOptions();
		return $this->updateOptions(array_merge($options, $values));
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return get_network_option($this->getNetworkId(), self::OPTION_KEY) ?: [];
	}

	/**
	 * @return int
	 */
	public function getNetworkId()
	{
		return get_current_network_id();
	}

	/**
	 * @param array $options
	 * @return bool
	 */
	protected function updateOptions(array $options)
	{
		return update_network_option($this->getNetworkId(), self::OPTION_KEY, $options);
	}
}
