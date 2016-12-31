<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\core\HookSubscriber;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_NAME;
use SmartcatSupport\util\UserUtils;

class TicketCptComponent extends AbstractComponent implements HookSubscriber {

    private $form;

    public function start() {
        $this->plugin->add_api_subscriber( $this );
        $this->form = include $this->plugin->dir() . '/config/quick_edit_form.php';
    }

    public function register_cpt() {
        //<editor-fold desc="$args array">
        $labels = array(
            'name'                  => _x( 'Support Tickets', 'Post Type General Name', PLUGIN_NAME ),
            'singular_name'         => _x( 'Support Ticket', 'Post Type Singular Name', PLUGIN_NAME ),
            'menu_name'             => __( 'Support Tickets', PLUGIN_NAME ),
            'name_admin_bar'        => __( 'Support Tickets', PLUGIN_NAME ),
            'archives'              => __( 'Item Archives', PLUGIN_NAME ),
            'parent_item_colon'     => __( 'Parent Item:', PLUGIN_NAME ),
            'all_items'             => __( 'All Tickets', PLUGIN_NAME ),
            'add_new_item'          => __( 'New Ticket', PLUGIN_NAME ),
            'add_new'               => __( 'New Ticket', PLUGIN_NAME ),
            'new_item'              => __( 'New Ticket', PLUGIN_NAME ),
            'edit_item'             => __( 'Edit Ticket', PLUGIN_NAME ),
            'update_item'           => __( 'Update Ticket', PLUGIN_NAME ),
            'view_item'             => __( 'View Ticket', PLUGIN_NAME ),
            'search_items'          => __( 'Search Ticket', PLUGIN_NAME ),
            'not_found'             => __( 'Ticket Not found', PLUGIN_NAME ),
            'not_found_in_trash'    => __( 'Ticket Not found in Trash', PLUGIN_NAME ),
            'featured_image'        => __( 'Featured Image', PLUGIN_NAME ),
            'set_featured_image'    => __( 'Set featured image', PLUGIN_NAME ),
            'remove_featured_image' => __( 'Remove featured image', PLUGIN_NAME ),
            'use_featured_image'    => __( 'Use as featured image', PLUGIN_NAME ),
            'insert_into_item'      => __( 'Insert into ticket', PLUGIN_NAME ),
            'uploaded_to_this_item' => __( 'Uploaded to this ticket', PLUGIN_NAME ),
            'items_list'            => __( 'Tickets list', PLUGIN_NAME ),
            'items_list_navigation' => __( 'Tickets list navigation', PLUGIN_NAME ),
            'filter_items_list'     => __( 'Filter tickets list', PLUGIN_NAME )
        );

        $capabilities = array();

        $args = array(
            'label'               => __( 'Support Ticket', PLUGIN_NAME ),
            'description'         => __( 'Tickets for support requests', PLUGIN_NAME ),
            'labels'              => $labels,
            'supports'            => array( 'editor', 'comments', 'title' ),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 70,
            'menu_icon'           => 'dashicons-sos',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capabilities'        => $capabilities
        );
        //</editor-fold>

        register_post_type( 'support_ticket', $args );
    }

    public function post_table_sortable_columns( $columns ) {
        return array_merge(
            $columns, array(
                'status'   => 'status',
                'priority' => 'priority',
                'assigned' => 'assigned',
                'product'  => 'product',
            )
        );
    }

