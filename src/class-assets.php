<?php
/**
 * The file that defines css and js files loaded for the plugin
 *
 * A class definition that includes css and js files used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/AgriLife/agrilife-degree-programs/blob/master/src/class-assets.php
 * @since      0.1.0
 * @package    agrilife-degree-programs
 * @subpackage agrilife-degree-programs/src
 */

namespace DegreePrograms;

/**
 * Add assets
 *
 * @package agrilife-degree-programs
 * @since 0.1.0
 */
class Assets {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// Register global styles used in the theme.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ), 2 );

		// Enqueue extension styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 2 );

		// Register scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 11 );

		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 13 );

	}

	/**
	 * Registers all styles used within the plugin
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_styles() {

		wp_register_style(
			'agrilife-degree-programs',
			AGDPR_DIR_URL . 'css/styles.css',
			array(),
			filemtime( AGDPR_DIR_PATH . 'css/styles.css' ),
			'screen'
		);

	}

	/**
	 * Enqueues extension styles
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'agrilife-degree-programs' );

	}

	/**
	 * Registers scripts
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_scripts() {

		wp_register_script(
			'agrilife-degree-programs',
			AGDPR_DIR_URL . '/js/degree-search.js',
			array( 'jquery' ),
			filemtime( AGDPR_DIR_PATH . 'js/degree-search.js' ),
			true
		);

	}

	/**
	 * Enqueues scripts
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'agrilife-degree-programs' );

	}

}
