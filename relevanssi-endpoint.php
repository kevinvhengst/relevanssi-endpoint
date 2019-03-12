<?php
/**
 * Plugin Name: Relevanssi Endpoint
 * Description: Add a REST endpoint for Relevanssi search
 * Author: Kevin van Hengst
 * Author URI: https://github.com/kevinvhengst
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !class_exists( 'Relevanssi_Endpoint' ) ) {

	class Relevanssi_Endpoint extends WP_REST_Controller {

			public function __construct() {
				add_action('rest_api_init', [$this, 'register_routes']);
			}

			public function register_routes() {
				$version = '1';
				$namespace 	= 'relevanssi/v' . $version;
				$base				= 'search';

				register_rest_route( $namespace, '/' . $base, array( 
					'methods'							=> WP_REST_Server::READABLE,
					'callback' 						=> array( $this, 'get_search_results' ),
					'args' 								=> array()
				) );
			}

			public function get_search_results( WP_REST_Request $request ) {
				$parameters = $request->get_query_params($request);
				return $parameters;
			}

	}

	$search = new Relevanssi_Endpoint();

}
