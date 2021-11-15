<?php
namespace wpsdcss;

/**
 * Admin back-end
*/

if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'wp_before_admin_bar_render' , 'wpsdcss\topbar_admin_menu' );

function topbar_admin_menu() {

	if (!current_user_can( 'manage_options' )) return;

	global $wp_admin_bar;
	
	$current_slug = add_query_arg(  'wpsdcss', '' );

	$args = array(
			'id' => 'wpsdcss-top-menu',
			'title' => __('Custom CSS','wpsdcss'),
			'href' => $current_slug,
			'meta' => array()
	);

	$wp_admin_bar->add_menu($args);

	the_topbar_submenu( $current_slug );

}

function get_submenu_items() {

	$submenu_items = array( 
								'global',
								'template',
								'page',
								'inline'
							);

	if ( IS_WC_ACTIVE_WPSDCSS ){

		$submenu_items[] = 'woocommerce';
		
		$submenu_items[] = 'woocommerce-page';

	}

	return $submenu_items;

}

function the_topbar_submenu( $current_slug ) {
	
	$submenu_items = get_submenu_items();

	foreach ( $submenu_items as $key => $css_type) {

		$css_asset_file_array = get_css_file_path( $css_type );

		if ( !$css_asset_file_array ) continue;

		$args = [	'page-type'		=>	$css_type,
					'file-exists'	=>	$css_asset_file_array['file-exists'],
					'key'			=>	$key,
					'current-slug'	=>	$current_slug
				];

		add_submenu( $args );
	}
}

function add_submenu( $args ) {

	$args_menu = array(
			'id' => 'wpsdcss-top-submenu'.$args['key'],
			'parent' => 'wpsdcss-top-menu',
			'meta' => array()
	);

	if ( $args['file-exists'] ) {

		$args_menu['title'] = '<i>'.ucfirst(get_template_name()).' '.__( 'CSS file is already present', 'wpsdcss').'</i>';

	} else {

		$args_menu['title'] = get_submenu_title_text( $args['page-type'] );

		$args_menu['href'] = $args['current-slug'].'='.$args['page-type'];
	}

	global $wp_admin_bar;

	$wp_admin_bar->add_menu($args_menu);

}

function get_submenu_title_text( $page_type ) {

	$submenu_text_function = 'wpsdcss\get_'.str_replace( '-', '_', $page_type).'_submenu_text';

	if ( is_callable($submenu_text_function) ) $submenu_text = call_user_func( $submenu_text_function, $page_type );

	return $submenu_text ?? $page_type;

}

function get_global_submenu_text( $post_type ){

	return __('Create GLOBAL CSS file for all website','wpsdcss');
}

function get_page_submenu_text( $post_type ){

	return __('Create CSS file for the current page','wpsdcss');
}


function get_inline_submenu_text( $post_type ){

	return __('Create <strong>inlined</strong> CSS file for the current page','wpsdcss');
}


function get_template_submenu_text( $post_type ){

	return __('Create CSS file for current template','wpsdcss').' "'.get_template_name().'"';
}


function get_woocommerce_page_submenu_text( $post_type ){

	$text = remove_dashes_from_string( get_woocommerce_page_type() );

	$template_name = str_replace('woocommerce ', '"', $text ).'"';

	return  __('WooCommerce ','wpsdcss').' '.__( $template_name, 'wpsdcss').' '.__('template','wpsdcss');
}


function get_woocommerce_submenu_text( $post_type ){

	return __('All WooCommerce pages','wpsdcss');
}
