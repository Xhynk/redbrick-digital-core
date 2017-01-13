<?php
	/**
	 * Parse Widget Front-End
	 *
	 * @since 0.2.1
	 *
	 * @uses rbd_core_review_slider_options_array() - Return an Array of Options
	 * @uses rbd_core_url() - Return the Review Engine URL
	*/

	$theme = 'jive';

	$array = rbd_core_review_slider_options_array();
	foreach( $array as $var => $default_value){
		${$var} = apply_filters( 'widget_'.$var, $instance[$var] );
	}

	# Backwards Compatibility
	$characters = !empty( $character_count ) ? $character_count : $characters;

	# Slider Speed
	/* Had to increase to n+1 because 0 was acting funny, so 1 is effectively 0, 101 is 100, etc. */
	$speed = ( $slider_speed == 101 ? array( true, 3000, 500, '' ) :
					( $slider_speed == 76 ? array( true, 5000, 700, '' ) :
						( $slider_speed == 51 ? array( true, 7000, 900, '' ) :
							( $slider_speed == 26 ? array( true, 9000, 900, '' ) :
								( $slider_speed == 1 ? array( false, 86400, 86400, 'manual' ) : array( true, 3000, 750, '' ) ) ) ) ) );

	# Build The Query, Save As Transient - refactored in 0.8.9
	$query_array = array(
		'perpage' => 'reviews_per_page',
		'service' => 'category',
		'employee' => 'employee',
		'location' => 'location',
		'threshold' => 'threshold'
	);

	foreach( $query_array as $key => $val ){
		$var	= ${$key};
		${$key}	= !empty( $var ) ? "&$val=$var" : '';
	}

	$_url		= rbd_core_url( true, $url );
	$_query		= $threshold . $perpage . $service . $location . $employee;
	$_id		= $args['widget_id'];
	$_var		= ${$args['widget_id']};
	$api_url	= $_url . $_query;

	if ( false === ( $_var = get_transient( $_id ) ) ) {
		$_var = rbd_core_file_get_contents_curl( $api_url );
		set_transient( $_id, $_var, 86400 );
	}
	$api_object = json_decode( get_transient( $_id ) );

	# Define Snippets and Preliminary Information
	$arrow		= ' <i class="fa fa-angle-right"></i>';
	$button_classes	= ( $disable_css == true ) ? 'button button-primary btn read-more' : 'rbd-button';
	$counter	= 0;

	//var_dump( rbd_core_review_slider_options_array() );

	# Before/After Widget Defined By Theme
	echo $args['before_widget']; ?>
		<div class="rbd-core-plugin rbd-review-slider rbd-core-ui <?php echo $speed[3]; ?>" data-attr-slider-autoplay="<?php echo ($speed[0] == 1) ? 'true' : 'false'; ?>" data-attr-slider-delay="<?php echo $speed[1]; ?>"data-attr-slider-speed="<?php echo $speed[2]; ?>">
			<?php echo !empty( $title ) ? $args['before_title'] . $title . $args['after_title'] : ''; ?>
			<div class="review-slider-container unslider-horizontal">
				<ul class="unslider-wrap unslider-carousel">
					<?php
						if( $api_object->data[0]->returned_reviews > 0 ){
							foreach( $api_object->reviews as $review ){
								$counter++;

								$_title			= $review->title;
								$_reviewed		= $review->review_meta->reviewed_item;
								$_aggregate		= intval( $api_object->data->aggregate );
								$_rating		= intval( $review->rating );
								$_bad			= str_repeat( '★', 5 - $_rating );
								$_good			= str_repeat( '★', $_rating );
								$_content		= substr( $review->content, 0, $characters );
								$_ellipses		= strlen( $review->content ) > $characters ? '...' : '';
								$_more			= strlen( $review->content ) > $characters ? "<div class='_readmore center'>
																									<a href='{$review->url}' target='_blank' class='$button_classes center' data-attr='Read More'><span class='_label'>Read More</span>$arrow</a>
																								</div>" : '';
								if( defined( 'RBD_HIPAA_COMPLIANCE' ) ){
									$_author		= $hide_reviewer == true ? '' : "<br /><br /><span class='reviewer'><span><em class='tooltip' data-tooltip='Removed for HIPAA compliance.'>Anonymous</em></span></span>";
									$_gravatar		= false;
								} else {
									$_gravatar		= $hide_gravatar == true ? @file_get_contents( 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( $review->review_meta->reviewer->reviewer_email ) ) ) . '?d=404&s=32') : @file_get_contents( 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( $review->review_meta->reviewer->reviewer_email ) ) ) . '?d=404&s=32');
									$gravatar		= 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( $review->review_meta->reviewer->reviewer_email ) ) ) . '?d=mm&s=70';
									$_author		= $hide_reviewer == true ? "" : "<span class='reviewer'> - <span><em>{$review->review_meta->reviewer->display_name}</em></span></span>";
								}


								$_stars			= "<div class='review-stars center'>
														<span>
															<span class='star medium'>$_good</span>
															<span class='dark-star medium'>$_bad</span>
														</span> $_author
													</div>";

								$_service		= !empty( $_reviewed->service ) ? 'Reviewing <strong>'. $_reviewed->service .'</strong>' : '';
								$_staff			= !empty( $_reviewed->staff->name ) ? ' provided by <strong>'. $_reviewed->staff->name .'</strong>' : '';
								$_location		= !empty( $_reviewed->location->name ) ? ' in <strong>'.  $_reviewed->location->name .'</strong>' : '';

								$_meta			= ( $hide_meta == true ) ? '' : '<div class="meta center">'. $_service . $_staff . $_location .'</div>';

								$content		= $_content . $_ellipses . $_more; ?>

								<li class="review-wrap review" style="list-style-type: none;">
									<div>
										<?php if($_gravatar != false) { ?>
											<div class="gravatar">
												<img src="<?php print( $gravatar ); ?>" />
											</div>
										<?php } ?>
										<h5 class="review-title"><strong><?php echo $_title; ?></strong></h5>
										<p class="review-content"><?php echo $content ?></p>
										<div class="synopsis center">
											<?php echo $_stars . $_meta; ?>
										</div>
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
?>
