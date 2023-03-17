<?php
/**
 * Class to manage Api Exceptions.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Http
 */

namespace Pngx\OpenAI\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

/**
 * Class Api_Exception
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Exceptions
 */
class Api_Exception extends Exception {

	/**
	 * The response from the client.
	 *
	 * @since 0.1.0
	 *
	 * @var null|Response
	 */
	protected $response;

	/**
	 * Get the response.
	 *
	 * @since 0.1.0
	 *
	 * @return Response
	 */
	public function get_response() {
		return $this->response;
	}

	/**
	 * Create Api Exception from supplied exception from client. ( default is Guzzle )
	 *
	 * @since 0.1.0
	 *
	 * @param RequestException $guzzle_exception
	 *
	 * @return static
	 */
	public static function create( RequestException $guzzle_exception ): self {
		$e = new static( static::sanitize_response_message( $guzzle_exception->getMessage() ), $guzzle_exception->getCode(), $guzzle_exception );

		$e->response = $guzzle_exception->getResponse();

		return $e;
	}

	/**
	 * Sanitize response message to remove sensitive info.
	 *
	 * @since 0.1.0
	 *
	 * @param string $message The message to remove sensitive information.
	 *
	 * @return string The sanitized string.
	 */
	protected static function sanitize_response_message( string $message ): string {
		return preg_replace( '/(api_key|access_token)=[a-z0-9-]+/i', '$1=***', $message );
	}
}