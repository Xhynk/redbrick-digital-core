<?php
	// Add Review Engine Shortcode "Add Media" button
	add_action( 'media_buttons', 'rbd_core_add_review_engine_button', 25 );
	function rbd_core_add_review_engine_button(){
		if( get_option('rbd_core_review_engine_url') ){
			echo '<button type="button" id="insert-reviews-button" class="button insert-review-engine-display add_review_engine" data-editor="content"><span class="wp-media-buttons-icon"></span> Add Review Engine Display</button>';
		}
	}

	// Pop Up after clicking "Add Review Engine Display" Button
	add_action( 'admin_footer', 'trm_faq_dropdowns_admin_cloud_x' );
	function trm_faq_dropdowns_admin_cloud_x(){
		$pre_data			= rbd_core_api_call();
		$current_user		= wp_get_current_user(); ?>

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
											<?php foreach( $pre_data->company[0]->taxonomies[0]->service[0] as $service_x ){
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
											<?php foreach( $pre_data->company[0]->taxonomies[0]->employee[0] as $employee_x ){
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
											<?php foreach( $pre_data->company[0]->taxonomies[0]->location[0] as $location_x ){
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

	add_shortcode( 'rbd_review_engine', 'rbd_core_review_engine_display_shortcode' );
    function rbd_core_review_engine_display_shortcode( $atts ){
        extract( shortcode_atts( array(
            'placeholder' => ''
        ), $atts ) );

        $url			= 'http://'. str_replace( array( 'http://', 'https://' ), '', urldecode( $atts['url'] ) );
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

		$threshold		= ( !empty( $atts['threshold'] ) ) ? '&threshold='.urldecode( $atts['threshold'] ) : '';

		$service		= ( empty( $atts['service'] ) || $atts['service'] == 'all' ) ? '' : '&category='.urldecode( $atts['service'] );
		$employee		= ( empty( $atts['employee'] ) || $atts['employee'] == 'all' ) ? '' : '&employee='.urldecode( $atts['employee'] );
		$location		= ( empty( $atts['location'] ) || $atts['location'] == 'all' ) ? '' : '&location='.urldecode( $atts['location'] );

        $button_classes = ( $disable_css == true ) ? 'button button-primary btn read-more' : 'rbd-button';

		$api_url		= rbd_core_url( true )."$threshold$perpage$service$location$employee";

		$transient_salt	= 'review_engine_transient-'. get_the_title() . get_the_modified_time( 'U' );

        if ( false === ( $review_engine_transient = get_transient( $transient_salt ) ) ){
            $review_engine_transient = @file_get_contents( $api_url );
            set_transient( $transient_salt, $review_engine_transient, 86400 );
        }

        $count = $row = 0;

        $data_object = json_decode( get_transient( $transient_salt ) ); ?>

        <div class="rbd-core-ui" itemscope itemtype="http://schema.org/LocalBusiness">
            <?php echo ( ($title !== '') ? '<h2 class="re-display-title">'.$title.'</h2>' : ''); ?>
			<?php echo ( ($hide_overview !== true) ?
				'<div class="re-header">
				<h3 class="overview dib">
				<span class="aggregate"><strong>'. $data_object->data[0]->aggregate. '</strong></span><span class="star">★</span>/<strong>5</strong> based on <strong><span class="review-count">'. $data_object->data[0]->count .'</span> reviews</strong>
				</h3>
				<a style="position: relative; top: -3px;" class="dib write-review '. $button_classes .' big" target="_blank" href="'. $url .'">Write a Review</a>
				</div>' : '' );
			?>
            <div class="row reviews-container">
                <div class="reviews col-lg-12">
                    <div class="row">
                        <?php if( !empty( $data_object->reviews ) ){
							foreach( $data_object->reviews as $review ){
	                                if( $review->status == 'publish' ){
	                                    $count++;
	                                    $row++;

	                                    $col_class  =  ( $columns == 1 ? 'col-lg-12' :
	                                                        ( $columns == 2 ? 'col-lg-6 col-sm-12' :
	                                                            ( $columns == 3 ? 'col-lg-4 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-12' ) ) );

	                                    $meta_date  = ( $hide_date == true ) ? '' : ' on <strong>'. $review->review_meta->review_date->date .'</strong>';
	                                    $meta_name  = ( $hide_reviewer == true ) ? '' : ' by <strong>'. $review->review_meta->reviewer->display_name .'</strong>';
	                                    $meta       = ( $hide_date == true || $hide_reviewer == true ) ? '' : 'Reviewed'.$meta_name.$meta_date ;

	                                    $magic		= intval( substr( $review->rating, 0, 1 ) );
	                                    $stars		= '<span class="review-stars"><span class="star">'. str_repeat( '★', $magic ) .'</span><span class="dark-star">'. str_repeat( '★', 5 - $magic ) .'</span></span>';

	                                    $ellipses	= strlen( $review->content ) > $characters ? '...' : '';
	                                    $read_more	= $ellipses != '' ? '<div><a href="'. $review->url .'" target="_blank" class="'. $button_classes .' right" data-attr="Read More">Read More</a></div>' : '';
	                                    $visibility	= $count <= $perpage ? 'show' : '';
										if( $count > $perpage ){
											break;
										}
	                                ?>
	                                <div itemscope itemtype="http://schema.org/Review" class="review <?php echo $col_class; ?> <?php echo $visibility; ?>" data-attr="<?php echo $count; ?>">
	                                    <p class="_title"><?php echo $review->title; ?></p>
	                                    <?php if( !empty( $meta) ){ echo '<p class="_meta">'. $meta .'</p>'; } ?>
	                                    <p class="_content"><?php echo $stars.substr( $review->content, 0, $characters ).$ellipses.$read_more; ?></p>
	                                </div>
	                            <?php
	                                if( $columns == 3 || empty( $columns ) ){
	                                    if( $row % 2 == 0 ) { echo '<div class="clearfix visible-md-block"></div>'; }
	                                    if( $row % 3 == 0 ) { echo '<div class="clearfix visible-lg-block"></div>'; }
	                                } else if( $columns == 2 ){
	                                    if( $row % 2 == 0 ) {
	                                        echo '<div class="clearfix visible-lg-block"></div>';
	                                    } else {
	                                        echo '<div class="clearfix visible-md-block"></div>';
	                                    }
	                                } else {
	                                    echo '<div class="clearfix"></div>';
	                                }
	                            }
	                        }
						} ?>
                    </div>
                </div>
                <?php if( $data_object->data[0]->total_reviews > $data_object->data[0]->returned_reviews ){ ?>
                    <div class="text-center row re-footer">
                        <?php /* TODO: Make these buttons AJAX enabled, instead of linking to the review engine */ ?>
                        <a <?php /*href="#" */ ?> href="<?php echo $data_object->data[0]->review_engine_url; ?>all-reviews" target="_blank" class="showmore center <?php echo $button_classes; ?> big paginated" data-per-page="<?php echo $perpage; ?>" data-loop-count="1" data-treshold="<?php echo $threshold; ?>" data-category="<?php echo $category; ?>">Show More Reviews</a>
                        <!-- <a href="#" class="hidemore center <?php echo $button_classes; ?> big paginated hide">Reset Review List</a>-->
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }
?>
