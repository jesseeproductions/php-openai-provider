<?php
/**
 * Manages the OpenAI Api Endpoint for Completions.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */

namespace Pngx\OpenAI\Endpoints;

use GuzzleHttp\RequestOptions;
use Pngx\OpenAI\Endpoints\Abstract_Endpoint;
use Pngx\OpenAI\Exceptions\Bad_Request;

/**
 * Class Completions
 *
 * https://platform.openai.com/docs/api-reference/completions/create
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */
class Completions extends Abstract_Endpoint {

	/*
	 * @inheritdoc
	 */
	public static string $endpoint_path = 'completions';

	/*
	 * @inheritdoc
	 */
	protected array $required_args = [
		'model',
	];

	/*
	 * @inheritdoc
	 */
	protected array $default_args = [
		'prompt'            => 'Three fish in a tree, how can that be?',
		'model'             => 'text-davinci-003',
		'max_tokens'        => 25,
		'temperature'       => 0.8,
		'frequency_penalty' => 0,
		'presence_penalty'  => 0.2,
	];

	/**
	 * Create a completion.
	 **
	 * $response = $openai->completions()->create( [ 'prompt' => 'No Pat no don't sit on that.' ] );
	 * $result = $response->result;
	 *
	 * @since   0.1.0
	 *
	 * @param array<string|mixed> $args Arguments for the request.
	 *
	 * @return \OpenAI\Http\Api_Response
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \OpenAI\Exceptions\Bad_Request
	 */
	public function create( array $args ) {
		$args     = $this->parse_args( $args );
		if ( ! $this->check_required_keys( $args ) ) {
			throw new Bad_Request( 'Missing required keys, check your parameters include: ' . implode( ', ', $this->required_args ), 400 );
		}

	     return $this->client->post(
		     static::$endpoint_url,
	         [ RequestOptions::JSON => $args ]
	     );
    }

	/**
	 * Create a streaming completion.
	 **
	 * $response = $openai->completions()->create_stream( [], $stream );
	 * $result = $response->result;
	 *
	 * @since   0.1.0
	 *
	 * @param array<string|mixed> $args   Arguments for the request.
	 * @param mixed               $stream A stream function for server-sent events.
	 *
	 * @return \OpenAI\Http\Api_Response
	 */
	public function create_stream( array $args, $stream = null ) {
		$args     = $this->parse_args( $args );
		if ( ! $this->check_required_keys( $args ) ) {
			throw new Bad_Request( 'Missing required keys, check your parameters include: ' . implode( ', ', $this->required_args ), 400 );
		}

		if ( $stream === null ) {
			throw new Bad_Request( 'Missing a stream function. Provide a compatible function for server-sent events: https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events' );
		}

		// Set stream to true, no override with this endpoint.
		$args['stream'] = true;

	     return $this->client->post(
		     static::$endpoint_url,
	         [
	            RequestOptions::JSON => $args,
	            'curl' => [
		            CURLOPT_WRITEFUNCTION => $stream
	            ]
	         ]
	     );
    }
}
