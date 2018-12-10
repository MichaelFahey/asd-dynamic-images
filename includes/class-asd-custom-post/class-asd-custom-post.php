<?php
/**
 *  Defines the 'ASD_Custom_Post' class,
 *
 * @package ASD_Custom_Post_Type
 * Author:      Michael H Fahey
 * Author URI:  https://artisansitedesigns.com/staff/michael-h-fahey
 * Version:     1.201812042
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

$asd_customposttype_file_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
$this_asd_custom_post_version = $asd_customposttype_file_data['Version'];

if ( isset( $asd_custom_post_version ) ) {
	if ( $asd_custom_post_version < $this_asd_custom_post_version ) {
		$asd_custom_post_version = $this_asd_custom_post_version;
	}
} else {
	$asd_custom_post_version = $this_asd_custom_post_version;
}


if ( ! class_exists( 'ASD_Custom_Post' ) ) {
	/** ----------------------------------------------------------------------------
	 *   class ASD_Custom_Post
	 *   more or less an empty class so that codesniffer won't freak out over the
	 *   file name
	 *  --------------------------------------------------------------------------*/
	class ASD_Custom_Post {

		/** ----------------------------------------------------------------------------
		 *   constructor
		 *   does practically nothing
		 *  ----------------------------------------------------------------------------
		 *
		 *   @param String $name - the slug of the custom post type.
		 *   @param Array  $args - Parameters passed from the shortcode.
		 */
		public function __construct( $name, $args ) {

		}

	}

}


if ( ! class_exists( 'ASD_Custom_Unpost_1_201811241' ) ) {
	/** ----------------------------------------------------------------------------
	 *   class ASD_Custom_Unpost_1_201811241
	 *   defines the most basic custom post parent class (that actually does
	 *   something.)
	 *  --------------------------------------------------------------------------*/
	class ASD_Custom_Unpost_1_201811241 extends ASD_Custom_Post {

		/** ----------------------------------------------------------------------------
		 *
		 *   @var $custom_type_handle returned from cuztom library
		 *  --------------------------------------------------------------------------*/
		public $custom_type_handle;

		/** ----------------------------------------------------------------------------
		 *
		 *   @var $custom_type_name slug for the custom post type
		 *  --------------------------------------------------------------------------*/
		public $custom_type_name;

		/** ----------------------------------------------------------------------------
		 *
		 *   @var $custom_main_tax main taxonomy to use
		 *  --------------------------------------------------------------------------*/
		public $custom_main_tax;

		/** ----------------------------------------------------------------------------
		 *   constructor
		 *   calls parent constructor (which does practically nothing)
		 *   then sets member variables defines taxonomy filters, etc
		 *  ----------------------------------------------------------------------------
		 *
		 *   @param String $name - the slug of the custom post type.
		 *   @param Array  $args - Parameters passed from the shortcode.
		 */
		public function __construct( $name, $args ) {

			parent::__construct( $name, $args );

			$this->custom_type_name   = $name;
			$this->custom_type_handle = register_cuztom_post_type( $this->custom_type_name, $args );

			foreach ( $args['taxonomies'] as $thistaxonomy ) {
				if ( 'category' !== $thistaxonomy ) {
					$this->custom_type_handle->addTaxonomy( $thistaxonomy );
				}
			}

			if ( $args['taxonomies'] ) {
				$thesetaxonomies       = $args['taxonomies'];
				$this->custom_main_tax = reset( $thesetaxonomies );
				add_action( 'restrict_manage_posts', array( &$this, 'add_taxonomy_filter' ) );
				add_filter( 'parse_query', array( &$this, 'convert_id_to_term' ) );
			}
		}

		/** ----------------------------------------------------------------------------
		 *   function add_taxonomy_filter()
		 *   Adds the taxonomy filter to the list, so that it
		 *   can be used to find/filter asdproducts
		 *   Hooks into the restrict_manage_posts action
		 *  --------------------------------------------------------------------------*/
		public function add_taxonomy_filter() {
			self::filter_post_type_by_taxonomy( $this->custom_type_name, $this->custom_main_tax );
		}


		/** ----------------------------------------------------------------------------
		 *   function convert_id_to_term( $query )
		 *
		 *   Hooks into the parse_query filter
		 *  ----------------------------------------------------------------------------
		 *
		 *  @param string $query - query data passed into this filter hook.
		 */
		public function convert_id_to_term( $query ) {
			self::convert_id_to_term_in_query( $query, $this->custom_type_name, $this->custom_main_tax );
		}


		/** ----------------------------------------------------------------------------
		 *   function filter_post_type_by_taxonomy( $post_type, $taxonomy )
		 *   adds the dropdown category for the taxonomy to the pick list
		 *  ----------------------------------------------------------------------------
		 *
		 *  @param string $post_type - post type.
		 *  @param string $taxonomy - taxonomy.
		 */
		protected function filter_post_type_by_taxonomy( $post_type, $taxonomy ) {
			global $typenow;
			if ( $typenow === $post_type ) {
				$selected = filter_input( INPUT_GET, $taxonomy, FILTER_SANITIZE_STRING );

				if ( get_taxonomy( $taxonomy ) ) {

					$info_taxonomy = get_taxonomy( $taxonomy );

					wp_dropdown_categories(
						array(
							'show_option_all' => "Show All {$info_taxonomy->label}",
							'taxonomy'        => $taxonomy,
							'name'            => $taxonomy,
							'orderby'         => 'name',
							'selected'        => $selected,
							'show_count'      => true,
							'hide_empty'      => true,
						)
					);
				}
			};
		}

		/** ----------------------------------------------------------------------------
		 *   function convert_id_to_term_in_query( $query, $post_type, $taxonomy )
		 *    called by functions which hook into the parse_query filter, to
		 *    alllow filter by taxonomy while editing a particular post type
		 *  ----------------------------------------------------------------------------
		 *
		 *  @param string $query - query.
		 *  @param string $post_type - post type.
		 *  @param string $taxonomy - taxonomy.
		 */
		protected function convert_id_to_term_in_query( $query, $post_type, $taxonomy ) {
			global $pagenow;
			$q_vars = &$query->query_vars;
			if ( 'edit.php' === $pagenow &&
			isset( $q_vars['post_type'] ) &&
			$q_vars['post_type'] === $post_type &&
			isset( $q_vars[ $taxonomy ] ) &&
			is_numeric( $q_vars[ $taxonomy ] ) &&
			0 !== $q_vars[ $taxonomy ] ) {
				$term                = get_term_by( 'id', $q_vars[ $taxonomy ], $taxonomy );
				$q_vars[ $taxonomy ] = $term->slug;
			}
		}

	}

}



