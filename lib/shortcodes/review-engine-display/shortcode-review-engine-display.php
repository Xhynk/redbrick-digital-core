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
	 *       ( . .) ♥ ~< Creates a columnar list of reviews on pages/posts >
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
	 * Move Asset Functions to New Files
	 *
	 * @since 0.8.9
	 *
	 * @internal { Code was getting too messy in one file }
	*/
	$ext	= '.php';
	$path 	= 'asset-functions/';
	$slug 	= 'review-engine-display-';
	$func 	= array(
		'admin',
		'front-end'
	);

	foreach( $func as $include ){
		include( plugin_dir_path( __FILE__ ) . $path . $slug . $include . $ext );
	}
?>
