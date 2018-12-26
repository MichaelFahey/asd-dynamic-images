<?php
/**
 *  Defines the ASD_Dynamic_Images_Shortcode class.
 *
 *  @package ASD_Dynamic_Images
 *  Author:              Michael H Fahey
 *  Author URI:      https://artisansitedesigns.com/staff/michael-h-fahey
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/** ----------------------------------------------------------------------------
 *   class ASD_Dynamic_Images_Shortcode
 *   used to create a shortcode for asddynamic_image post types and instantiate the
 *   ASD_Add_Dynamic_Images class to return template-formatted post data.
 *  --------------------------------------------------------------------------*/
class ASD_Dynamic_Images_Shortcode {

	/** ----------------------------------------------------------------------------
	 *   constructor
	 *   Defines a new shortcode for inserting asddynamic_image custom post types.
	 *   Shortcode is [asd_insert_dynamic_images]
	 *  --------------------------------------------------------------------------*/
	public function __construct() {
		add_shortcode( 'asd_insert_dynamic_images', array( &$this, 'asd_insert_dynamic_images' ) );
	}

	/** ----------------------------------------------------------------------------
	 *   function asd_insert_dynamic_images( $shortcode_params )
	 *   This function is a callback set in add_shortcode in the class constructor.
	 *   This function instantiates a new ASD_Add_Dynamic_Images class object and
	 *   passes parameter data from the shortcode to the new object.
	 *  ----------------------------------------------------------------------------
	 *
	 *   @param Array $shortcode_params - data from the shortcode.
	 */
	public function asd_insert_dynamic_images( $shortcode_params ) {
		$posts = new ASD_Add_Dynamic_Images( $shortcode_params );

		ob_start();

		echo wp_kses_post( $posts->output_customposts() );
		return ob_get_clean();
	}

}
