<?php
	/**
	 * Redbrick Digital Core
	 *
	 * @package     Redbrick Digital Core
	 * @author      RedbrickDigital.net
	 * @copyright   2016 RedbrickDigital.net
	 * @license     GPL-2.0+
	 * @link		https://plugins.svn.wordpress.org/redbrick-digital-core/
	 *
	 * @wordpress-plugin
	 * Plugin Name: Redbrick Digital Core
	 * Description: This plugin enables Redbrick Digital Core usage, including the Review Engine Display shortcode, Review Slider widget, and Social Proof widget.
	 * Version:     0.8.4
	 * Author:      RedbrickDigital.net
	 * Text Domain: rbd-core
	 * License:     GPL-2.0+
	 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
	*/

/// <Code Block A>
/// STANDARD FUNCTIONS

	/**
	 * Load Stylesheets
	 *
	 * @since 0.1
	*/
	add_action( 'wp_enqueue_scripts', 'rbd_core_load_css' );
	function rbd_core_load_css(){
		# Register Styles
		wp_register_style( 'rbd-core', plugins_url( '/assets/css/core.css', __FILE__ ) );
		wp_register_style( 'rbd-cutestrap-min', plugins_url( '/assets/css/cutestrap.min.css', __FILE__ ) );

		# Enqueue Styles
		wp_enqueue_style( 'rbd-widgets' );
		wp_enqueue_style( 'rbd-core' );
		wp_enqueue_style( 'rbd-cutestrap-min' );
	}

	/**
	 * Load Javascript
	 *
	 * @since 0.1
	*/
	add_action( 'wp_enqueue_scripts', 'rbd_core_load_js' );
	function rbd_core_load_js(){
		# Unslider Plugin
		wp_register_script( 'unslider-min', plugins_url( '/assets/js/unslider.min.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'unslider-min' );

		# Widget Javascript
		wp_register_script( 'rbd-core-widgets', plugins_url( '/assets/js/widgets.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'rbd-core-widgets' );
	}

	/**
	 * Load Admin-side Stylesheets
	 *
	 * @since 0.1
	*/
	add_action( 'admin_enqueue_scripts', 'rbd_core_load_admin_css' );
	function rbd_core_load_admin_css(){
		# Register Styles
		wp_register_style( 'rbd-admin', plugins_url( '/assets/css/admin.css', __FILE__ ) );
		wp_register_style( 'rbd-rangeslider', plugins_url( '/assets/plugins/rangeslider/rangeslider.css', __FILE__ ) );

		# Enqueue Styles
		wp_enqueue_style( 'rbd-admin' );
		wp_enqueue_style( 'rbd-rangeslider' );
	}

	/**
	 * Load Admin-side Javascript
	 *
	 * @since 0.1
	*/
	add_action( 'admin_enqueue_scripts', 'rbd_core_conditional_add_media_js' );
	function rbd_core_conditional_add_media_js(){
		# Enqueue Styles
		wp_enqueue_style( 'wp-color-picker' );

		# Enqueue Scripts
    	wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( 'rbd-rangeslider', plugins_url( '/assets/plugins/rangeslider/rangeslider.min.js', __FILE__ ), array('jquery'), '1.0', true );
		wp_enqueue_script( 'rbd-admin', plugins_url( '/assets/js/admin.js', __FILE__ ), array('jquery', 'rbd-rangeslider', 'wp-color-picker'), '1.0', true );
	}

	/**
	 * Initialize Admin Settings Pages
	 *
	 * @since 0.3.5
	 *
	 * @internal { Instead of running `plugin_dir_path` for each page, and
	 *	keeping track of them, we can just add any additional admin page slugs
	 *	to the `$admin_pages` array. These files must be in the `/admin/` folder
	 *	and named `admin-PAGENAME.php` to be included. }
	*/
	add_action( 'after_setup_theme', 'rbd_core_load_admin_pages' );
	function rbd_core_load_admin_pages(){
		# Define Admin Pages as Array
		$admin_pages = array(
			'core'
		);

		# Loop Through Array, Include Matched Pages
		foreach( $admin_pages as $admin_page ){
			include_once( plugin_dir_path( __FILE__ ) . 'admin/admin-'. $admin_page .'.php' );
		}
	}

	/**
	 * Initialize Widgets
	 *
	 * @since 0.2.1
	 *
	 * @internal { Each widget is loaded individually, and can be commented
	 *	out for testing. Similar to "Initialize Admin Settings Pages", we loop
	 *	through an array of widget slugs and load them from the `/widgets/`
	 *	folder. Files must be named `widget-NAME.php` to be included. We also
	 *	prevent loading until they put a Review Engine url in the Core Settings. }
	*/
	add_action( 'after_setup_theme', 'rbd_core_load_widgets' );
	function rbd_core_load_widgets(){
		# Define Review Engine URL Option as Variable
		$engine_url = get_option( 'rbd_core_review_engine_url' );

		# Define Widgets
		$widgets = array(
			'social-proof',
			'review-slider'
		);

		# If Review Engine URL Exists
		/**
		 * @TODO: Check if this is a real URL via an API calls
		*/
		if( !empty( $engine_url ) ){
			# Loop Widgets and Include if Matched
			foreach( $widgets as $widget ){
				include( plugin_dir_path( __FILE__ ) . 'lib/widgets/widget-'. $widget .'.php' );
			}
		}
	}

	/**
	 * Initialize Shortcodes
	 *
	 * @since 0.2.2
	 *
	 * @internal { Shortcodes are loaded exactly the same way as Widgets. The
	 *	only difference is in the file functionality. @see: Initialize Widgets }
	*/
	add_action( 'after_setup_theme', 'rbd_core_load_shortcodes' );
	function rbd_core_load_shortcodes(){
		# Define Review Engine URL Option as Variable
		$engine_url = get_option( 'rbd_core_review_engine_url' );

		# Define Shortcodes
		$shortcodes = array(
			'review-engine-display'
		);

		# If Review Engine URL Exists
		/**
		 * @TODO: Check if this is a real URL via an API calls
		*/
		if( !empty( $engine_url ) ){
			# Loop Shortcodes and Include if Matched
			foreach( $shortcodes as $shortcode ){
				include( plugin_dir_path( __FILE__ ) . 'lib/shortcodes/shortcode-'. $shortcode .'.php' );
			}
		}
	}

/// <Code Block B>
/// LARGE SCOPE FUNCTIONS

	/**
	 * Reconstruct Review Engine URL
	 *
	 * @since 0.8.3
	 *
	 * @internal { I have to `str_replace` the URL sooo freaking much. I turned
  	 *	it into it's own function so I don't have to double function each string. }
	 *
	 * @param Optional: Type. Indicates request for URL or API URL, otherwise
	 *	returns standard URL.
	 * @return A reconstructed URL so there's no fragment or missing protocol.
	*/
	function rbd_core_url( $rbd_core_url_args ){
		# Force http:// and get the URL
		$url		= get_option( 'rbd_core_review_engine_url' );
		$protocol	= 'http://';
		$_api_url	= '/reviews-api-v2/';
		$_api_ver	= '?query_v2=true';
		$_api_usr	= '&user=thirdriverdev';
		$_api_key	= '&key=56677c860f0f351a0a1b726b74f2f215';
		$_new_url	= str_replace( array( 'http://', 'https://' ), '', $url );

		# See If User Wants API URL or Not and return
		/**
		 * @internal { We don't currently use `https://`, so lets remove it if a
	 	 *	client adds it for some reason. }
		*/
		return ( $rbd_core_url_args == true ) ? $_new_url : $_new_url.$_api_url.$_api_ver.$_api_usr.$_api_key;
	}

	/**
	 * Get Review Engine Info via API Call
	 *
	 * @since 0.7.5
	 *
	 * @internal { Sometimes we need to load in options from the client's
	 *	Review Engine. Such as getting a list of Service Categories they have
	 *	so we can build a dropdown menu of them for selection. }
	 *
	 * @uses rbd_core_url() - Returns Review Engine URL w/ or w/o API string.
	 * @return Object from basic API Call
	 * @example @see `/lib/shortcodes/shortcode-review-engine-display.php`
	*/
	function rbd_core_api_call(){
		# Define Basic Light API String
		/**
		 * @TODO: Make NO reviews an Option
		*/
		$api_url = rbd_core_url( true ) .'&reviews_per_page=1';

		# Check for `rbd_core_api_call`, set and use if doesn't exist
		if ( false === ( $rbd_core_api_call = get_transient( 'rbd_core_api_call' ) ) ) {
			$rbd_core_api_call = @file_get_contents( $api_url );
			set_transient( 'rbd_core_api_call', $rbd_core_api_call, 86400 );
		}

		# Return JSON Decoded transient as an object
		return json_decode( get_transient( 'rbd_core_api_call' ) );
	}
?>
