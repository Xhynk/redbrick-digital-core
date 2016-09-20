<?php
	$array = rbd_core_review_slider_options_array();
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
		<p>
			<?php rbd_core_hipaa_warning(); ?>
		</p>
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
		<p style="<?php echo "$third $left $ib $mb"; ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_reviewer' ); ?>" name="<?php echo $this->get_field_name( 'hide_reviewer' ); ?>" value="true" <?php if($hide_reviewer == true){ echo 'checked="checked"'; } ?> />
			<strong><label style="<?php echo "$cl"; ?>" for="<?php echo $this->get_field_id( 'hide_reviewer' ); ?>"><?php _e( 'Hide Reviewer' ); ?></label></strong>
		</p>
		<p style="<?php echo "$third $center $ib $mb"; ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_meta' ); ?>" name="<?php echo $this->get_field_name( 'hide_meta' ); ?>" value="true" <?php if($hide_meta == true){ echo 'checked="checked"'; } ?> />
			<strong><label style="<?php echo "$cl"; ?>" for="<?php echo $this->get_field_id( 'hide_meta' ); ?>"><?php _e( 'Hide Meta' ); ?></label></strong>
		</p>
		<p style="<?php echo "$third $right $ib $mb"; ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_gravatar' ); ?>" name="<?php echo $this->get_field_name( 'hide_gravatar' ); ?>" value="true" <?php if($hide_gravatar == true){ echo 'checked="checked"'; } ?> />
			<strong><label style="<?php echo "$cl"; ?>" for="<?php echo $this->get_field_id( 'hide_gravatar' ); ?>"><?php _e( 'Hide Gravatar' ); ?></label></strong>
		</p>
		<p style="<?php echo "$third $left $ib"; ?>">
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
