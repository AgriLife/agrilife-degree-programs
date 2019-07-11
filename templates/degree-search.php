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

/**
 * Show degree search filters.
 *
 * @since 0.1.0
 * @return void
 */
function degree_search_filters() {

	$id               = 'degree-sidebar-search';
	$sidebar_defaults = apply_filters(
		'genesis_widget_area_defaults',
		array(
			'before'              => genesis_markup(
				array(
					'open'    => '<aside class="widget-area cell small-12 medium-3">' . genesis_sidebar_title( $id ),
					'context' => 'widget-area-wrap',
					'echo'    => false,
					'params'  => array(
						'id' => $id,
					),
				)
			),
			'after'               => genesis_markup(
				array(
					'close'   => '</aside>',
					'context' => 'widget-area-wrap',
					'echo'    => false,
				)
			),
			'default'             => '',
			'show_inactive'       => 0,
			'before_sidebar_hook' => 'genesis_before_' . $id . '_widget_area',
			'after_sidebar_hook'  => 'genesis_after_' . $id . '_widget_area',
		),
		'degree-sidebar-search',
		array()
	);

	$output = $sidebar_defaults['before'];

	// Get taxonomies of posts which have a Level taxonomy of Undergrad.
	$args   = array(
		'post_type'      => 'degree-program',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	);
	$fields = get_field( 'degree_program_search' );
	$level  = $fields['degree_level'];

	if ( $level ) {

		$level             = $level->slug;
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'level',
				'field'    => 'slug',
				'terms'    => $level,
			),
		);

	}

	$query    = new WP_Query( $args );
	$post_ids = $query->posts;

	if ( empty( $post_ids ) ) {

		return;

	}

	$departments = get_terms(
		array(
			'taxonomy'   => 'department',
			'object_ids' => $post_ids,
		)
	);
	$degreetypes = get_terms(
		array(
			'taxonomy'   => 'degree-type',
			'object_ids' => $post_ids,
		)
	);
	$interests   = get_terms(
		array(
			'taxonomy'   => 'interest',
			'object_ids' => $post_ids,
		)
	);

	// Taxonomy search bar output.
	$checkbox = '<li><input class="degree-filter %s" type="checkbox" id="dept_%s" value="%s-%s"><label for="dept_%s"> %s</label></li>';
	$output  .= '<h2>Departments</h2>';
	$output  .= '<ul class="reset">';
	foreach ( $departments as $key => $value ) {
		$output .= sprintf(
			$checkbox,
			"department-{$value->slug}",
			$value->term_id,
			'department',
			$value->slug,
			$value->term_id,
			$value->name
		);
	}
	$output .= '</ul>';

	$output .= '<h2>Degree Types</h2>';
	$output .= '<ul class="reset">';
	foreach ( $degreetypes as $key => $value ) {
		$output .= sprintf(
			$checkbox,
			"degree-type-{$value->slug}",
			$value->term_id,
			'degree-type',
			$value->slug,
			$value->term_id,
			$value->name
		);
	}
	$output .= '</ul>';

	$output .= '<h2>Interests</h2>';
	$output .= '<ul class="reset">';
	foreach ( $interests as $key => $value ) {
		$output .= sprintf(
			$checkbox,
			"interest-{$value->slug}",
			$value->term_id,
			'interest',
			$value->slug,
			$value->term_id,
			$value->name
		);
	}
	$output .= '</ul>';

	$output .= $sidebar_defaults['after'];

	// Output.
	echo wp_kses(
		$output,
		array(
			'aside' => array(
				'class' => array(),
			),
			'ul'    => array(
				'class' => array(),
			),
			'li'    => array(),
			'h2'    => array(),
			'label' => array(
				'for' => array(),
			),
			'input' => array(
				'class'    => array(),
				'onchange' => array(),
				'type'     => array(),
				'id'       => array(),
				'value'    => array(),
			),
		)
	);

}

/**
 * Provide the post body content.
 *
 * @since 0.1.0
 * @return void
 */
function degree_search_content() {

	$output = '<div class="grid-container full"><div class="degrees grid-x">';

	// Get degrees.
	$args   = array(
		'post_type' => 'degree-program',
	);
	$fields = get_field( 'degree_program_search' );
	$level  = $fields['degree_level'];

	if ( $level ) {

		$level             = $level->slug;
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'level',
				'field'    => 'slug',
				'terms'    => $level,
			),
		);

	}

	$degrees = new WP_Query( $args );

	if ( empty( $degrees->posts ) ) {

		return;

	}

	// Post list.
	foreach ( $degrees->posts as $key => $value ) {

		$terms = wp_get_post_terms( $value->ID, array( 'department', 'degree-type', 'interest' ) );
		$class = [ 'degree', 'cell', 'medium-3', 'small-6' ];
		$link  = get_field( 'degree_program', $value->ID );
		$href  = $link ? " href=\"{$link['link']}\"" : '';
		$tag   = $link ? 'a' : 'div';
		foreach ( $terms as $term ) {
			$class[] = "{$term->taxonomy}-{$term->slug}";
		}
		$open  = sprintf(
			'<%s class="%s"%s>',
			$tag,
			implode( ' ', $class ),
			$href
		);
		$close = "</{$tag}>";

		$output .= sprintf(
			'%s%s<div>%s</div>%s',
			$open,
			get_the_post_thumbnail( $value->ID, 'thumbnail' ),
			$value->post_title,
			$close
		);
	}

	$output .= '</div></div>';

	// Output.
	echo wp_kses_post( $output );

}

get_header();
genesis();
