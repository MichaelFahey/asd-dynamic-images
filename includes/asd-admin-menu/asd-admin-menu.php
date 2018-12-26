<?php
/**
 * Functions for adding a top-level menu to the WordPress Dashboard,
 * and controls the order of the top level menus.
 *
 * @package    WordPress
 * @subpackage ASD_Admin
 * Author:       Michael H Fahey
 * Author URI:   https://artisansitedesigns.com/staff/michael-h-fahey
 * Version:      1.201812101
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '' );
}

/** ------------------------------------------------------------------------------------
 * NOTE ON ODD FUNCTION NAMES WITH INCLUDED VERSIONS
 *
 * Because it is possible/likely that multiple versions of this module will be
 * present if multiple ASD plugins are installed, especially during updates,
 * there is a versioning mechanism built in:
 * The values of the version of this module ($this_asd_admin_menu_version)
 * is compared to the value of the currently hooked version ($asd_admin_menu_version)
 * and if this module is higher version, the function
 *   unhook_asd_admin_functions_1_201812101();
 * is called to unhook the old version, and the function
 *   setup_asd_admin_functions_1_201812101();
 * is called to hook the new versions.
 * This can happen more than once, so that in the end the highest version
 * will be the one that is hooked.
 * ---------------------------------------------------------------------------------- */

$asd_admin_menu_file_data    = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
$this_asd_admin_menu_version = $asd_admin_menu_file_data['Version'];

/** ----------------------------------------------------------------------------
 *   a global array listing all the registered ASD post types.
 *  --------------------------------------------------------------------------*/
if ( ! isset( $asd_cpt_list ) ) {
	$asd_cpt_list = array();
}

/** ----------------------------------------------------------------------------
 *   a global array listing all the registered ASD taxonomies
 *  --------------------------------------------------------------------------*/
if ( ! isset( $asd_tax_list ) ) {
	$asd_tax_list = array();
}


if ( ! function_exists( 'asd_register_option_groups_1_201812101' ) ) {
	/**
	 * ----------------------------------------------------------------------------
	 *   function asd_register_option_groups_1_201812101()
	 *   if a newer version of asd-admin-menu is detected, this function
	 *   is called to unhook the old version from filters
	 *  ----------------------------------------------------------------------------
	 */
	function asd_register_option_groups_1_201812101() {
		add_settings_section( 'asd_dashboard_option_section_id', 'Custom Type Menu Options', 'asd_dashboard_option_section_1_201812101', 'asd_dashboard_option_group' );
		add_settings_section( 'asd_dashboard_option_section2_id', 'Custom Taxonomy Menu Options', 'asd_dashboard_option_section2_1_201812101', 'asd_dashboard_option_group2' );
	}
	if ( is_admin() ) {
		add_action( 'admin_init', 'asd_register_option_groups_1_201812101', 10 );
	}
}



if ( ! function_exists( 'asd_dashboard_option_section_1_201812101' ) ) {
	/**
	 * ----------------------------------------------------------------------------
	 *   function asd_dashboard_option_section_1_201812101()
	 *   calls the action to add options section for where Custom Types
	 *   appear in the Dashboard
	 *  ----------------------------------------------------------------------------
	 */
	function asd_dashboard_option_section_1_201812101() {
		echo '<i>Customize where Custom Types appear in your Dashboard.</i><br>' . "\r\n";
		do_action( 'asd_dashboard_option_section' );
	}
}

if ( ! function_exists( 'asd_dashboard_option_section2_1_201812101' ) ) {
	/**
	 * ----------------------------------------------------------------------------
	 *   function asd_dashboard_option_section2_1_201812101()
	 *   calls the action to add options section for where Custom Taxonomies
	 *   appear in the Dashboard
	 *  ----------------------------------------------------------------------------
	 */
	function asd_dashboard_option_section2_1_201812101() {
		echo '<i>Customize where Custom Taxonomies appear in your Dashboard.</i><br>' . "\r\n";
		do_action( 'asd_dashboard_option_section2' );
	}
}





