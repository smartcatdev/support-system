<?php

namespace ucare;


use smartcat\post\FormMetaBox;


add_action( 'init', 'ucare\register_ticket_post_type' );

add_action( 'admin_init', 'ucare\ticket_meta_boxes' );

add_action( 'wp_insert_post', 'ucare\set_default_ticket_meta', 10, 3 );

add_action( 'restrict_manage_posts', 'ucare\tickets_table_filters' );

add_action( 'update_post_metadata', 'ucare\ticket_properties_updated', 10, 4 );

add_action( 'manage_support_ticket_posts_custom_column', 'ucare\tickets_table_column_data', 10, 2 );

add_filter( 'post_row_actions', 'ucare\remove_quick_edit_link', 10, 2 );

add_filter( 'parse_query', 'ucare\filter_tickets_table' );

add_filter( 'bulk_actions-edit-support_ticket', 'ucare\disable_ticket_inline_edit' );

add_filter( 'manage_edit-support_ticket_sortable_columns', 'ucare\tickets_table_sortable_columns' );

add_filter( 'manage_support_ticket_posts_columns', 'ucare\tickets_table_columns' );


function register_ticket_post_type() {

    $labels = array(
        'name'                  => _x( 'Support Tickets', 'Post Type General Name', 'ucare' ),
        'singular_name'         => _x( 'Support Ticket', 'Post Type Singular Name', 'ucare' ),
        'menu_name'             => __( 'uCare Support', 'ucare' ),
        'name_admin_bar'        => __( 'uCare Support', 'ucare' ),
        'archives'              => __( 'Item Archives', 'ucare' ),
        'parent_item_colon'     => __( 'Parent Item:', 'ucare' ),
        'all_items'             => __( 'Ticket List', 'ucare' ),
        'add_new_item'          => __( 'Create Ticket', 'ucare' ),
        'add_new'               => __( 'Create Ticket', 'ucare' ),
        'new_item'              => __( 'Create Ticket', 'ucare' ),
        'edit_item'             => __( 'Edit Ticket', 'ucare' ),
        'update_item'           => __( 'Update Ticket', 'ucare' ),
        'view_item'             => __( 'View Ticket', 'ucare' ),
        'search_items'          => __( 'Search Ticket', 'ucare' ),
        'not_found'             => __( 'Ticket Not found', 'ucare' ),
        'not_found_in_trash'    => __( 'Ticket Not found in Trash', 'ucare' ),
        'featured_image'        => __( 'Featured Image', 'ucare' ),
        'set_featured_image'    => __( 'Set featured image', 'ucare' ),
        'remove_featured_image' => __( 'Remove featured image', 'ucare' ),
        'use_featured_image'    => __( 'Use as featured image', 'ucare' ),
        'insert_into_item'      => __( 'Insert into ticket', 'ucare' ),
        'uploaded_to_this_item' => __( 'Uploaded to this ticket', 'ucare' ),
        'items_list'            => __( 'Tickets list', 'ucare' ),
        'items_list_navigation' => __( 'Tickets list navigation', 'ucare' ),
        'filter_items_list'     => __( 'Filter tickets list', 'ucare' )
    );

    $args = array(
        'label'                => __( 'Support Ticket', 'ucare' ),
        'description'          => __( 'Tickets for support requests', 'ucare' ),
        'labels'               => $labels,
        'supports'             => array( 'editor', 'comments', 'title' ),
        'hierarchical'         => false,
        'public'               => false,
        'show_ui'              => true,
        'show_in_menu'         => false,
        'menu_position'        => 10,
        'menu_icon'            => 'dashicons-sos',
        'show_in_admin_bar'    => false,
        'show_in_nav_menus'    => false,
        'can_export'           => true,
        'has_archive'          => false,
        'exclude_from_search'  => true,
        'publicly_queryable'   => false,
        'capability_type'      => array( 'support_ticket', 'support_tickets' ),
        'feeds'                => null,
        'map_meta_cap'         => true,
        'register_meta_box_cb' => 'ucare\add_support_ticket_metaboxes'
    );

    register_post_type( 'support_ticket', $args );

}


