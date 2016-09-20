<?php
	/**
	 * Redbrick Digital Core
	 *
	 * @package \lib\widgets
	 * @author  RedbrickDigital.net
	 * @license GPL-2.0+
	 * @since 0.2.1
	 *
	 *        (\ /)
	 *       ( . .) ♥ ~< Creates an "unslider" slider for reviews! >
	 *       c(”)(”)
	*/

	/**
	 * Define Options As Array
	 *
	 * @since 0.2.5
	*/
	function rbd_core_review_slider_options_array(){
		$array = array(
			'url'					=> get_option( 'rbd_core_review_engine_url' ),
			'title'					=> 'Our Reviews',
			'perpage'				=> 10,
			'service'				=> '',
			'employee'				=> '',
			'location'				=> '',
			'threshold'				=> 3,
			'hide_meta'				=> false,
			'characters'			=> 155,
			'disable_css'			=> false,
			'slider_speed'			=> 101,
			'hide_reviewer'			=> false,
			'hide_progress'			=> false,
			'character_count'		=> 155,
		);

		return $array;
	}

	/**
	 * Construct Widget
	 *
	 * @since 0.2.1
	*/
	class rbd_core_widget_review_slider extends WP_Widget {
		function __construct() {
			parent::__construct(
				# Widget Base ID
				'rbd_core_widget_review_slider',

				# Widget Display Name
				__('Review Slider ★ (RBD Core)', 'rbd_core_widget_review_slider_domain'),

				# Widget Description
				array( 'description' => __( 'Display a slider with reviews from your Review Engine.', 'rbd_core_widget_review_slider_domain' ), )
			);
		}

		/**
		 * Include Front-End Parsing
		 *
		 * @since 0.8.9
		*/
		public function widget( $args, $instance ) {
			include( plugin_dir_path( __FILE__ ) . 'asset-functions/review-slider-front-end.php' );
		}

		/**
		 * Include Admin Widget Form
		 *
		 * @since 0.8.9
		*/
		public function form( $instance ) {
			include( plugin_dir_path( __FILE__ ) . 'asset-functions/review-slider-admin-form.php' );

			# Enqueue Range Slider JS File on Widget Form Init
			wp_enqueue_script( 'rbd-rangeslider', plugins_url( '/assets/js/admin-rangeslider.min.js', __FILE__ ), array('jquery'), '1.0', true );
		}

		/**
		 * Update Old Instance With New, Saving Function
		 *
		 * @since 0.2.1
		*/
		public function update( $new_instance, $old_instance ) {
			delete_transient( $new_instance['widget_instance_id'] );
			$instance = array();

			$array = rbd_core_review_slider_options_array();
			foreach( $array as $var => $default_value){
				$instance[$var] = ( !empty( $new_instance[$var] ) ) ? sanitize_text_field( $new_instance[$var] ) : '';
			}

			return $instance;
		}
	}

	/**
	 * Register and Load Widget
	 *
	 * @since 0.2.1
	*/
	function rbd_core_load_widget_review_slider() {
		register_widget( 'rbd_core_widget_review_slider' );
	}
	add_action( 'widgets_init', 'rbd_core_load_widget_review_slider' );
?>