if ( ! function_exists( 'unhook_asd_admin_functions_1_201812101' ) ) {
	/**
	 * ----------------------------------------------------------------------------
	 *   function unhook_asd_admin_functions_1_201812101()
	 *   if a newer version of asd-admin-menu is detected, this function
	 *   is called to unhook the old version from filters
	 *  ----------------------------------------------------------------------------
	 */
	function unhook_asd_admin_functions_1_201812101() {
		global $asd_admin_menu_version;
		$underscore_asd_admin_menu_version = str_replace( '.', '_', $asd_admin_menu_version );
		remove_action( 'admin_init', 'asd_register_option_groups_' . $underscore_asd_admin_menu_version, 10 );
		remove_action( 'admin_menu', 'asd_admin_menu_' . $underscore_asd_admin_menu_version, 11 );
		remove_action( 'admin_menu', 'asd_category_admin_submenu_' . $underscore_asd_admin_menu_version, 16 );
		remove_action( 'admin_enqueue_scripts', 'asd_setup_asd_admin_enqueues_' . $underscore_asd_admin_menu_version, 16 );

		remove_action( 'asd_settings_tabs_links', 'asd_settings_tabs_links_standard_' . $underscore_asd_admin_menu_version, 10 );
		remove_action( 'asd_settings_tabs_content', 'asd_settings_tabs_content_standard_' . $underscore_asd_admin_menu_version, 10 );

		remove_filter( 'custom_menu_order', 'asd_custom_menu_order_' . $underscore_asd_admin_menu_version, 12 );
		remove_filter( 'menu_order', 'asd_custom_menu_order_' . $underscore_asd_admin_menu_version, 12 );
	}
}


