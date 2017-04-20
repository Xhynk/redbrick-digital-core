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
	 * Version:     0.9.6.1
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
		$disable_cutestrap = get_option( 'rbd_core_disable_cutestrap' );
		if( $disable_cutestrap != true )
			wp_enqueue_style( 'rbd-cutestrap-min', plugins_url( '/assets/css/cutestrap.min.css', __FILE__ ) ); // Now Bootstrap 4 based

		wp_enqueue_style( 'rbd-core', plugins_url( '/assets/css/core.css', __FILE__ ) );
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
		$widgets = array(
			'social-proof',
			'review-slider'
		);

		# If Review Engine URL Exists
		if( rbd_core_verify() == true ){
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
		if( rbd_core_verify() == true ){
			foreach( $shortcodes as $shortcode ){
				include( plugin_dir_path( __FILE__ ) . "lib/shortcodes/$shortcode/shortcode-$shortcode.php" );
			}
		}
	}

	/**
	 * Delete Transients Attached to Pages
	 *
	 * @since 0.9.3.4
	 *
	 * @internal { Shortcodes on pages that made transients were getting stuck
  	 *	until expiration. This should fix that so they update when updated. }
	*/
	add_action( 'save_post', 'rbd_core_reset_post_transients' );
	function rbd_core_reset_post_transients( $post_id ){
		$shortcode_pre_salts = array(
			'rbd_core_shortcode_review_engine_display'
		);

		foreach( $shortcode_pre_salts as $pre_salt ){
			$salt = "$pre_salt-$post_id";

			delete_transient( $salt );
		}
	}

	##        (\ /)
	##       ( . .) ♥ ~< Code Block - B: Larger Scope Functions >
	##       c(”)(”)

	/**
	 * Replace file_get_contents with cURL for larger responses
	 *
	 * @since 0.9.1
	 *
	 * @internal { `file_get_content()` was really slow on other servers, so
	 *	cURL is supposed to be faster. }
	 *
	 * @return $response, the JSON Data from the API
	 * @example @see `/lib/shortcodes/review-engine-display/widget-review-engine-display.php`
	*/
	function rbd_core_file_get_contents_curl( $url ) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	/**
	 * Reconstruct Review Engine URL
	 *
	 * @since 0.8.3
	 *
	 * @version 2.0 since 0.9.3.4, added alternate URL option.
	 *
	 * @internal { I have to `str_replace` the URL sooo freaking much. I turned
  	 *	it into it's own function so I don't have to double function each string. }
	 *
	 * @param Optional: Type. Boolean. Indicates request for URL or API URL,
	 * otherwise returns standard URL.
	 * @param Options: Alternat URL. String. Request an alternate URL than the
	 * one provided in RBD Core Admin Settings page.
	 *
	 * @return A reconstructed URL so there's no fragment or missing protocol.
	 *
	 * @example rbd_core_url() = engine url, rbd_core_url(true) = engine URL
	 *	with API key string attached, add more parameters to the end.
	*/
	function rbd_core_url( $optional = false, $alternate_url = '' ){
		# Force http:// and get the URL
		$url		= $alternate_url == '' ? get_option( 'rbd_core_review_engine_url' ) : $alternate_url;
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
	 * Determine if HIPAA Compliance is Requested.
	 *
	 * @since 0.9.3.4
	 *
	 * @internal { We'll check if a constant is defined, and overwrite Variables
	 *	as we go if it's true. }
	*/
	if( get_option( 'rbd_core_hipaa_compliance' ) == true ){
		define( 'RBD_HIPAA_COMPLIANCE', true );
	}

	/**
	 * Issue Warning if HIPAA Mode is enabled in some Admin Areas
	 *
	 * @since 0.9.3.4
	 *
	 * @internal { At this time, I'm not 'blocking' options, just letting the
 	 *	user know some things will be removed from the front end. }
	*/
	function rbd_core_hipaa_warning(){
		if( defined( 'RBD_HIPAA_COMPLIANCE' ) ) {
			echo '<div style="background: #f8f8f8; padding: 1px 30px; border-left: 4px solid #dc3232; box-shadow: 0 2px 2px rgba(0,0,0,.15); margin-bottom: 15px;">
						<p><strong>HIPAA Compliance Enabled:</strong> Personally identifying information will be removed from reviews. To change this setting, go <a href="'. admin_url() .'admin.php?page=redbrick-digital-core/admin/admin-core.php">here</a>.</p>
					</div>';
		}
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
	 * @uses rbd_core_url() - Returns Review Engine URL w/ or w/o API string.o
	 * @return Object from basic API Call
	 * @example @see `/lib/shortcodes/review-engine-display/shortcode-review-engine-display.php`
	*/
	function rbd_core_api_call(){
		# Define Basic Light API String, we need 1 review, or we get X or ALL
		$api_url = rbd_core_url( true ) .'&reviews_per_page=1';

		# Check for `rbd_core_api_call`, set and use if doesn't exist
		if( false === ( $rbd_core_api_call = get_transient( 'rbd_core_api_call' ) ) ) {
			$rbd_core_api_call = rbd_core_file_get_contents_curl( $api_url );
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
		# Force http:// and get the URL, we don't use SSL currently.
		$_symbol	= '#';
		$_replace	= str_replace( $_symbol, '', $color );
		$_css_ready	= $_symbol . $_replace;

		return $_css_ready;
	}

	/**
	 * Verification
	 * @since 0.9.5
	 *
	 * @internal { I've wanted the URL in the admin pages to be a verification since
 	 *	the beginning, because the plugin is technically public now. }
	*/
	function rbd_core_verify(){
		// If URL has a value and the RBD_CORE_VALID validifier is false
		if( get_option( 'rbd_core_review_engine_url' ) != '' && get_option( 'RBD_CORE_VALID' ) == false ){	// This isn't good.
			return false;
		} else if( get_option( 'rbd_core_review_engine_url' ) != '' && get_option( 'RBD_CORE_VALID' ) == true ){	// This IS good. Non-empty and true!
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Write A Review Link
	 * @since 0.9.6
	 *
	 * @internal { This used to link straight to the Review Engine's homepage,
 	 *	but with the introduction of Review Funnels, people may want to gather
	 *	reviews that way instead. So this function builds the link. }
	*/
	function rbd_core_write_a_review_url( $url ){
		$write_a_review_url = esc_attr( get_option('rbd_core_review_engine_write_a_review_url') );
		if( !empty( $write_a_review_url ) )
			$write_a_review_url = "r/$write_a_review_url";

		return $url.$write_a_review_url;
	}

	/**
	 * Debugging
	 * @since 0.9.0
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

				$api_object = $_GET['strict'] == true ? rbd_core_file_get_contents_curl( $_api_url ) : rbd_core_file_get_contents_curl( $_api_url );

				echo '<h2 style="width: 100%; clear: both; background: #e4e4e4; padding: 10px 20px; margin: 0; border: 1px solid #aaa; border-left-width: 10px; border-bottom: 0;">Redbrick Digital Core: Debug Error Output:</h2>';
				echo '<div style="width: 100%; clear: both; float: left; background: #f8f8f8; color: #333; border: 1px solid #aa0000; border-left-width: 10px; padding: 10px 20px; font-size: 16px;">';
					if( $_url ){
						print_r( $api_object );
					} else {
						echo 'Please send a Review Engine URL as a GET request: &url=client.site.com';
					}
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
