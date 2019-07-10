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

	// Get taxonomies.
	$departments = get_terms( 'department' );
	$degreetypes = get_terms( 'degree-type' );
	$interests   = get_terms( 'interest' );
	$checkbox    = '<li><input class="degree-filter %s" type="checkbox" id="dept_%s" value="%s-%s"><label for="dept_%s"> %s</label></li>';

	// Taxonomy search bar output.
	$output .= '<h2>Departments</h2>';
	$output .= '<ul class="reset">';
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
	$degrees = new WP_Query( array( 'post_type' => 'degree-program' ) );

	// Post list.
	foreach ( $degrees->posts as $key => $value ) {

		$terms = wp_get_post_terms( $value->ID, array( 'department', 'degree-type', 'interest' ) );
		$class = [ 'degree', 'cell', 'medium-3', 'small-6' ];
		$link  = get_field( 'degree_program', $value->ID );
		$href  = $link ? " href=\"{$link['link']}\"" : '';
		$tag   = $link ? 'a' : 'div';
		$open  = sprintf(
			'<%s class="%s"%s>',
			$tag,
			implode( ' ', $class ),
			$href
		);
		$close = "</{$tag}>";

		foreach ( $terms as $term ) {
			$class[] = "{$term->taxonomy}-{$term->slug}";
		}

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
