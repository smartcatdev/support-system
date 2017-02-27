<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\form\SelectBoxField;
use SmartcatSupport\admin\FormMetaBox;
use SmartcatSupport\descriptor\Option;

class TicketCPT extends AbstractComponent {

    private $quick_edit_form;

    public function start() {
        $this->quick_edit_form = include $this->plugin->config_dir . '/quick_edit_form.php';

        $this->plugin->add_api_subscriber( new FormMetaBox(
            array(
                'id'        => 'ticket_support_meta',
                'title'     => __( 'Ticket Information', \SmartcatSupport\PLUGIN_ID ),
                'post_type' => 'support_ticket',
                'context'   => 'advanced',
                'priority'  => 'high',
                'config'    =>  $this->plugin->config_dir . '/properties_metabox_form.php'
            )
        ) );

        if( \SmartcatSupport\util\ticket\ecommerce_enabled() ) {

            $this->plugin->add_api_subscriber( new FormMetaBox(
                array(
                    'id'        => 'ticket_product_meta',
                    'title'     => __( 'Product Information', \SmartcatSupport\PLUGIN_ID ),
                    'post_type' => 'support_ticket',
                    'context'   => 'side',
                    'priority'  => 'high',
                    'config'    => $this->plugin->config_dir . '/product_metabox_form.php'
                )
            ) );

        }
    }

