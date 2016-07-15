<?php
	/**
	 * Redbrick Digital Core
	 *
	 * @package \lib\shortcodes
	 * @author  RedbrickDigital.net
	 * @license GPL-2.0+
	 * @since 0.2.1
	 *
	 *        (\ /)
	 *       ( . .) ♥ ~< Creates a columnar list of reviews on pages/posts! >
	 *       c(”)(”)
	*/

	/**
	 * Add Review Engine Shortcode Button to Add Media
	 *
	 * @since 0.2.2
	*/
	add_action( 'media_buttons', 'rbd_core_add_shortcode_button', 25 );
	function rbd_core_add_shortcode_button(){
		if( get_option( 'rbd_core_review_engine_url' ) ){
			$type	= 'button';
			$class	= "$type insert-review-engine-display add_review_engine";
			$icon	= '<span class="wp-media-buttons-icon"></span>';
			$label	= 'Add Review Engine Display';

			echo "<$type type='$type' id='insert-reviews-$type' class='$class' data-editor='content'>$icon $label</$type>";
		}
	}

	/**
	 * Add Popup to Footer
	 *
	 * @since 0.2.2
	 *
	 * @internal { Thickbox was being a whore, so we're using a custom popup
 	 *	instead. It works... }
	 *
	 * @uses rbd_core_api_call() - Returns object from Review Engine API.
	*/
	add_action( 'admin_footer', 'trm_faq_dropdowns_admin_cloud_x' );
	function trm_faq_dropdowns_admin_cloud_x(){ ?>
		<div class="rbd-core-ui">
			<div class="rbd-re-popup-cloud p-a bg-transparent-dark hidden">
				<div class="rbd-re-popup p-f m-0-a bg-white box-close">
					<form class="rbd-re-form">
						<div class="form-header p-sq-xl p-b-0 bg-gray-dark border-bottom-lighter">
							<h2 class="m-0 p-b-xl">Insert Review Engine Shortcode<a href="#" id="rbd-re-popup-close"><div class="tb-close-icon"></div></a></h2>
						</div>
						<div class="form-fields container p-sq-xxl p-t-xl p-b-xl">
							<div class="row input-container m-b-md">
								<div class="six columns">
									<label for="url">
										<span>URL:</span>
										<input type="text" name="url" id="url" class="wp-core-ui widefat" value="<?php echo rbd_core_url(); ?>" />
									</label>
								</div>
								<div class="six columns">
									<label for="title">
										<span>Title: <span class="tooltip-wrap"><span class="dashicons dashicons-warning"></span><span class="tooltip tooltip-top" data-tooltip="Leave empty to hide."></span></span></span>
										<input type="text" name="title" id="title" class="wp-core-ui widefat" value="What Our Customers Are Saying:" />
									</label>
								</div>
							</div>
							<div class="row input-container m-b-md">
								<div class="three columns">
									<label for="threshold">
										<span>Threshold:</span>
										<select name="threshold" id="threshold" class="wp-core-ui widefat">
											<option value="3" style="font-size: 24px;" selected="selected">★★★☆☆</option>
											<option value="4" style="font-size: 24px;">★★★★☆</option>
											<option value="5" style="font-size: 24px;">★★★★★</option>
										</select>
									</label>
								</div>
								<div class="three columns">
									<label for="characters">
										<span>Service:</span>
										<select id="service" name="service" class="wp-core-ui widefat">
											<option value="all">All</option>
											<?php foreach( rbd_core_api_call()->company[0]->taxonomies[0]->service[0] as $service_x ){
												$selected = ( $service == $service_x->slug ) ? 'selected="selected"' : '';
												echo "<option $selected value='$service_x->slug'>$service_x->name</option>";
											} ?>
										</select>
									</label>
								</div>
								<div class="three columns">
									<label for="characters">
										<span>Staff:</span>
										<select id="employee" name="employee" class="wp-core-ui widefat">
											<option value="all">All</option>
											<?php foreach( rbd_core_api_call()->company[0]->taxonomies[0]->employee[0] as $employee_x ){
												$selected = ( $employee == $employee_x->slug ) ? 'selected="selected"' : '';
												echo "<option $selected value='$employee_x->slug'>$employee_x->name</option>";
											} ?>
										</select>
									</label>
								</div>
								<div class="three columns">
									<label for="characters">
										<span>Location:</span>
										<select id="location" name="location" class="wp-core-ui widefat">
											<option value="all">All</option>
											<?php foreach( rbd_core_api_call()->company[0]->taxonomies[0]->location[0] as $location_x ){
												$selected = ( $location == $location_x->slug ) ? 'selected="selected"' : '';
												echo "<option $selected value='$location_x->slug'>$location_x->name</option>";
											} ?>
										</select>
									</label>
								</div>
							</div>
							<div class="row input-container m-b-md">
								<div class="four columns">
									<label for="characters">
										<span>Limit Characters:</span>
										<input type="number" name="characters" id="characters" class="wp-core-ui widefat" value="135" />
									</label>
								</div>
								<div class="four columns">
									<label for="perpage">
										<span>Reviews Per Page:</span>
										<select name="perpage" id="perpage" class="wp-core-ui widefat">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6" selected="selected">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="20">20</option>
										</select>
									</label>
								</div>
								<div class="four columns">
									<label for="columns">
										<span>Max Columns:  <span class="tooltip-wrap"><span class="dashicons dashicons-warning"></span><span class="tooltip tooltip-top" data-tooltip="Do you want 1, 2, or 3 columns of reviews?"></span></span></span>
										<select name="columns" class="wp-core-ui widefat">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3" selected="selected">3</option>
										</select>
									</label>
								</div>
							</div>
							<div class="row input-container m-b-md">
								<div class="four columns">
									<label for="hide-reviewer">
										<input type="checkbox" name="hide-reviewer" id="hide-reviewer" class="wp-core-ui widefat" value="true" />
										<span>Hide Reviewer?</span>
									</label>
								</div>
								<div class="four columns">
									<label for="hide-date">
										<input type="checkbox" name="hide-date" id="hide-date" class="wp-core-ui widefat" value="true" />
										<span>Hide Date?</span>
									</label>
								</div>
								<div class="four columns" style="color: #bbb;">
									<label for="hide-city-state">
										<input type="checkbox" disabled="disabled" name="hide-city-state" id="hide-city-state" class="wp-core-ui widefat" value="true" />
										<span>Hide City?</span>
									</label>
								</div>
							</div>
							<div class="row input-container m-b-md">
								<div class="four columns" style="color: #bbb;">
									<label for="hide-location">
										<input type="checkbox" disabled="disabled" name="hide-location" id="hide-location" class="wp-core-ui widefat" value="true" />
										<span>Hide Location?</span>
									</label>
								</div>
								<div class="four columns" style="color: #bbb;">
									<label for="hide-staff">
										<input type="checkbox" disabled="disabled" name="hide-staff" id="hide-staff" class="wp-core-ui widefat" value="true" />
										<span>Hide Staff?</span>
									</label>
								</div>
								<div class="four columns" style="color: #bbb;">
									<label for="hide-category">
										<input type="checkbox" disabled="disabled" name="hide-category" id="hide-category" class="wp-core-ui widefat" value="true" />
										<span>Hide Category?</span>
									</label>
								</div>
							</div>
							<div class="row input-container">
								<div class="twelve columns">
									<label style="font-weight:600;" class="d-ib m-t-xl m-b-sm">Advanced Options:</label>
								</div>
							</div>
							<div class="row">
								<div class="four columns">
									<label for="enable-ajax" style="color: #bbb;">
										<input type="checkbox" disabled="disabled" name="enable-ajax" id="enable-ajax" class="wp-core-ui widefat" value="true" />
										<span>Enable Ajax? <span class="tooltip-wrap"><span class="dashicons dashicons-warning"></span><span class="tooltip tooltip-right" data-tooltip="Javascript will load in more reviews dynamically."></span></span></span>
									</label>
								</div>
								<div class="four columns">
									<label for="hide-overview">
										<input type="checkbox" name="hide-overview" id="hide-overview" class="wp-core-ui widefat" value="true" />
										<span>Hide Overview? <span class="tooltip-wrap"><span class="dashicons dashicons-warning"></span><span class="tooltip tooltip-right" data-tooltip="Hide the subheading with aggregate score information."></span></span></span>
									</label>
								</div>
								<div class="four columns">
									<label for="disable-css">
										<input type="checkbox" name="disable-css" id="disable-css" class="wp-core-ui widefat" value="true" />
										<span>Disable CSS? <span class="tooltip-wrap"><span class="dashicons dashicons-warning"></span><span class="tooltip tooltip-right" data-tooltip="Disables some non-layout CSS."></span></span></span>
									</label>
								</div>
							</div>
							<div class="row input-container" style="overflow: hidden !important;">
								<div class="twelve columns">
									<button type="submit" class="right wp-core-ui button-primary m-t-xl m-b-xs" id="submit">Insert Into <?php echo ucwords( get_current_screen()->post_type ); ?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php }

	/**
	 * Add and Parse Shortcode
	 *
	 * @since 0.2.2
	 *
	 * @uses rbd_core_url() - Returns Review Engine URL w/ or w/o API string.
	*/
	add_shortcode( 'rbd_review_engine', 'rbd_core_review_engine_display_shortcode' );
    function rbd_core_review_engine_display_shortcode( $atts ){
        extract( shortcode_atts( array(
            'placeholder' => ''
        ), $atts ) );

		# Decode all parameters from shortcode
        $title			= urldecode( $atts['title'] );
        $perpage	    = urldecode( $atts['perpage'] );
        $columns	    = urldecode( $atts['columns'] );
		$category		= urldecode( $atts['category'] );
        $hide_date		= urldecode( $atts['hide-date'] );
        $characters		= urldecode( $atts['characters'] );
        $hide_staff		= urldecode( $atts['hide-staff'] );
        $enable_ajax	= urldecode( $atts['enable-ajax'] );
        $disable_css    = urldecode( $atts['disable-css'] );
        $hide_reviewer	= urldecode( $atts['hide-reviewer'] );
        $hide_location	= urldecode( $atts['hide-location'] );
        $hide_category	= urldecode( $atts['hide-category'] );
        $hide_overview	= urldecode( $atts['hide-overview'] );
        $hide_citystate	= urldecode( $atts['hide-city-state'] );

		# Nomenclature has been messed up between API and Access, so we need Service AND Category...
		$tax_array = array(
			'service'			=> 'category',
			'employee'			=> 'employee',
			'location'			=> 'location',
			'threshold'			=> 'threshold',
			'reviews_per_page'	=> 'reviews_per_page'
		);

		foreach( $tax_array as $key => $val ){
			${$key} = ( empty( $atts[$key] ) || $atts[$key] == 'all' ) ? '' : "&$val=".urldecode( $atts[$key] );
		}

		$count 			= $row = 0;
		$arrow			= ' <i class="fa fa-angle-right"></i>';
		$api_url		= rbd_core_url( true )."$threshold$reviews_per_page$service$location$employee";
        $button_classes = ( $disable_css == true ) ? 'button button-primary btn read-more' : 'rbd-button';
		$transient_salt	= 'review_engine_transient-'. get_the_title() . get_the_modified_time( 'U' );

		# Turn API Query from shortcode into a transient saved object
        if ( false === ( $review_engine_transient = get_transient( $transient_salt ) ) ){
            $review_engine_transient = @file_get_contents( $api_url );
            set_transient( $transient_salt, $review_engine_transient, 86400 );
        }
		$data_object 	= json_decode( get_transient( $transient_salt ) );

		$url			= rbd_core_url();
		$title			= ( $title == '' ) ? '' : "<h2 class='re-display-title'>$title</h2>";
		$overview		= ( $hide_overview == true ) ? '' : "
			<div class='re-header'>
				<h3 class='overview dib'>
					<span class='aggregate'><strong>{$data_object->data[0]->aggregate}</strong></span><span class='star'>★</span>/<strong>5</strong> based on <strong><span class='review-count'>{$data_object->data[0]->total_reviews}</span> reviews</strong>
				</h3>
				<a style='position: relative; top: -3px;' class='dib write-review big $button_classes' target='_blank' href='$url'><span class='_label'>Write a Review</span>$arrow</a>
			</div>"; ?>

        <div class="rbd-core-ui" itemscope itemtype="http://schema.org/LocalBusiness">
            <?php
				echo $title;
				echo $overview;
			?>
            <div class="reviews-container">
                <div class="reviews row">
                    <?php if( !empty( $data_object->reviews ) ){
						foreach( $data_object->reviews as $review ){
                                if( $review->status == 'publish' ){
									$row++;
                                    $count++;

                                    $col_class  =  ( $columns == 1 ? 'col-lg-12' :
                                                        ( $columns == 2 ? 'col-lg-6 col-sm-12' :
                                                            ( $columns == 3 ? 'col-lg-4 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-12' ) ) );

                                    $meta_date  = ( $hide_date == true ) ? '<meta itemprop="datePublished" content="'. $review->review_meta->review_date->timestamp .'" />' : ' on <strong><meta itemprop="datePublished" content="'. $review->review_meta->review_date->timestamp .'" />'. $review->review_meta->review_date->date .'</strong>';
                                    $meta_name  = ( $hide_reviewer == true ) ? '<span itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="Alexander D." /></span>' : ' by <strong><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">'. $review->review_meta->reviewer->display_name .'</span></strong>';
                                    $meta       = ( $hide_date == true || $hide_reviewer == true ) ? '<span>Reviewed'.$meta_name.$meta_date.'</span>' : '<p>Reviewed'.$meta_name.$meta_date.'</p>';

                                    $magic		= intval( substr( $review->rating, 0, 1 ) );
                                    $stars		= '<span class="review-stars"><span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><meta itemprop="ratingValue" content="'. $magic .'" /><meta itemprop="bestRating" content="5" /><span class="star">'. str_repeat( '★', $magic ) .'</span><span class="dark-star">'. str_repeat( '★', 5 - $magic ) .'</span></span></span>';

                                    $ellipses	= strlen( $review->content ) > $characters ? '...' : '';
                                    $read_more	= $ellipses != '' ? "<div class='_readmore'><a href='{$review->url}' target='_blank' class='$button_classes right' data-attr='Read More'><span class='_label'>Read More</span>$arrow</a></div>" : '';
									$reviewbody	= '<span itemprop="reviewBody">'.substr( $review->content, 0, $characters ).'</span>';

									if( $count > $perpage ){
										break;
									}
                                ?>
                                <div itemscope itemtype="http://schema.org/Review" class="<?php echo "review $col_class"; ?>" data-attr="<?php echo $count; ?>">
									<meta itemprop="itemReviewed" content="<?php echo $data_object->company[0]->name; ?>" />
									<p itemprop="name" class="_title"><?php echo $review->title; ?></p>
                                    <?php echo "<p class='_meta'>$meta</p>"; ?>
                                    <p class="_content"><?php echo $stars.$reviewbody.$ellipses.$read_more; ?></p>
									<span itemprop="publisher" itemscope itemtype="http://schema.org/WebPage">
										<meta itemprop="url" content="<?php echo $data_object->data[0]->engine; ?>">
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
                            }
                        }
					} ?>
                </div>
                <?php if( $data_object->data[0]->total_reviews > $data_object->data[0]->returned_reviews ){ ?>
                    <div class="text-center row re-footer">
                        <?php /* TODO: Make these buttons AJAX enabled, instead of linking to the review engine */ ?>
                        <a <?php /*href="#" */ ?> href="<?php echo $data_object->data[0]->review_engine_url; ?>all-reviews" target="_blank" class="showmore center <?php echo $button_classes; ?> big paginated" data-per-page="<?php echo $perpage; ?>" data-loop-count="1" data-treshold="<?php echo $threshold; ?>" data-category="<?php echo $category; ?>"><span class='_label'>Show More Reviews</span><?php echo $arrow; ?></a>
                        <!-- <a href="#" class="hidemore center <?php echo $button_classes; ?> big paginated hide">Reset Review List</a>-->
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }
?>
