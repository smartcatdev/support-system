<?php

namespace SmartcatSupport\admin;

use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use function SmartcatSupport\render_template;
use const SmartcatSupport\TEXT_DOMAIN;

final class PostTableModifiers {

    private function __construct() {}

    public static function init() {
        add_action( 'restrict_manage_posts', array ( __CLASS__, 'post_filters' ) );
        add_action( 'parse_query', array ( __CLASS__, 'filter_posts' ) );
        add_action( 'manage_support_ticket_posts_columns', array ( __CLASS__, 'post_columns' ) );
        add_action( 'support_ticket_sortable_columns', array ( __CLASS__, 'sortable_columns' ) );
        add_action( 'manage_support_ticket_posts_custom_column', array ( __CLASS__, 'post_column_data' ), 10, 2 );
        add_action( 'quick_edit_custom_box', array ( __CLASS__, 'quick_edit' ), 10, 2 );
        add_action( 'save_post', array ( __CLASS__, 'quick_edit_save' ));
    }

    public static function post_columns( $columns ) {
        unset( $columns['author'] );

        $left_cols = array_splice( $columns, 0, 2 );
        $left_cols['title'] = __( 'Subject', TEXT_DOMAIN );
        $product_cols = array();

        if( !empty( get_products() ) ) {
            $product_cols['thumb'] = '<span class="support_icon dashicons dashicons-format-image"></span>';
            $product_cols['product'] = __( 'Product', TEXT_DOMAIN );
        }

        return array_merge(
            $left_cols, array( 'id' => __( 'Case #', TEXT_DOMAIN ) ), $product_cols,
            array(
                'email'    => __( 'Email', TEXT_DOMAIN ),
                'agent'    => __( 'Assigned', TEXT_DOMAIN ),
                'status'   => __( 'Status', TEXT_DOMAIN ),
                'priority' => __( 'Priority', TEXT_DOMAIN ),
                'flagged'     => '<i class="support_icon icon-flag2"></i>'
            ),
            $columns
        );
    }

    public static function post_column_data( $column, $post_id ) {
        $value = get_post_meta( $post_id, $column, true ) ;

        switch ( $column ) {
            case 'id':
                echo $post_id;
                echo '<div class="hidden" id="support_inline_' . $post_id . '">';

                $form = include SUPPORT_PATH . '/config/quick_edit_form.php';

                foreach( $form->fields as $field ) {
                    $id = $field->id;
                    echo '<div class="' . $id . '">' . get_post_meta( $post_id, $id, true ) . '</div>';
                }

                echo '</div>';
                break;

            case 'email':
                esc_html_e( get_post_meta( $post_id, 'email', true ) );
                break;

            case 'product':
                $products = get_products();

                if( !empty( $products ) && array_key_exists( $value, $products ) ) {
                    echo $products[ $value ];
                }

                break;

            case 'thumb':
                $products = get_products();
                $value = get_post_meta( $post_id, 'product', true );

                if( !empty( $products ) && array_key_exists( $value, $products ) ) {
                    echo get_the_post_thumbnail( $value, array( 150, 150 ) );
                }

                break;

            case 'agent':
                $agents = get_agents();

                if( array_key_exists( $value, $agents ) ) {
                    echo $agents[ $value ];
                } else {
                    _e( 'Unassigned', TEXT_DOMAIN );
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

    public static function sortable_columns( $columns ) {
        return array_merge(
            array(
                'status'   => 'status',
                'priority' => 'priority',
                'assigned' => 'assigned',
                'product'  => 'product',
            ),

            $columns
        );
    }

    public static function quick_edit( $column, $post_type ) {
        if( $post_type == 'support_ticket' && $column == 'id' ) {
            $form = include SUPPORT_PATH . '/config/quick_edit_form.php';

            echo render_template( 'ticket_quick_edit', array( 'form' => $form ) );
        }
    }

    public static function quick_edit_save( $post_id ) {
        if( defined( 'DOING_AJAX' ) ) {
            $form = include SUPPORT_PATH . '/config/quick_edit_form.php';

            if ( $form->is_valid() ) {
                foreach ( $form->data as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }
        }
    }

    public static function post_filters() {
        if ( get_current_screen()->post_type == 'support_ticket' ) {
            $agent_filter = new SelectBoxField(
                array(
                    'id'        => 'agent',
                    'options'   =>  array( '' => __( 'All Agents', TEXT_DOMAIN ) ) + get_agents(),
                    'value'     => !empty( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : ''
                )
            );

            $agent_filter->render();

            $meta_filter = new SelectBoxField(
                array(
                    'id'        => 'checked_meta',
                    'value'     => !empty( $_REQUEST['checked_meta'] ) ? $_REQUEST['checked_meta'] : '',
                    'options'   =>  array(
                        '' => __( 'All Tickets', TEXT_DOMAIN ),
                        'flagged' => __( 'Flagged', TEXT_DOMAIN )
                    ),
                )
            );

            $meta_filter->render();
        }
    }

    public static function filter_posts( $query ) {
        if ( ( ! empty( $GLOBALS['typenow'] ) && ! empty( $GLOBALS['pagenow'] ) ) &&
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
}


