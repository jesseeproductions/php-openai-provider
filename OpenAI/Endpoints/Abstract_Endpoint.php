<?php
/**
 * Manages the OpenAI Api Endpoint for Models.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */

namespace Pngx\OpenAI\Endpoints;

use GuzzleHttp\Client as GuzzleClient;
use Pngx\OpenAI\Http\Url;

/**
 * Class Models
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */
class Abstract_Endpoint {

	/**
	 * The endpoint url path.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static string $endpoint_path = '';

	/**
	 * The OpenAI Api endpoint url.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected static string $endpoint_url = '';

	/**
	 * An array of required parameters for an Api request.
	 *
	 * @since 0.1.0
	 *
	 * @var array<string>
	 */
	protected array $required_args = [];

	/**
	 * An array of default parameters for an Api request.
	 *
	 * @since 0.1.0
	 *
	 * @var array<string|mixed>
	 */
	protected array $default_args = [];

	/**
	 * An instance of the Guzzle Http Client.
	 *
	 * @since 0.1.0
	 *
	 * @var GuzzleClient
	 */
	protected $client;

	/**
	 * An instance of the Url handler.
	 *
	 * @since 0.1.0
	 *
	 * @var Url
	 */
	protected $url;

	/**
	 * Endpoint constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param GuzzleClient $client Guzzle Http client or utilize another library.
	 * @param Url          $url    An instance of the url handler.
	 */
	public function __construct( $client, Url $url ) {
		$this->client         = $client;
		$this->url            = $url;
		static::$endpoint_url = $this->url->get_endpoint_url( static::$endpoint_path );
	}


	/**
	 * Parse arguments with default arguments into an array.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|mixed> $args Arguments for the request.
	 *
	 * @return array<string|mixed> Parsed arguments for the request.
	 */
	function parse_args( array $args ): array {
		// If no default arguments, return arguments.
		if ( empty( $this->default_args ) || ! is_array( $this->default_args ) ) {
			return $args;
		}

		$parsed_args = $this->default_args;
		foreach ( $args as $key => $value ) {
			if ( isset( $this->default_args[ $key ] ) ) {
				$parsed_args[ $key ] = $value;
			}
		}

		return $parsed_args;
	}

	/**
	 * Check if the required parameters are set with values.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|mixed> $args Arguments for the request.
	 *
	 * @return bool
	 */
	protected function check_required_keys( $args ): bool {
		// If no required arguments, return true.
		if ( empty( $this->required_args ) || ! is_array( $this->required_args ) ) {
			return true;
		}

		foreach ( $this->required_args as $key ) {
			if ( ! array_key_exists( $key, $args ) || $args[ $key ] === null ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Format the arguments when including an image.
	 *
	 * @since   0.1.0
	 *
	 * @param array<string|mixed> $args Arguments for the request.
	 *
	 * @return array<string|mixed> An array of arguments formatted to use the multipart.
	 */
	protected function format_args_for_image_multipart( $args ) {
		$formatted = [];
		foreach ( $args as $key => $value ) {
			if ( $key === 'image' ) {
				$formatted[] = [
					'name'     => $key,
					'contents' => fopen( $value, 'r' ),
                    'filename' => basename( $value ),
				];
				continue;
			}

			$formatted[] = [
				'name'     => $key,
				'contents' => $value,
			];
		}

		return $formatted;
	}
}