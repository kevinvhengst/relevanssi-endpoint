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

		private $post_types;

		public function __construct() {
			register_activation_hook( __FILE__, [$this, 'install'] );
			add_action( 'rest_api_init', [$this, 'register_routes'] );
			$this->post_types = $this->get_post_types();
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
			$parameters = $request->get_query_params( $request );
			$args = array();

			if ( isset( $parameters['page'] ) ) {
				$page = (int) $parameters['page'];
				$args['paged'] = (int) $parameters['page'];
				unset( $parameters['page'] );
			} 
	
			if ( isset( $parameters['per_page'] ) ) {
				$per_page = (int) $parameters['per_page'];
				$args['posts_per_page'] = (int) $parameters['per_page'];
				unset( $parameters['per_page'] );
			} 

			if ( isset( $parameters['keyword'] ) ) {
				$args['s'] = $parameters['keyword'];
				unset( $parameters['keyword'] );
			}

			if( isset( $parameters['type'] ) &&  in_array( $parameters['type'], $this->$post_types )){
				$args['post_type'] = $parameters['type'];
				unset( $parameters['type'] );
			}

			foreach( $parameters as $parameter => $value) {
				$args[$parameter] = $value;
			}

			$search_query = new WP_Query( $args );
			if(function_exists('relevanssi_do_query')) {
				relevanssi_do_query($search_query);
			}

			$posts = array();

			while( $search_query->have_posts() ) {
				$search_query->the_post();
			}

			return $search_query;
		}

		public function install() {
			if ( ! is_plugin_active( 'relevanssi/relevanssi.php' ) and current_user_can( 'activate_plugins' ) ) {
				wp_die('Relevanssi is required to activate this plugin. <br><a href="' . admin_url( 'plugins.php' ) . '"> Return to Plugin overview</a>');
			}
		}

		public function get_post_types() {
			$post_types = array();
			$get = get_post_types( array( 'public' => true) , 'objects' );
		 
			foreach( $get as $post_type=>$value ) {
				$post_types[] = $post_type; 
			}

			return $post_types;
		}

	}

	$search = new Relevanssi_Endpoint();

}
