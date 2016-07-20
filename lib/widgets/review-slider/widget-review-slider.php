<?php
	// Instead of redefining these 3 or 4 times, each function will loop through this array.
	function rbd_core_definitions_array_rbd_core_widget_review_slider(){
		$array = array(
			'url'					=> get_option('rbd_core_review_engine_url'),
			'title'					=> 'Our Reviews',
			'perpage'				=> 10,
			'service'				=> '',
			'employee'				=> '',
			'location'				=> '',
			'threshold'				=> 3,
			'hide_meta'				=> false,
			'disable_css'			=> false,
			'slider_speed'			=> 101,
			'hide_reviewer'			=> false,
			'character_count'		=> 155
		);

		return $array;
	}

	// Creating the widget
	class rbd_core_widget_review_slider extends WP_Widget {

		function __construct() {
			parent::__construct(
			// Base ID of your widget
			'rbd_core_widget_review_slider',

			// Widget name will appear in UI
			__('Review Slider ★ (RBD Core)', 'rbd_core_widget_review_slider_domain'),

			// Widget description
			array( 'description' => __( 'Display a slider with reviews from your Review Engine.', 'rbd_core_widget_review_slider_domain' ), )
			);
		}


		// Widget Frontend
		public function widget( $args, $instance ) {
			$array = rbd_core_definitions_array_rbd_core_widget_review_slider();
			foreach( $array as $var => $default_value){
				${$var} = apply_filters( 'widget_'.$var, $instance[$var] );
			}

			$speed		= ( $slider_speed == 101 ? array( true, 3000, 500, '' ) :
			 				( $slider_speed == 76 ? array( true, 5000, 700, '' ) :
								( $slider_speed == 51 ? array( true, 7000, 900, '' ) :
									( $slider_speed == 26 ? array( true, 9000, 900, '' ) :
										( $slider_speed == 1 ? array( false, 86400, 86400, 'manual' ) : array( true, 3000, 750, '' ) ) ) ) ) );

			$perpage	= ( !empty( $perpage ) ) ? '&reviews_per_page='.$perpage : '';
			$service	= ( !empty( $service ) ) ? '&category='.$service : '';
			$employee	= ( !empty( $employee ) ) ? '&employee='.$employee : '';
			$location	= ( !empty( $location ) ) ? '&location='.$location : '';
			$threshold	= ( !empty( $threshold ) ) ? '&threshold='.$threshold : '';

			$query = "?query_v2=true&user=thirdriverdev&key=56677c860f0f351a0a1b726b74f2f215$threshold$perpage$service$location$employee";

			$arrow				= ' <i class="fa fa-angle-right"></i>';

			$api_string			= "/reviews-api-v2/$query";
			$api_url			= 'http://'. str_replace( 'http://', '', $url ) . $api_string;

			if ( false === ( ${$args['widget_id']} = get_transient( $args['widget_id'] ) ) ) {
				${$args['widget_id']} = @file_get_contents( $api_url );
				set_transient( $args['widget_id'], ${$args['widget_id']}, 86400 );
			}

			$data_object = json_decode( get_transient( $args['widget_id'] ) );

			$counter = 0;

			// before and after widget arguments are defined by themes
			echo $args['before_widget'];
				if ( !empty( $title ) ) {
					echo $args['before_title'] . $title . $args['after_title'];
				} ?>
				<div class="rbd-core-plugin rbd-review-slider rbd-core-ui <?php echo $speed[3]; ?>" data-attr-slider-autoplay="<?php echo ($speed[0] == 1) ? 'true' : 'false'; ?>" data-attr-slider-delay="<?php echo $speed[1]; ?>"data-attr-slider-speed="<?php echo $speed[2]; ?>">
					<div class="review-slider-container unslider-horizontal">
						<ul class="unslider-wrap unslider-carousel">
							<?php
								if( $data_object->data[0]->returned_reviews > 0 ){
									foreach( $data_object->reviews as $review ){
										$counter++;
										$title			= $review->title;
										$aggregate		= intval( $data_object->data->aggregate );
										$rating			= intval( $review->rating );
										$button_classes	= ( $disable_css == true ) ? 'button button-primary btn read-more' : 'rbd-button';

										$visibility	= '';

										$reviewer	= $hide_reviewer != true ? '<br /><span class="reviewer"><em>'. $review->review_meta->reviewer->display_name .'</em></span>' : '';
										$ellipses	= strlen( $review->content ) > $character_count ? '...' : '';
										$read_more	= strlen( $review->content ) > $character_count ? "<div class='center'><a class='$button_classes' target='_blank' href='{$review->review_data->url}'><span class='_label'>Read More</span>$arrow</a></a></div>" : '';

										$stars		= '<span class="star medium">'. str_repeat( '★', $rating ) . '<span class="dark-star">'. str_repeat( '★', 5 - $rating ) .'</span></span>';
										$synopsis	= '<div class="synopsis center">'. $stars.$reviewer .'</div>';

										$service	= ( !empty( $review->review_meta->reviewed_item->service ) ) ? 'Reviewing <strong>'. $review->review_meta->reviewed_item->service .'</strong>' : '';
										$staff		= ( !empty( $review->review_meta->reviewed_item->staff->name ) ) ? ' provided by <strong>'. $review->review_meta->reviewed_item->staff->name .'</strong>' : '';
										$location	= ( !empty( $review->review_meta->reviewed_item->location->name ) ) ? ' in <strong>'.  $review->review_meta->reviewed_item->location->name .'</strong>' : '';

										$meta		= ( $hide_meta == true ) ? '' : '<div class="meta center">'. $service.$staff.$location .'</div>';

										$content	= substr( $review->content, 0, $character_count ).$ellipses.$read_more.$synopsis.$meta; ?>

										<li class="review-wrap <?php echo $visibility; ?>" style="list-style-type: none;"><?php // List Discs are not okay! Forcing them off. ?>
											<div>
												<h5 class="review-title">"<?php echo $title; ?>"</h5>
												<p class="review-content"><?php echo $content ?></p>
											</div>
										</li>

										<?php if( $counter == $perpage ){
											break;
										}
									}
								} else { ?>
									<li class="review-wrap" style="list-style-type: none;">
										<div>
											<h5 class="review-title">No Reviews Found</h5>
											<p class="review-content">Please adjust your review query.</p>
										</div>
									</li>
								<?php }
							?>
						</ul>
					</div>
				</div>
			<?php echo $args['after_widget'];
		}

		// Widget Backend
		public function form( $instance ) {
			$array = rbd_core_definitions_array_rbd_core_widget_review_slider();
			foreach( $array as $var => $default_value){
				${$var} = isset( $instance[$var] ) ? $instance[$var] : $default_value;
			}

			$pre_data = rbd_core_api_call();

			$mb		= 'margin-bottom: 1em;';
			$tl		= 'text-align: left;';
			$tr		= 'text-align: right;';
			$tc		= 'text-align: center;';
			$ib		= 'display: inline-block;';
			$cl		= 'padding-left: 10px; margin-left: -7px;';
			$half	= 'width: 47.5%;';
			$third	= 'width: 30%;';
			$left	= 'margin: 0 -4px 0 0;';
			$right	= 'margin: 0 0 0 5%;';
			$center	= 'margin: 0 -4px 0 5%;';

			// Widget admin form
			?>
			<div class="rbd-core-ui-admin">
				<p style="<?php echo "$half $left $ib $mb"; ?>">
					<strong><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label></strong>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
				</p>
				<p style="<?php echo "$half $right $ib $mb"; ?>">
					<strong><label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Review Engine URL:' ); ?></label></strong>
					<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" />
				</p>
				<p style="<?php echo "$third $left $ib $mb"; ?>">
					<strong><label for="<?php echo $this->get_field_id( 'threshold' ); ?>"><?php _e( 'Threshold:' ); ?></label></strong>
					<select class="widefat" id="<?php echo $this->get_field_id( 'threshold' ); ?>" name="<?php echo $this->get_field_name( 'threshold' ); ?>">
						<option <?php if($threshold == '3'){ echo 'selected="selected"'; } ?> value="3">★★★☆☆</option>
						<option <?php if($threshold == '4'){ echo 'selected="selected"'; } ?> value="4">★★★★☆</option>
						<option <?php if($threshold == '5'){ echo 'selected="selected"'; } ?> value="5">★★★★★</option>
					</select>
				</p>
				<p style="<?php echo "$third $center $ib $mb"; ?>">
					<strong><label for="<?php echo $this->get_field_id( 'perpage' ); ?>"><?php _e( 'Review Count:' ); ?></label></strong>
					<select class="widefat" id="<?php echo $this->get_field_id( 'perpage' ); ?>" name="<?php echo $this->get_field_name( 'perpage' ); ?>">
						<?php
							$count_array = range( 1, 20 );	// Create an array of integers, 1 through 20

							foreach( $count_array as $i ){	// Loop through the array
								$s = ( $i > 1 ) ? 's' : '';	// Is it plural? Over 1 means plural.
								$selected = ( $perpage == $i ) ? 'selected="selected"' : '';	// Set the default after new page loads to what was selected
								echo '<option '. $selected .' value="'. $i .'">'. $i .' Review'. $s .'</option>';	// Echo a select option of "1 Review" through "20 Reviews"
							}
						?>
					</select>
				</p>
				<p style="<?php echo "$third $right $ib $mb"; ?>">
					<strong><label for="<?php echo $this->get_field_id( 'character_count' ); ?>"><?php _e( 'Character Count:' ); ?></label></strong>
					<input class="widefat" id="<?php echo $this->get_field_id( 'character_count' ); ?>" name="<?php echo $this->get_field_name( 'character_count' ); ?>" type="number" value="<?php echo esc_attr( $character_count ); ?>" />
				</p>

				<?php
					#var_dump( $pre_data->company[0]->taxonomies[0]->service[0] );

					if( !empty( $pre_data->company[0]->taxonomies[0]->service[0] ) ){ ?>
						<p style="<?php echo "$third $left $ib $mb"; ?> margin-right: 0">
							<strong><label for="<?php echo $this->get_field_id( 'service' ); ?>"><?php _e( 'Service:' ); ?></label></strong>
							<select class="widefat" id="<?php echo $this->get_field_id( 'service' ); ?>" name="<?php echo $this->get_field_name( 'service' ); ?>">
								<option value="">All</option>
								<?php foreach( $pre_data->company[0]->taxonomies[0]->service[0] as $service_x ){
									$selected = ( $service == $service_x->slug ) ? 'selected="selected"' : '';
									echo "<option $selected value='$service_x->slug'>$service_x->name</option>";
								} ?>
							</select>
					<?php }

					if( !empty( $pre_data->company[0]->taxonomies[0]->employee[0] ) ){ ?>
						<p style="<?php echo "$third $center $ib $mb"; ?> margin-right: 0;">
							<strong><label for="<?php echo $this->get_field_id( 'employee' ); ?>"><?php _e( 'Staff:' ); ?></label></strong>
							<select class="widefat" id="<?php echo $this->get_field_id( 'employee' ); ?>" name="<?php echo $this->get_field_name( 'employee' ); ?>">
								<option value="">All</option>
								<?php foreach( $pre_data->company[0]->taxonomies[0]->employee[0] as $employee_x ){
									$selected = ( $employee == $employee_x->slug ) ? 'selected="selected"' : '';
									echo "<option $selected value='$employee_x->slug'>$employee_x->name</option>";
								} ?>
							</select>
					<?php }

					if( !empty( $pre_data->company[0]->taxonomies[0]->location[0] ) ){ ?>
						<p style="<?php echo "$third $right $ib $mb"; ?>">
							<strong><label for="<?php echo $this->get_field_id( 'location' ); ?>"><?php _e( 'Location:' ); ?></label></strong>
							<select class="widefat" id="<?php echo $this->get_field_id( 'location' ); ?>" name="<?php echo $this->get_field_name( 'location' ); ?>">
								<option value="">All</option>
								<?php foreach( $pre_data->company[0]->taxonomies[0]->location[0] as $location_x ){
									$selected = ( $location == $location_x->slug ) ? 'selected="selected"' : '';
									echo "<option $selected value='$location_x->slug'>$location_x->name</option>";
								} ?>
							</select>
					<?php }
				?>
				<p style="<?php echo "$third $left $ib"; ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_reviewer' ); ?>" name="<?php echo $this->get_field_name( 'hide_reviewer' ); ?>" value="true" <?php if($hide_reviewer == true){ echo 'checked="checked"'; } ?> />
					<strong><label style="<?php echo "$cl"; ?>" for="<?php echo $this->get_field_id( 'hide_reviewer' ); ?>"><?php _e( 'Hide Reviewer' ); ?></label></strong>
				</p>
				<p style="<?php echo "$third $center $ib"; ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_meta' ); ?>" name="<?php echo $this->get_field_name( 'hide_meta' ); ?>" value="true" <?php if($hide_meta == true){ echo 'checked="checked"'; } ?> />
					<strong><label style="<?php echo "$cl"; ?>" for="<?php echo $this->get_field_id( 'hide_meta' ); ?>"><?php _e( 'Hide Meta' ); ?></label></strong>
				</p>
				<p style="<?php echo "$third $right $ib"; ?>">
					<input type="checkbox" id="<?php echo $this->get_field_id( 'disable_css' ); ?>" name="<?php echo $this->get_field_name( 'disable_css' ); ?>" value="true" <?php if($disable_css == true){ echo 'checked="checked"'; } ?> />
					<strong><label style="<?php echo "$cl"; ?>" for="<?php echo $this->get_field_id( 'disable_css' ); ?>"><?php _e( 'Disable CSS' ); ?></label></strong>
				</p>
				<p class="control">
					<strong><label for="<?php echo $this->get_field_id( 'slider_speed' ); ?>"><?php _e( 'Slider Speed' ); ?></label></strong>
					<input type="range" min="1" max="101" step="25" id="<?php echo $this->get_field_id('slider_speed'); ?>" name="<?php echo $this->get_field_name('slider_speed'); ?>" value="<?php echo esc_attr($slider_speed); ?>" />
				</p>
				<div class="range__ruler">Off 25% 50% 75% 100%</div>
				<div style="display: none;">
					<input type="hidden" id="<?php echo $this->get_field_id( 'widget_instance_id' ); ?>" name="<?php echo $this->get_field_name( 'widget_instance_id' ); ?>" value="<?php echo esc_attr( $this->id ); ?>" />
				</div>
			</div>
		<?php
			wp_enqueue_script( 'rbd-rangeslider', plugins_url( '/assets/js/admin-rangeslider.min.js', __FILE__ ), array('jquery'), '1.0', true );
		}

		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			delete_transient( $new_instance['widget_instance_id'] );
			$instance = array();

			$array = rbd_core_definitions_array_rbd_core_widget_review_slider();
			foreach( $array as $var => $default_value){
				$instance[$var] = ( !empty( $new_instance[$var] ) ) ? sanitize_text_field( $new_instance[$var] ) : '';
			}

			return $instance;
		}
	} // Class rbd_core_widget_review_slider ends here

	// Register and load the widget
	function rbd_load_widget_rbd_core_widget_review_slider() {
		register_widget( 'rbd_core_widget_review_slider' );
	}
	add_action( 'widgets_init', 'rbd_load_widget_rbd_core_widget_review_slider' );
?>
