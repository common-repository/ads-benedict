<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ab_submenu_page() {
  add_submenu_page('edit.php?post_type=adsbenedict', 'Ad\'s Benedict Settings', 'Ad\'s Benedict Settings', 'manage_options', 'absetting','ab_settings_page');
  register_setting('ab_yourls-group', 'ab_yourls_url', 'esc_attr');
	register_setting('ab_yourls-group', 'ab_yourls_token', 'esc_attr');
}
add_action('admin_menu','ab_submenu_page');
function ab_settings_page () {
  ?>
  <div class="wrap">
	<h2>Ad's Benedict Settings</h2>

	<form method="post" action="options.php">
	    <?php settings_fields( 'ab_yourls-group' ); ?>
	    <?php do_settings_sections( 'ab_yourls-group' ); ?>
	    <table class="form-table">
	        <tr valign="top">
	        <th scope="row">YOURLS address:</th>
	        <td><input type="text" class="regular-text" name="ab_yourls_url" value="<?php echo esc_attr( get_option('ab_yourls_url') ); ?>" /></td>
	        </tr>
        
          <tr valign="top">
	        <th scope="row">YOURLS secret token:</th>
	        <td><input type="text" class="regular-text" name="ab_yourls_token" value="<?php echo esc_attr( get_option('ab_yourls_token') ); ?>" /></td>
	        </tr>
        
	    </table>
    
	    <?php submit_button(); ?>

	</form>
	</div><?php
}