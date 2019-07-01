<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/AgriLife/agrilife-degree-programs/blob/master/src/class-degreeprograms.php
 * @since      0.1.0
 * @package    agrilife-degree-programs
 * @subpackage agrilife-degree-programs/src
 */

/**
 * The core plugin class
 *
 * @since 0.1.0
 * @return void
 */
class DegreePrograms {

	/**
	 * File name
	 *
	 * @var file
	 */
	private static $file = __FILE__;

	/**
	 * Instance
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function __construct() {

		$this->register_templates();

		add_action( 'init', array( $this, 'init' ) );

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {

		/* Set up asset files */
		require_once AGDPR_DIR_PATH . 'src/class-assets.php';
		$assets = new \DegreePrograms\Assets();

		/* Add taxonomies */
		require_once AGDPR_DIR_PATH . 'src/class-taxonomy.php';
		new \DegreePrograms\Taxonomy( 'Department', 'department', 'degree-program' );
		new \DegreePrograms\Taxonomy( 'Degree Type', 'degree-type', 'degree-program' );
		new \DegreePrograms\Taxonomy( 'Interest', 'interest', 'degree-program' );

		/* Add custom post type */
		require_once AGDPR_DIR_PATH . 'src/class-posttype.php';
		$post_type = new \DegreePrograms\PostType(
			array(
				'singular' => 'Degree Program',
				'plural'   => 'Degree Programs',
			),
			AGDPR_TEMPLATE_PATH,
			'degree-program',
			'agrilife-degree-program',
			array(),
			'dashicons-portfolio',
			array( 'title', 'editor', 'thumbnail', 'genesis-seo', 'genesis-scripts' ),
			array( 'single' => 'single-degree-program.php' )
		);

		/* Flush rewrite rules on plugin installation */
		if ( get_option( 'af4_agrilife_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'af4_agrilife_flush_rewrite_rules_flag' );
		}

	}

	/**
	 * Initialize page templates
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_templates() {

		require_once AGDPR_DIR_PATH . '/src/class-pagetemplate.php';
		$search = new \DegreePrograms\PageTemplate( AGDPR_TEMPLATE_PATH, 'degree-search.php', 'Degree Search' );
		$search->register();

	}

	/**
	 * Autoloads any classes called within the theme
	 *
	 * @since 0.1.0
	 * @param string $classname The name of the class.
	 * @return void.
	 */
	public static function autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
			DIRECTORY_SEPARATOR .
			str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
			'.php';

		if ( file_exists( $filename ) ) {
			require $filename;
		}

	}

	/**
	 * Return instance of class
	 *
	 * @since 0.1.0
	 * @return object.
	 */
	public static function get_instance() {

		return null === self::$instance ? new self() : self::$instance;

	}

}