if ( ! class_exists( 'ASD_Custom_Post_1_201811241' ) ) {
	/** ----------------------------------------------------------------------------
	 *   class ASD_Custom_Unpost_1_201811241
	 *   extends custom Unpost of the same generation,
	 *   adds wrapper class functionality
	 *  --------------------------------------------------------------------------*/
	class ASD_Custom_Post_1_201811241 extends ASD_Custom_Unpost_1_201811241 {

		/** ----------------------------------------------------------------------------
		 *   constructor
		 *   calls parent constructor
		 *   registers wrapper class post meta
		 *  ----------------------------------------------------------------------------
		 *
		 *   @param String $name - the slug of the custom post type.
		 *   @param Array  $args - Parameters passed from the shortcode.
		 */
		public function __construct( $name, $args ) {

			parent::__construct( $name, $args );

			add_filter( 'the_content', array( &$this, 'wrapper_html' ) );

			$html_section = array(
				'title'  => 'HTML Fields',
				'fields' => array(
					array(
						'id'    => 'wrapperclasses',
						'label' => 'Wrapper Classes',
						'type'  => 'textarea',
					),
				),
			);

			register_cuztom_meta_box(
				'some_html',
				array( $this->custom_type_name ),
				$html_section,
				'advanced',
				'low'
			);

		}

		/** ----------------------------------------------------------------------------
		 *   function wrapper_html( $some_content )
		 *   if we are using our post type, then prepend and append
		 *   output with <div> tags and DOM classes
		 *   Hooks into the the_content filter
		 *  ----------------------------------------------------------------------------
		 *
		 *  @param Array $some_content - post data passed into this filter hook.
		 */
		public function wrapper_html( $some_content ) {

			global $post;

			$wrapped_content = '';

			// leave other post types alone.
			if ( get_post_type( $post ) === $this->custom_type_name ) {

				$closing_divs   = '';
				$wrapperclasses = explode( PHP_EOL, get_post_meta( $post->ID, 'wrapperclasses', 'false' ) );

				foreach ( $wrapperclasses as $wrapperclass ) {
					$wrapped_content .= '<div class="' .
					self::sanitize_html_classes( $wrapperclass ) .
					'">' . PHP_EOL;
					$closing_divs    .= '</div>' . PHP_EOL;
				}

				$wrapped_content .= balanceTags( $some_content, true );
				$wrapped_content .= $closing_divs;

				return $wrapped_content;
			} else {
				return $some_content;
			}

		}

		/** ----------------------------------------------------------------------------
		 *   function sanitize_html_classes( $class, $fallback = null )
		 *   much like sanitize_html_class but it works on multiple classes
		 *   on a single line separated by whitespace, or an array of class names
		 *  ----------------------------------------------------------------------------
		 *
		 *  @param String $class - classes to be sanitized, array or string.
		 *  @param String $fallback - passed to sanitize_html_class.
		 */
		public function sanitize_html_classes( $class, $fallback = null ) {
			// Explode it, if it's a string.
			if ( is_string( $class ) ) {
				$class = explode( ' ', $class );
			}
			if ( is_array( $class ) && count( $class ) > 0 ) {
				$class = array_map( 'sanitize_html_class', $class );
				return implode( ' ', $class );
			} else {
				return sanitize_html_class( $class, $fallback );
			}
		}


	}

}

