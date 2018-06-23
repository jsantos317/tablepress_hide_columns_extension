<?php
/*
Plugin Name: TablePress Extension: Hide Columns/Rows from user roles
Plugin URI:
Description: Custom Extension for TablePress that allows to hide certain columns/rows from users with certain roles
Version: 1.0
Author: Joseph Santos
Author URI: http://codeunicorn.com/
*/
add_filter( 'tablepress_shortcode_table_default_shortcode_atts', 'tablepress_add_shortcode_parameter_hide_role' );
add_filter( 'tablepress_table_render_options', 'tablepress_render_columns_from_user_role', 10, 2 );

/**
 * @return array Set of editable roles
 */
function tablepress_get_editable_roles() {
    global $wp_roles;

    $all_roles = $wp_roles->roles;
    $editable_roles = apply_filters('editable_roles', $all_roles);

    return $editable_roles;
}

/**
 * Add `hide_columns_logged_out` and `hide_rows_logged_out` as valid Shortcode parameters.
 *
 * @since 1.0
 *
 * @param array $default_atts Current set of Shortcode parameters.
 * @return array Extended set of Shortcode parameters.
 */
function tablepress_add_shortcode_parameter_hide_role( $default_atts ) {

    $editable_roles = tablepress_get_editable_roles();

    foreach( $editable_roles as $role => $roleArray ) {

        $attrCol = 'hide_columns_'. $role;
        $attrRow = 'hide_rows_'. $role;

        $default_atts[$attrCol] = '';
        $default_atts[$attrRow] = '';
    }

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
function tablepress_render_columns_from_user_role( $render_options, $table ) {

    // var_dump($render_options);

    $editable_roles = tablepress_get_editable_roles();
    foreach( $editable_roles as $role => $roleArray ) {

        $render_options = render_helper($render_options, 'hide', 'columns', $role);
        $render_options = render_helper($render_options, 'hide', 'rows', $role);
        $render_options = render_helper($render_options, 'show', 'columns', $role);
        $render_options = render_helper($render_options, 'show', 'rows', $role);

    }

	return $render_options;
}

function render_helper($render_options, $hide_show, $row_col, $role) {
    if ( ! empty( $render_options[$hide_show .'_'. $row_col .'_'. $role] ) ) {
        if ( empty( $render_options[$hide_show .'_'. $row_col] ) ) {
            $render_options[$hide_show .'_'. $row_col] = $render_options[$hide_show .'_'. $row_col .'_'. $role];
        } else {
            $render_options[$hide_show .'_'. $row_col] .= ',' . $render_options[$hide_show .'_'. $row_col .'_'. $role];
        }
    }

    return $render_options;
}
