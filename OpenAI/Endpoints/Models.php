<?php
/**
 * Manages the OpenAI Api Endpoint for Models.
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */

namespace Pngx\OpenAI\Endpoints;

/**
 * Class Models
 *
 * https://platform.openai.com/docs/api-reference/models
 *
 * @since   0.1.0
 *
 * @package Pngx\AI\OpenAI\Endpoints
 */
class Models extends Abstract_Endpoint {

	/*
	 * @inheritdoc
	 */
	public static string $endpoint_path = 'models';

	/**
	 * List models.
	 *
	 * $response = $openai->models()->all();
	 * foreach ( $response->data as $model ) {}
	 *
	 * @since   0.1.0
	 *
	 * @return \Pngx\OpenAI\Http\Api_Response
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \Pngx\OpenAI\Exceptions\Bad_Request
	 */
	public function all() {
		return $this->client->get(
			static::$endpoint_url,
			[]
		);
	}

	/**
	 * Retrieve model.
	 *
	 * $response = $openai->models()->retrieve( 'gpt-3.5-turbo' );
	 * $model = $response->result;
	 * $model->id ~ 'gpt-3.5-turbo'
	 *
	 * @since   0.1.0
	 *
	 * @param string $model The name of the model.
	 *
	 * @return \Pngx\OpenAI\Http\Api_Response
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \Pngx\OpenAI\Exceptions\Bad_Request
	 */
	public function retrieve( string $model ) {
		 $endpoint = $this->url->get_endpoint_url( static::$endpoint_path, $model );

	     return $this->client->get(
		     $endpoint,
	         []
	     );
	 }

	/**
	 * Delete fine-tune model.
	 *
	 * Account must have Owner role in your organization.
	 *
	 * $response = $openai->models()->delete( 'curie:ft-acmeco-2021-03-03-21-44-20' );
	 * $model = $response->result;
	 * $model->id ~ 'curie:ft-acmeco-2021-03-03-21-44-20'
	 * $model->object ~ 'model'
	 * $model->deleted ~ true
	 *
	 * @since   0.1.0
	 *
	 * @param string $model The name of the model.
	 *
	 * @return \Pngx\OpenAI\Http\Api_Response
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \Pngx\OpenAI\Exceptions\Bad_Request
	 */
	public function delete( string $model ) {
		 $endpoint = $this->url->get_endpoint_url( static::$endpoint_path, $model );

	     return $this->client->get(
	         $endpoint,
	         []
	     );
	 }
}
