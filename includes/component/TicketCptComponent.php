<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\form\SelectBoxField;
use SmartcatSupport\admin\FormMetaBox;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\UserUtils;

class TicketCptComponent extends AbstractComponent {

    private $quick_edit_form;

    public function start() {
        $this->quick_edit_form = include $this->plugin->config_dir . '/quick_edit_form.php';

        $this->plugin->add_api_subscriber( new FormMetaBox(
            array(
                'id'        => 'ticket_support_meta',
                'title'     => __( 'Ticket Information', Plugin::ID ),
                'post_type' => 'support_ticket',
                'context'   => 'advanced',
                'priority'  => 'high',
                'config'    =>  $this->plugin->config_dir . '/support_metabox_form.php'
            )
        ) );

        $this->plugin->add_api_subscriber( new FormMetaBox(
            array(
                'id'        => 'ticket_product_meta',
                'title'     => __( 'Product Information', Plugin::ID ),
                'post_type' => 'support_ticket',
                'context'   => 'side',
                'priority'  => 'high',
                'config'    =>  $this->plugin->config_dir . '/product_metabox_form.php'
            )
        ) );

        $this->plugin->add_api_subscriber( new FormMetaBox(
            array(
                'id'        => 'ticket_customer_meta',
                'title'     => __( 'Customer Information', Plugin::ID ),
                'post_type' => 'support_ticket',
                'context'   => 'side',
                'priority'  => 'high',
                'config'    =>  $this->plugin->config_dir . '/customer_metabox_form.php'
            )
        ) );
    }

    public function register_cpt() {
        //<editor-fold desc="$args array">
        $labels = array(
            'name'                  => _x( 'Support Tickets', 'Post Type General Name', Plugin::ID ),
            'singular_name'         => _x( 'Support Ticket', 'Post Type Singular Name', Plugin::ID ),
            'menu_name'             => __( 'Support Tickets', Plugin::ID ),
            'name_admin_bar'        => __( 'Support Tickets', Plugin::ID ),
            'archives'              => __( 'Item Archives', Plugin::ID ),
            'parent_item_colon'     => __( 'Parent Item:', Plugin::ID ),
            'all_items'             => __( 'All Tickets', Plugin::ID ),
            'add_new_item'          => __( 'New Ticket', Plugin::ID ),
            'add_new'               => __( 'New Ticket', Plugin::ID ),
            'new_item'              => __( 'New Ticket', Plugin::ID ),
            'edit_item'             => __( 'Edit Ticket', Plugin::ID ),
            'update_item'           => __( 'Update Ticket', Plugin::ID ),
            'view_item'             => __( 'View Ticket', Plugin::ID ),
            'search_items'          => __( 'Search Ticket', Plugin::ID ),
            'not_found'             => __( 'Ticket Not found', Plugin::ID ),
            'not_found_in_trash'    => __( 'Ticket Not found in Trash', Plugin::ID ),
            'featured_image'        => __( 'Featured Image', Plugin::ID ),
            'set_featured_image'    => __( 'Set featured image', Plugin::ID ),
            'remove_featured_image' => __( 'Remove featured image', Plugin::ID ),
            'use_featured_image'    => __( 'Use as featured image', Plugin::ID ),
            'insert_into_item'      => __( 'Insert into ticket', Plugin::ID ),
            'uploaded_to_this_item' => __( 'Uploaded to this ticket', Plugin::ID ),
            'items_list'            => __( 'Tickets list', Plugin::ID ),
            'items_list_navigation' => __( 'Tickets list navigation', Plugin::ID ),
            'filter_items_list'     => __( 'Filter tickets list', Plugin::ID )
        );

        $capabilities = array();

        $args = array(
            'label'               => __( 'Support Ticket', Plugin::ID ),
            'description'         => __( 'Tickets for support requests', Plugin::ID ),
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

                    <legend class="inline-edit-legend"><?php _e( 'Ticket Details', Plugin::ID ); ?></legend>

                    <div class="inline-edit-group">

                        <?php foreach ( $this->quick_edit_form->fields as $field ) : ?>

                            <label for="<?php esc_attr_e( $field->id ); ?>">

                                <span class="title"><?php _e( $field->label, Plugin::ID ); ?></span>

                                <?php $field->render(); ?>

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

        $left_cols = array_splice( $columns, 0, 2 );
        $left_cols['title'] = __( 'Subject', Plugin::ID );
        $left_cols['id'] = __( 'Case #', Plugin::ID );

        if( $this->plugin->edd_active || $this->plugin->woo_active ) {
            $left_cols['product'] = __( 'Product', Plugin::ID );
        }

        return array_merge(
            $left_cols,
            array(
                'email'    => __( 'Email', Plugin::ID ),
                'agent'    => __( 'Assigned', Plugin::ID ),
                'status'   => __( 'Status', Plugin::ID ),
                'priority' => __( 'Priority', Plugin::ID ),
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

                foreach( $this->quick_edit_form->fields as $field ) {
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
                $agents = UserUtils::list_agents( array( '' => __( 'Unassigned', Plugin::ID ) ) );

                if( array_key_exists( $value, $agents ) ) {
                    echo $agents[ $value ];
                }

                break;

            case 'status':
                $statuses = get_option( Option::STATUSES, Option\Defaults::$STATUSES );

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
                    'options'   =>  UserUtils::list_agents( array( '' => __( 'All Agents', Plugin::ID ) ) ),
                    'value'     => !empty( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : ''
                )
            );

            $agent_filter->render();

            $meta_filter = new SelectBoxField(
                array(
                    'id'        => 'checked_meta',
                    'value'     => !empty( $_REQUEST['checked_meta'] ) ? $_REQUEST['checked_meta'] : '',
                    'options'   =>  array(
                        '' => __( 'All Tickets', Plugin::ID ),
                        'flagged' => __( 'Flagged', Plugin::ID )
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
            $this->plugin->id() . '_cleanup' => array( 'cleanup' ),
            'quick_edit_custom_box' => array( 'render_quick_edit', 10, 2 ),
            'manage_support_ticket_posts_columns' => array( 'post_table_columns' ),
            'manage_support_ticket_posts_custom_column' => array( 'post_table_column_data', 10, 2 ),
            'manage_edit-support_ticket_sortable_columns' => array( 'post_table_sortable_columns' ),
        );
    }
}
