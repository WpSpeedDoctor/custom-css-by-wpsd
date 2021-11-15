<?php

namespace wpsdcss;

if ( !defined( 'ABSPATH' ) ) exit;


/* CONSTANTS */

define( 'WPSDCSS_ASSET_PATH', get_asset_location()["basedir"] );

define( 'WPSDCSS_ASSET_URL', get_asset_location()["baseurl"] );

define(
	
	'IS_WC_ACTIVE_WPSDCSS', 
	
	in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
	
	 );

/********************************/


function get_asset_location(){

	if ( IS_WPSDCSS_PLUGIN ) 

		$result = array(

						'basedir' => trailingslashit(wp_get_upload_dir()["basedir"]).'custom-css-by-wpsd/',

						'baseurl' => trailingslashit(wp_get_upload_dir()["baseurl"]).'custom-css-by-wpsd/' 
					);

	else
	
		$result = array(

						'basedir' => trailingslashit( get_stylesheet_directory() ).'assets/css/',

						'baseurl' => trailingslashit( get_stylesheet_directory_uri() ).'assets/css/' 
					);

	return $result;

}


add_action('wp','wpsdcss\load_topbar_menu_for_admin');

function load_topbar_menu_for_admin() {

	if ( current_user_can( 'manage_options' ) && !get_option('custom-css-wpsd') ){

		require_once __DIR__ . '/admin-top-bar.php';
	}
	
}


function load_styles() {

	create_css_file();

	$page_type = array('global','template','woocommerce','woocommerce-page','page','inline');

	array_map( 'wpsdcss\load_css', $page_type);

	the_debug();
}

add_action( 'wp_print_styles', 'wpsdcss\load_styles', 1 );

function remove_dashes_from_string( $string ) {

	return str_replace( "-", " ", $string );

}

function get_woocommerce_main_template() {

	if ( !IS_WC_ACTIVE_WPSDCSS || !get_woocommerce_page_type() ) return false;

	return 'template-woocommerce';

}

function get_woocommerce_page_type() {
	
	if ( !IS_WC_ACTIVE_WPSDCSS ) return false;

	$wc_pages = array(

		'is_cart'				=>	'woocommerce-cart',
		'is_account_page'		=>	'woocommerce-account',
		'is_shop'				=>	'woocommerce-shop',
		'is_product'			=>	'woocommerce-product',
		'is_product_category'	=>	'woocommerce-product-category',
		'is_product_tag'		=>	'woocommerce-product-tag',
		'is_checkout'			=>	'woocommerce-checkout',
		);

	foreach ($wc_pages as $wc_function => $page_name) {
		
		if ( is_wc_page( $wc_function ) ) return $page_name;
	}

	return false;

}

function is_wc_page( $wc_function ){
	
	return is_callable($wc_function) ? call_user_func($wc_function) : false;
}

function get_file_slug( $css_type = false ) {

	if ( !$css_type ) return false;

	if ( $css_type == 'woocommerce') return get_woocommerce_main_template();

	if ( $css_type == 'woocommerce-page') return get_woocommerce_page_type();

	if ( $css_type == 'template') return get_template_slug();

	if ( $css_type == 'global') return 'global-syle';

	if ( is_single() || is_page() ) return $css_type.'-'.get_the_ID();

	return false;
}

function is_woo_template_present() {

	return get_woocommerce_page_type();
}


function get_template_slug() {
	
	if ( is_woo_template_present() ) return false;

	$result = get_template_name();
	
	return 'template-'.$result;
}

function get_template_name(){// deb(get_queried_object());

	if ( is_home() ) return 'archive-blog';

	$template_name = get_template_nice_name();

	$query = get_queried_object();

	if ( $query->post_type == 'page' ) return $template_name;

	$name = isset( $query->taxonomy ) ? get_tax_name( $query ) : get_post_name( $query );

	return $name;

}

function get_tax_name( $query ){

	if (is_tag()) return 'tag';

	$suffix = is_post_archive( ) ? '' : '-'.str_replace("_","-",$query->taxonomy);

	return 'archive'.$suffix;

}

function is_post_archive(){

	return is_archive() && is_category();

}

function get_post_name( $query ){

	$prefix = $query->post_type == 'post' ? '' : 'cpt-';

	return $prefix.$query->post_type;
}

