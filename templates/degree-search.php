<?php
/**
 * The file that renders the degree search page
 *
 * @link       https://github.com/AgriLife/agrilife-degree-programs/blob/master/templates/degree-search.php
 * @since      0.1.0
 * @package    agrilife-degree-programs
 * @subpackage agrilife-degree-programs/templates
 */

// Force page layout.
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

// Move page heading before sidebar and content containers.
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_entry_header_markup_open', 5 );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_entry_header_markup_close', 15 );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_post_title', 11 );

// Page content.
add_action( 'genesis_before_content', 'degree_search_filters' );
add_action( 'genesis_entry_content', 'degree_search_content' );

/**
 * Get degree program posts based on a custom field taxonomy.
 *
 * @since 0.1.0
 * @param array $args Args for a WP_Query call.
 * @return WP_Query object
 */
function adp_get_degree_posts( $args = array() ) {

	// Get taxonomies of posts which have a given Level taxonomy.
	$args   = array_merge(
		array(
			'post_type'      => 'degree-program',
			'posts_per_page' => -1,
		),
		$args
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

	return new WP_Query( $args );

}

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
					'open'    => '<aside class="degree-search-sidebar widget-area cell small-12 medium-3">' . genesis_sidebar_title( $id ) . '<h2>Filter Programs<a href="#" class="reset-degree-search">Reset</a></h2>',
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

	$output   = $sidebar_defaults['before'];
	$query    = adp_get_degree_posts( array( 'fields' => 'ids' ) );
	$post_ids = $query->posts;

	if ( empty( $post_ids ) ) {

		return;

	}

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
	$departments = get_terms(
		array(
			'taxonomy'   => 'department',
			'object_ids' => $post_ids,
		)
	);

	// Taxonomy search bar output.
	$checkbox = '<li class="item grid-x"><input class="cell shrink %s" type="checkbox" id="dept_%s" value="%s-%s"><label class="cell auto" for="dept_%s">%s</label></li>';
	$output  .= '<ul id="degree-filters" class="reset">';
	$output  .= '<li><h3>Degree Types</h3>';
	$output  .= '<ul>';
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
	$output .= '</ul></li>';

	$output .= '<li><h3>Interest Categories</h3>';
	$output .= '<ul>';
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
	$output .= '</ul></li>';

	$output .= '<li><h3>Departments</h3>';
	$output .= '<ul class="last">';
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
	$output .= '</ul></li></ul>';

	$output .= $sidebar_defaults['after'];

	// Output.
	echo wp_kses(
		$output,
		array(
			'aside' => array(
				'class' => array(),
			),
			'ul'    => array(
				'id'    => array(),
				'class' => array(),
			),
			'li'    => array(
				'class' => array(),
			),
			'a'     => array(
				'href'  => array(),
				'class' => array(),
			),
			'div'   => array(
				'class' => array(),
			),
			'h2'    => array(),
			'h3'    => array(),
			'label' => array(
				'class' => array(),
				'for'   => array(),
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

	$output  = '<div class="grid-container full"><div class="degrees grid-x">';
	$degrees = adp_get_degree_posts();

	if ( empty( $degrees->posts ) ) {

		return;

	}

	// Post list.
	foreach ( $degrees->posts as $key => $value ) {

		$terms = wp_get_post_terms( $value->ID, array( 'department', 'degree-type', 'interest' ) );
		$class = [ 'degree', 'cell', 'medium-3', 'small-6' ];
		$link  = get_field( 'degree_program', $value->ID );
		$thumb = get_the_post_thumbnail( $value->ID, 'medium' );
		$href  = $link ? " href=\"{$link['link']}\"" : '';
		$tag   = $link ? 'a' : 'div';
		foreach ( $terms as $term ) {
			$class[] = "{$term->taxonomy}-{$term->slug}";
		}
		if ( empty( $thumb ) ) {
			$thumb = sprintf(
				'<img alt="Image unavailable" src="%simages/default.svg" style="border:1px solid black;" />',
				AGDPR_DIR_URL
			);
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
			$thumb,
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
