<?php
/**
 * The file that loads and handles custom fields
 *
 * @link       https://github.com/AgriLife/agrilife-degree-programs/blob/master/src/class-customfields.php
 * @since      0.1.0
 * @package    agrilife-degree-programs
 * @subpackage agrilife-degree-programs/src
 */

namespace DegreePrograms;

/**
 * The custom fields class
 *
 * @since 0.1.0
 * @return void
 */
class CustomFields {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// Add page template custom fields.
		if ( class_exists( 'acf' ) ) {
			require_once AGDPR_DIR_PATH . 'fields/degree-fields.php';
			require_once AGDPR_DIR_PATH . 'fields/search-fields.php';
		}

		// Action hooks to make Degree Link custom field editable from the Quick Edit screen.
		add_filter( 'manage_edit-degree-program_columns', array( $this, 'add_columns' ) );
		add_action( 'manage_degree-program_posts_custom_column', array( $this, 'show_value' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'add_custom_box' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_quick_edit_value' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'autoload_existing_field_value' ) );

	}

	/**
	 * Add columns for custom fields
	 *
	 * @since 0.1.0
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_columns( $columns ) {

		$columns['degree_program_link'] = 'Degree Link';
		return $columns;

	}

	/**
	 * Echo the string value of the custom field.
	 *
	 * @since 0.1.0
	 * @param string $column_name The column name being handled.
	 * @param int    $post_id The post id being handled.
	 * @return void
	 */
	public function show_value( $column_name, $post_id ) {

		if ( 'degree_program_link' !== $column_name ) {
			return;
		}

		$degreelink = get_post_meta( $post_id, 'degree_program_link', true );
		$output     = empty( $degreelink ) ? 'No' : $degreelink;

		echo esc_html( $output );

	}

	/**
	 * Add HTML for the custom field as a form field.
	 *
	 * @since 0.1.0
	 * @param string $column_name The column name being handled.
	 * @param string $post_type The post type being handled.
	 * @return void
	 */
	public function add_custom_box( $column_name, $post_type ) {

		if ( 'degree_program_link' !== $column_name ) {
			return;
		}

		static $print_nonce = true;
		if ( $print_nonce ) {
			$print_nonce = false;
			wp_nonce_field( AGDPR_BASENAME, 'degree-program_edit_nonce' );
		} ?> <fieldset class="inline-edit-col-right inline-edit-custom column-<?php echo esc_attr( $column_name ); ?>"> <div class="inline-edit-col inline-edit-<?php echo esc_attr( $column_name ); ?>">
		<?php
		switch ( $column_name ) {
			case 'degree_program_link':
				?>
			<legend>Degree Link</legend><textarea cols="42" rows="1" name="degree_program_link" class="degree_program_link" autocomplete="off" role="combobox" aria-expanded="false"></textarea>
					<?php
				break;
		}
		?>
		</div> </fieldset>
		<?php

	}

	/**
	 * Save the new value entered during Quick Edit.
	 *
	 * @since 0.1.0
	 * @param int $post_id The post id being handled.
	 * @return void
	 */
	public function save_quick_edit_value( $post_id ) {

		$post_slug = 'degree-program';
		$slug      = 'degree_program_link';
		$post_type = get_post_type( $post_id );

		if ( isset( $_POST[ "{$post_slug}_edit_nonce" ] ) ) {
			$nonce        = sanitize_key( $_POST[ "{$post_slug}_edit_nonce" ] );
			$verify_nonce = wp_verify_nonce( $nonce, AGDPR_BASENAME );
		} else {
			$verify_nonce = false;
		}

		if ( $post_slug !== $post_type ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( false === $verify_nonce ) {
			return;
		}

		if ( isset( $_REQUEST[ $slug ] ) ) {
			update_post_meta(
				$post_id,
				$slug,
				wp_slash( sanitize_text_field( wp_unslash( $_REQUEST[ $slug ] ) ) )
			);
		}

	}

	/**
	 * Use JavaScript to set the default value for Quick Edit form fields.
	 *
	 * @since 0.1.0
	 * @param string $hook The file being handled.
	 * @return void
	 */
	public function autoload_existing_field_value( $hook ) {

		global $post_type;
		$post_slug = 'degree-program';

		if ( 'edit.php' === $hook && $post_slug === $post_type ) {

			wp_enqueue_script(
				'agrilife_degree_program_edit',
				plugins_url( 'js/admin_edit.js', AGDPR_DIR_FILE ),
				false,
				filemtime( AGDPR_DIR_PATH . 'js/admin_edit.js' ),
				true
			);

		}

	}


}
