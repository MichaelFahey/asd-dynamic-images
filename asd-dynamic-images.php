<?php
/**
 *
 * This is the root file of the ASD Dynamic_Images WordPress plugin
 *
 * @package ASD_Dynamic Images
 * Plugin Name:    ASD Dynamic Images
 * Plugin URI:     https://artisansitedesigns.com/dynamic_images/asd-dynamic_images/
 * Description:    Defines an "ASD DynamicImage" Custom Post Type
 * Author:         Michael H Fahey
 * Author URI:     https://artisansitedesigns.com/staff/michael-h-fahey/
 * Text Domain:    asd_dynamic_images
 * License:        GPL3
 * Version:        1.201808031
 *
 * ASD Dynamic Images is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * ASD Dynamic Images is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ASD Dynamic Images. If not, see
 * https://www.gnu.org/licenses/gpl.html
 */

$asd_dynamic_images_file_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
$asd_dynamic_images_version   = $asd_dynamic_images_file_data['Version'];

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

if ( ! defined( 'ASD_DYNAMIC_IMAGES_DIR' ) ) {
	define( 'ASD_DYNAMIC_IMAGES_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ASD_DYNAMIC_IMAGES_URL' ) ) {
	define( 'ASD_DYNAMIC_IMAGES_URL', plugin_dir_url( __FILE__ ) );
}

require_once 'includes/asd-admin-menu/asd-admin-menu.php';
require_once 'includes/class-asd-custom-post/class-asd-custom-post.php';
require_once 'includes/class-asd-addcustomposts/class-asd-addcustomposts.php';
require_once 'includes/class-asd-dynamic-images.php';
require_once 'includes/class-asd-dynamic-images-shortcode.php';
require_once 'includes/class-asd-add-dynamic-images.php';

/* include components */
if ( ! class_exists( 'Gizburdt\Cuztom\Cuztom' ) ) {
	include 'components/cuztom/cuztom.php';
}


/** ----------------------------------------------------------------------------
 *   Function asd_dynamic_image_admin_submenu()
 *   Adds two submenu pages to the admn menu with the asd_settings slug.
 *   This admin top menu is loaded in includes/asd-admin-menu.php .
 *  --------------------------------------------------------------------------*/
function asd_dynamic_image_admin_submenu() {
	global $asd_cpt_dashboard_display_options;
	if ( get_option( 'asd_dynamic_images_display' ) !== $asd_cpt_dashboard_display_options[1] ) {
		add_submenu_page(
			'asd_settings',
			'Dynamic Images',
			'Dynamic Images',
			'manage_options',
			'edit.php?post_type=dynamic-images',
			''
		);
	}
	if ( 'false' !== get_option( 'asd_dynamic_imagegroups_display' ) ) {
		add_submenu_page(
			'asd_settings',
			'Dynamic Image Groups',
			'Dynamic Image Groups',
			'manage_options',
			'edit-tags.php?taxonomy=dynamic-imagegroups',
			''
		);
	}

}
if ( is_admin() ) {
		add_action( 'admin_menu', 'asd_dynamic_image_admin_submenu', 15 );
}


/** ----------------------------------------------------------------------------
 *   function instantiate_dynamic_images_class_object()
 *   create a single ASD_Pagersections instance
 *   Hooks into the init action
 *  --------------------------------------------------------------------------*/
function instantiate_dynamic_images_class_object() {
	$asd_dynamic_image_type = new ASD_Dynamic_Images();
}
add_action( 'init', 'instantiate_dynamic_images_class_object' );


/** ----------------------------------------------------------------------------
 *   function instantiate_dynamic_images_shortcode_object()
 *   create a single ASD_Dynamic_Images_Shortcode instance
 *   Hooks into the plugins_loaded action
 *  --------------------------------------------------------------------------*/
function instantiate_dynamic_images_shortcode_object() {
	new ASD_Dynamic_Images_Shortcode();
}
add_action( 'plugins_loaded', 'instantiate_dynamic_images_shortcode_object' );


/** ----------------------------------------------------------------------------
 *   function asd_dynamic_image_rewrite_flush()
 *   This rewrites the permalinks but ONLY when the plugin is activated
 *  --------------------------------------------------------------------------*/
