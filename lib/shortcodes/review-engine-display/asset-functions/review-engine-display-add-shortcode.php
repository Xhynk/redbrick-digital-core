<?php
	/**
	 * Add and Parse Shortcode
	 *
	 * @since 0.2.2
	 *
	 * @uses rbd_core_url() - Returns Review Engine URL w/ or w/o API string.
	*/
	add_shortcode( 'rbd_review_engine', '_review_engine_display_add_shortcode' );
	function _review_engine_display_add_shortcode( $atts ){
		# Placeholder Parsing
		extract( shortcode_atts( array(
			'placeholder' => ''
		), $atts ) );

		# Decode Non-Query Shortcode Parameters
		$decoded_attr = array (
			'title',
			'columns',
			'category',
			'hide_date',
			'characters',
			'hide_staff',
			'enable_ajax',
			'disable_css',
			'hide_reviewer',
			'hide_location',
			'hide_category',
			'hide_overview',
			'hide_citystate'
		);

		foreach( $decoded_attr as $attr ){
			${$attr} = urldecode( $atts[$attr] );
		}

		# API Query Parameters
		$query_params	= array(
			# Note: Nomenclature is messed up, we swap between service and category :(
			'service'			=> 'category',
			'employee'			=> 'employee',
			'location'			=> 'location',
			'threshold'			=> 'threshold',
			'perpage'			=> 'reviews_per_page',
			'reviews_per_page'	=> 'reviews_per_page',
		);

		foreach( $query_params as $key => $val ){
			${$key} = ( empty( $atts[$key] ) || $atts[$key] == 'all' ) ? '' : "&$val=".urldecode( $atts[$key] );
		}

		# Turn API Query from shortcode into a transient saved object
		$_url		= rbd_core_url( true );
		$_key		= get_the_ID();
		$_salt		= "rbd_core_shortcode_review_engine_display-$_key";
		$api_url	= $_url . $threshold . $reviews_per_page . $service . $location . $employee;

		if( false === ( $transient = get_transient( $_salt ) ) ){
			$transient = @file_get_contents( $api_url );
			set_transient( $_salt, $transient, 86400 );
		}
		$api_object	= json_decode( get_transient( $_salt ) );

		# Define Standard Variables
		$count = $row = 0;

		$arrow			= ' <i class="fa fa-angle-right"></i>';
		$link			= rbd_core_url();
		$button_classes = ( $disable_css == true ) ? 'button button-primary btn read-more' : 'rbd-button';

		# Define Snips of HTML
		$title		= ( $title == '' ) ? '' : "<h2 class='re-display-title'>$title</h2>";
		$overview	= ( $hide_overview == true ) ? '' : "
			<div class='re-header'>
				<h3 class='overview dib'>
					<span class='aggregate'><strong>{$api_object->data[0]->aggregate}</strong></span><span class='star'>★</span>/<strong>5</strong> based on <strong><span class='review-count'>{$api_object->data[0]->total_reviews}</span> reviews</strong>
				</h3>
				<a style='position: relative; top: -3px;' class='dib write-review big $button_classes' target='_blank' href='{$api_object->data[0]->review_engine_url}'><span class='_label'>Write a Review</span>$arrow</a>
			</div>";

			ob_start(); ?>
			<div class='rbd-core-ui review-engine-display' itemscope itemtype="http://schema.org/LocalBusiness">
				<?php echo $title; ?>
				<?php echo $overview; ?>
				<div class="reviews-container">
					<div class="reviews row">
						<?php if( !empty( $api_object->reviews ) ){
							foreach( $api_object->reviews as $review ){
								if( $review->status == 'publish' ){ ?>
									<?php
										# Increase Tally of Rows/Counts
										$row++;
										$count++;

										$classes	= 'review';
										$_rating	= intval( substr( $review->rating, 0, 1 ) );
										$_bad		= str_repeat( '★', 5 - $_rating );
										$_good		= str_repeat( '★', $_rating );
										$_content	= substr( $review->content, 0, $characters );
										$_ellipses	= strlen( $review->content ) > $characters ? '...' : '';
										$_more		= strlen( $review->content ) > $characters ? "<div class='_readmore'>
																										<a href='{$review->url}' target='_blank' class='$button_classes right' data-attr='Read More'><span class='_label'>Read More</span>$arrow</a>
																									</div>" : '';

										$_stars		= "<span class='review-stars'>
															<span itemprop='reviewRating' itemscope itemtype='http://schema.org/Rating'>
																<meta itemprop='ratingValue' content='$_rating' />
																<meta itemprop='bestRating' content='5' />
																<span class='star'>$_good</span>
																<span class='dark-star'>$_bad</span>
															</span>
														</span>";

										$_reviewbody= "<span itemprop='reviewBody'>$_content</span>";
										$content	= '<p class="_content">'. $_stars . $_reviewbody . $_ellipses . $_more .'</p>';

										$classes	.=  ( $columns == 1 ? ' col-lg-12' :
															( $columns == 2 ? ' col-lg-6 col-sm-12' :
																( $columns == 3 ? ' col-lg-4 col-md-6 col-sm-12' : ' col-lg-4 col-md-6 col-sm-12' ) ) );

										$meta_date	= ( $hide_date == true ) ? "<meta itemprop='datePublished' content='{$review->review_meta->review_date->timestamp}' />"
																			: " on <strong><meta itemprop='datePublished' content='{$review->review_meta->review_date->timestamp}' />{$review->review_meta->review_date->date}</strong>";

										$meta_name	= ( $hide_reviewer == true ) ? "<span itemprop='author' itemscope itemtype='http://schema.org/Person'><meta itemprop='name' content='{$review->review_meta->reviewer->display_name}' /></span>"
																				: " by <strong><span itemprop='author' itemscope itemtype='http://schema.org/Person'><span itemprop='name'>{$review->review_meta->reviewer->display_name}</span></strong>";

										$meta		= ( $hide_date == true || $hide_reviewer == true ) ? $meta_name.$meta_date
																									: '<p class="_meta">Reviewed'. $meta_name . $meta_date .'</p>';
									?>
									<div itemscope itemtype="http://schema.org/Review" class="<?php echo $classes; ?>" data-attr="<?php echo $count; ?>">
										<meta itemprop="itemReviewed" content="<?php echo $api_object->company[0]->name; ?>" />
										<p itemprop="name" class="_title"><?php echo $review->title; ?></p>
										<?php
											echo $meta;
											echo $content;
										?>
										<span itemprop="publisher" itemscope itemtype="http://schema.org/WebPage">
											<meta itemprop="url" content="<?php echo $api_object->data[0]->engine; ?>">
										</span>
									</div>
									<?php
										if( $columns == 3 || empty( $columns ) ){
											echo ( $row % 2 == 0 ) ? '<div class="clearfix visible-md-block"></div>' : '';
											echo ( $row % 3 == 0 ) ? '<div class="clearfix visible-lg-block"></div>' : '';
											echo '<div class="clearfix visible-sm-block visible-xs-block"></div>';
										} else if( $columns == 2 ){
											echo ( $row % 2 == 0 ) ? '<div class="clearfix visible-lg-block"></div>' : '<div class="clearfix visible-md-block visible-sm-block visible-xs-block"></div>';
										} else {
											echo '<div class="clearfix"></div>';
										}
									?>
								<?php }
							}
						} ?>
					</div>
					<?php if( $api_object->data[0]->total_reviews > $api_object->data[0]->returned_reviews ){ ?>
						<div class="text-center row re-footer">
							<?php /* TODO: Make these buttons AJAX enabled, instead of linking to the review engine */ ?>
							<a <?php /*href="#" */ ?> href="<?php echo $api_object->data[0]->review_engine_url; ?>all-reviews" target="_blank" class="showmore center <?php echo $button_classes; ?> big paginated" data-per-page="<?php echo $perpage; ?>" data-loop-count="1" data-treshold="<?php echo $threshold; ?>" data-category="<?php echo $category; ?>"><span class='_label'>Show More Reviews</span><?php echo $arrow; ?></a>
							<!-- <a href="#" class="hidemore center <?php echo $button_classes; ?> big paginated hide">Reset Review List</a>-->
						</div>
					<?php } ?>
				</div>
			</div>
		<?php
		$ob = ob_get_contents();
		ob_end_clean();

		return $ob;
	}
?>
