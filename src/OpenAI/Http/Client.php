<?php
/**
 * Class to manage OpenAI Api Http.
 *
 * Utilizes coding SevenShores\Hubspot\Http\Response as the base.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Http
 */

namespace Pngx\OpenAI\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use InvalidArgumentException;
use Pngx\OpenAI\Exceptions\Api_Exception;
use Pngx\OpenAI\Exceptions\Bad_Request;
use Pngx\OpenAI\Http\Api_Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Http
 */
class Client {

	/**
	 * The OpenAI Api key.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $api_key;

	/**
	 * An instance of the Guzzle Http Client.
	 *
	 * @since 0.1.0
	 *
	 * @var GuzzleClient
	 */
	public $client;

	/**
	 * Default options for Guzzle request method.
	 *
	 * @since 0.1.0
	 *
	 * @var array<string|mixed>
	 */
	protected $client_options = [];

	/**
	 * The OpenAI organization id.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $organization_id;

	/**
	 * Whether to return Response object if true or Guzzle's if false.
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $wrap_response = true;

	/**
	 * Client constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string>       $config         An array of config options.
	 * @param GuzzleClient        $client         Guzzle Http client or utilize another library.
	 * @param array<string|mixed> $client_options An array of default options passed to the client on each request.
	 * @param bool                $wrap_response  Whether to return Response object if true or Guzzle's if false.
	 */
	public function __construct(
		array $config = [],
		$client = null,
		array $client_options = [],
		bool $wrap_response = true
	) {
		$this->client_options = $client_options;
		$this->wrap_response  = $wrap_response;

		$this->api_key = isset( $config['api_key'] ) ? $config['api_key'] : getenv( 'OPENAI_Api_SECRET' );
		if ( empty( $this->api_key ) ) {
			throw new InvalidArgumentException( 'You must provide a OpenAI api key.' );
		}

		if ( isset( $config['organization_id'] ) ) {
			$this->organization_id = $config['organization_id'];
		}

		if ( is_null( $client ) ) {
			$client = new GuzzleClient();
		}

		$this->client = $client;
	}

	/**
	 * Request from Api Endpoint.
	 *
	 * @since 0.1.0
	 *
	 * @param string              $method       The HTTP request type.
	 * @param string              $endpoint     The Open AI Api endpoint.
	 * @param array<string|mixed> $args         An array of options to modify the default options on each request.
	 * @param string              $query_string Query string to send with request. //@todo remove this?
	 *
	 * @return ResponseInterface|Api_Response An Api_Response or Client response from Api.
	 *
	 * @throws Api_Exception
	 */

	/**
	 * Makes a request to an Api.
	 *
	 * @since 0.1.0
	 *
	 * @param string $method      The HTTP request type.
	 * @param string $url         The URL to make the request to.
	 * @param array  $args        An array of arguments for the request. Should include 'method' (POST/GET/PATCH, etc).
	 *
	 * @return Api_Response An Api response to act upon the response result.
	 *
	 * @throws Api_Exception|Bad_Request
	 */
	protected function request( $method, $url, $args ) {
		$args['headers']['Authorization'] = 'Bearer ' . $this->api_key;
		if ( ! empty( $this->organization_id ) ) {
			$args['headers'][] = 'OpenAI-Organization: ' . $this->organization_id;
		}

		try {
			if ( $this->wrap_response === false ) {
				return $this->client->request( $method, $url, $args );
			}

			return new Api_Response( $this->client->request( $method, $url, $args ) );
		} catch ( ServerException $e ) {
			if ( $this->wrap_response === false ) {
				throw $e;
			}

			throw Api_Exception::create($e);
		} catch ( ClientException $e ) {
			if ( $this->wrap_response === false ) {
				throw $e;
			}

			throw Bad_Request::create( $e );
		}
	}

	/**
	 * Makes a POST request to an Api.
	 *
	 * @since 0.1.0
	 *
	 * @param string        $url         The URL to make the request to.
	 * @param array<string> $args        An array of arguments for the request.
	 * @param string        $method      Method for the request.
	 *
	 * @return Api_Response An Api response to act upon the response result.
	 */
	public function post( $url, array $args, $method = 'POST' ) {
		return $this->request( $method, $url, $args );
	}

	/**
	 * Makes a GET request to an Api.
	 *
	 * @since 0.1.0
	 *
	 * @param string        $url         The URL to make the request to.
	 * @param array<string> $args        An array of arguments for the request.
	 * @param string        $method      Method for the request.
	 *
	 * @return Api_Response An Api response to act upon the response result.
	 */
	public function get( $url, array $args, $method = 'GET' ) {
		return $this->request( $method, $url, $args );
	}

	/**
	 * Makes a DELETE request to an Api.
	 *
	 * @since 0.1.0
	 *
	 * @param string        $url         The URL to make the request to.
	 * @param array<string> $args        An array of arguments for the request.
	 * @param string        $method      Method for the request.
	 *
	 * @return Api_Response An Api response to act upon the response result.
	 */
	public function delete( $url, array $args, $method = 'delete' ) {
		return $this->request( $method, $url, $args );
	}
}