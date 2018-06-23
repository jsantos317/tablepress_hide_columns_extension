<?php
/*
Plugin Name: TablePress Extension: Hide Columns/Rows from logged-out users
Plugin URI: https://tablepress.org/extensions/hide-data-logged-out-users/
Description: Custom Extension for TablePress that allows to hide certain columns/rows from logged-out users
Version: 1.0
Author: Tobias Bäthge
Author URI: https://tobias.baethge.com/
*/
add_filter( 'tablepress_shortcode_table_default_shortcode_atts', 'tablepress_add_shortcode_parameter_hide_logged_out' );
add_filter( 'tablepress_table_render_options', 'tablepress_hide_columns_from_logged_out_users', 10, 2 );

/**
 * Add `hide_columns_logged_out` and `hide_rows_logged_out` as valid Shortcode parameters.
 *
 * @since 1.0
 *
 * @param array $default_atts Current set of Shortcode parameters.
 * @return array Extended set of Shortcode parameters.
 */
function tablepress_add_shortcode_parameter_hide_logged_out( $default_atts ) {
	$default_atts['hide_columns_logged_out'] = '';
	$default_atts['hide_rows_logged_out'] = '';
	return $default_atts;
}

/**
 * Add the desired rows/columns to the hidden rows/columns, if the user is logged out.
 *
 * @since 1.0
 *
 * @param array $render_options Render options for the table.
 * @param array $table          The table.
 * @return array The modified render options.
 */
function tablepress_hide_columns_from_logged_out_users( $render_options, $table ) {
	if ( is_user_logged_in() ) {
		return $render_options;
	}

	if ( ! empty( $render_options['hide_columns_logged_out'] ) ) {
		if ( empty( $render_options['hide_columns'] ) ) {
			$render_options['hide_columns'] = $render_options['hide_columns_logged_out'];
		} else {
			$render_options['hide_columns'] .= ',' . $render_options['hide_columns_logged_out'];
		}
	}

	if ( ! empty( $render_options['hide_rows_logged_out'] ) ) {
		if ( empty( $render_options['hide_rows'] ) ) {
			$render_options['hide_rows'] = $render_options['hide_rows_logged_out'];
		} else {
			$render_options['hide_rows'] .= ',' . $render_options['hide_rows_logged_out'];
		}
	}

	return $render_options;
}

