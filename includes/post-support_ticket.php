<?php

namespace ucare;

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use smartcat\post\FormMetaBox;


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

add_action( 'init', 'ucare\register_ticket_post_type' );


function force_menu_expand( $file ) {

    $screen = get_current_screen();

    if( $screen && $screen->taxonomy == 'ticket_category' ) {
        return 'ucare_support';
    }

    return $file;

}

add_filter( 'parent_file', 'ucare\force_menu_expand' );


function menu_highlight_categories( $file ) {

    $screen = get_current_screen();

    if( $screen && $screen->taxonomy == 'ticket_category' ) {
        return 'edit-tags.php?post_type=support_ticket&taxonomy=ticket_category';
    }

    return $file;

}

add_filter( 'submenu_file', 'ucare\menu_highlight_categories' );


function tickets_table_sortable_columns( $columns ) {

    $sortable = array(
        'status'   => 'status',
        'priority' => 'priority',
        'assigned' => 'assigned',
        'product'  => 'product',
    );

    return array_merge(  $columns, $sortable );

}

add_filter( 'manage_edit-support_ticket_sortable_columns', 'ucare\tickets_table_sortable_columns' );


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

add_filter( 'manage_support_ticket_posts_columns', 'ucare\tickets_table_columns' );


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

add_action( 'manage_support_ticket_posts_custom_column', 'ucare\tickets_table_column_data', 10, 2 );

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

add_action( 'restrict_manage_posts', 'ucare\tickets_table_filters' );


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

add_filter( 'parse_query', 'ucare\filter_tickets_table' );


function ticket_quick_edit_form() {

    $agents = array( 0 => __( 'Unassigned', 'ucare' ) ) + \ucare\util\list_agents();
    $statuses = \ucare\util\statuses();
    $priorities = \ucare\util\priorities();

    $form = new Form( 'ticket_quick_edit' );

    $form->add_field( new SelectBoxField(
        array(
            'name'          => 'agent',
            'class'         => array( 'quick-edit-field', 'agent' ),
            'label'         => __( 'Assigned', 'ucare' ),
            'options'       => $agents,
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $agents ) )
            )
        )

    ) )->add_field( new SelectBoxField(
        array(
            'name'          => 'status',
            'class'         => array( 'quick-edit-field', 'status' ),
            'label'         => __( 'Status', 'ucare' ),
            'options'       => $statuses,
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $statuses ) )
            )
        )

    ) )->add_field( new SelectBoxField(
        array(
            'name'          => 'priority',
            'class'         => array( 'quick-edit-field', 'priority' ),
            'label'         => __( 'Priority', 'ucare' ),
            'options'       => $priorities,
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $priorities ) )
            )
        )
    ) );

    return $form;

}

function ticket_quick_edit_save( $post_id ) {

    if( wp_doing_ajax() ) {

        $form = ticket_quick_edit_form();

        if( $form->is_valid() ) {

            foreach( $form->data as $key => $value ) {
                update_post_meta( $post_id, $key, $value, get_post_meta( $post_id, $key, true ) );
            }

        }

    }

}

add_action( 'save_post', 'ucare\ticket_quick_edit_save' );


function render_ticket_quick_edit( $column, $post_type ) {

    if( $post_type == 'support_ticket' && $column == 'id' ) : ?>

        <?php $form = ticket_quick_edit_form(); ?>

        <fieldset class="inline-edit-col-left">

            <div class="inline-edit-col">

                <legend class="inline-edit-legend"><?php _e( 'Ticket Details', 'ucare' ); ?></legend>

                <div class="inline-edit-group">

                    <?php foreach ( $form->fields as $field ) : ?>

                        <label>

                            <span class="title"><?php _e( $field->label, 'ucare' ); ?></span>

                            <span class="input-text-wrap">

                                    <?php $field->render(); ?>

                                </span>

                        </label>

                    <?php endforeach; ?>

                    <input type="hidden" name="<?php esc_attr_e( $form->id ); ?>"/>

                </div>

            </div>

        </fieldset>

    <?php endif;

}

add_action( 'quick_edit_custom_box', 'ucare\render_ticket_quick_edit', 10, 2 );


function ticket_meta_boxes() {

    //TODO Refactor these into a single metabox
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

add_action( 'admin_init', 'ucare\ticket_meta_boxes' );


