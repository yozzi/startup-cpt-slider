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

//add_action( 'init', 'startup_reloaded_slider_updater' );

//CPT
function startup_reloaded_slider() {
	$labels = array(
        'name'                => _x( 'Slides', 'Post Type General Name', 'startup-cpt-slider' ),
		'singular_name'       => _x( 'Slide', 'Post Type Singular Name', 'startup-cpt-slider' ),
		'menu_name'           => __( 'Slider', 'startup-cpt-slider' ),
		'name_admin_bar'      => __( 'Slider', 'startup-cpt-slider' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-slider' ),
		'all_items'           => __( 'All Items', 'startup-cpt-slider' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-slider' ),
		'add_new'             => __( 'Add New', 'startup-cpt-slider' ),
		'new_item'            => __( 'New Item', 'startup-cpt-slider' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-slider' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-slider' ),
		'view_item'           => __( 'View Item', 'startup-cpt-slider' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-slider' ),
		'not_found'           => __( 'Not found', 'startup-cpt-slider' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-slider' )
	);
	$args = array(
        'label'               => __( 'slider', 'startup-cpt-slider' ),
		'description'         => __( '', 'startup-cpt-slider' ),
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
		'title'         => __( 'Slide details', 'startup-cpt-slider' ),
		'object_types'  => array( 'slider' )
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Content position', 'startup-cpt-slider' ),
		'id'               => $prefix . 'position',
		'type'             => 'select',
		'show_option_none' => false,
        'default'          => 'center',
		'options'          => array(
			'left' => __( 'Left', 'startup-cpt-slider' ),
			'center'   => __( 'Center', 'startup-cpt-slider' ),
			'right'     => __( 'Right', 'startup-cpt-slider' )
		)
	) );
    
     $cmb_box->add_field( array(
		'name'             => __( 'Effect', 'startup-cpt-slider' ),
		'id'               => $prefix . 'effect',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => array(
			'light' => __( 'Light', 'startup-cpt-slider' ),
			'dark'   => __( 'Dark', 'startup-cpt-slider' ),
			'trame-01'     => __( 'Trame 1', 'startup-cpt-slider' ),
            'trame-02'     => __( 'Trame 2', 'startup-cpt-slider' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Background color', 'startup-cpt-slider' ),
        'id'      => $prefix . 'background_color',
        'type'    => 'colorpicker',
        'default' => '#fff'
    ) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Background image position', 'startup-cpt-slider' ),
		'id'               => $prefix . 'background_position',
		'type'             => 'select',
        'default'          => 'center',
		'options'          => array(
			'top' => __( 'Top', 'startup-cpt-slider' ),
			'center'   => __( 'Center', 'startup-cpt-slider' ),
			'bottom'     => __( 'Bottom', 'startup-cpt-slider' )
		)
	) );
    
    $cmb_box->add_field( array(
        'name'    => __( 'Video', 'startup-cpt-slider' ),
        'desc'             => __( 'YouTube url for background video. Always use in first slide only to prevent CPU load.', 'startup-cpt-slider' ),
        'id'      => $prefix . 'background_video',
        'type'    => 'text'
    ) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Boxed', 'startup-cpt-slider' ),
        'desc'             => __( 'Put the text inside a box', 'startup-cpt-slider' ),
		'id'               => $prefix . 'boxed',
		'type'             => 'checkbox'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button text', 'startup-cpt-slider' ),
		'id'         => $prefix . 'button_text',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button url', 'startup-cpt-slider' ),
		'id'         => $prefix . 'button_url',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button target', 'startup-cpt-slider' ),
        'desc'             => __( '_blank', 'startup-cpt-slider' ),
		'id'               => $prefix . 'blank',
		'type'             => 'checkbox'
	) );
    
    require get_template_directory() . '/inc/animate-css.php';
    
    $cmb_box->add_field( array(
		'name'             => __( 'Title animation', 'startup-cpt-slider' ),
		'id'               => $prefix . 'title_animation',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => $animate_css
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Title delay', 'startup-cpt-slider' ),
        'desc'             => __( 'in ms', 'startup-cpt-slider' ),
		'id'         => $prefix . 'title_delay',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Content animation', 'startup-cpt-slider' ),
		'id'               => $prefix . 'content_animation',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => $animate_css
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Content delay', 'startup-cpt-slider' ),
        'desc'             => __( 'in ms', 'startup-cpt-slider' ),
		'id'         => $prefix . 'content_delay',
		'type'       => 'text'
	) );
    
    $cmb_box->add_field( array(
		'name'             => __( 'Button animation', 'startup-cpt-slider' ),
		'id'               => $prefix . 'button_animation',
		'type'             => 'select',
		'show_option_none' => 'none',
        'default'          => 'none',
		'options'          => $animate_css
	) );
    
    $cmb_box->add_field( array(
		'name'       => __( 'Button delay', 'startup-cpt-slider' ),
        'desc'             => __( 'in ms', 'startup-cpt-slider' ),
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

// Enqueue scripts and styles.
function startup_cpt_slider_scripts() {
    wp_enqueue_style( 'startup-cpt-slider-style', plugins_url( '/css/startup-cpt-slider.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_slider_scripts' );
?>