    public function register_cpt() {
        //<editor-fold desc="$args array">
        $labels = array(
            'name'                  => _x( 'Support Tickets', 'Post Type General Name', \SmartcatSupport\PLUGIN_ID ),
            'singular_name'         => _x( 'Support Ticket', 'Post Type Singular Name', \SmartcatSupport\PLUGIN_ID ),
            'menu_name'             => __( 'uCare Support', \SmartcatSupport\PLUGIN_ID ),
            'name_admin_bar'        => __( 'uCare Support', \SmartcatSupport\PLUGIN_ID ),
            'archives'              => __( 'Item Archives', \SmartcatSupport\PLUGIN_ID ),
            'parent_item_colon'     => __( 'Parent Item:', \SmartcatSupport\PLUGIN_ID ),
            'all_items'             => __( 'Support Tickets', \SmartcatSupport\PLUGIN_ID ),
            'add_new_item'          => __( 'Create Ticket', \SmartcatSupport\PLUGIN_ID ),
            'add_new'               => __( 'Create Ticket', \SmartcatSupport\PLUGIN_ID ),
            'new_item'              => __( 'Create Ticket', \SmartcatSupport\PLUGIN_ID ),
            'edit_item'             => __( 'Edit Ticket', \SmartcatSupport\PLUGIN_ID ),
            'update_item'           => __( 'Update Ticket', \SmartcatSupport\PLUGIN_ID ),
            'view_item'             => __( 'View Ticket', \SmartcatSupport\PLUGIN_ID ),
            'search_items'          => __( 'Search Ticket', \SmartcatSupport\PLUGIN_ID ),
            'not_found'             => __( 'Ticket Not found', \SmartcatSupport\PLUGIN_ID ),
            'not_found_in_trash'    => __( 'Ticket Not found in Trash', \SmartcatSupport\PLUGIN_ID ),
            'featured_image'        => __( 'Featured Image', \SmartcatSupport\PLUGIN_ID ),
            'set_featured_image'    => __( 'Set featured image', \SmartcatSupport\PLUGIN_ID ),
            'remove_featured_image' => __( 'Remove featured image', \SmartcatSupport\PLUGIN_ID ),
            'use_featured_image'    => __( 'Use as featured image', \SmartcatSupport\PLUGIN_ID ),
            'insert_into_item'      => __( 'Insert into ticket', \SmartcatSupport\PLUGIN_ID ),
            'uploaded_to_this_item' => __( 'Uploaded to this ticket', \SmartcatSupport\PLUGIN_ID ),
            'items_list'            => __( 'Tickets list', \SmartcatSupport\PLUGIN_ID ),
            'items_list_navigation' => __( 'Tickets list navigation', \SmartcatSupport\PLUGIN_ID ),
            'filter_items_list'     => __( 'Filter tickets list', \SmartcatSupport\PLUGIN_ID )
        );

        $capabilities = array();

        $args = array(
            'label'               => __( 'Support Ticket', \SmartcatSupport\PLUGIN_ID ),
            'description'         => __( 'Tickets for support requests', \SmartcatSupport\PLUGIN_ID ),
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
            'capabilities'        => $capabilities,
            'feeds'               => null
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
        if( wp_doing_ajax() ) {
            if( $this->quick_edit_form->is_valid() ) {
                foreach( $this->quick_edit_form->data as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }
        }
    }

    public function render_quick_edit( $column, $post_type ) { ?>

        <?php if( $post_type == 'support_ticket' && $column == 'id' ) : ?>

            <fieldset class="inline-edit-col-left">

                <div class="inline-edit-col">

                    <legend class="inline-edit-legend"><?php _e( 'Ticket Details', \SmartcatSupport\PLUGIN_ID ); ?></legend>

                    <div class="inline-edit-group">

                        <?php foreach ( $this->quick_edit_form->fields as $field ) : ?>

                            <label>

                                <span class="title"><?php _e( $field->label, \SmartcatSupport\PLUGIN_ID ); ?></span>

                                <span class="input-text-wrap">

                                    <?php $field->render(); ?>

                                </span>

                            </label>

                        <?php endforeach; ?>

                        <input type="hidden" name="<?php esc_attr_e( $this->quick_edit_form->id ); ?>"/>

                    </div>

                </div>

            </fieldset>

        <?php endif; ?>

    <?php }

    public function post_table_columns( $columns ) {
        unset( $columns['author'] );

        $cb = array_splice( $columns, 0, 1 );
        $left_cols = array_splice( $columns, 0, 1 );
        $left_cols['title'] = __( 'Subject', \SmartcatSupport\PLUGIN_ID );

        $left_cols = array_merge( array( 'id' => __( 'Case', \SmartcatSupport\PLUGIN_ID ) ), $left_cols );

        if( \SmartcatSupport\util\ticket\ecommerce_enabled() ) {
            $left_cols['product'] = __( 'Product', \SmartcatSupport\PLUGIN_ID );
        }

        return array_merge(
            $cb,
            $left_cols,
            array(
                'email'    => __( 'Email', \SmartcatSupport\PLUGIN_ID ),
                'agent'    => __( 'Assigned', \SmartcatSupport\PLUGIN_ID ),
                'status'   => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
                'priority' => __( 'Priority', \SmartcatSupport\PLUGIN_ID ),
                'flagged'  => '<span class="support_icon icon-flag2"></span>'
            ),
            $columns
        );
    }

    public function post_table_column_data( $column, $post_id ) {
        $value = get_post_meta( $post_id, $column, true ) ;
        $ticket = get_post( $post_id );

        switch ( $column ) {
            case 'id':
                echo $post_id;
                echo '<div class="hidden" id="support_inline_' . $post_id . '">';

                foreach( $this->quick_edit_form->fields as $name => $field ) {
                    $id = $field->name;
                    echo '<div class="' . $id . '">' . get_post_meta( $post_id, $id, true ) . '</div>';
                }

                echo '</div>';
                break;

            case 'email':
                echo \SmartcatSupport\util\ticket\author_email( $ticket );
                break;

            case 'product':
                $products = \SmartcatSupport\util\ticket\products();

                echo array_key_exists( $value, $products ) ? $products[ $value ] : '—';

                break;

            case 'agent':
                $agents = \SmartcatSupport\util\user\list_agents();

                echo array_key_exists( $value, $agents ) ? $agents[ $value ] : __( 'Unassigned', \SmartcatSupport\PLUGIN_ID );

                break;

            case 'status':
                $statuses = \SmartcatSupport\util\ticket\statuses();

                if( array_key_exists( $value, $statuses ) ) {
                    echo $statuses[ $value ];
                }

                break;

            case 'priority':
                $priorities = \SmartcatSupport\util\ticket\priorities();

                echo array_key_exists( $value, $priorities ) ? $priorities[ $value ] : '—';

                break;

            case 'flagged':
                $flagged = get_post_meta( $post_id, 'flagged', true ) == 'on';

                echo '<p style="display: none;">' . ( $flagged ? 1 : 0 ) . '</p>' .
                     '<span class="toggle flag-ticket support-icon icon-flag2 ' . ( $flagged ? 'active' : '' ) . '" ' .
                        'name="flagged"' .
                        'data-id="' . $post_id .'"></i>';
                break;
        }
    }

    public function post_table_filters() {
        if( get_current_screen()->post_type == 'support_ticket' ) {

            $agents = \SmartcatSupport\util\user\list_agents();
            $agents = array( 0 => __( 'All Agents', \SmartcatSupport\PLUGIN_ID ) ) + $agents;

            $agent_filter = new SelectBoxField(
                array(
                    'name'      => 'agent',
                    'options'   =>  $agents,
                    'value'     => !empty( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : ''
                )
            );

            $meta_filter = new SelectBoxField(
                array(
                    'name'      => 'checked_meta',
                    'value'     => !empty( $_REQUEST['checked_meta'] ) ? $_REQUEST['checked_meta'] : '',
                    'options'   =>  array(
                        '' => __( 'All Tickets', \SmartcatSupport\PLUGIN_ID ),
                        'flagged' => __( 'Flagged', \SmartcatSupport\PLUGIN_ID )
                    ),
                )
            );

            $agent_filter->render();
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
            $this->plugin->id() . '_cleanup' => array( 'cleanup' ),
            'quick_edit_custom_box' => array( 'render_quick_edit', 10, 2 ),
            'manage_support_ticket_posts_columns' => array( 'post_table_columns' ),
            'manage_support_ticket_posts_custom_column' => array( 'post_table_column_data', 10, 2 ),
            'manage_edit-support_ticket_sortable_columns' => array( 'post_table_sortable_columns' )
        );
    }
}
