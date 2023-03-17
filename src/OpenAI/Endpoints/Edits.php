<?php
/**
 * Manages the OpenAI Api Endpoint for Edits.
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
 * Class Edits
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */
class Edits extends Abstract_Endpoint {

	/*
	 * @inheritdoc
	 */
	public static string $endpoint_path = 'edits';

	/*
	 * @inheritdoc
	 */
	protected array $required_args = [
		'instruction',
		'model',
	];

	/*
	 * @inheritdoc
	 */
	protected array $default_args = [
		'input'             => 'Al ball fll of the wal.',
		"instruction"       => "Fix the spelling mistakes",
		'model'             => 'text-davinci-edit-001',
	];

	/**
	 * Create an edit.
	 **
	 * $response = $openai->edits()->create( [] );
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
}
