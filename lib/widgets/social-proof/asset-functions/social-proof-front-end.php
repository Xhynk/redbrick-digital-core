<?php
	/**
	 * Parse Widget Front-End
	 *
	 * @since 0.2.1
	 *
	 * @uses rbd_core_social_proof_options_array() - Return an Array of Options
	 * @uses rbd_core_api_call() - Returns API Object w/ Company Info and Data
	 * @uses rbd_core_colorize() - Returns a guaranteed usable color string
	 * @uses rbd_core_url() - Return the Review Engine URL
	*/
	$array = rbd_core_social_proof_options_array();
	foreach( $array as $var => $default_value){
		${$var} = apply_filters( 'widget_'.$var, $instance[$var] );
	}

	$pre_data		= rbd_core_api_call();
	$_url			= rbd_core_url();
	$_color			= rbd_core_colorize( $font_color );
	$_aggregate		= $pre_data->data[0]->aggregate;
	$_total_reviews	= $pre_data->data[0]->total_reviews;

	$_width			= $_aggregate * 20 . '%';
	$_font_color	= ( !empty( $font_color ) ) ? "color: $_color;": '';

	$meta			= "<span class='meta small relative' style='$_font_color'>Based on <span class='bold'><a target='_blank' href='$_url' style='$_font_color text-decoration: none;'><span>$_total_reviews</span> Reviews</a></span></span>";
	$dark			= '<span class="dark-star absolute dib">★★★★★</span>';
	$star			= "<span class='star relative dib'><span class='trim flex' style='width: $_width'>★★★★★</span></span>";
	$string			= "<span class='aggregate' style='$_font_color'><span class='bold'>$_aggregate</span> <span class='small'>out of</span> <span class='bold'>5</span></span>";

	// before and after widget arguments are defined by themes
	echo $args['before_widget']; ?>
		<div class="rbd-core-ui">
			<div class="rbd-reviews-social-proof" style="text-align: <?php echo $text_align; ?>;">
				<span class="text-center dib">
					<span class="star-container relative text-left">
						<?php echo $dark . $star; ?>
					</span>
					<span class="padding">&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<?php echo $string; ?>
					<br />
					<?php echo $meta; ?>
				</span>
			</div>
		</div>
	<?php echo $args['after_widget'];
?>
