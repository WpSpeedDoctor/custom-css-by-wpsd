<?php

if ( !defined( 'ABSPATH' ) ) exit;


if ( is_callable('wpsdcss\start_load_custom_css') ) {

	function deactivate_plugin_notice() {
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'You moved Custom CSS loader to the child theme, deactivate plugin Custom CSS loader', 'wpsdcss' ); ?></p>
        <p><?php _e( 'Custom CSS loader is not active in the child theme until plugin is removed', 'wpsdcss' ); ?></p>
    </div>
    <?php
	}

	add_action( 'admin_notices', 'deactivate_plugin_notice' );

} else {

	require_once ( trailingslashit( get_theme_file_path() ) . 'custom-css-by-wpsd/custom-css-by-wpsd.php');
}
