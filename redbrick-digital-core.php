<?php
	/**
	 * Redbrick Digital Core
	 *
	 * @package     RBD Core
	 * @author      RedbrickDigital.net
	 * @copyright   2016 RedbrickDigital.net
	 * @license     GPL-2.0+
	 *
	 * @wordpress-plugin
	 * Plugin Name: RBD Core
	 * Description: This plugin enables Redbrick Digital Core usage, including the Review Engine Display shortcode, Review Slider widget, and Social Proof widget.
	 * Version:     0.8.3
	 * Author:      RedbrickDigital.net
	 * Text Domain: rbd-core
	 * License:     GPL-2.0+
	 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
	 */

	// Load Core CSS
	add_action( 'wp_enqueue_scripts', 'rbd_core_load_css' );
	function rbd_core_load_css(){
		wp_register_style( 'rbd-core', plugins_url( '/assets/css/core.css', __FILE__ ) );
		wp_register_style( 'rbd-cutestrap-min', plugins_url( '/assets/css/cutestrap.min.css', __FILE__ ) );
		wp_enqueue_style( 'rbd-widgets' );
		wp_enqueue_style( 'rbd-core' );
		wp_enqueue_style( 'rbd-cutestrap-min' );
	}

	// Load Core JS
	add_action( 'wp_enqueue_scripts', 'rbd_core_load_js' );
	function rbd_core_load_js(){
		// Unslider
		wp_register_script( 'unslider-min', plugins_url( '/assets/js/unslider.min.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'unslider-min' );

		// Widgets JS
		wp_register_script( 'rbd-core-widgets', plugins_url( '/assets/js/widgets.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'rbd-core-widgets' );
	}

	// Load Admin CSS
	add_action( 'admin_enqueue_scripts', 'rbd_core_load_admin_css' );
	function rbd_core_load_admin_css(){
		wp_register_style( 'rbd-admin', plugins_url( '/assets/css/admin.css', __FILE__ ) );
		wp_enqueue_style( 'rbd-admin' );
		wp_register_style( 'rbd-rangeslider', plugins_url( '/assets/plugins/rangeslider/rangeslider.css', __FILE__ ) );
		wp_enqueue_style( 'rbd-rangeslider' );
	}

	// Load Admin JS
	add_action( 'admin_enqueue_scripts', 'rbd_core_conditional_add_media_js' );
	function rbd_core_conditional_add_media_js(){
		wp_enqueue_style( 'wp-color-picker' );
    	wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( 'rbd-rangeslider', plugins_url( '/assets/plugins/rangeslider/rangeslider.min.js', __FILE__ ), array('jquery'), '1.0', true );
		wp_enqueue_script( 'rbd-admin', plugins_url( '/assets/js/admin.js', __FILE__ ), array('jquery', 'rbd-rangeslider', 'wp-color-picker'), '1.0', true );
	}

	// Load Admin Pages
	add_action( 'after_setup_theme', 'rbd_core_load_admin_pages' );
	function rbd_core_load_admin_pages(){
		$admin_pages = array( 'core' );
		foreach($admin_pages as $admin_page){
			include_once( plugin_dir_path( __FILE__ ) . 'admin/admin-'. $admin_page .'.php' );
		}
	}

	// Load Widgets
	add_action( 'after_setup_theme', 'rbd_core_load_widgets' );
	function rbd_core_load_widgets(){
		$review_engine_url = get_option( 'rbd_core_review_engine_url' );

		if( !empty( $review_engine_url ) ){
			// Review Slider Widget
			include( plugin_dir_path( __FILE__ ) . 'lib/widgets/widget-review-slider.php' );

			// Reviews At a Glance (RAG)
			include( plugin_dir_path( __FILE__ ) . 'lib/widgets/widget-social-proof.php' );
		}
	}

	// Load Shortcodes
	add_action( 'after_setup_theme', 'rbd_core_load_shortcodes' );
	function rbd_core_load_shortcodes(){
		// Review Engine Display [rbd_review_engine]
		include( plugin_dir_path( __FILE__ ) . 'lib/shortcodes/review-engine-display.php' );
	}

	//Global Functions
	function rbd_core_api_call(){
		$api_string			= '/reviews-api-v2/?query_v2=true&user=thirdriverdev&key=56677c860f0f351a0a1b726b74f2f215&reviews_per_page=1';
		$api_url			= 'http://'. str_replace( 'http://', '', get_option('rbd_core_review_engine_url') ) . $api_string;

		if ( false === ( $rbd_core_api_call = get_transient( 'rbd_core_api_call' ) ) ) {
			$rbd_core_api_call = @file_get_contents( $api_url );
			set_transient( 'rbd_core_api_call', $rbd_core_api_call, 86400 );
		}

		$data_object = json_decode( get_transient( 'rbd_core_api_call' ) );

		return $data_object;
	}
?>
