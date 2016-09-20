<?php
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
	add_action( 'admin_footer', '_review_engine_display_dropdown_cloud' );
	function _review_engine_display_dropdown_cloud(){ ?>
		<div class="rbd-core-ui">
			<div class="rbd-re-popup-cloud p-a bg-transparent-dark hidden">
				<div class="rbd-re-popup p-f m-0-a bg-white box-close">
					<form class="rbd-re-form">
						<div class="form-header p-sq-xl p-b-0 bg-gray-dark border-bottom-lighter">
							<h2 class="m-0 p-b-xl">Insert Review Engine Shortcode<a href="#" id="rbd-re-popup-close"><div class="tb-close-icon"></div></a></h2>
						</div>
						<div class="form-fields container p-sq-xxl p-t-xl p-b-xl">
							<?php rbd_core_hipaa_warning(); ?>
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
									<label for="hide-gravatar">
										<input type="checkbox" name="hide-gravatar" id="hide-gravatar" class="wp-core-ui widefat" value="true" />
										<span>Hide Gravatar?</span>
									</label>
								</div>
								<div class="four columns">
									<label for="hide-date">
										<input type="checkbox" name="hide-date" id="hide-date" class="wp-core-ui widefat" value="true" />
										<span>Hide Date?</span>
									</label>
								</div>
							</div>
							<div class="row input-container m-b-md">
								<div class="four columns" style="color: #bbb;">
									<label for="hide-city-state">
										<input type="checkbox" disabled="disabled" name="hide-city-state" id="hide-city-state" class="wp-core-ui widefat" value="true" />
										<span>Hide City?</span>
									</label>
								</div>
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
							</div>
							<div class="row input-container">
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
?>
