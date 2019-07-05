<?php
/**
 * The file that renders the degree search page
 *
 * @link       https://github.com/AgriLife/agrilife-degree-programs/blob/master/templates/degree-search.php
 * @since      0.1.0
 * @package    agrilife-degree-programs
 * @subpackage agrilife-degree-programs/templates
 */

add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
add_action( 'genesis_before_content', 'degree_search_filters' );
add_action( 'genesis_entry_content', 'degree_search_content' );
add_filter( 'genesis_attr_content', 'degree_content_attr' );

/**
 * Add class name to content element
 *
 * @since 0.1.4
 * @param array $attributes HTML attributes.
 * @return array
 */
function degree_content_attr( $attributes ) {
	$attributes['class'] .= ' cell small-12 medium-8';
	return $attributes;
}

/**
 * Show degree search filters.
 *
 * @since 0.1.0
 * @return void
 */
function degree_search_filters() {

	$output = '<div class="cell small-12 medium-4">';

	// Get taxonomies.
	$departments = get_terms( 'department' );
	$degreetypes = get_terms( 'degree-type' );
	$interests   = get_terms( 'interest' );
	$checkbox    = '<input type="checkbox" id="dept_%s" value="%s"><label for="dept_%s"> %s</label>';

	// Taxonomy search bar output.
	$output .= '<h2>Departments</h2>';
	foreach ( $departments as $key => $value ) {
		$output .= sprintf(
			$checkbox,
			$value->term_id,
			$value->slug,
			$value->term_id,
			$value->name
		);
	}

	$output .= '<h2>Degree Types</h2>';
	foreach ( $degreetypes as $key => $value ) {
		$output .= sprintf(
			$checkbox,
			$value->term_id,
			$value->slug,
			$value->term_id,
			$value->name
		);
	}

	$output .= '<h2>Interests</h2>';
	foreach ( $interests as $key => $value ) {
		$output .= sprintf(
			$checkbox,
			$value->term_id,
			$value->slug,
			$value->term_id,
			$value->name
		);
	}

	$output .= '</div>';

	// Output.
	echo wp_kses_post( $output );

}

/**
 * Provide the post body content.
 *
 * @since 0.1.0
 * @return void
 */
function degree_search_content() {

	$output = '';

	// Get degrees.
	$degrees = new WP_Query( array( 'post_type' => 'degree-program' ) );

	// Post list.
	foreach ( $degrees->posts as $key => $value ) {

		$terms = wp_get_post_terms( $value->ID, array( 'department', 'degree-type', 'interest' ) );
		$class = [];

		foreach ( $terms as $term ) {
			$class[] = "term-{$term->taxonomy} {$term->taxonomy}-{$term->slug}";
		}

		$output .= sprintf(
			'<a class="%s" href="%s">%s<div>%s</div></a>',
			implode( ' ', $class ),
			get_permalink( $value->ID ),
			get_the_post_thumbnail( $value->ID, 'thumbnail' ),
			$value->post_title
		);
	}

	// Output.
	echo wp_kses_post( $output );

}

get_header();
genesis();
