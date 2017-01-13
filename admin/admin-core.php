<?php
add_action( 'admin_menu', 'rbd_core_settings_menu_item' );
function rbd_core_settings_menu_item() {
	add_menu_page( 'RBD Core Settings', 'RBD Core', 'manage_options', __FILE__, 'rbd_core_settings_page', 'dashicons-star-filled' );

	// Register Settings Function on Init
	add_action( 'admin_init', 'rbd_core_settings_on_init' );
}

function rbd_core_settings_on_init() {
    $rbd_settings_group = 'rbd-core-settings-group';
    $rbd_settings_array = array(
		'rbd_core_hipaa_compliance',
        'rbd_core_review_engine_url',
		'rbd_core_review_engine_write_a_review_url'
    );

    foreach( $rbd_settings_array as $setting ){
        register_setting( 'rbd-core-settings-group', $setting );
    }
}

function rbd_core_settings_page() {
    $rbd_settings_group = 'rbd-core-settings-group'; ?>
    <div class="wrap">
        <h2>RBD Core Verification</h2>
        <form method="post" action="options.php">
            <?php settings_fields( $rbd_settings_group ); ?>
            <?php do_settings_sections( $rbd_settings_group ); ?>
            <table class="form-table">
				<tr valign="top">
                    <th scope="row">Verifiable Review Engine URL:</th>
                    <td>
						<?php
							if( update_option( 'rbd_core_review_engine_url_updated', get_option( 'rbd_core_review_engine_url' ) ) ){
								delete_transient( 'rbd_core_api_call' );
							}

							$verification_data	= rbd_core_api_call();
							$verify				= $verification_data->data[0]->message;

							if( $verify == 'token:valid::accepted' ){ // All is good! Don't need to do anything else.
								update_option( 'RBD_CORE_VALID', true );
							} else { // Whoops, not a valid Review Engine.
								update_option( 'RBD_CORE_VALID', false );
							}
						?>
                        <input class="regular-text" type="text" name="rbd_core_review_engine_url" value="<?php echo esc_attr( get_option('rbd_core_review_engine_url') ); ?>" />
						<?php
							if( get_option( 'rbd_core_review_engine_url' ) != '' ){
								if( rbd_core_verify() == false ){
									echo '<br /><br /><span style="background: #fcc; border: 1px solid #f36; padding: 3px 8px;">Invalid Review Engine URL. Please make sure you typed your URL properly, or check with your account representative.</span><br /><br />';
								} else if( rbd_core_verify() == true ){
									echo '<span style="background: #cfc; border: 1px solid #0c3; padding: 3px 8px;">Valid Review Engine URL!</span><br />';
								}
							}
						?>
                        <p class="description">Put a Review Engine URL in here. This field verifies access to Review Engines. You can change the URL in Widgets and Shortcodes as needed. <br />If you don't have a review engine, please contact your account representative.</p>
                    </td>
				</tr>
				<?php if( $verification_data->data[0]->review_funnels->advanced_review_funnels == true ){ ?>
					<tr valign="top">
		                <th scope="row">"Write A Review" Link:</th>
		                <td>
							<?php $write_a_review_url = esc_attr( get_option('rbd_core_review_engine_write_a_review_url') ); ?>
		                    <select name="rbd_core_review_engine_write_a_review_url">
								<option value="">Default - Leave a Review Form</option>
								<?php
									$_write_a_review_url_options = array (
										'Net Promoter Score'		=>	'nps',
										'Smile Rating'				=>	'smile-rating',
										'Star Rating'				=>	'star-rating',
										'Would You Recommend Us'	=>	'would-you-recommend-us'
									);

									foreach( $_write_a_review_url_options as $option => $value ){
										$selected = '';

										if( $write_a_review_url == $value )
											$selected = 'selected="selected"';

										if( $verification_data->data[0]->review_funnels->selected_review_funnel == $value )
											$option = "✅ $option";

										echo "<option $selected value='$value'>$option</option>";
									}
								?>
							</select>
		                    <p class="description">By default, the Write A Review link goes to the main review form. You can pick a Review Funnel here instead.<br />The option denoted with a "✅" is the option you have selected in your Review Engine.</p>
		                </td>
		            </tr>
				<?php } ?>
				<tr valign="top">
	                <th scope="row">Enable HIPAA Compliance:</th>
	                <td>
						<?php $hipaa = get_option('rbd_core_hipaa_compliance'); ?>
	                    <input type="checkbox" name="rbd_core_hipaa_compliance" <?php if( $hipaa == true ){ echo 'checked="checked"'; } ?> />
	                    <p class="description">This field forces Shortcodes and Widgets to comply with HIPAA's guidelines and helps prevent sharing sensitive information, including reviewer names and "Gravatars".</p>
	                </td>
	            </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>
