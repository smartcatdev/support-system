<?php
/**
 * Admin-side functions for managing the support ticket post type.
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


// Output custom columns
add_action( 'manage_support_ticket_posts_custom_column', 'ucare\_tickets_table_column_data', 10, 2 );

// Add custom filters
add_action( 'restrict_manage_posts', 'ucare\_tickets_table_filters' );

// Remove the quick editor
add_filter( 'bulk_actions-edit-support_ticket', 'ucare\_tickets_table_disable_inline_edit' );

// Remove the quick edit link
add_filter( 'post_row_actions', 'ucare\_tickets_table_remove_quick_edit_link', 10, 2 );

// Set sortable columns
add_filter( 'manage_edit-support_ticket_sortable_columns', 'ucare\_tickets_table_sortable_columns' );

// Set custom columns
add_filter( 'manage_support_ticket_posts_columns', 'ucare\_tickets_table_custom_columns' );

// Filter the posts table
add_filter( 'parse_query', 'ucare\_filter_tickets_table_posts' );


/**
 * Remove support for the post table quick editor.
 *
 * @param $actions
 *
 * @internal
 * @since 1.4.2
 * @return mixed
 */
function _tickets_table_disable_inline_edit( $actions ) {

    if ( get_post_type() == 'support_ticket' ) {
        unset( $actions['edit'] );
    }

    return $actions;

}


/**
 * Remove the quick edit shortcut link.
 *
 * @param $actions
 * @param $post
 *
 * @internal
 * @since 1.4.2
 * @return mixed
 */
function _tickets_table_remove_quick_edit_link( $actions, $post ) {

    if ( $post->post_type == 'support_ticket' ) {
        unset( $actions['inline hide-if-no-js'] );
    }

    return $actions;

}


/**
 * Set sortable columns for the ticket posts table.
 *
 * @param $columns
 *
 * @internal
 * @since 1.0.0
 * @return array
 */
function _tickets_table_sortable_columns( $columns ) {

    $sortable = array(
        'status'   => 'status',
        'priority' => 'priority',
        'assigned' => 'assigned',
        'product'  => 'product',
    );

    return array_merge(  $columns, $sortable );

}


/**
 * Set custom columns in the ticket posts table.
 *
 * @param $columns
 *
 * @internal
 * @since 1.0.0
 * @return array
 */
function _tickets_table_custom_columns( $columns ) {
    unset( $columns['author'] );

    $check_box = array_splice( $columns, 0, 1 );
    $left_cols = array_splice( $columns, 0, 1 );

    $left_cols['title'] = __( 'Subject', 'ucare' );

    if ( ucare_is_ecommerce_enabled() ) {
        $left_cols['product'] = __( 'Product', 'ucare' );
    }

    $custom = array(
        'email'    => __( 'Email', 'ucare' ),
        'agent'    => __( 'Assigned', 'ucare' ),
        'status'   => __( 'Status', 'ucare' ),
        'priority' => __( 'Priority', 'ucare' ),
        'flagged'  => '<span class="support_icon icon-flag"></span>'
    );

    return array_merge( $check_box, $left_cols, $custom, $columns );

}


/**
 * Output custom columns in the ticket posts table.
 *
 * @param $column
 * @param $post_id
 *
 * @internal
 * @since 1.0.0
 * @return void
 */
function _tickets_table_column_data( $column, $post_id ) {

    $ticket = get_post( $post_id );

    switch ( $column ) {
        /**
         * Output the ticket author email.
         *
         * @since 1.0.0
         */
        case 'email':
            $author = get_user( $ticket->post_author );

            if ( $author ) {
                echo stripslashes( $author->user_email );
            }

            break;

        /**
         * Output the associated product title.
         *
         * @since 1.0.0
         */
        case 'product':
            $product = get_post( get_metadata( 'product' ) );

            if ( $product ) {
                echo stripslashes( $product->post_title );
            }

            break;

        /**
         * Output the name of the assigned agent.
         *
         * @since 1.0.0
         */
        case 'agent':
            $agent = get_user( get_metadata( 'agent' ), false );

            if ( $agent && ucare_is_support_agent( $agent->ID ) ) {
                echo stripslashes( $agent->display_name );

            // The ticket does not belong to a valid agent
            } else {
                _e( 'Unassigned', 'ucare' );
            }

            break;

        /**
         * Output the current ticket status. If the ticket is stale, output a stale tag beside the status.
         *
         * @since 1.0.0
         */
        case 'status':
            echo  '<span class="status-tag">', ticket_status( $ticket, '-', false ), '</span>';

            if ( get_metadata( 'stale' ) ) {
                echo '<span class="stale-tag">', __( 'Stale', 'ucare' ), '</span>';
            }

            break;

        /**
         * Output the human readable ticket priority.
         *
         * @since 1.0.0
         */
        case 'priority':
            ticket_priority( $ticket, '-' );
            break;

        /**
         * Output the indicator for flagging tickets.
         *
         * @since 1.0.0
         */
        case 'flagged':
            $flagged = get_metadata( 'flagged' );

            $attributes = array(
                'class' => array(
                    'toggle', 'flag-ticket', 'support-icon', 'icon-flag', $flagged ? 'active' : ''
                ),
                'name'    => 'flagged',
                'data-id' => $post_id
            ) ;

            echo '<p style="display: none;">', ( $flagged ? 1 : 0 ), '</p>',
                 '<span ', parse_attributes( $attributes ), '></span>';

            break;

    }

}


