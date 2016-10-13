<?php

namespace SmartcatSupport\Admin\Ticket;

use SmartcatSupport\Ticket\Meta;
use SmartcatSupport\Enum\Role;
use const SmartcatSupport\TEXT_DOMAIN;

class MetaBox {
    private $users;
    
    private function __construct() {
        $this->users = get_users( [ 'role__in' => [ Role::AGENT, Role::ADMIN ] ] );
    }
    
    public static function install() {
        $instance = new self;
        
        if ( is_admin() ) {
            add_action( 'load-post.php',    [ $instance, 'add_hooks' ] );
            add_action( 'load-post-new.php', [ $instance, 'add_hooks' ] );
        }
    }
    
    public function add_hooks() {
        add_action( 'add_meta_boxes', [ $this, 'add' ] );
        add_action( 'save_post', [ $this, 'save' ], 10, 2 );
    }

    public function add() {
        add_meta_box(
            'ticket_meta',
            __( 'Ticket Information', 'text_domain' ),
            [ $this, 'render' ],
            'sc_support_ticket',
            'advanced',
            'default'
        );
    }

    public function render( $post ) {
        include_once 'template.php';
    }

    public function save( $post_id, $post ) {
        if( isset( $_POST[ Meta::EMAIL_ADDRESS ] ) ) {
            update_post_meta(
                $post_id, 
                Meta::EMAIL_ADDRESS,
                sanitize_email( $_POST[ Meta::EMAIL_ADDRESS ] ) 
            );
        }
        
        if( isset( $_POST[ Meta::DATE_OPENED ] ) ) {
            $date = $this->validate_date( 
                $_POST[ Meta::DATE_OPENED ], 
                get_post_meta( $post_id, Meta::DATE_OPENED, true )
            ); 
     
            update_post_meta( 
                $post_id, 
                Meta::DATE_OPENED, 
                $date
            );
        }
        
        if( isset( $_POST[ Meta::STATUS ] ) ) {
            $value = $this->validate_select( 
                $_POST[ Meta::STATUS ], 
                Meta::STATUS_VALUES,
                get_post_meta( $post_id, Meta::STATUS, true )
            );
            
            update_post_meta( 
                $post_id, 
                Meta::STATUS, 
                $value
            );
        }
        
        if( isset( $_POST[ Meta::ASSIGNED_TO ] ) ) {
            $user_ids = [ '' ];
            
            foreach( $this->users as $user ) {
                $user_ids[] = $user->ID;
            }
           
            $value = $this->validate_select( 
                $_POST[ Meta::ASSIGNED_TO ],
                $user_ids,
                get_post_meta( $post_id, Meta::ASSIGNED_TO, true )
            );
 
            update_post_meta( 
                $post_id, 
                Meta::ASSIGNED_TO, 
                $value
            );
        }
    }
    
    private function validate_select( $value, array $options, $default ) {
        if( !in_array( $value , $options ) ) {
            $value = $default;
        }
        
        return $value;
    }
    
    private function validate_date( $date, $default ) {
        $date = date_create( $date );
        
        if( $date !== false ) {
            $date = $date->format( 'Y-m-d' );    
        } else {
            $date = $default;
        }
            
        return $date;
    }
}