function disable_ticket_inline_edit( $actions ) {

    if ( get_post_type() == 'support_ticket' ) {
        unset( $actions['edit'] );
    }

    return $actions;

}


function remove_quick_edit_link( $actions, $post ) {

    if ( $post->post_type == 'support_ticket' ) {
        unset( $actions['inline hide-if-no-js'] );
    }

    return $actions;

}


function tickets_table_sortable_columns( $columns ) {

    $sortable = array(
        'status'   => 'status',
        'priority' => 'priority',
        'assigned' => 'assigned',
        'product'  => 'product',
    );

    return array_merge(  $columns, $sortable );

}


function tickets_table_columns( $columns ) {

    unset( $columns['author'] );

    $cb = array_splice( $columns, 0, 1 );
    $left_cols = array_splice( $columns, 0, 1 );
    $left_cols['title'] = __( 'Subject', 'ucare' );

    if( \ucare\util\ecommerce_enabled() ) {
        $left_cols['product'] = __( 'Product', 'ucare' );
    }

    return array_merge(
        $cb,
        $left_cols,
        array(
            'email'    => __( 'Email', 'ucare' ),
            'agent'    => __( 'Assigned', 'ucare' ),
            'status'   => __( 'Status', 'ucare' ),
            'priority' => __( 'Priority', 'ucare' ),
            'flagged'  => '<span class="support_icon icon-flag"></span>'
        ),
        $columns
    );

}


function tickets_table_column_data( $column, $post_id ) {

    $value = get_post_meta( $post_id, $column, true ) ;
    $ticket = get_post( $post_id );

    switch ( $column ) {

        case 'email':
            echo \ucare\util\author_email( $ticket );
            break;

        case 'product':
            $products = \ucare\util\products();

            echo array_key_exists( $value, $products ) ? $products[ $value ] : '—';

            break;

        case 'agent':
            $agents = \ucare\util\list_agents();

            echo array_key_exists( $value, $agents ) ? $agents[ $value ] : __( 'Unassigned', 'ucare' );

            break;

        case 'status':
            $statuses = \ucare\util\statuses();

            if( array_key_exists( $value, $statuses ) ) {
                echo  '<span class="status-tag">' . $statuses[ $value ] . '</span>';
            }

            if( get_post_meta( $post_id, 'stale', true ) ) {
                echo '<span class="stale-tag">' . __( 'Stale', 'ucare' ) . '</span>';
            }

            break;

        case 'priority':
            $priorities = \ucare\util\priorities();

            echo array_key_exists( $value, $priorities ) ? $priorities[ $value ] : '—';

            break;

        case 'flagged':
            $flagged = get_post_meta( $post_id, 'flagged', true ) == 'on';

            echo '<p style="display: none;">' . ( $flagged ? 1 : 0 ) . '</p>' .
                '<span class="toggle flag-ticket support-icon icon-flag ' . ( $flagged ? 'active' : '' ) . '" ' .
                'name="flagged"' .
                'data-id="' . $post_id .'"></i>';

            break;

    }

}


function tickets_table_filters() {

    if( get_current_screen()->post_type == 'support_ticket' ) {

        $agents = \ucare\util\list_agents();
        $products = \ucare\util\products();
        $statuses = \ucare\util\statuses();

        $agents = array( 0 => __( 'All Agents', 'ucare' ) ) + $agents;
        $statuses = array( '' => __( 'All Statuses', 'ucare' ) ) + $statuses;

        selectbox( 'meta[status]', $statuses, !empty( $_GET['meta']['status'] ) ? $_GET['meta']['status'] : '' );
        selectbox( 'meta[agent]', $agents, !empty( $_GET['meta']['agent'] ) ? $_GET['meta']['agent'] : '' );

        if( \ucare\util\ecommerce_enabled() ) {

            $products = array( 0 => __( 'All Products', 'ucare' ) ) + $products;

            selectbox( 'meta[product]', $products, !empty( $_GET['meta']['product'] ) ? $_GET['meta']['product'] : '' );

        }

        ?>

        <div class="ucare_filter_checkboxes">
            <label><input type="checkbox" name="flagged"

                <?php checked( 'on', isset( $_GET['flagged'] ) ? $_GET['flagged'] : '' ); ?> /> <?php _e( 'Flagged', 'ucare' ); ?></label>

            <label><input type="checkbox" name="stale"

            <?php checked( 'on', isset( $_GET['stale'] ) ? $_GET['stale'] : '' ); ?> /> <?php _e( 'Stale', 'ucare' ); ?></label>

        </div>

    <?php }

}


