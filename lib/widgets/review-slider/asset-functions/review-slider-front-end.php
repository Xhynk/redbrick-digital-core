<?php
	wp_enqueue_style( 'widget-review-slider' );
	wp_enqueue_script( 'widget-review-slider' );

	/*add_action( 'wp_footer', function(){
		RBD_core::display_popup();
	});*/

	foreach( rbd_core_review_slider_options() as $var => $default_value )
		${$var} = apply_filters( 'widget_'.$var, $instance[$var] );

	# Backwards Compatibility
	$characters = !empty( $character_count ) ? $character_count : $characters;

	#Query Parameters
	$query_params	= array(
		# Nomenclature is messed up service is actually category.
		'service'          => 'category',
		'employee'         => 'employee',
		'location'         => 'location',
		'threshold'        => 'threshold',
		'reviews_per_page' => 'reviews_per_page',
		'perpage'          => 'reviews_per_page',
	);

	# Turn API Query from shortcode into a transient saved object
	$url            = 'https://'. str_replace( ['http://', 'https://'], '', $url );
	$api_url        = RBD_Core::rbd_core_url( true, $url );
	$transient_name = RBD_Core::rbd_transient_salt( $args['widget_id'], 'rbd-review-slider' );

	foreach( $query_params as $key => $val )
		$api_url .= ( empty( ${$key} ) || ${$key} == 'all' ) ? '' : "&$val=". urldecode( ${$key} );

	# Check Transient and make sure it's a valid request
	$transient = get_transient( $transient_name );
	if( false === $transient || strlen( $transient ) < 69 )
		set_transient( $transient_name, wp_remote_retrieve_body( wp_remote_get( $api_url ) ), 86400 );

	$json  = json_decode( get_transient( $transient_name ) );
	$data  = $json->data[0];
	$count = 0;

	# Before/After Widget Defined By Theme
	echo $args['before_widget']; ?>
		<?php echo !empty( $title ) ? $args['before_title'] . $title . $args['after_title'] : ''; ?>
		<?php if( $data->returned_reviews > 0 ){ ?>
			<div class="rbd-core-ui">
				<div class="rbd-review-slider" data-speed="<?= $slider_speed * 1000; ?>">
					<?php foreach( $json->reviews as $review ){ ?>
						<?php
							if( $hide_meta != true ){
								$meta['reviewer'] = ( $hide_reviewer == true || defined( 'RBD_HIPAA_COMPLIANCE' ) ) ? '' : "by {$review->review_meta->reviewer->display_name}";
								$meta['date']     = ( $hide_date == true ) ? '' : "on {$review->review_meta->review_date->date}";
							}
							$count++;

							if( $count == 1 ) $start_class = 'rbd-curr';
							else if( $count == 2 ) $start_class = 'rbd-next';
							else $start_class = '';
						?>
						<div class="rbd-review <?= $start_class ?> ">
							<h3 class="rbd-title"><?= $review->title; ?></h3>
							<i class="rbd-score renderSVG" data-icon="star" data-repeat="5" data-score="<?= $review->rating; ?>"></i>
							<div class="rbd-content">
								<?php if( !defined( 'RBD_HIPAA_COMPLIANCE' ) && $hide_gravatar != true && $hide_reviewer != true ){
									echo ( $review->review_meta->reviewer->gravatar != null ) ? '<img class="rbd-gravatar" src="'. $review->review_meta->reviewer->gravatar .'" />' : '';
								} ?>
								<?= // Echo Review Content. Trimmed string is shortened by the word
									strlen( strip_tags( $review->content ) ) > $characters ? // if longer than defined
									'<span class="rbd-content-limit">'. substr( $review->content, 0, strpos( $review->content, ' ', $characters ) ) .'…</span>' : // Trim and display Read More
									'<span class="rbd-content-limit">'. $review->content .'</span>'; // Otherwise show full review content
								?>
							</div>
							<div class="rbd-footing">
								<a class="rbd-button rbd-small" href="<?= $review->url; ?>" target="_blank">Read More</a>
								<!--<a href="#" class="rbd-button rbd-small" data-more="<?= str_replace( substr( $review->content, 0, strpos( $review->content, ' ', $characters ) ), '', $review->content ); ?>">Read More</a>-->
							</div>
							<div class="rbd-review-meta"><?= !empty( $meta ) ? 'Written '. join( ' ', $meta ) : ''; ?></div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } else { ?>
			<div class="rbd-core-ui">
				<div class="rbd-review-slider">
					<div class="rbd-review rbd-curr">
						<h3 class="rbd-heading">No Reviews Found</h3>
						<div class="rbd-content">Please adjust your review query.</div>
					</div>
				</div>
			</div>
		<?php }?>
	<?php echo $args['after_widget'];
?>
