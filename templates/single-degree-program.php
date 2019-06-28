<?php
/**
 * The file that renders single degree program posts
 *
 * @link       https://github.com/AgriLife/agrilife-degree-programs/blob/master/templates/single-degree-program.php
 * @since      0.1.0
 * @package    agrilife-degree-programs
 * @subpackage agrilife-degree-programs/templates
 */

add_action( 'genesis_entry_content', 'degree_program_content' );

/**
 * Provide the post body content.
 *
 * @since 0.1.0
 * @return void
 */
function degree_program_content() {

	// Echo content.
}

get_header();
genesis();
