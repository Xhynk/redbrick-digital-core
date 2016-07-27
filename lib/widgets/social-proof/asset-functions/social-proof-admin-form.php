<?php
	$array = rbd_core_social_proof_options_array();
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
		<?php /*<p>
			<strong><label for="<?php echo $this->get_field_id( 'text_align' ); ?>"><?php _e( 'Alignment:' ); ?></label></strong>
			<select class="widefat" id="<?php echo $this->get_field_id( 'text_align' ); ?>" name="<?php echo $this->get_field_name( 'text_align' ); ?>">
				<option <?php if($text_align == 'left'){	echo 'selected="selected"'; } ?> value="left">Left</option>
				<option <?php if($text_align == 'center'){	echo 'selected="selected"'; } ?> value="center">Center</option>
				<option <?php if($text_align == 'right'){	echo 'selected="selected"'; } ?> value="right">Right</option>
			</select>
		</p> */ ?>
		<p>
			<strong><label for="<?php echo $this->get_field_id( 'font_color' ); ?>"><?php _e( 'Font Color:' ); ?></label></strong><br />
			<input class="widefat color-picker" id="<?php echo $this->get_field_id( 'font_color' ); ?>" name="<?php echo $this->get_field_name( 'font_color' ); ?>" type="text" value="<?php echo esc_attr( $font_color ); ?>" />
		</p>
	</div>
