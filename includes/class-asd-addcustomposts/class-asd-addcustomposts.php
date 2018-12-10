<?php
/**
 * Defines the ASD_AddCustomPosts class and derived classes
 *
 * @package        ASD_CustomPosts
 * Author:         Michael H Fahey
 * Author URI:     https://artisansitedesigns.com/staff/michael-h-fahey
 * Version:        1.201812042
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

$asd_addcustomposts_file_data    = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
$this_asd_addcustom_post_version = $asd_addcustomposts_file_data['Version'];

if ( isset( $asd_addcustom_post_version ) ) {
	if ( $asd_addcustom_post_version < $this_asd_addcustom_post_version ) {
		$asd_addcustom_post_version = $this_asd_addcustom_post_version;
	}
} else {
	$asd_addcustom_post_version = $this_asd_addcustom_post_version;
}


$output_custompost_index;


if ( ! class_exists( 'ASD_AddCustomPosts' ) ) {
	/** ----------------------------------------------------------------------------
	 *   class ASD_AddCustomPosts
	 *   more or less an empty class so that codesniffer won't freak out over the
	 *   file name
	 *  --------------------------------------------------------------------------*/
	class ASD_AddCustomPosts {

		/** ----------------------------------------------------------------------------
		 *   constructor
		 *   does practically nothing
		 *  ----------------------------------------------------------------------------
		 *
		 *   @param Array  $atts - Parameters passed from the shortcode.
		 *   @param String $child_plugin_dir - directory of the plugin.
		 *   @param String $child_default_template - default page template for this
		 *          post type.
		 *   @param String $child_posttype - custom post type slug.
		 */
		public function __construct( $atts, $child_plugin_dir, $child_default_template, $child_posttype ) {

		}

	}

}