function get_template_nice_name(){

	global $template;

	return str_replace("_","-", basename($template,'.php') );
}

function the_debug_window( $value='' ) {
	
	if ( empty($value) ) return;

	?>
	<style>
		.debug_box {
			position:fixed;
			bottom:55px;
			right:10px;
			z-index:9999;
			background-color:#ddd;
			border:1px solid grey;
			line-height:30px;
			color:#000;
			font-size:20px;
			max-height:800px;
			overflow-y:scroll;
			padding:0 35px 0 5px;
		}
	</style>
	<div id="dw" class="debug_box"><?php echo $value; ?></div>
	<?php
}

function enqueue_style( $handle, $file ) {
	
	$style_url =  WPSDCSS_ASSET_URL. $file;

	$style_path = WPSDCSS_ASSET_PATH. $file;

	$css_query_string = filemtime( $style_path );

	wp_enqueue_style( $handle, $style_url, array(), $css_query_string, false );

}

function the_debug() {
	
	if ( !isset( $_GET['wpsdcss']) ) return;

	if ( !current_user_can( 'manage_options' ) ) return;

	global $debug_data_css;

	the_debug_window( $debug_data_css );
}


function add_to_debug( $value=false ) {

	if ( $value === false ) return;

	global $debug_data_css;

	if (!isset($debug_data_css)) $GLOBALS['debug_data_css'] =='';

	$GLOBALS['debug_data_css'] .= empty($value) ? '' : $value.'<br>';
}



function create_assets_css_folder() {
	
	if ( file_exists(WPSDCSS_ASSET_PATH) ) return;

	mkdir( WPSDCSS_ASSET_PATH, 0755, true);
	
}

function get_create_filename() {

	return get_file_slug ( $_GET['wpsdcss'] ?? false );

}

function create_css_file() {
	
	if( !current_user_can( 'manage_options' ) ) return;

	$filename = get_create_filename();

	if ( $filename === false ) return;

	$file_path = WPSDCSS_ASSET_PATH.$filename.'.css';

	if ( file_exists( $file_path) ) {

		the_debug_window( __('Already exists -> ','wpsdcss').$file_path);
		
		return;
	}

	create_assets_css_folder();

	$create_file = fopen ($file_path, 'w');

	fclose($create_file);

	the_debug_window( __('Created','wpsdcss').' -> '.$file_path);

}


function get_css_file_path( $css_type ) {

	$file_slug = get_file_slug( $css_type );

	if (!$file_slug) return $file_slug;

	$file_name = $file_slug.'.css';

	$css_file_path = WPSDCSS_ASSET_PATH.$file_name;

	$result['handle'] = $file_slug;

	$result['filepath'] = $css_file_path;

	$result['filename'] = $file_name;

	$result['file-exists'] = file_exists($css_file_path);

	return $result;

}


function enqueue_inline_style( $handle, $file_name ) {

	$file_path = WPSDCSS_ASSET_PATH. $file_name;

	$inline_content = file_get_contents( $file_path );
	
	wp_register_style( $handle, false );

	wp_enqueue_style( $handle );

	wp_add_inline_style( $handle, $inline_content );

}


function load_css( $css_type ) {
	
	$css_asset_file_array = get_css_file_path($css_type);

	if ($css_asset_file_array === false ) return;
	
	if ( !$css_asset_file_array || !$css_asset_file_array['file-exists'] ) {

		$file_path = ( $css_asset_file_array ? ' -> '.$css_asset_file_array['filepath'] : '' );

		add_to_debug( __('Not present','wpsdcss').' '.__( get_display_debug_name( $css_type, $css_asset_file_array ) ).' '.$file_path);

		return;
	}	
	
	$handle = $css_asset_file_array['handle'];

	$filename = $css_asset_file_array['filename'];

	$filepath = $css_asset_file_array['filepath'];

	if ( $css_type == 'inline' ) {

		enqueue_inline_style( $handle , $filename  );

	} else {

		enqueue_style( $handle , $filename );

	}

	add_to_debug( __( 'Loaded','wpsdcss' ).' '.get_display_debug_name( $css_type, $css_asset_file_array ) .' -> '.$filepath );			
	
}


function get_display_debug_name( $css_type, $css_asset_file_array ) {

	if ( $css_type = 'woocommerce-page') return remove_dashes_from_string($css_asset_file_array['handle']);

	return $css_type;

}


