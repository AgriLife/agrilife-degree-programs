<?php
/**
 * College degree program custom post type.
 *
 * @package      agrilife-degree-programs
 * @author       Zachary Watkins
 * @copyright    2019 Texas A&M AgriLife Communications
 * @license      GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:  AgriLife Degree Programs
 * Plugin URI:   https://github.com/AgriLife/agrilife-degree-programs
 * Description:  College degree program custom post type.
 * Version:      0.1.0
 * Author:       Zachary Watkins
 * Author URI:   https://github.com/ZachWatkins
 * Author Email: zachary.watkins@ag.tamu.edu
 * Text Domain:  agrilife-degree-programs
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* Define some useful constants */
define( 'AGDPR_DIRNAME', 'agrilife-degree-programs' );
define( 'AGDPR_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'AGDPR_DIR_FILE', __FILE__ );
define( 'AGDPR_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'AGDPR_BASENAME', plugin_basename( __FILE__ ) );
define( 'AGDPR_TEXTDOMAIN', 'agrilife-degree-programs' );
define( 'AGDPR_TEMPLATE_PATH', AGDPR_DIR_PATH . 'templates' );

/* Code for plugins */
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'agrilife_degree_programs_activation' );

/**
 * Helper option flag to indicate rewrite rules need flushing
 *
 * @since 0.1.0
 * @return void
 */
function agrilife_degree_programs_activation() {

	if ( ! get_option( 'AGDPR_flush_rewrite_rules_flag' ) ) {

		add_option( 'AGDPR_flush_rewrite_rules_flag', true );

	}

	// Check for missing dependencies.
	$plugin = is_plugin_active( 'advanced-custom-fields-pro/acf.php' );
	if ( false === $plugin ) {
		$error = sprintf(
			/* translators: %s: URL for plugins dashboard page */
			__(
				'Plugin NOT activated: The <strong>AgriLife Degree Programs</strong> plugin needs the <strong>Advanced Custom Fields Pro</strong> plugin to be installed and activated first. <a href="%s">Back to plugins page</a>',
				'agrilife-degree-programs'
			),
			get_admin_url( null, '/plugins.php' )
		);
		wp_die( wp_kses_post( $error ) );
	}

}

/**
 * The core plugin class that is used to initialize the plugin
 */
require AGDPR_DIR_PATH . 'src/class-degreeprograms.php';

/* Autoload all classes */
spl_autoload_register( 'DegreePrograms::autoload' );
DegreePrograms::get_instance();
