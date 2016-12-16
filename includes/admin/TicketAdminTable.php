<?php

namespace SmartcatSupport\admin;

use smartcat\form\CheckBoxField;
use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\FormBuilder;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\agents_dropdown;
use function SmartcatSupport\boolean_meta_dropdown;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use function SmartcatSupport\render_template;
use const SmartcatSupport\TEXT_DOMAIN;
use SmartcatSupport\util\ActionListener;

class TicketAdminTable extends ActionListener {
    public function __construct() {

        $this->add_actions();
    }

    private function add_actions() {
        $this->add_action( 'restrict_manage_posts', 'post_filters' );
        $this->add_action( 'parse_query', 'filter_posts' );

        $this->add_action( 'manage_support_ticket_posts_columns', 'post_columns' );
        $this->add_action( 'manage_edit-support_ticket_sortable_columns', 'sortable_columns' );
        $this->add_action( 'manage_support_ticket_posts_custom_column', 'post_column_data', 10, 2 );
        $this->add_action( 'quick_edit_custom_box', 'quick_edit', 10, 2 );
        $this->add_action( 'save_post', 'quick_edit_save' );
    }

    public function post_columns( $columns ) {
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

    public function post_column_data( $column, $post_id ) {
        $value = get_post_meta( $post_id, $column, true ) ;

        switch ( $column ) {
            case 'id':
                echo $post_id;
                echo '<div class="hidden" id="support_inline_' . $post_id . '">';

                foreach ( self::quick_edit_form()->fields as $field ) {
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

    public function sortable_columns( $columns ) {
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

    public function quick_edit( $column, $post_type ) {
        if( $post_type == 'support_ticket' && $column == 'id' ) {
            echo render_template( 'ticket_quick_edit', array( 'form' => self::quick_edit_form() ) );
        }
    }

    public function quick_edit_save( $post_id ) {
        if( defined( 'DOING_AJAX' ) ) {
            $form = self::quick_edit_form();

            if ( $form->is_valid() ) {
                foreach ( $form->data as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }
        }
    }

    public function post_filters() {
        if ( get_current_screen()->post_type == 'support_ticket' ) {

            $agent_filter = new SelectBoxField(
                array(
                    'id'        => 'agent',
                    'options'   =>  array( '' => __( 'All Agents', TEXT_DOMAIN ) ) + get_agents(),
                    'value'     => !empty( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : ''
                )
            );

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

            $agent_filter->render();
            $meta_filter->render();
        }
    }

    public function filter_posts( $query ) {
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

    private static function quick_edit_form() {
        $agents = array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + get_agents();
        $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
        $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

        $form = new Form( 'ticket_quick_edit' );

        $form->add_field( new CheckBoxField(
            array(
                'id'        => 'flagged',
                'cb_title'  => __( 'Flagged', TEXT_DOMAIN ),
                'value'     => false
            )

        ) )->add_field( new SelectBoxField(
            array(
                'id'            => 'agent',
                'label'         => __( 'Assigned', TEXT_DOMAIN ),
                'options'       => $agents,
                'constraints'   => array(
                    new ChoiceConstraint( array_keys( $agents ) )
                )
            )

        ) )->add_field( new SelectBoxField(
            array(
                'id'            => 'status',
                'label'         => __( 'Status', TEXT_DOMAIN ),
                'options'       => $statuses,
                'constraints'   => array(
                    new ChoiceConstraint( array_keys( $statuses ) )
                )
            )

        ) )->add_field( new SelectBoxField(
            array(
                'id'            => 'priority',
                'label'         => __( 'Priority', TEXT_DOMAIN ),
                'options'       => $priorities,
                'constraints'   => array(
                    new ChoiceConstraint( array_keys( $priorities ) )
                )
            )
        ) );

        return $form;
    }
}


