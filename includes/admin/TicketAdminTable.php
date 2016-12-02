<?php

namespace SmartcatSupport\admin;

use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\agents_dropdown;
use SmartcatSupport\form\constraint\Choice;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\FormBuilder;
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
        $cb = array_splice( $columns, 0, 1 );
        unset( $columns['title'] );
        unset( $columns['author'] );

        return array_merge(
            $cb + array(
                'id'       => __( 'Case #', TEXT_DOMAIN ),
                'title'    => __( 'Subject', TEXT_DOMAIN ),
                'product'  => __( 'Product', TEXT_DOMAIN ),
                'email'    => __( 'Email', TEXT_DOMAIN ),
                'assigned' => __( 'Assigned', TEXT_DOMAIN ),
                'status'   => __( 'Status', TEXT_DOMAIN ),
                'priority' => __( 'Priority', TEXT_DOMAIN ),
                'flag'     => '<i class="support_icon icon-flag2"></i>'
            ),
            $columns
        );
    }

    public function post_column_data( $column, $post_id ) {
        $value = get_post_meta( $post_id, $column, true ) ;

        switch ( $column ) {
            case 'id':
                echo $post_id;
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

            case 'assigned':
                $agents = get_agents();

                if( !empty( $agents ) && array_key_exists( $value, $agents ) ) {
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

            case 'flag':
                $flagged = boolval( get_post_meta( $post_id, 'flagged', true ) );

                echo '<i class="support_admin_toggle support_icon flag icon-flag2 ' . ( $flagged ? 'active' : '' ) . '" ' .
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
                'product'  => 'product'
            ),
            $columns
        );
    }

    public function quick_edit( $column, $post_type ) {
        if( $post_type == 'support_ticket' && $column == 'id' ) {
            echo render_template( 'ticket_quick_edit', array( 'form' => $this->quick_edit_form( $GLOBALS['post'] ) ) );
        }
    }

    public function quick_edit_save( $post_id ) {
        $form = $this->quick_edit_form( $post_id );

        if( $form->is_valid() ) {
            $data = $form->get_data();

            foreach( $data as $key => $value ) {
                update_post_meta( $post_id, $key, $value );
            }
        }
    }

    public function post_filters() {
        if ( get_current_screen()->post_type == 'support_ticket' ) {
            agents_dropdown( 'agent', ! empty( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : '' );
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

            $query->query_vars['meta_query'] = $meta_query;
        }

        return $query;
    }


    private function quick_edit_form( $post_id ) {
        $builder = new FormBuilder( 'quick_edit' );
        $agents = array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + get_agents();
        $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
        $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

        $builder->add( SelectBox::class, 'agent', array(
            'label'             => __( 'Assigned', TEXT_DOMAIN ),
            'options'           => $agents,
            'value'             => get_post_meta( $post_id, 'agent', true ),
            'constraints'       => array(
                $builder->create_constraint( Choice::class, array_keys( $agents ) )
            )

        ) )->add( SelectBox::class, 'status', array(
            'label'             => __( 'Status', TEXT_DOMAIN ),
            'options'           => $statuses,
            'value'             => get_post_meta( $post_id, 'status', true ),
            'constraints'       => array(
                $builder->create_constraint( Choice::class, array_keys( $statuses ) )
            )
        ) )->add( SelectBox::class, 'priority', array(
            'error_msg'   => __( 'Invalid priority selected', TEXT_DOMAIN ),
            'label'       => __( 'Priority', TEXT_DOMAIN ),
            'options'     => $priorities,
            'value'       => get_post_meta( $post_id, 'priority', true ),
            'constraints' => array(
                $builder->create_constraint( Choice::class, array_keys( $priorities ) )
            )
        ) );

        return $builder->get_form();
    }
}


