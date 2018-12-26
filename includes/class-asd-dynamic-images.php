<?php
/**
 *
 * Defines the class ASD_Products
 *
 * @package        WordPress
 * Plugin Name:    Defines class ASD_Dynamic_Images
 * Author:         Michael H Fahey
 * Author URI:     https://artisansitedesigns.com/staff/michael-h-fahey/
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}


if ( ! class_exists( 'ASD_Dynamic_Images' ) ) {
	/** ----------------------------------------------------------------------------
	 *   Defines the class ASD_Products
	 *  --------------------------------------------------------------------------*/
	class ASD_Dynamic_Images extends ASD_Custom_Unpost_1_201811241 {

		/** ----------------------------------------------------------------------------
		 *
		 * @var $customargs holds settings for the custom post type
		 *  --------------------------------------------------------------------------*/
		private $customargs = array(
			'label'               => 'Dynamic Images',
			'description'         => 'Dynamic Images',
			'labels'              => array(
				'name'               => 'Dynamic Images',
				'singular_name'      => 'Dynamic Image',
				'menu_name'          => 'Dynamic Images',
				'parent_item_colon'  => 'Parent Dynamic Image:',
				'all_items'          => 'All Dynamic Images',
				'view_item'          => 'View Dynamic Image',
				'add_new_item'       => 'Add New Dynamic Image',
				'add_new'            => 'Add New',
				'edit_item'          => 'Edit Dynamic Image',
				'update_item'        => 'Update Dynamic Image',
				'search_items'       => 'Search Dynamic Images',
				'not_found'          => 'Dynamic Image Not Found',
				'not_found_in_trash' => 'Dynamic Image Not Found In Trash',
			),
			'supports'            => array( 'title', 'page-attributes' ),
			'taxonomies'          => array( 'dynamic-imagegroups', 'category' ),
			'heirarchical'        => false,
			'public'              => true,
			'has_archive'         => false,
			'rewrite'             => array( 'slug' => 'dynamic-images' ),
			'capability_type'     => 'page',
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'show_admin_column'   => true,
			'can_export'          => true,
			'menu_position'       => 31,
		);

		/** ----------------------------------------------------------------------------
		 *
		 * @var $meta_section_def defines custom post meta fields for passing
		 * to cuztom functions.
		 *  --------------------------------------------------------------------------*/
		private $meta_section_settings = array(
			'title'  => 'Image Settings',
			'fields' => array(
				array(
					'id'    => 'image_class',
					'label' => 'Image Class(es)',
					'type'  => 'text',
				),
				array(
					'id'    => 'image_id',
					'label' => 'Image ID',
					'type'  => 'text',
				),
			),
		);

		/** ----------------------------------------------------------------------------
		 *
		 * @var $meta_section_images defines custom post meta fields for
		 * for defining dynamic images
		 *  --------------------------------------------------------------------------*/
		private $meta_section_images = array(
			'title'  => 'Component Images',
			'fields' => array(
				array(
					'id'    => 'image_path_xs',
					'label' => 'Size XS (Phones)',
					'type'  => 'image',
				),
				array(
					'id'    => 'image_path_sm',
					'label' => 'Size SM (iPads)',
					'type'  => 'image',
				),
				array(
					'id'    => 'image_path_md',
					'label' => 'Size MD (small desktops)',
					'type'  => 'image',
				),
				array(
					'id'    => 'image_path_lg',
					'label' => 'Size LG (normal/large desktops)',
					'type'  => 'image',
				),
				array(
					'id'    => 'image_path_xl',
					'label' => 'Size XL (extra large desktops)',
					'type'  => 'image',
				),
				array(
					'id'    => 'image_path_xxl',
					'label' => 'Size XXL (double-x large desktops)',
					'type'  => 'image',
				),
			),
		);

		/** ----------------------------------------------------------------------------
		 *   function __construct()
		 *   Constructor, calls the parent constructor, adds structured data hook
		 *   to the wp_print_footer_scripts action.
		 *  --------------------------------------------------------------------------*/
		public function __construct() {

			/* check the option, and if it's not set don't show this cpt in the dashboard main meny */
			global $asd_cpt_dashboard_display_options;
			if ( get_option( 'asd_dynamic_images_display' ) === $asd_cpt_dashboard_display_options[2] ) {
				$this->customargs['show_in_menu'] = 0;
			}

			parent::__construct( 'dynamic-images', $this->customargs );
			$meta_section_settings_obj = register_cuztom_meta_box( 'meta_section_settings', $this->custom_type_name, $this->meta_section_settings );
			$meta_section_images_obj   = register_cuztom_meta_box( 'meta_section_images', $this->custom_type_name, $this->meta_section_images );

			add_action( 'admin-init', array( &$this, 'shortcode_helper_add_meta' ) );
		}

		/** ----------------------------------------------------------------------------
		 *   function shortcode_helper_add_meta()
		 *   hooks in the shortcode helpers
		 *  --------------------------------------------------------------------------*/
		public function shortcode_helper_add_meta() {
			if ( is_admin() ) {
				add_meta_box( 'dynamic_image_shortcode_n-init', array( &$this, 'shortcode_helper_add_meta' ), 'Shortcode Examples', 'asd_shortcode_helpers', 'dynamic-images', 'side', 'low' );
			}
		}

		/** ---------------------------------------------------------------------------
		 *   function asd_shortcode_helpers()
		 *   adds sample shortcodes to dashboard
		 *  --------------------------------------------------------------------------*/
		public function asd_shortcode_helpers() {
			global $post;
			echo '<code>';
			if ( $post->ID ) {
				echo '</br>[asd_insert_dynamic_images id="' . esc_attr( $post->ID ) . '" ]</br></br>';
			}
			if ( $post->post_name ) {
				echo '[asd_insert_dynamic_images name="' . esc_attr( $post->post_name ) . '" ]</br></br>';
			}
			echo '</code>';
		}

	}

}
