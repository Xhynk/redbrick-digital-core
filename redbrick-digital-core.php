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
	 * Version:     0.9.1.2
	 * Author:      RedbrickDigital.net
	 * Text Domain: rbd-core
	 * License:     GPL-2.0+
	 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
	*/

	##        (\ /)
	##       ( . .) ♥ ~< Code Block - A: Standard Functions >
	##       c(”)(”)

	/**
	 * Load Stylesheets
	 *
	 * @since 0.1
	*/
	add_action( 'wp_enqueue_scripts', 'rbd_core_load_css' );
	function rbd_core_load_css(){
		wp_enqueue_style( 'rbd-core', plugins_url( '/assets/css/core.css', __FILE__ ) );
		wp_enqueue_style( 'rbd-cutestrap-min', plugins_url( '/assets/css/cutestrap.min.css', __FILE__ ) );
		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
	}

	/**
	 * Load Javascript
	 *
	 * @since 0.1
	*/
	add_action( 'wp_enqueue_scripts', 'rbd_core_load_js' );
	function rbd_core_load_js(){
		wp_enqueue_script( 'unslider-min', plugins_url( '/assets/js/unslider.min.js', __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'rbd-core-widgets', plugins_url( '/assets/js/widgets.js', __FILE__ ), array( 'jquery' ), '', true );
	}

	/**
	 * Load Admin-side Stylesheets
	 *
	 * @since 0.1
	*/
	add_action( 'admin_enqueue_scripts', 'rbd_core_load_admin_css' );
	function rbd_core_load_admin_css(){
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'rbd-admin', plugins_url( '/assets/css/admin.css', __FILE__ ) );
		wp_enqueue_style( 'rbd-rangeslider', plugins_url( '/assets/plugins/rangeslider/rangeslider.css', __FILE__ ) );
	}

	/**
	 * Load Admin-side Javascript
	 *
	 * @since 0.1
	*/
	add_action( 'admin_enqueue_scripts', 'rbd_core_conditional_add_media_js' );
	function rbd_core_conditional_add_media_js(){
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
		$admin_pages = array(
			'core'
		);

		foreach( $admin_pages as $admin_page ){
			include_once( plugin_dir_path( __FILE__ ) . 'admin/admin-core.php' );
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
		$widgets = array(
			'social-proof',
			'review-slider'
		);

		# If Review Engine URL Exists
		/**
		 * @TODO: Check if this is a real URL via an API calls
		*/
		if( get_option( 'rbd_core_review_engine_url' ) ){
			foreach( $widgets as $widget ){
				include( plugin_dir_path( __FILE__ ) . "lib/widgets/$widget/widget-$widget.php" );
			}
		}
	}

	/**
	 * Initialize Shortcodes
	 *
	 * @since 0.2.2
	 *
	 * @internal { Shortcodes are loaded exactly the same way as Widgets.
	 *	The only difference is in the file functionality. }
	 * @see: Initialize Widgets
	*/
	add_action( 'after_setup_theme', 'rbd_core_load_shortcodes' );
	function rbd_core_load_shortcodes(){
		$shortcodes = array(
			'review-engine-display'
		);

		# If Review Engine URL Exists
		/**
		 * @TODO: Check if this is a real URL via an API calls
		*/
		if( get_option( 'rbd_core_review_engine_url' ) ){
			foreach( $shortcodes as $shortcode ){
				include( plugin_dir_path( __FILE__ ) . "lib/shortcodes/$shortcode/shortcode-$shortcode.php" );
			}
		}
	}

	##        (\ /)
	##       ( . .) ♥ ~< Code Block - B: Larger Scope Functions >
	##       c(”)(”)

	/**
	 * Reconstruct Review Engine URL
	 *
	 * @since 0.8.3
	 *
	 * @internal { I have to `str_replace` the URL sooo freaking much. I turned
  	 *	it into it's own function so I don't have to double function each string. }
	 *
	 * @param Optional: Type. Boolean. Indicates request for URL or API URL,
	 * otherwise returns standard URL.
	 *
	 * @return A reconstructed URL so there's no fragment or missing protocol.
	 *
	 * @example rbd_core_url() = engine url, rbd_core_url(true) = engine URL
	 *	with API key string attached, add more parameters to the end.
	*/
	function rbd_core_url( $optional = false ){
		# Force http:// and get the URL
		$url		= get_option( 'rbd_core_review_engine_url' );
		$protocol	= 'http://';
		$_mod_url	= str_replace( array( 'http://', 'https://' ), '', $url );
		$_api_url	= '/reviews-api-v2/';
		$_api_ver	= '?query_v2=true';
		$_api_usr	= '&user=RedbrickDigitalDev';
		$_api_key	= '&key=c97d195f043c82acd070a6e8be211eeb';
		$_new_url	= $protocol.$_mod_url;

		# See If User Wants API URL or Not and return it
		/**
		 * @internal { We don't currently use `https://`, so it's removed if a
	 	 *	client adds it for some reason, and replaced with http:// }
		*/
		return ( $optional == true ) ? $_new_url.$_api_url.$_api_ver.$_api_usr.$_api_key : $_new_url;
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
	 * @example @see `/lib/shortcodes/review-engine-display/shortcode-review-engine-display.php`
	*/
	function rbd_core_api_call(){
		# Define Basic Light API String, we need 1 review, or we get X or ALL
		$api_url = rbd_core_url( true ) .'&reviews_per_page=1';

		# Check for `rbd_core_api_call`, set and use if doesn't exist
		if ( false === ( $rbd_core_api_call = get_transient( 'rbd_core_api_call' ) ) ) {
			$rbd_core_api_call = @file_get_contents( $api_url );
			set_transient( 'rbd_core_api_call', $rbd_core_api_call, 86400 );
		}
		return json_decode( get_transient( 'rbd_core_api_call' ) );
	}

	/**
	 * Colorize Color Variable
	 *
	 * @since 0.8.9
	 *
	 * @internal { Colors are a nuisance. Sometimes people use # and Sometimes
	 * they don't. Let's parse that for them, maybe add RGB colors too? }
	 *
	 * @return CSS-Ready Colorized String
	 * @example @see `/lib/widgets/social-proof/widget-social-proof.php`
	*/
	function rbd_core_colorize( $color ){
		# Force http:// and get the URL
		$_symbol	= '#';
		$_replace	= str_replace( $_symbol, '', $color );
		$_css_ready	= $_symbol . $_replace;

		return $_css_ready;
	}

	/**
	 * Debugging
	*/
	add_action( 'get_footer', 'rbd_core_debug' );
	function rbd_core_debug(){
		if( $_GET['debug'] ){
			if( $_GET['debug'] == 'phpinfo' ){
				phpinfo();
			} else if( $_GET['debug'] == 'api' ){
				$_url		= $_GET['url'];
				$_mod_url	= str_replace( array( 'http://', 'https://' ), '', $_url );
				$_api_url	= 'http://'. $_mod_url .'/reviews-api-v2/?query_v2=true&user=RedbrickDigitalDev&key=aacb.1493ccebbe';

				$api_object = $_GET['strict'] == true ? file_get_contents( $_api_url ) : @file_get_contents( $_api_url );

				echo '<h2 style="width: 100%; clear: both; background: #e4e4e4; padding: 10px 20px; margin: 0; border: 1px solid #aaa; border-left-width: 10px; border-bottom: 0;">Redbrick Digital Core: Debug Error Output:</h2>';
				echo '<div style="width: 100%; clear: both; float: left; background: #f8f8f8; color: #333; border: 1px solid #aa0000; border-left-width: 10px; padding: 10px 20px; font-size: 16px;">';
					print_r( $api_object );
				echo '</div>';
			} else if( $_GET['debug'] == 'transient' ){
				$_url		= $_GET['url'];
				$_mod_url	= str_replace( array( 'http://', 'https://' ), '', $_url );
				$_api_url	= 'http://'. $_mod_url .'/reviews-api-v2/?query_v2=true&user=RedbrickDigitalDev&key=aacb.1493ccebbe';

				if( $_GET['delete'] != true ){
					if( false === ( $debug_transient = get_transient( 'rbd_core-debug' ) ) ){
						$debug_transient = @file_get_contents( $_api_url );
						set_transient( 'rbd_core-debug', $debug_transient, 86400 );
					}
				} else {
					delete_transient( 'rbd_core-debug' );
				}

				$api_object	= json_decode( get_transient( 'rbd_core-debug' ) );

				echo '<h2 style="width: 100%; clear: both; background: #e4e4e4; padding: 10px 20px; margin: 0; border: 1px solid #aaa; border-left-width: 10px; border-bottom: 0;">Redbrick Digital Core: Debug Error Output:</h2>';
				echo '<div style="width: 100%; clear: both; float: left; background: #f8f8f8; color: #333; border: 1px solid #aa0000; border-left-width: 10px; padding: 10px 20px; font-size: 16px;">';
					print_r( $api_object );
				echo '</div>';
			}
		}
	}
?>
