<?php
	add_shortcode( 'rbd_review_engine', function( $atts ){
		extract( shortcode_atts( array(
			'placeholder'   => '',
			'threshold'	    => 4,
			'service'		=> 'all',
			'employee'		=> 'all',
			'location'		=> 'all',
			'characters'	=> 135,
			'perpage'		=> 8,
			'columns'		=> 2,
			'hide_reviewer' => false,
			'hide_gravatar'	=> false,
			'hide_date'		=> false,
			'hide_overview' => false,
		), $atts ) );

		add_action( 'wp_footer', function(){
			$this->display_popup( null, ['rbd-review-engine-display'] );
		});

		# Scripts and Styles are registered in the main plugin file
		# but don't need to be enqueued unless the shortcode is run.
		wp_enqueue_style( 'shortcode-review-engine-display' );
		wp_enqueue_script( 'shortcode-review-engine-display' );

		# Define vars so we can use $key instead of $atts['key']
		foreach( $atts as $key => $val ) ${str_replace('-', '_', $key)} = $val;

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
		$api_url        = $this->rbd_core_url( true, $url );

		$transient_id   = isset( $transient_id ) ? $transient_id : get_the_ID();
		$transient_name = $this->rbd_transient_salt( $transient_id, 'rbd-review-engine-display' );

		foreach( $query_params as $key => $val )
			$api_url .= ( empty( ${$key} ) || ${$key} == 'all' ) ? '' : "&$val=". urldecode( ${$key} );

		# Check Transient and make sure it's a valid request
		$transient = get_transient( $transient_name );
		if( false === $transient || strlen( $transient ) < 69 )
			set_transient( $transient_name, wp_remote_retrieve_body( wp_remote_get( $api_url ) ), 86400 );

		$json = json_decode( get_transient( $transient_name ) );

		# Define API Information from Review Engine and initial load information
		$review_percents = $urls = [];
		
		$offset  = ( $perpage ) ? $perpage : $reviews_per_page;
		$data    = $json->data[0];
		$company = $json->company[0];

		$urls['all-reviews']    = $url.'/all-reviews/';
		$urls['write-a-review'] = $data->review_funnels->advanced_review_funnels == true ? $data->review_funnels->review_funnel_url : $url;

		$review_percents['5'] = ['percent' => round( ( $data->five_star_reviews  / $data->total_reviews ) * 100 ), 'count' => $data->five_star_reviews];
		$review_percents['4'] = ['percent' => round( ( $data->four_star_reviews  / $data->total_reviews ) * 100 ), 'count' => $data->four_star_reviews];
		$review_percents['3'] = ['percent' => round( ( $data->three_star_reviews / $data->total_reviews ) * 100 ), 'count' => $data->three_star_reviews];
		$review_percents['2'] = ['percent' => round( ( $data->two_star_reviews   / $data->total_reviews ) * 100 ), 'count' => $data->two_star_reviews];
		$review_percents['1'] = ['percent' => round( ( $data->one_star_reviews   / $data->total_reviews ) * 100 ), 'count' => $data->one_star_reviews];

		$aggregate_percent = $data->aggregate * 20;

		ob_start(); ?>

		<div class="rbd-core-ui rbd-review-engine-display <?= $disable_3d ? '' : 'rbd-3d-effects'; ?>" data-review-engine-url="<?= $url; ?>">
		    <?php if( $hide_overview != true ){ ?>
			    <h2 class="rbd-header">
					<span class="rbd-aggregate"><?= $data->aggregate; ?></span>
					<span class="rbd-aggregate-container">
						<span class="rbd-earned" style="width: <?= $aggregate_percent; ?>%;">
							<i class="rbd-score renderSVG" data-icon="star" data-repeat="5"></i>
						</span>
						<span>
							<i class="rbd-score renderSVG" data-icon="star" data-repeat="5"></i>
						</span>
					</span>
					<span class="rbd-normal rbd-review-count">(<?= $data->total_reviews; ?>)</span>
					<button class="rbd-view-breakdown">View Rating Breakdown</button>
				</h2>
				<div class="rbd-breakdown-container">
					<div class="rbd-bar-container">
						<?php foreach( $review_percents as $rating => $percent ){ ?>
							<div><i class="rbd-score renderSVG" data-icon="star" data-repeat="5" data-score="<?= $rating; ?>"></i><div class="rbd-bar" style="--width: <?= $percent['percent']; ?>%;"></div><span class="rbd-percent"><?= $percent['percent']; ?>%</span> <span class="rbd-percent rbd-count">(<?= $percent['count']; ?>)</span></div>
						<?php } ?>
					</div>
					<div class="rbd-links-container" data-grid="grid" data-columns="2">
						<div class="rbd-center">
							<a target="_blank" href="<?= $urls['write-a-review']; ?>" class="rbd-button rbd-small">Write a Review</a>
						</div>
						<div class="rbd-center">
							<a target="_blank" href="<?= $urls['all-reviews']; ?>" class="rbd-button rbd-small rbd-secondary">Read All Reviews</a>
						</div>
					</div>
				</div>
			<?php } ?>
			<section class="rbd-review-grid" data-grid="grid" data-columns="<?= $columns; ?>" data-max="<?= $data->total_reviews; ?>">
				<?php foreach( $json->reviews as $review ){ ?>
					<?php
						$meta['reviewer'] = ( $hide_reviewer == true || defined( 'RBD_HIPAA_COMPLIANCE' ) ) ? 'by Anonymous' : "by {$review->review_meta->reviewer->display_name}";
						$meta['date']     = ( $hide_date == true ) ? '' : "on {$review->review_meta->review_date->date}";
					?>
					<div class="rbd-review" data-meta="<?= !empty( $meta ) ? 'Written '. join( ' ', $meta ) : ''; ?>" data-permalink="<?= $review->url; ?>">
						<h3 class="rbd-heading"><?= $review->title; ?></h3>
						<i class="rbd-score renderSVG" data-icon="star" data-repeat="5" data-score="<?= $review->rating; ?>"></i>
						<p class="rbd-content">
							<?php if( !defined( 'RBD_HIPAA_COMPLIANCE' ) && $hide_gravatar != true ){
								echo ( $review->review_meta->reviewer->gravatar != null ) ? '<img class="rbd-gravatar" src="'. $review->review_meta->reviewer->gravatar .'" />' : '';
							} ?>
							<?= // Echo Review Content. Trimmed string is shortened by the word
								strlen( strip_tags( $review->content ) ) > $characters ? // if longer than defined
								'<span class="rbd-content-limit">'. substr( $review->content, 0, strpos( $review->content, ' ', $characters ) ) .'…</span><a href="#" data-more="'.str_replace( substr( $review->content, 0, strpos( $review->content, ' ', $characters ) ), '', $review->content ).'">Read More</a>' : // Trim and display Read More
								'<span class="rbd-content-limit">'. $review->content .'</span>'; // Otherwise show full review content
							?>
						</p>
						</div>
				<?php } ?>
			</section>
			<?php if( $data->total_reviews > $offset ) { ?>
				<button class="rbd-load-more" data-hide-gravatars="<?= $hide_gravatar || defined( 'RBD_HIPAA_COMPLIANCE' ) ? 'true' : 'false'; ?>" <?php foreach( $atts as $key => $val ) echo "data-$key=\"$val\""; ?> data-offset="<?= $offset; ?>">Load More Reviews</button>
			<?php } ?>
		<div>

		<?php $ob = ob_get_contents();
		ob_end_clean();

		return $ob;
	});
?>
