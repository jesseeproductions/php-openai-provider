<?php
/**
 * Class to manage OpenAI Api.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI
 */

namespace Pngx\OpenAI;

use GuzzleHttp\Client as GuzzleClient;
use Pngx\OpenAI\Endpoints\Abstract_Endpoint;
use Pngx\OpenAI\Http\Client;
use Pngx\OpenAI\Http\Url;

/**
 * Class Provider
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI
 */
class Provider {

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
	 * Provider constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|mixed> $config         An array of configuration parameters.
	 * @param Client              $client         Guzzle Http client or utilize another library.
	 * @param Url                 $url            An instance of the Url handler.
	 * @param array<string|mixed> $client_options An array of default options passed to the client on each request.
	 * @param bool                $wrap_response  Whether to return Response object if true or Guzzle's if false.
	 */
	public function __construct(
		array $config = [],
		Client $client = null,
		Url $url = null,
		array $client_options = [],
		bool $wrap_response = true
	) {
		if ( is_null( $client ) ) {
			$client = new Client( $config, null, $client_options, $wrap_response );
		}
		$this->client = $client;

		if ( is_null( $url ) ) {
			$url = new Url();
		}
		$this->url    = $url;
	}

	/**
	 * Return an instance of an Endpoint based on the method called.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $args
	 */
	public function __call( string $name, $args ): Abstract_Endpoint {
		$endpoint = 'Pngx\\OpenAI\\Endpoints\\' . ucfirst( $name );

		return new $endpoint( $this->client, $this->url, ...$args );
	}

	/**
	 * Create an instance of the service with an Api key.
	 *
	 * @since 0.1.0
	 *
	 * @param string              $api_key         OpenAI Api key.
	 * @param string              $organization_id Organization key.
	 * @param Client              $client          Guzzle Http client or utilize another library.
	 * @param Url                 $url             An instance of the Url handler.
	 * @param array<string|mixed> $client_options  An array of default options passed to the client on each request.
	 * @param bool                $wrap_response   Whether to return Response object if true or Guzzle's if false.
	 *
	 * @return static
	 */
	public static function create(
		string $api_key = null,
		string $organization_id = null,
		Client $client = null,
		Url $url = null,
		array $client_options = [],
		bool $wrap_response = true
	 ): self {
		return new static(
			[ 'api_key' => $api_key, 'organization_id' => $organization_id ],
			$client,
			$url,
			$client_options,
			$wrap_response
		);
	}
}
