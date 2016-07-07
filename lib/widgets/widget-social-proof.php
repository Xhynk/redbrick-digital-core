<?php
	// Instead of redefining these 3 or 4 times, each function will loop through this array.
	function rbd_core_definitions_array_rbd_core_widget_social_proof(){
		$array = array(
			'url'			=> get_option('rbd_core_review_engine_url'),
			'font_color'	=> '',
			'text_align'	=> 'left'
		);

		return $array;
	}

	// Creating the widget
	class rbd_core_widget_social_proof extends WP_Widget {

		function __construct() {
			parent::__construct(
			// Base ID of your widget
			'rbd_core_widget_social_proof',

			// Widget name will appear in UI
			__('Social Proof ★ (RBD Core)', 'rbd_core_widget_social_proof_domain'),

			// Widget description
			array( 'description' => __( 'Display your social proof.', 'rbd_core_widget_social_proof_domain' ), )
			);
		}

		// Widget Frontend
		public function widget( $args, $instance ) {
			$array = rbd_core_definitions_array_rbd_core_widget_social_proof();
			foreach( $array as $var => $default_value){
				${$var} = apply_filters( 'widget_'.$var, $instance[$var] );
			}

			$pre_data		= rbd_core_api_call();
			$aggregate		= $pre_data->data[0]->aggregate;
			$total_reviews	= $pre_data->data[0]->total_reviews;

			$width			= $aggregate * 20 . '%';
			$font_color		= ( !empty( $font_color ) ) ? 'color: #'.str_replace( '#', '', $font_color ) : '';

			$meta			= "<span class=\"meta small relative\" style=\"$font_color\">Based on <span class=\"bold\"><a target=\"_blank\" href=\"$url\" style=\"$font_color\">$total_reviews Reviews</a></span></span>";
			$dark			= '<span class="dark-star absolute dib">★★★★★</span>';
			$star			= "<span class=\"star relative dib\"><span class=\"trim flex\" style=\"width: $width\">★★★★★</span></span>";
			$string			= "<span class=\"aggregate\" style=\"$font_color\"><span class=\"bold\">$aggregate</span> <span class=\"small\">out of</span> <span class=\"bold\">5</span></span>";

			// before and after widget arguments are defined by themes
			echo $args['before_widget']; ?>
				<div class="rbd-core-ui">
					<div class="rbd-reviews-social-proof" style="text-align: <?php echo $text_align; ?>;">
						<span class="text-center dib">
							<span class="star-container relative text-left">
								<?php echo $dark.$star; ?>
							</span>
							<span class="padding">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<?php echo $string; ?>
							<br />
							<?php echo $meta; ?>
						</span>
					</div>
				</div>
			<?php echo $args['after_widget'];
		}

		// Widget Backend
		public function form( $instance ) {
			$array = rbd_core_definitions_array_rbd_core_widget_social_proof();
			foreach( $array as $var => $default_value){
				${$var} = isset( $instance[$var] ) ? $instance[$var] : $default_value;
			}

			// Widget admin form
			?>
			<div class="rbd-core-ui-admin">
				<p>
					<strong><label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Review Engine URL:' ); ?></label></strong>
					<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" />
				</p>
				<p>
					<strong><label for="<?php echo $this->get_field_id( 'text_align' ); ?>"><?php _e( 'Alignment:' ); ?></label></strong>
					<select class="widefat" id="<?php echo $this->get_field_id( 'text_align' ); ?>" name="<?php echo $this->get_field_name( 'text_align' ); ?>">
						<option <?php if($text_align == 'left'){	echo 'selected="selected"'; } ?> value="left">Left</option>
						<option <?php if($text_align == 'center'){	echo 'selected="selected"'; } ?> value="center">Center</option>
						<option <?php if($text_align == 'right'){	echo 'selected="selected"'; } ?> value="right">Right</option>
					</select>
				</p>
				<p>
					<strong><label for="<?php echo $this->get_field_id( 'font_color' ); ?>"><?php _e( 'Font Color:' ); ?></label></strong><br />
					<input class="widefat color-picker" id="<?php echo $this->get_field_id( 'font_color' ); ?>" name="<?php echo $this->get_field_name( 'font_color' ); ?>" type="text" value="<?php echo esc_attr( $font_color ); ?>" />
				</p>
			</div>
		<?php
			wp_enqueue_script( 'rbd-rangeslider', plugins_url( '/assets/js/admin-rangeslider.min.js', __FILE__ ), array('jquery'), '1.0', true );
		}

		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$array = rbd_core_definitions_array_rbd_core_widget_social_proof();
			foreach( $array as $var => $default_value){
				$instance[$var] = ( !empty( $new_instance[$var] ) ) ? sanitize_text_field( $new_instance[$var] ) : '';
			}

			return $instance;
		}
	} // Class rbd_core_widget_social_proof ends here

	// Register and load the widget
	function rbd_load_widget_rbd_core_widget_social_proof() {
		register_widget( 'rbd_core_widget_social_proof' );
	}
	add_action( 'widgets_init', 'rbd_load_widget_rbd_core_widget_social_proof' );
?>
