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

		$this->require_classes();

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
		$assets = new \DegreePrograms\Assets();

		/* Add taxonomies */
		new \DegreePrograms\Taxonomy(
			'Level',
			'level',
			'degree-program',
			array(
				'hierarchical' => true,
				'public'       => false,
			)
		);
		new \DegreePrograms\Taxonomy( 'Degree Type', 'degree-type', 'degree-program', array( 'public' => false ) );
		new \DegreePrograms\Taxonomy( 'Interest', 'interest', 'degree-program', array( 'public' => false ) );

		// We share the department taxonomy with other custom post types.
		if ( ! taxonomy_exists( 'department' ) ) {
			new \DegreePrograms\Taxonomy( 'Department', 'department', null, array( 'public' => false ) );
		}

		/* Add custom post type */
		$post_type = new \DegreePrograms\PostType(
			array(
				'singular' => 'Degree Program',
				'plural'   => 'Degree Programs',
			),
			AGDPR_TEMPLATE_PATH,
			'degree-program',
			'agrilife-degree-program',
			array( 'department' ),
			'dashicons-portfolio',
			array( 'title', 'editor', 'thumbnail' ),
			array(),
			array(
				'public'              => false,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'exclude_from_search' => false,
				'show_in_nav_menus'   => false,
				'has_archive'         => true,
				'rewrite'             => false,
				'show_in_rest'        => true,
			)
		);

		// Add page template custom fields.
		$fields = new \DegreePrograms\CustomFields();

		/* Flush rewrite rules on plugin installation */
		if ( get_option( 'AGDPR_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'AGDPR_flush_rewrite_rules_flag' );
		}

	}

	/**
	 * Initialize page templates
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_templates() {

		$search = new \DegreePrograms\PageTemplate( AGDPR_TEMPLATE_PATH, 'degree-search.php', 'Degree Search' );
		$search->register();

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function require_classes() {

		// Set up asset files.
		require_once AGDPR_DIR_PATH . 'src/class-assets.php';

		// Add post type classes.
		require_once AGDPR_DIR_PATH . 'src/class-taxonomy.php';
		require_once AGDPR_DIR_PATH . 'src/class-posttype.php';
		require_once AGDPR_DIR_PATH . 'src/class-pagetemplate.php';

		// Add custom fields.
		require_once AGDPR_DIR_PATH . 'src/class-customfields.php';

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
