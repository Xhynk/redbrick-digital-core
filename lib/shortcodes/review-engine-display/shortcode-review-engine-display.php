<?php
	/**
	 * Redbrick Digital Core
	 *
	 * @package \lib\shortcodes
	 * @author  RedbrickDigital.net
	 * @license GPL-2.0+
	 * @since 0.2.1
	 *
	 *        (\ /)
	 *       ( . .) ♥ ~< Creates a columnar list of reviews on pages/posts! >
	 *       c(”)(”)
	*/

	/**
	 * Add Review Engine Shortcode Button to Add Media
	 *
	 * @since 0.2.2
	*/
	add_action( 'media_buttons', 'rbd_core_add_shortcode_button', 25 );
	function rbd_core_add_shortcode_button(){
		if( get_option( 'rbd_core_review_engine_url' ) ){
			$type	= 'button';
			$class	= "$type insert-review-engine-display add_review_engine";
			$icon	= '<span class="wp-media-buttons-icon"></span>';
			$label	= 'Add Review Engine Display';

			echo "<$type type='$type' id='insert-reviews-$type' class='$class' data-editor='content'>$icon $label</$type>";
		}
	}

	/**
	 * Add Popup to Footer
	 *
	 * @since 0.2.2
	 *
	 * @internal { Thickbox was being a whore, so we're using a custom popup
 	 *	instead. It works... }
	 *
	 * @uses rbd_core_api_call() - Returns object from Review Engine API.
	*/
	add_action( 'admin_footer', '_review_engine_display_dropdown_cloud' );
	function _review_engine_display_dropdown_cloud(){
		include( plugin_dir_path( __FILE__ ) . 'asset-functions/review-engine-display-dropdown-cloud.php' );
	}

	/**
	 * Add and Parse Shortcode
	 *
	 * @since 0.2.2
	 *
	 * @uses rbd_core_url() - Returns Review Engine URL w/ or w/o API string.
	*/
	add_shortcode( 'rbd_review_engine', '_review_engine_display_add_shortcode' );
    function _review_engine_display_add_shortcode( $atts ){
		include( plugin_dir_path( __FILE__ ) . 'asset-functions/review-engine-display-add-shortcode.php' );
    }
?>