if ( ! class_exists( 'ASD_AddCustomPosts_1_201811241' ) ) {
	/** ----------------------------------------------------------------------------
	 *   class ASD_AddCustomPosts_1_201811241
	 *   instantiated by an instance of the ASD_CustomPostsShortscode class,
	 *   which also passes along the shortcode parameters.
	 *  --------------------------------------------------------------------------*/
	class ASD_AddCustomPosts_1_201811241 {

		/** ----------------------------------------------------------------------------
		 *
		 *   @var $parameters
		 *   to contain parameters from the shortcode
		 *  --------------------------------------------------------------------------*/
		protected $parameters = array();

		/** ----------------------------------------------------------------------------
		 *
		 *   @var $plugin_dir contains the location of this plugin
		 *  --------------------------------------------------------------------------*/
		protected $plugin_dir = '';

		/** ----------------------------------------------------------------------------
		 *
		 *   @var $default_template the default page template for this post type
		 *  --------------------------------------------------------------------------*/
		protected $defalt_template = '';

		/** ----------------------------------------------------------------------------
		 *
		 *   @var $posttype the slug for the post type
		 *  --------------------------------------------------------------------------*/
		protected $posttype = '';


		/** ----------------------------------------------------------------------------
		 *   constructor
		 *   sets member variables
		 *   calls two functions, to set default shortcode parameters,
		 *   and another to parse parameters from the shortcode
		 *  ----------------------------------------------------------------------------
		 *
		 *   @param Array  $atts - Parameters passed from the shortcode.
		 *   @param String $child_plugin_dir - directory of the plugin.
		 *   @param String $child_default_template - default page template for this
		 *          post type.
		 *   @param String $child_posttype - custom post type slug.
		 */
		public function __construct( $atts, $child_plugin_dir, $child_default_template, $child_posttype ) {

			$this->plugin_dir       = $child_plugin_dir;
			$this->default_template = $child_default_template;
			$this->posttype         = $child_posttype;

			$this->set_default_parameters();
			$this->set_parameters( $atts );
		}

		/** ----------------------------------------------------------------------------
		 *   function set_default_parameters()
		 *   sets default/failsafe options for shortcode parameters
		 *  --------------------------------------------------------------------------*/
		protected function set_default_parameters() {
			$this->parameters = array(
				'post_type'           => $this->posttype,
				'post_status'         => 'publish',
				'orderby'             => 'menu_order',
				'order'               => 'ASC',
				'paginate'            => false,
				'template'            => false,
				'none_found'          => '',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => -1,
			);
		}


		/** ----------------------------------------------------------------------------
		 *   function set_parameters( $atts )
		 *   Parses through shortcode options passed in $atts, and adds query
		 *   parameters to $parameters member variable
		 *  ----------------------------------------------------------------------------
		 *
		 *   @param Array $atts - options passed from the shortcode through
		 *   the ASD_ProductsShortscode instance.
		 */
		protected function set_parameters( $atts ) {

			$this->parameters = wp_parse_args( $atts, $this->parameters );

			if ( isset( $atts['ids'] ) ) {
				$customposts_ids              = explode( ',', sanitize_text_field( $atts['ids'] ) );
				$this->parameters['post__in'] = $customposts_ids;
			}

			if ( isset( $atts['template'] ) ) {
				$this->parameters['template'] = sanitize_text_field( $atts['template'] );
			}

			if ( isset( $atts['name'] ) ) {
				$this->parameters['name'] = sanitize_text_field( $atts['name'] );
			}
			if ( isset( $atts['category'] ) ) {
				$this->parameters['category_name'] = sanitize_text_field( $atts['category'] );
			} elseif ( isset( $atts['cats'] ) ) {
				$this->parameters['cat'] = sanitize_text_field( $atts['cats'] );
			}

		}

		/** ----------------------------------------------------------------------------
		 *   function output_customposts()
		 *   Queries the database for posts based on data in the parameters array
		 *   does a have_posts() loop with a shortcode template
		 *   and concantenates and returns $output
		 *  --------------------------------------------------------------------------*/
		public function output_customposts() {

			if ( ! $this->parameters ) {
				return 'no arguments';
			}

			global $output_custompost_index;

			$matching = new WP_Query( $this->parameters );
			$output   = '';

			if ( $matching->have_posts() ) {
				$output_custompost_index = 0;
				while ( $matching->have_posts() ) {
					global $post;
					$output .= self::add_template_part( $matching );
					$output_custompost_index++;
				}
			} else {
				$output = '<div class="no_customposts"></div>';
			}

			$output_no_wpautop = str_replace( '<p></p>', '', $output );
			return $output_no_wpautop;
		}

		/** ----------------------------------------------------------------------------
		 *   function shortcode_template()
		 *   Finds and returns a template for the shortcode.
		 *   Sets a safe default if no template is specified. Specified templates are
		 *   searched for in the theme (stylesheet) directory first, then in the
		 *   plugin dir. If the specified template does not exist, the safe default
		 *   is kept.
		 *   Specified templates are disassembled into path-file-extension,
		 *   path locations are compared and and any path funny business results in
		 *   the default template being used.
		 *   Finally, the resultant template file is checked to see that it exists
		 *   including the default, and if it does not, the function returns false.
		 *  -------------------------------------------------------------------------- */
		protected function shortcode_template() {
			// set the return value to the default.
			$template_file = $this->plugin_dir . '/' . $this->default_template;

			$template_matched = false;

			// check to see if 'template' parameter was passed from shortcode.
			if ( ! empty( $this->parameters['template'] ) ) {

				// look first for a template match in the theme directory.
				// looking in the "stylesheet" dir does not break.
				// child themes, "template" dir does.
				$theme_template_file = get_stylesheet_directory() . '/' . $this->parameters['template'];
				// check for path funny business.
				$path_parts = pathinfo( $theme_template_file );
				if ( get_stylesheet_directory() . '/' . $path_parts['filename'] . '.' . $path_parts['extension'] === $theme_template_file ) {
					if ( ! file_exists( $theme_template_file ) ) {
						$template_file = $this->plugin_dir . '/' . $this->default_template;
					} else {
						$template_file    = $theme_template_file;
						$template_matched = true;
					}
				}

				// if nothing was matched yet.
				// look second for a match in the plugin directory.
				if ( ! $template_matched ) {
					$plugin_template_file = $this->plugin_dir . '/' . $this->parameters['template'];
					// check for path funny business.
					$path_parts = pathinfo( $plugin_template_file );
					if ( $this->plugin_dir . '/' . $path_parts['filename'] . '.' . $path_parts['extension'] === $plugin_template_file ) {
						if ( ! file_exists( $plugin_template_file ) ) {
							$template_file = $this->plugin_dir . '/' . $this->default_template;
						} else {
							$template_file    = $plugin_template_file;
							$template_matched = true;
						}
					}
				}
			}

			if ( file_exists( $template_file ) ) {
				return $template_file;
			} else {
				return false;
			}
		}


		/** ----------------------------------------------------------------------------
		 *   function add_template_part( $ic_posts, $singles = false )
		 *   called in ::output_products(),
		 *   returns post data from a shortcode template
		 *  ----------------------------------------------------------------------------
		 *
		 *   @param Array  $ic_posts - post or posts to output.
		 *   @param string $singles  - whether single posts were sent.
		 */
		protected function add_template_part( $ic_posts, $singles = false ) {
			if ( $singles ) {
				setup_postdata( $ic_posts );
			} else {
				$ic_posts->the_post();
			}
			ob_start();
			$selected_template = self::shortcode_template();
			if ( $selected_template ) {
				require $selected_template;
			} else {
				require $this->plugin_dir . '/' . $this->default_template;
			}
			ob_get_contents();
			return ob_get_clean();

		}
	}

}

