<?php
/**
 * Defines the ASD_AddDynamicImages class
 *
 * @package        ASD_Dynamic_Images
 * Author:         Michael H Fahey
 * Author URI:     https://artisansitedesigns.com/staff/michael-h-fahey
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/** ----------------------------------------------------------------------------
 *   class ASD_Add_Dynamic_Images
 *   instantiated by an instance of the ASD_Dynamic_Images_Shortscode class,
 *   which also passes along the shortcode parameters.
 *  --------------------------------------------------------------------------*/
class ASD_Add_Dynamic_Images extends ASD_AddCustomPosts_1_201811241 {


	/** ----------------------------------------------------------------------------
	 *   contsructor
	 *   calls two functions, to set default shortcode parameters,
	 *   and another to parse parameters from the shortcode
	 *  ----------------------------------------------------------------------------
	 *
	 *   @param Array $atts - Parameters passed from the shortcode through
	 *   the ASD_Dynamic_Images_Shortscode instance.
	 */
	public function __construct( $atts ) {
      parent::__construct( $atts, ASD_DYNAMIC_IMAGES_DIR, 'dynamic-images-template.php', 'dynamic-images' );
	}




}

