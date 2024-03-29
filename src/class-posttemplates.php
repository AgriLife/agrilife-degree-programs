<?php
/**
 * The file that defines the custom post type page template class
 *
 * @link       https://github.com/AgriLife/agrilife-degree-programs/blob/master/src/class-posttemplates.php
 * @since      0.1.0
 * @package    agrilife-degree-programs
 * @subpackage agrilife-degree-programs/src
 */

namespace DegreePrograms;

/**
 * Redirects to correct template files based on query variables.
 * Also provides static methods to pull certain views
 */
class PostTemplates {

	/**
	 * Template file folder path
	 *
	 * @var path
	 */
	private $path;

	/**
	 * Custom post type slug
	 *
	 * @var post_type
	 */
	private $post_type;

	/**
	 * Single template file name
	 *
	 * @var single_file
	 */
	private $single_file;

	/**
	 * Archive template file name
	 *
	 * @var archive_file
	 */
	private $archive_file;

	/**
	 * Search template file name
	 *
	 * @var search_file
	 */
	private $search_file;

	/**
	 * Adds custom post type templates for different contexts
	 *
	 * @param  string $path     The path for the template file folder.
	 * @param  string $posttype The slug for the custom post type.
	 * @param  string $single   The single template.
	 * @param  string $archive  The archive template.
	 * @param  string $search   The search page template.
	 * @return void
	 */
	public function __construct( $path, $posttype, $single, $archive = false, $search = false ) {

		$this->path         = $path;
		$this->post_type    = $posttype;
		$this->single_file  = $single;
		$this->archive_file = $archive;
		$this->search_file  = $search;

		add_filter( 'single_template', array( $this, 'get_single_template' ) );

		if ( $archive ) {
			add_filter( 'archive_template', array( $this, 'get_archive_template' ) );
		}

		if ( $search ) {
			add_filter( 'search_template', array( $this, 'get_search_template' ) );
		}

	}

	/**
	 * Shows the archive template when needed
	 *
	 * @param  string $archive_template The default archive template.
	 * @return string                   The correct archive template
	 */
	public function get_archive_template( $archive_template ) {

		global $post;

		if ( is_post_type_archive( $this->post_type ) ) {
			$archive_template = $this->path . '/' . $this->archive_file;
		}

		return $archive_template;

	}

	/**
	 * Shows the single template when needed
	 *
	 * @param  string $single_template The default single template.
	 * @return string                  The correct single template
	 */
	public function get_single_template( $single_template ) {

		global $post;

		if ( get_query_var( 'post_type' ) === $this->post_type ) {
			$single_template = $this->path . '/' . $this->single_file;
		}

		return $single_template;

	}

	/**
	 * Shows the single template when needed
	 *
	 * @param  string $search_template The default single template.
	 * @return string                  The correct single template
	 */
	public function get_search_template( $search_template ) {

		if ( get_query_var( 'post_type' ) === $this->post_type ) {
			$search_template = $this->path . '/' . $this->archive_file;
		}

		return $search_template;

	}

}
