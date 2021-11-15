<?php

namespace wpsdcss;

/**
 * @wordpress-plugin
 * Plugin Name:       Custom CSS loader by WP Speed Doctor
 * Description:       Add your custom CSS only on website where you need it. Supports Page, Post, Archive, Tag, CPT, WooCommere 
 * Version:           1.2.0
 * Author:            Jaro Kurimsky <pixtweaks@protonmail.com>
 * Author URI:        https://wpspeeddoctor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpsdcss
 * Domain Path:       /languages
 */


if ( !defined( 'ABSPATH' ) ) exit;

start_load_custom_css();

function start_load_custom_css(){

	if ( is_futile_request() ) return;

	define( 'IS_WPSDCSS_PLUGIN', has_substr( __DIR__, basename( WP_PLUGIN_DIR ) ) );

	if ( is_admin() ) {
	
		require_once __DIR__ . '/admin-menu.php';

	} else {

		require_once __DIR__ . '/front-end.php'; 

	}

}

function is_futile_request(){

	if ( is_save_plugin_settings() ) return false;

	return !empty( $_POST ) || has_substr( $_SERVER['REQUEST_URI'], 'cron.php' ) || has_substr( $_SERVER['REQUEST_URI'], 'ajax');
}

function is_save_plugin_settings(){

	$post_option_page = $_POST['option_page'] ?? false;

	return $post_option_page == 'wpsdcss-settings'; 
}

function has_substr( $haystack, $needle ){

	return is_int( strpos( $haystack, $needle ) );
}