<?php
/**
 * Manages the OpenAI Api Endpoint for Images.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */

namespace Pngx\OpenAI\Endpoints;

use GuzzleHttp\RequestOptions;
use Pngx\OpenAI\Exceptions\Bad_Request;

/**
 * Class Edits
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */
class Images extends Abstract_Endpoint {

	/*
	 * @inheritdoc
	 */
	public static string $endpoint_path = 'images';

	/*
	 * @inheritdoc
	 */
	protected array $required_args = [
		'prompt',
	];

	/*
	 * @inheritdoc
	 */
	protected array $default_args = [
		'prompt'          => 'A cute baby sea monster',
		'n'               => 1,
		"size"            => "512x512",
		"response_format" => "url",
	];

	/*
	 * @inheritdoc
	 */
	protected array $required_edits_args = [
		'image',
		'prompt',
	];

	/*
	 * @inheritdoc
	 */
	protected array $default_edits_args = [
		'image'           => '',
		'prompt'          => 'A cute baby sea monster in a pool',
		'n'               => 1,
		"size"            => "512x512",
		"response_format" => "url",
	];

	/*
	 * @inheritdoc
	 */
	protected array $required_variations_args = [
		'image',
	];

	/*
	 * @inheritdoc
	 */
	protected array $default_variations_args = [
		'image'           => '',
		'n'               => 1,
		"size"            => "512x512",
		"response_format" => "url",
	];

	/**
	 * Create an image.
	 **
	 * $response = $openai->image()->create( [] );
	 * $result = $response->result;
	 * $url = $result->data[0]->url;
	 *
	 * @since   0.1.0
	 *
	 * @param array<string|mixed> $args Arguments for the request.
	 *
	 * @return \Pngx\OpenAI\Http\Api_Response
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \Pngx\OpenAI\Exceptions\Bad_Request
	 */
	public function create( array $args ) {
		$endpoint = $this->url->get_endpoint_url( static::$endpoint_path, 'generations' );

		$args     = $this->parse_args( $args );
		if ( ! $this->check_required_keys( $args ) ) {
			throw new Bad_Request( 'Missing required keys, check your parameters include: ' . implode( ', ', $this->required_args ), 400 );
		}

	     return $this->client->post(
		     $endpoint,
	         [ RequestOptions::JSON => $args ]
	     );
    }

	/**
	 * Edit an image.
	 *
	 * $response = $openai->image()->edits( 'image' => $image_path, 'prompt' => 'Add a cute monster.' );
	 * $result = $response->result;
	 * $url = $result->data[0]->url;
	 *
	 * Note Image must have transparent areas if no masked provided.
	 *
	 * @since   0.1.0
	 *
	 * @param array<string|mixed> $args Arguments for the request.
	 *
	 * @return \Pngx\OpenAI\Http\Api_Response
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \Pngx\OpenAI\Exceptions\Bad_Request
	 */
	public function edits( array $args ) {
		$this->required_args = $this->required_edits_args;
		$this->default_args  = $this->default_edits_args;
		$endpoint            = $this->url->get_endpoint_url( static::$endpoint_path, 'edits' );

		$args     = $this->parse_args( $args );
		if ( ! $this->check_required_keys( $args ) ) {
			throw new Bad_Request( 'Missing required keys, check your parameters include: ' . implode( ', ', $this->required_args ), 400 );
		}

		if ( ! file_exists( $args['image'] ) ) {
			throw new Bad_Request( 'Image file does not exist, please check the path: ' . $args['image'], 400 );
		}

		return $this->client->post(
		   $endpoint,
		   [
			   'multipart' => [
				   ...$this->format_args_for_image_multipart( $args ),
			   ],
			]
		  );
    }

	/**
	 * Create variations of images.
	 *
	 * $response = $openai->images()->edits( [ 'image' => $image_path ] );
	 * $result = $response->result;
	 * $url = $result->data[0]->url;
	 *
	 * Note Image must have transparent areas.
	 *
	 * @since   0.1.0
	 *
	 * @param array<string|mixed> $args Arguments for the request.
	 *
	 * @return \Pngx\OpenAI\Http\Api_Response
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \Pngx\OpenAI\Exceptions\Bad_Request
	 */
	public function variations( array $args ) {
		$this->required_args = $this->required_variations_args;
		$this->default_args  = $this->default_variations_args;
		$endpoint            = $this->url->get_endpoint_url( static::$endpoint_path, 'edits' );

		$args     = $this->parse_args( $args );
		if ( ! $this->check_required_keys( $args ) ) {
			throw new Bad_Request( 'Missing required keys, check your parameters include: ' . implode( ', ', $this->required_args ), 400 );
		}

		if ( ! file_exists( $args['image'] ) ) {
			throw new Bad_Request( 'Image file does not exist, please check the path: ' . $args['image'], 400 );
		}

		return $this->client->post(
		   $endpoint,
		   [
			   'multipart' => [
				   ...$this->format_args_for_image_multipart( $args ),
			   ],
			]
		  );
    }
}
