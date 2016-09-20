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
        'rbd_core_review_engine_url'
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
                    <th scope="row">Review Engine URL:</th>
                    <td>
                        <input class="regular-text" type="text" name="rbd_core_review_engine_url" value="<?php echo esc_attr( get_option('rbd_core_review_engine_url') ); ?>" />
                        <p class="description">This field verifies access to Review Engines. You can change the URL in Widgets and Shortcodes as needed. <br />If you don't have a review engine, please contact your account representative.</p>
                    </td>
					<tr valign="top">
	                    <th scope="row">Enable HIPAA Compliance Mode:</th>
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
