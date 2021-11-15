<?php
namespace wpsdcss;

if ( !defined( 'ABSPATH' ) ) exit;

if ( is_wp_plugin_page() && IS_WPSDCSS_PLUGIN && ! function_exists( 'add_action_links' ) ){
	
	function add_action_links ( $actions ) {

		$mylinks = array(
			'<a href="' . admin_url( 'themes.php?page=custom_css_by_wpsd' ) . '">'.__('Settings','wpsdcss').'</a>',
	   );

	   $actions = array_merge( $mylinks, $actions );

	   return $actions;
	}

add_filter( 'plugin_action_links_'.basename(__DIR__).'/custom-css-by-wpsd.php', 'wpsdcss\add_action_links' );

}

function is_wp_plugin_page(){

	return has_substr( $_SERVER["REQUEST_URI"], 'plugins.php');

}


function register_plugin_settings( $modules ) {

	register_setting( 'wpsdcss-settings', 'custom-css-wpsd' );

}

add_action ('init', 'wpsdcss\register_plugin_settings');



function custom_menu() {

	add_submenu_page( 

		'themes.php', 
		__('Custom CSS by WP Speed Doctor','wpsdcss'), 
		__('Custom CSS by WP Speed Doctor','wpsdcss'), 
		'administrator', 
		'custom_css_by_wpsd', 
		'wpsdcss\admin_page', 
		'dashicons-layout' 

	);
}

add_action('admin_menu', 'wpsdcss\custom_menu');

function admin_page() {
	
	$setting_value = get_option('custom-css-wpsd');

	$setting_status =  $setting_value == '1' ? 'checked' : '';

	disable_autoload_in_options_table( $setting_value )

	?>
	<div id="wpbody-content">
		<div class="wrap">
			<h1><?php _e('Custom CSS by WP Speed Doctor','wpsdcss') ?></h1>
			<form class="form-table" method="post" action="options.php" >
				<?php
				
				settings_fields( 'wpsdcss-settings' ); 

				do_settings_sections( 'wpsdcss-settings' ); 
							
				?>
				<label for="disabled-top-bar"><?php _e('Disable menu on the admin bar','wpsdcss') ?>
					<input id="disabled-top-bar" type="checkbox" name="custom-css-wpsd" value="1" <?php echo $setting_status ?> >
				</label>
				<br>
				<?php submit_button( __('Save settings','wpsdcss') ); ?>
			</form>

			<?php if ( IS_WPSDCSS_PLUGIN ) the_plugin_location_message() ?>
			
		</div>
	</div>
	<?php

}

function the_plugin_location_message() {

	?>
	<p style="max-width:600px">
		<?php _e("You're running Custom CSS loader by WP Speed Doctor as a plugin. To avoid accidental deactivation, you can run Custom CSS loader by WP Speed Doctor code directly from the child theme. Just <b>move</b> plugin folder to the child theme folder and add to functions.php in the child theme:",'wpsdcss');?>
	</p>
	
	<p style="max-width: 650px;background-color: #ddd;width: fit-content;padding: 0 10px 3px;font-weight: 500;">
		require_once ( trailingslashit( get_theme_file_path() ) . '<?=basename(__DIR__)?>/custom-css-by-wpsd-child-theme.php');
	</p>
	
	<p style="max-width:600px">
		<?php _e('After you you move plugin to the child theme, path for custom CSS will be in the child theme /assets/css/.','wpsdcss'); ?>
		<br><br>
		<?php _e('If you have already created with plugin custom CSS styles, move files from /uploads/custom-css-by-wpsd/ to child theme /assets/css/.','wpsdcss'); ?>
		<br><br>
		<?php _e('Before you move plugin to the child theme, deactivate the plugin. When plugin is updated, you need to move again the plugin to the child theme.','wpsdcss'); ?>
	</p>

	<?php

}

function disable_autoload_in_options_table( $setting_value ) {

	if ( !is_true_value_saved( $setting_value )  ) return;
	
	update_option( 'custom-css-wpsd', $setting_value === '1' ? true : false , 'no' );

}

function is_true_value_saved($setting_value){

	return isset( $_REQUEST["settings-updated"] ) && $setting_value =='1' ;
}
