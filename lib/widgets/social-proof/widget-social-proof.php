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
	 *       ( . .) ♥ ~< Creates a little badge for "Social Proof"! >
	 *       c(”)(”)
	*/

	/**
	 * Define Options As Array
	 *
	 * @since 0.2.5
	*/
	function rbd_core_social_proof_options_array(){
		$array = array(
			'url'			=> rbd_core_url(),
			'theme'			=> 'detailed',
			'font_color'	=> '',
			'text_align'	=> 'left',
		);

		return $array;
	}

	/**
	 * Construct Widget
	 *
	 * @since 0.2.1
	*/
	class rbd_core_widget_social_proof extends WP_Widget {
		function __construct() {
			parent::__construct(
				# Widget Base ID
				'rbd_core_widget_social_proof',

				# Widget Display Name
				__('Social Proof ★ (RBD Core)', 'rbd_core_widget_social_proof_domain'),

				# Widget Description
				array( 'description' => __( 'Display your social proof.', 'rbd_core_widget_social_proof_domain' ), )
			);
		}

		/**
		 * Include Front-End Parsing
		 *
		 * @since 0.8.9
		 * @version 2.0 - Added Themes
		*/
		public function widget( $args, $instance ) {
			$dir		= plugin_dir_path( __FILE__ ) . 'asset-functions/themes/';
			$str		= 'social-proof-';
			$ext		= '.php';

			$theme		= $instance['theme'] == '' ? 'detailed' : $instance['theme'];

			include( $dir.$str.$theme.$ext );
		}

		/**
		 * Include Admin Widget Form
		 *
		 * @since 0.8.9
		*/
		public function form( $instance ) {
			include( plugin_dir_path( __FILE__ ) . 'asset-functions/social-proof-admin-form.php' );
		}

		/**
		 * Update Old Instance With New, Saving Function
		 *
		 * @since 0.2.1
		*/
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$array = rbd_core_social_proof_options_array();
			foreach( $array as $var => $default_value){
				$instance[$var] = ( !empty( $new_instance[$var] ) ) ? sanitize_text_field( $new_instance[$var] ) : '';
			}

			return $instance;
		}
	} // Class rbd_core_widget_social_proof ends here

	/**
	 * Register and Load Widget
	 *
	 * @since 0.2.1
	*/
	function rbd_core_load_widget_social_proof() {
		register_widget( 'rbd_core_widget_social_proof' );
	}
	add_action( 'widgets_init', 'rbd_core_load_widget_social_proof' );
?>