function asd_dynamic_image_rewrite_flush() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'asd_dynamic_image_rewrite_flush' );



/** ----------------------------------------------------------------------------
 *   function asd_dynamic_images_enqueues()
 *   enqueue some CSS for this custom post type
 *  --------------------------------------------------------------------------*/
function asd_dynamic_images_enqueues() {
	wp_enqueue_script( 'asd_functions', ASD_DYNAMIC_IMAGES_URL . 'js/asd-functions.js', array(), $asd_dynamic_images_version, 'true' );
	wp_enqueue_script( 'asd_dynamic_images', ASD_DYNAMIC_IMAGES_URL . 'js/asd-dynamic-images.js', array(), $asd_dynamic_images_version, 'true' );
}
add_action( 'wp_enqueue_scripts', 'asd_dynamic_images_enqueues' );



/** ----------------------------------------------------------------------------
 *   function asd_register_settings_asd_dynamic_images()
 *  --------------------------------------------------------------------------*/
function asd_register_settings_asd_dynamic_images() {
	register_setting( 'asd_dashboard_option_group', 'asd_dynamic_images_display' );
	register_setting( 'asd_dashboard_option_group2', 'asd_dynamic_imagegroups_display' );

	/** ----------------------------------------------------------------------------
	 *   add the names of the post types and taxonomies being added
	 *  --------------------------------------------------------------------------*/
	global $asd_cpt_list;
	global $asd_tax_list;
	array_push(
		$asd_cpt_list,
		array(
			'name' => 'Dynamic Images',
			'slug' => 'dynamic-images',
			'desc' => 'jQuery-powered browser-smart image shortcodes that can load small images for phones, super-wide banners for huge screens, from a selection of images, automatically',
			'link' => '',
		)
	);
	array_push( $asd_tax_list, 'asdproductgroups' );
}
if ( is_admin() ) {
	add_action( 'admin_init', 'asd_register_settings_asd_dynamic_images' );
}


/** ----------------------------------------------------------------------------
 *   function asd_add_settings_asd_dynamic_images()
 *  --------------------------------------------------------------------------*/
function asd_add_settings_asd_dynamic_images() {
	global $asd_cpt_dashboard_display_options;

	add_settings_field(
		'asd_dynamic_images_display_fld',
		'show Dynamic Images in:',
		'asd_select_option_insert',
		'asd_dashboard_option_group',
		'asd_dashboard_option_section_id',
		array(
			'settingname'   => 'asd_dynamic_images_display',
			'selectoptions' => $asd_cpt_dashboard_display_options,
		)
	);

}
if ( is_admin() ) {
	add_action( 'asd_dashboard_option_section', 'asd_add_settings_asd_dynamic_images' );
}


/** ----------------------------------------------------------------------------
 *   function asd_add_settings_asd_dynamic_imagegroups()
 *  --------------------------------------------------------------------------*/
function asd_add_settings_asd_dynamic_imagegroups() {
	add_settings_field(
		'asd_dynamic_imagegroups_display_fld',
		'show Dynamic Imagegroups in submenu:',
		'asd_truefalse_select_insert',
		'asd_dashboard_option_group2',
		'asd_dashboard_option_section2_id',
		'asd_dynamic_imagegroups_display'
	);
}
if ( is_admin() ) {
	add_action( 'asd_dashboard_option_section2', 'asd_add_settings_asd_dynamic_imagegroups' );
}



/** ----------------------------------------------------------------------------
 *   Function asd_dynamic_image_plugin_action_links()
 *   Adds links to the Dashboard Plugin page for this plugin.
 *   Hooks to admin_menu action.
 *  ----------------------------------------------------------------------------
 *
 *   @param Array $actions -  Returned as an array of html links.
 */
function asd_dynamic_image_plugin_action_links( $actions ) {
	if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
		$actions[0] = '<a target="_blank" href="https://artisansitedesigns.com/plugins/asd-dynamic_images#support/">Help</a>';
		/* $actions[1] = '<a href="' . admin_url()   . '">' .  'Settings'  . '</a>';  */
	}
	return apply_filters( 'dynamic_images_actions', $actions );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'asd_dynamic_image_plugin_action_links' );

