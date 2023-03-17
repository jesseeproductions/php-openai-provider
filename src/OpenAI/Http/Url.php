<?php
/**
 * Manages the OpenAI Api URLs for the plugin.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Http
 */

namespace Pngx\OpenAI\Http;

/**
 * Class Url
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Http
 */
class Url {

	/**
	 * The OpenAI Api Url.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected static string $api_base_url = 'https://api.openai.com';

	/**
	 * The OpenAI Api version.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected static string $api_version = 'v1';

	/**
	 * Set the base URL used to access the OpenAI Api.
	 *
	 * @since 0.1.0
	 *
	 * @param string The new base url.
	 */
	public function set_api_base_url( string $url ) {
		static::$api_base_url = $url;
	}

	/**
	 * Set the Api version used to access the OpenAI Api.
	 *
	 * @since 0.1.0
	 *
	 * @param string The new Api version.
	 */
	public function set_api_version( string $version ) {
		static::$api_version = $version;
	}

	/**
	 * Returns the base URL used to access the OpenAI Api.
	 *
	 * @since 0.1.0
	 *
	 * @return string The URL to access the api.
	 */
	public function get_api_url(): string {
		return static::$api_base_url . '/' . static::$api_version . '/';
	}

	/**
	 * Returns an Endpoint URL used to access the OpenAI Api.
	 *
	 * @since 0.1.0
	 *
	 * @param string $endpoint The endpoint 'id' to add to use url.
	 *
	 * @return string The URL to access the provided endpoint.
	 */
	public function get_endpoint_url( string $endpoint, string $additional_path = '' ): string {
		if ( $additional_path ) {
			return static::get_api_url() . $endpoint . "/$additional_path";
		}

		return static::get_api_url() . $endpoint;
	}
}