    public function quick_edit_save( $post_id ) {
        if( defined( 'DOING_AJAX' ) ) {
            if( $this->form->is_valid() ) {
                foreach( $this->form->data as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }
        }
    }

    public function render_quick_edit( $column, $post_type ) { ?>

        <?php if( $post_type == 'support_ticket' && $column == 'id' ) : ?>

            <fieldset class="inline-edit-col-left">

                <div class="inline-edit-col">

                    <legend class="inline-edit-legend"><?php _e( 'Ticket Details', PLUGIN_NAME ); ?></legend>

                    <div class="inline-edit-group">

                        <?php foreach ( $this->form->fields as $field ) : ?>

                            <label for="<?php esc_attr_e( $field->id ); ?>">

                                <span class="title"><?php _e( $field->label, PLUGIN_NAME ); ?></span>

                                <?php $field->render(); ?>

                            </label>

                        <?php endforeach; ?>

                        <input type="hidden" name="<?php esc_attr_e( $this->form->id ); ?>"/>

                    </div>

                </div>

            </fieldset>

        <?php endif; ?>

    <?php }

    public function post_table_columns( $columns ) {
        unset( $columns['author'] );

        $left_cols = array_splice( $columns, 0, 2 );
        $left_cols['title'] = __( 'Subject', PLUGIN_NAME );
        $left_cols['id'] = __( 'Case #', PLUGIN_NAME );

        if( $this->plugin->edd_active || $this->plugin->woo_active ) {
            $left_cols['product'] = __( 'Product', PLUGIN_NAME );
        }

        return array_merge(
            $left_cols,
            array(
                'email'    => __( 'Email', PLUGIN_NAME ),
                'agent'    => __( 'Assigned', PLUGIN_NAME ),
                'status'   => __( 'Status', PLUGIN_NAME ),
                'priority' => __( 'Priority', PLUGIN_NAME ),
                'flagged'  => '<i class="support_icon icon-flag2"></i>'
            ),
            $columns
        );
    }

    public function post_table_column_data( $column, $post_id ) {
        $value = get_post_meta( $post_id, $column, true ) ;

        switch ( $column ) {
            case 'id':
                echo $post_id;
                echo '<div class="hidden" id="support_inline_' . $post_id . '">';

                foreach( $this->form->fields as $field ) {
                    $id = $field->id;
                    echo '<div class="' . $id . '">' . get_post_meta( $post_id, $id, true ) . '</div>';
                }

                echo '</div>';
                break;

            case 'email':
                esc_html_e( get_post_meta( $post_id, 'email', true ) );
                break;

            case 'product':
                $products = apply_filters( 'support_list_products', array() );

                if( !empty( $products ) && array_key_exists( $value, $products ) ) {
                    echo $products[ $value ];
                }

                break;

            case 'agent':
                $agents = UserUtils::list_agents( array( '' => __( 'Unassigned', PLUGIN_NAME ) ) );

                if( array_key_exists( $value, $agents ) ) {
                    echo $agents[ $value ];
                }

                break;

            case 'status':
                $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );

                if( array_key_exists( $value, $statuses ) ) {
                    echo $statuses[ $value ];
                }

                break;

            case 'priority':
                $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

                if( array_key_exists( $value, $priorities ) ) {
                    echo $priorities[ $value ];
                }

                break;

            case 'flagged':
                $flagged = get_post_meta( $post_id, 'flagged', true ) == 'on';

                echo '<p style="display: none;">' . ( $flagged ? 1 : 0 ) . '</p>' .
                     '<i class="support_admin_toggle support_icon flag icon-flag2 ' . ( $flagged ? 'active' : '' ) . '" ' .
                        'name="flagged"' .
                        'data-id="' . $post_id .'"></i>';
                break;
        }
    }

    public function post_table_filters() {
        if( get_current_screen()->post_type == 'support_ticket' ) {
            $agent_filter = new SelectBoxField(
                array(
                    'id'        => 'agent',
                    'options'   =>  UserUtils::list_agents( array( '' => __( 'All Agents', PLUGIN_NAME ) ) ),
                    'value'     => !empty( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : ''
                )
            );

            $agent_filter->render();

            $meta_filter = new SelectBoxField(
                array(
                    'id'        => 'checked_meta',
                    'value'     => !empty( $_REQUEST['checked_meta'] ) ? $_REQUEST['checked_meta'] : '',
                    'options'   =>  array(
                        '' => __( 'All Tickets', PLUGIN_NAME ),
                        'flagged' => __( 'Flagged', PLUGIN_NAME )
                    ),
                )
            );

            $meta_filter->render();
        }
    }

    public function filter_post_table( $query ) {
        if ( ( !empty( $GLOBALS['typenow'] ) && !empty( $GLOBALS['pagenow'] ) ) &&
            ( $GLOBALS['typenow'] == 'support_ticket' && $GLOBALS['pagenow'] == 'edit.php' )
        ) {
            $meta_query = array();

            if ( !empty( $_REQUEST['agent'] ) ) {
                $meta_query[] = array( 'key' => 'agent', 'value' => intval( $_REQUEST['agent'] ) );
            }

            if ( !empty( $_REQUEST['checked_meta'] ) ) {
                $meta_query[] = array( 'key' => $_REQUEST['checked_meta'], 'value' => 'on' );
            }

            $query->query_vars['meta_query'] = $meta_query;
        }

        return $query;
    }

    public function cleanup() {
        unregister_post_type( 'support_ticket' );
    }

    public function subscribed_hooks() {
        return array(
            'init' => array( 'register_cpt' ),
            'save_post' => array( 'quick_edit_save' ),
            'restrict_manage_posts' => array( 'post_table_filters' ),
            'parse_query' => array( 'filter_post_table' ),
            $this->plugin->name() . '_cleanup' => array( 'cleanup' ),
            'quick_edit_custom_box' => array( 'render_quick_edit', 10, 2 ),
            'manage_support_ticket_posts_columns' => array( 'post_table_columns' ),
            'manage_support_ticket_posts_custom_column' => array( 'post_table_column_data', 10, 2 ),
            'manage_edit-support_ticket_sortable_columns' => array( 'post_table_sortable_columns' ),
        );
    }
}
