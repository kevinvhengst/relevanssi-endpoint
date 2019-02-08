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

			public function register_routes() {
				$version 		= '1';
				$namespace 	= 'relevanssi/v';
				$base				= 'route';

				register_rest_route( $namespace, '/' . $base, array( 
					'methods'							=> WP_REST_Server::READABLE,
					'callback' 						=> array( $this, 'get_search_results' ),
					'permission_callback' => array( $this, 'get_search_results_permissions_check' ),
					'args' 								=> array()
				) );
			}

			public function get_search_results( $request ) {
				return $request;
			}

			public function get_search_results_permissions_check( $request ) {
				return true;
			}
	}

	$search = new Relevanssi_Endpoint();

}
