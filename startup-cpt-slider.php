<?php
/*
Plugin Name: StartUp CPT Slider
Description: Le plugin pour activer le Custom Post Slider
Author: Yann Caplain
Version: 1.3.0
Text Domain: startup-cpt-slider
*/

//GitHub Plugin Updater
function startup_reloaded_slider_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-slider',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-slider',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-slider/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-slider',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-slider/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

add_action( 'init', 'startup_reloaded_slider_updater' );

//CPT
function startup_reloaded_slider() {
	$labels = array(
		'name'                => 'Slides',
		'singular_name'       => 'Slide',
		'menu_name'           => 'Slider',
		'name_admin_bar'      => 'Slider',
		'parent_item_colon'   => 'Parent Item:',
		'all_items'           => 'All Items',
		'add_new_item'        => 'Add New Item',
		'add_new'             => 'Add New',
		'new_item'            => 'New Item',
		'edit_item'           => 'Edit Item',
		'update_item'         => 'Update Item',
		'view_item'           => 'View Item',
		'search_items'        => 'Search Item',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash'
	);
	$args = array(
		'label'               => 'slider',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-gallery',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
        'capability_type'     => array('slide','slides'),
        'map_meta_cap'        => true
	);
	register_post_type( 'slider', $args );

}

add_action( 'init', 'startup_reloaded_slider', 0 );

//Flusher les permalink à l'activation du plugin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_slider_rewrite_flush() {
    startup_reloaded_slider();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_slider_rewrite_flush' );

// Capabilities
function startup_reloaded_slider_caps() {
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_slide' );
	$role_admin->add_cap( 'read_slide' );
	$role_admin->add_cap( 'delete_slide' );
	$role_admin->add_cap( 'edit_others_slides' );
	$role_admin->add_cap( 'publish_slides' );
	$role_admin->add_cap( 'edit_slides' );
	$role_admin->add_cap( 'read_private_slides' );
	$role_admin->add_cap( 'delete_slides' );
	$role_admin->add_cap( 'delete_private_slides' );
	$role_admin->add_cap( 'delete_published_slides' );
	$role_admin->add_cap( 'delete_others_slides' );
	$role_admin->add_cap( 'edit_private_slides' );
	$role_admin->add_cap( 'edit_published_slides' );
}

register_activation_hook( __FILE__, 'startup_reloaded_slider_caps' );

// Metaboxes

function startup_reloaded_slider_meta() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_slider_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Slide details', 'cmb2' ),
		'object_types'  => array( 'slider' )
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Content position', 'cmb2' ),
		'id'               => $prefix . 'position',
		'type'             => 'select',
		'show_option_none' => false,
        'default'          => 'center',
		'options'          => array(
			'left' => __( 'Left', 'cmb2' ),
			'center'   => __( 'Center', 'cmb2' ),
			'right'     => __( 'Right', 'cmb2' )
		)
	) );
    
     $cmb_box->add_field( array(
		'name'             => __( 'Effect', 'cmb2' ),
		'id'               => $prefix . 'effect',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => array(
			'light' => __( 'Light', 'cmb2' ),
			'dark'   => __( 'Dark', 'cmb2' ),
			'trame-01'     => __( 'Trame 1', 'cmb2' ),
            'trame-02'     => __( 'Trame 2', 'cmb2' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Background color', 'cmb2' ),
        'id'      => $prefix . 'background_color',
        'type'    => 'colorpicker',
        'default' => '#fff'
    ) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Background image position', 'cmb2' ),
		'id'               => $prefix . 'background_position',
		'type'             => 'select',
        'default'          => 'center',
		'options'          => array(
			'top' => __( 'Top', 'cmb2' ),
			'center'   => __( 'Center', 'cmb2' ),
			'bottom'     => __( 'Bottom', 'cmb2' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Video', 'cmb2' ),
        'desc'             => __( 'YouTube url for background video. Always use in first slide only to prevent CPU load.', 'cmb2' ),
        'id'      => $prefix . 'background_video',
        'type'    => 'text'
    ) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Boxed', 'cmb2' ),
        'desc'             => __( 'Put the text inside a box', 'cmb2' ),
		'id'               => $prefix . 'boxed',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button text', 'cmb2' ),
		'id'         => $prefix . 'button_text',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button url', 'cmb2' ),
		'id'         => $prefix . 'button_url',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button target', 'cmb2' ),
        'desc'             => __( '_blank', 'cmb2' ),
		'id'               => $prefix . 'blank',
		'type'             => 'checkbox'
	) );
    
    require get_template_directory() . '/inc/animate-css.php';
    
    $cmb_box->add_field( array(
		'name'             => __( 'Title animation', 'cmb2' ),
		'id'               => $prefix . 'title_animation',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => $animate_css
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Title delay', 'cmb2' ),
        'desc'             => __( 'in ms', 'cmb2' ),
		'id'         => $prefix . 'title_delay',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Content animation', 'cmb2' ),
		'id'               => $prefix . 'content_animation',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => $animate_css
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Content delay', 'cmb2' ),
        'desc'             => __( 'in ms', 'cmb2' ),
		'id'         => $prefix . 'content_delay',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button animation', 'cmb2' ),
		'id'               => $prefix . 'button_animation',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => $animate_css
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button delay', 'cmb2' ),
        'desc'             => __( 'in ms', 'cmb2' ),
		'id'         => $prefix . 'button_delay',
		'type'       => 'text'
	) );
}

add_action( 'cmb2_admin_init', 'startup_reloaded_slider_meta' );

// Shortcode
function startup_reloaded_slider_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'shortcode' => 'true'
        ), $atts);
    
	// Code
    ob_start();
    require get_template_directory() . '/template-parts/slider-home.php';
    return ob_get_clean();       
}
add_shortcode( 'slider', 'startup_reloaded_slider_shortcode' );
?>