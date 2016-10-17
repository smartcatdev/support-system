<?php

namespace SmartcatSupport;

abstract class ActionListener {
    protected $events = [];

    public function add_action( $event, $callback, $priority = 10, $argsc = 1 ) {
        add_filter( $event, [ $this, $callback ], $priority, $argsc );
        
        $this->subscribed_events[ $event ] = $callback;
    }
    
    public function remove_action( $event ) {
        if( array_key_exists( $event , $this->subscribed_events ) ) {
            remove_filter( $event, [ $this, $this->subscribed_events[ $event ] ] );
            
            unset( $events[ $event ] );
        }
    }
    
    public function add_ajax_action( $event, $callback, $priv = true, $priority = 10, $argsc = 1 ) {
        $this->add_action( $this->prefix_ajax( $event, $priv ), [ $this, $callback ], $priority, $argsc );
    }
    
    public function remove_ajax_action( $event, $priv = true ) {
        $this->remove_action( $this->prefix_ajax( $event, $priv ) );
    }
    
    private function prefix_ajax( $event, $priv = true ) {
        return ( $priv ? 'wp_ajax_' : 'wp_ajax_no_priv_' ) . $event;
    }
}