function filter_tickets_table( $query ) {

    if( ! isset( $_GET['post_type'] ) || $_GET['post_type'] !== 'support_ticket' ) {
        return $query;
    }

    $meta_query = array();

    if( isset( $_GET['meta'] ) ) {

        foreach( $_GET['meta'] as $key => $value ) {

            if( !empty( $_GET['meta'][ $key ] ) ) {
                $meta_query[] = array('key' => $key, 'value' => $value);
            }
        }

    }

    if( isset( $_GET['flagged'] ) ) {
        $meta_query[] = array( 'key' => 'flagged', 'value' => 'on' );
    }

    if( isset( $_GET['stale'] ) ) {
        $meta_query[] = array( 'key' => 'stale', 'compare' => 'EXISTS' );
    }

    $query->query_vars['meta_query'] = $meta_query;


    return $query;

}


function ticket_meta_boxes() {

    //TODO Remove this and replace with regular metaboxes
    $support_metabox = new FormMetaBox(
        array(
            'id'        => 'ticket_support_meta',
            'title'     => __( 'Ticket Information', 'ucare' ),
            'post_type' => 'support_ticket',
            'context'   => 'advanced',
            'priority'  => 'high',
            'config'    =>  plugin_dir() . '/config/properties_metabox_form.php'
        )
    );

    if( \ucare\util\ecommerce_enabled() ) {

        $product_metabox = new FormMetaBox(
            array(
                'id'        => 'ticket_product_meta',
                'title'     => __( 'Product Information', 'ucare' ),
                'post_type' => 'support_ticket',
                'context'   => 'side',
                'priority'  => 'high',
                'config'    => plugin_dir() . '/config/product_metabox_form.php'
            )
        );

    }

}


function ticket_properties_updated( $null, $id, $key, $value ) {

    global $wpdb;

    if( get_post_type( $id ) == 'support_ticket' && $key == 'status' ) {

        $q = "UPDATE {$wpdb->posts}
              SET post_modified = %s, post_modified_gmt = %s
              WHERE ID = %d ";

        $q = $wpdb->prepare( $q, array( current_time( 'mysql' ), current_time( 'mysql', 1 ), $id ) );

        $wpdb->query( $q );

        delete_post_meta( $id, 'stale' );

        if( $value == 'closed' ) {

            update_post_meta( $id, 'closed_date', current_time( 'mysql' ) );
            update_post_meta( $id, 'closed_by', wp_get_current_user()->ID );

        }

    }

}


function set_default_ticket_meta( $post_id, $post, $update ) {

    $defaults = array(
        'priority' => 0
    );

    if( !$update ) {

        foreach( $defaults as $key => $value ) {
            add_post_meta( $post_id, $key, $value );
        }

    }

}


function get_recent_tickets( $args = array() ) {

    $defaults = array(
        'author'  => '',
        'after'   => 'now',
        'before'  => '30 days ago',
        'exclude' => array(),
        'limit'   => -1
    );

    $args = wp_parse_args( $args, $defaults );


    $q = array(
        'post_type'      => 'support_ticket',
        'post_status'    => 'publish',
        'author'         => $args['author'],
        'after'          => $args['after'],
        'before'         => $args['before'],
        'post__not_in'   => $args['exclude'],
        'posts_per_page' => $args['limit'] ?: -1
    );

    return new \WP_Query( $q );

}