/**
 * Output custom filters in the ticket posts table.
 *
 * @internal
 * @since 1.0.0
 * @return void
 */
function _tickets_table_filters() {

    if ( get_current_screen()->base == 'edit' && get_post_type() == 'support_ticket' ) {

        /**
         * Render statuses dropdown
         *
         * @since 1.0.0
         */
        $all_statuses = array(
            '' => __( 'All Statuses', 'ucare' )
        );

        $statuses = $all_statuses + get_ticket_statuses();

        dropdown( $statuses, get_var( 'ticket_status' ), array( 'name' => 'ticket_status' ) );


        /**
         * Render agents dropdown.
         *
         * @since 1.0.0
         */
        $agents = array(
            '' => __( 'All Agents', 'ucare' ),
            0  => __( 'Unassigned', 'ucare' )
        );

        foreach ( get_users_with_cap( 'manage_support_tickets' ) as $agent ) {
            $agents[ $agent->ID ] = $agent->display_name;
        }

        dropdown( $agents, get_var( 'assigned_agent' ), array( 'name' => 'assigned_agent' )  );


        /**
         * Render products dropdown
         *
         * @since 1.0.0
         */
        if ( ucare_is_ecommerce_enabled() ) {
            $products = array(
                '' => __( 'All Products', 'ucare' )
            );

            foreach ( get_posts( get_product_post_type() ) as $product ) {
                $products[ $product->ID ] = $product->post_title;
            }

            dropdown( $products, get_var( 'ticket_product' ), array( 'name' => 'ticket_product' )  );

        }


        /**
         * Render flag filter
         *
         * @since 1.6.0
         */
        $toggles = array(
             ''        => __( 'All Tickets', 'ucare' ),
             'flagged' => __( 'Flagged', 'ucare' ),
             'stale'   => __( 'Stale', 'ucare' )
        );

        dropdown( $toggles, get_var( 'ticket_flag' ), array( 'name' => 'ticket_flag' ) );

    }

}


/**
 * Apply custom filters to the ticket posts table results.
 *
 * @param \WP_Query $query
 *
 * @internal
 * @since 1.6.0
 * @return mixed
 */
function _filter_tickets_table_posts( $query ) {

    if ( get_current_screen()->base == 'edit' && get_var( 'post_type' ) == 'support_ticket' ) {
        $meta_query = array(
            'relation' => 'AND'
        );

        $status = get_var( 'ticket_status' );
        if ( $status ) {
            $meta_query[] = array(
                'key'   => 'status',
                'value' => sanitize_text_field( $status )
            );
        }

        $product = get_var( 'ticket_product' );
        if ( $product ) {
            $meta_query[] = array(
                'key'   => 'product',
                'value' => sanitize_text_field( $product )
            );
        }

        $agent = get_var( 'assigned_agent' );
        if ( is_numeric( $agent ) ) {
            if ( (int) $agent > 0 ) {
                $meta_query[] = array(
                    'key'   => 'agent',
                    'value' => sanitize_text_field( $agent )
                );
            } else {
                $meta_query[] = array(
                    'key'     => 'agent',
                    'value'   => 0,
                    'type'    => 'NUMERIC',
                    'compare' => '<='
                );
            }
        }

        $flag = get_var( 'ticket_flag' );
        if ( $flag ) {
            $meta_query[] = array(
                'key'     => sanitize_key( $flag ),
                'value'   => array( '', 0, false, 'off', 'no' ),
                'compare' => 'NOT IN'
            );
        }

        $query->query_vars['meta_query'] = $meta_query;

    }

    return $query;

}