if ( ! function_exists( 'asd_setup_asd_admin_enqueues_1_201812101' ) ) {
	/**
	 * ----------------------------------------------------------------------------
	 *   function asd_setup_asd_admin_enqueues_1_201812101()
	 *   enqueue jquery, ui, tabs, css theme
	 *  --------------------------------------------------------------------------
	 */
	function asd_setup_asd_admin_enqueues_1_201812101() {
		global $this_asd_admin_menu_version;
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_style( 'asd-jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this_asd_admin_menu_version );

	}
}
add_action( 'admin_enqueue_scripts', 'asd_setup_asd_admin_enqueues_1_201812101' );


if ( ! function_exists( 'setup_asd_admin_functions_1_201812101' ) ) {
	/**
	 * ----------------------------------------------------------------------------
	 *   function setup_asd_admin_functions_1_201812101()
	 *   groups the functions and their filter hook calls
	 *  --------------------------------------------------------------------------
	 */
	function setup_asd_admin_functions_1_201812101() {

		if ( ! function_exists( 'asd_admin_menu_1_201812101' ) ) {
			/**
			 * ----------------------------------------------------------------------------
			 *   function asd_admin_menu_1_201812101()
			 *   Adds the top-level menu, named Artisan Site Designs
			 *   hooks into the admin_menu action
			 *  --------------------------------------------------------------------------
			 */
			function asd_admin_menu_1_201812101() {
				add_menu_page(
					'Artisan Site Designs',
					'Artisan Site Designs',
					'manage_options',
					'asd_settings',
					'asd_admin_menu_settings_1_201812101',
					'dashicons-admin-generic',
					'2'
				);
			}
			if ( is_admin() ) {
				add_action( 'admin_menu', 'asd_admin_menu_1_201812101', 11 );
			}
		}

		if ( ! function_exists( 'asd_admin_menu_settings_1_201812101' ) ) {
			/**
			 * ----------------------------------------------------------------------------
			 *   function asd_admin_menu_settings()
			 *   Adds a little text to the top-level menu, a little plug.
			 *   This function is a callback in asd_admin_menu()
			 *  --------------------------------------------------------------------------
			 */
			function asd_admin_menu_settings_1_201812101() {

				echo '<a target="_blank" href="https://artisansitedesigns.com"><h1>Artisan Site Designs</h1></a>';

				echo '<script type="text/javascript">' . "\r\n";
				echo '   jQuery(function() {' . "\r\n";
				echo '      jQuery("#asd_settings_tabs").tabs();' . "\r\n";
				echo '   });' . "\r\n";
				echo '</script>' . "\r\n";

				echo '<div id="asd_settings_tabs">' . "\r\n";
				echo '   <ul>' . "\r\n";
				do_action( 'asd_settings_tabs_links' );
				echo '   </ul>' . "\r\n";
				do_action( 'asd_settings_tabs_content' );
				echo '</div>' . "\r\n";

				info_on_published_plugins_1_201812101();

				echo '<br><br><h4>Library and Version Info:</h4>' . "\r\n";

				global $asd_admin_menu_version;
				echo 'ASD Admin Menu Version = ';
				if ( isset( $asd_admin_menu_version ) ) {
					echo esc_attr( $asd_admin_menu_version ) . "<br>\r\n";
				} else {
					echo "unset<br>\r\n";
				}

				global $asd_function_lib_version;
				echo 'ASD Function Library Version = ';
				if ( isset( $asd_function_lib_version ) ) {
					echo esc_attr( $asd_function_lib_version ) . "<br>\r\n";
				} else {
					echo "unset<br>\r\n";
				}

				global $asd_addcustom_post_version;
				echo 'ASD Add Custom Post Class Version = ';
				if ( isset( $asd_addcustom_post_version ) ) {
					echo esc_attr( $asd_addcustom_post_version ) . "<br>\r\n";
				} else {
					echo "unset<br>\r\n";
				}

				global $asd_custom_post_version;
				echo 'ASD Parent Custom Post Class Version = ';
				if ( isset( $asd_custom_post_version ) ) {
					echo esc_attr( $asd_custom_post_version ) . "<br>\r\n";
				} else {
					echo "unset<br>\r\n";
				}

				global $asd_register_site_data_version;
				echo 'ASD Register Site Data Version = ';
				if ( isset( $asd_register_site_data_version ) ) {
					echo esc_attr( $asd_register_site_data_version ) . "<br>\r\n";
				} else {
					echo "unset<br>\r\n";
				}

			}
		}

		if ( ! function_exists( 'asd_settings_tabs_links_standard_1_201812101' ) ) {
			/**
			 * ----------------------------------------------------------------------------
			 *   function asd_settings_tabs_links_standard_1_201812101()
			 *  --------------------------------------------------------------------------
			 */
			function asd_settings_tabs_links_standard_1_201812101() {
				echo '<li><a href="#asd_settings_tabs_content_custom_post_types">Custom Post Types</a></li>' . "\r\n";
				echo '<li><a href="#asd_settings_tabs_content_dashboard_options">Dashboard Options</a></li>' . "\r\n";
			}
			if ( is_admin() ) {
				add_action( 'asd_settings_tabs_links', 'asd_settings_tabs_links_standard_1_201812101' );
			}
		}

		if ( ! function_exists( 'asd_settings_tabs_content_standard_1_201812101' ) ) {
			/**
			 * ----------------------------------------------------------------------------
			 *   function asd_settings_tabs_content_standard_1_201812101()
			 *  --------------------------------------------------------------------------
			 */
			function asd_settings_tabs_content_standard_1_201812101() {

				global $asd_cpt_list;
				global $asd_tax_list;

				echo '<div id="asd_settings_tabs_content_custom_post_types">' . "\r\n";
				echo '      <h3>Custom Post Types</h3>' . "\r\n";

				foreach ( $asd_cpt_list as $asd_cpt ) {
					echo '<div class="row clearfix">' . "\r\n";

					echo '   <div style="float:left;width:15%">' . "\r\n";
					echo '      <a href="' . esc_url( site_url( '/wp-admin/edit.php?post_type=' . esc_attr( $asd_cpt['slug'] ) ) ) . '">' . "\r\n";
					echo esc_attr( $asd_cpt['name'] ) . "\r\n";
					echo '      </a>' . "\r\n";
					echo '   </div>' . "\r\n";

					echo '   <div style="float:left;width:85%">' . "\r\n";
					echo '      <small><i>' . "\r\n";
					echo esc_attr( $asd_cpt['desc'] ) . "<br>\r\n";

					if ( '' !== $asd_cpt['link'] ) {
						echo '<a target="_blank" href="' . esc_url( $asd_cpt['link'] ) . '">More information about ' . esc_attr( $asd_cpt['name'] ) . ' on WordPress.org</a><br>' . "\r\n";
					}

					echo '      </small></i>' . "\r\n";

					echo '   </div>' . "\r\n";

					echo '</div>' . "\r\n";
					echo '&nbsp;<br>' . "\r\n";
				}

				echo '</div>' . "\r\n";

				echo '<div id="asd_settings_tabs_content_dashboard_options">' . "\r\n";

				echo '   <form method="post" action="options.php">' . "\r\n";
				settings_fields( 'asd_dashboard_option_group' );
				do_settings_sections( 'asd_dashboard_option_group' );
				submit_button( 'Save Custom Type Options' );
				echo '   </form>' . "\r\n";

				echo '   <form method="post" action="options.php">' . "\r\n";
				settings_fields( 'asd_dashboard_option_group2' );
				do_settings_sections( 'asd_dashboard_option_group2' );
				submit_button( 'Save Taxonomy Options' );
				echo '   </form>' . "\r\n";

				echo '</div>' . "\r\n";

			}
			if ( is_admin() ) {
				add_action( 'asd_settings_tabs_content', 'asd_settings_tabs_content_standard_1_201812101' );
			}
		}

		if ( ! function_exists( 'asd_custom_menu_order_1_201812101' ) ) {
			/**
			 * ----------------------------------------------------------------------------
			 *   function asd_custom_menu_order( $menu_ord )
			 *   Sets order of top-level menus
			 *   Hooks into the custom_menu_order and menu_order filters
			 *   returns an array list of links to admin pages
			 *  ----------------------------------------------------------------------------
			 *
			 * @param Array $menu_ord -  if this is not defined the function returns true.
			 */
			function asd_custom_menu_order_1_201812101( $menu_ord ) {
				if ( ! $menu_ord ) {
					return true;
				}

				$asd_menu_entries = array();

				$asd_menu_entries[] = 'index.php';
				$asd_menu_entries[] = 'admin.php?page=asd_settings';
				$asd_menu_entries[] = 'edit.php?post_type=page';
				$asd_menu_entries[] = 'upload.php';
				$asd_menu_entries[] = 'edit.php';
				$asd_menu_entries[] = 'link-manager.php';
				$asd_menu_entries[] = 'edit-comments.php';
				$asd_menu_entries[] = 'separator2';
				$asd_menu_entries[] = 'themes.php';
				$asd_menu_entries[] = 'plugins.php';
				$asd_menu_entries[] = 'users.php';
				$asd_menu_entries[] = 'tools.php';
				$asd_menu_entries[] = 'options-general.php';
				$asd_menu_entries[] = 'separator-last';

				return $asd_menu_entries;
			}
			if ( is_admin() ) {
				add_filter( 'custom_menu_order', 'asd_custom_menu_order_1_201812101', 12 );
				add_filter( 'menu_order', 'asd_custom_menu_order_1_201812101', 12 );
			}
		}

		if ( ! function_exists( 'asd_category_admin_submenu_1_201812101' ) ) {
			/**
			 * ----------------------------------------------------------------------------
			 *   function asd_category_admin_submenu()
			 *   Adds "categories"  to the top-level menu
			 *   hooks into the admin_menu action
			 *  --------------------------------------------------------------------------
			 */
			function asd_category_admin_submenu_1_201812101() {
				add_submenu_page(
					'asd_settings',
					'Categories',
					'Categories',
					'manage_options',
					'edit-tags.php?taxonomy=category',
					''
				);
			}
			if ( is_admin() ) {
				add_action( 'admin_menu', 'asd_category_admin_submenu_1_201812101', 16 );
			}
		}

		if ( ! function_exists( 'info_on_published_plugins_1_201812101' ) ) {
			/**
			 * ----------------------------------------------------------------------------
			 *   function asd_category_admin_submenu()
			 *   Adds "categories"  to the top-level menu
			 *   hooks into the admin_menu action
			 *  --------------------------------------------------------------------------
			 */
			function info_on_published_plugins_1_201812101() {

				$moreplugins = '';

				if ( ! defined( 'ASD_FASTBUILD_DIR' ) ) {
					$moreplugins .= '<a target="_blank" href="https://wordpress.org/plugins/asd-fastbuild-widgets"><h4>ASD FastBuild Widgets</h4></a>' . "\r\n";
				}
				if ( ! defined( 'ASD_PRODUCTS_DIR' ) ) {
					$moreplugins .= '<a target="_blank" href="https://wordpress.org/plugins/asd-products"><h4>ASD Products</h4></a>' . "\r\n";
				}
				if ( ! defined( 'ASD_ROCKANDROLL_POWERSLIDER_DIR' ) ) {
					$moreplugins .= '<a target="_blank" href="https://wordpress.org/plugins/asd-rockandroll-powerslider"><h4>ASD RockAndRoll Powerslider</h4></a>' . "\r\n";
				}
				if ( ! defined( 'ASD_FULLWIDTH_ELEMENT_SIZER_DIR' ) ) {
					$moreplugins .= '<a target="_blank" href="https://wordpress.org/plugins/asd-fullwidth-element-sizer"><h4>ASD FullWidth Element Sizer</h4></a>' . "\r\n";
				}

				if ( '' !== $moreplugins ) {
					echo '<h3>Other ASD Plugins on the WordPress official plugin repository:</h3>' . wp_kses_post( $moreplugins ) . "\r\n";
				}

			}
		}

	}
}



if ( ! isset( $asd_admin_menu_version ) ) {
	$asd_admin_menu_version = $this_asd_admin_menu_version;
	setup_asd_admin_functions_1_201812101();
} else {
	if ( $this_asd_admin_menu_version > $asd_admin_menu_version ) {
		unhook_asd_admin_functions_1_201812101();
		setup_asd_admin_functions_1_201812101();
		$asd_admin_menu_version = $this_asd_admin_menu_version;
	}
}
